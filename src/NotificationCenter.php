<?php

namespace WANC;

defined('ABSPATH') || exit;

class NotificationCenter
{
    const ALWAYS_IN_PLACE_CLASSES = ['welcome-panel', 'update-message', 'hidden'];

    private Settings $settings;

    public function __construct(Settings $settings)
    {
        $this->settings = $settings;

        add_action('admin_enqueue_scripts', [$this, 'enqueueAssets']);
        add_action('admin_bar_menu', [$this, 'addAdminBarItem'], 100);
        add_action('admin_footer', [$this, 'renderContainer']);
        add_filter('mailpoet_conflict_resolver_whitelist_script', [$this, 'whitelistForMailpoet']);
        add_filter('mailpoet_conflict_resolver_whitelist_style', [$this, 'whitelistForMailpoet']);
    }

    public function enqueueAssets(): void
    {
        wp_enqueue_style('wanc_notice_style', plugins_url('assets/css/notification_center.css', WANC_FILE), [], WANC_VERSION);
        // Hides notices until the script has sorted them, its handle is referenced in notice.js
        wp_enqueue_style('wanc_pre_notice_style', plugins_url('assets/css/pre_notification_center.css', WANC_FILE), [], WANC_VERSION);

        if (!$this->settings->currentUserAllowed()) {
            wp_enqueue_script('wanc_notice_script', plugins_url('assets/js/notice_not_allowed.js', WANC_FILE), [], WANC_VERSION, true);

            return;
        }

        wp_enqueue_script('wanc_notice_script', plugins_url('assets/js/notice.js', WANC_FILE), [], WANC_VERSION, true);
        wp_add_inline_script('wanc_notice_script', 'const wancSettings = '.wp_json_encode($this->getScriptData()).';', 'before');
    }

    public function addAdminBarItem(\WP_Admin_Bar $adminBar): void
    {
        if (!$this->settings->currentUserAllowed()) {
            return;
        }

        $adminBar->add_menu([
            'id' => 'wanc_display_notification',
            'title' => __('Notifications', 'wp-admin-notification-center'),
            'href' => '#',
        ]);
    }

    public function renderContainer(): void
    {
        if (!$this->settings->currentUserAllowed()) {
            return;
        }
        ?>
        <div id="wanc_container">
            <span class="dashicons dashicons-no-alt" id="wanc_container_close"></span>
            <h3><?php esc_html_e('There is no notification to display', 'wp-admin-notification-center'); ?></h3>
        </div>
        <?php
    }

    public function whitelistForMailpoet(array $handles): array
    {
        $handles[] = dirname(plugin_basename(WANC_FILE));

        return $handles;
    }

    private function getScriptData(): array
    {
        $classesKeptInPlace = self::ALWAYS_IN_PLACE_CLASSES;
        foreach ($this->settings->getNoticeTypes() as $type => $moveToCenter) {
            if (!$moveToCenter) {
                $classesKeptInPlace[] = 'notice-'.$type;
            }
        }

        return [
            'classesKeptInPlace' => $classesKeptInPlace,
            'spamWords' => $this->settings->getWordList('wanc_spam_words'),
            'whiteList' => $this->settings->getWordList('wanc_white_list'),
        ];
    }
}
