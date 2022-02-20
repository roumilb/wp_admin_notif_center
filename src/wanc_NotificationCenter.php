<?php


namespace WANC;


class wanc_NotificationCenter
{
    private $wancSettingsLib;

    public function __construct()
    {
        $this->wancSettingsLib = new WancSettings();
        add_action('admin_enqueue_scripts', [$this, 'addScript']);
        add_action('admin_bar_menu', [$this, 'addItemInAdminBar'], 100);
    }

    public function addItemInAdminBar($admin_bar)
    {
        $wancSettings = new wanc_Settings();
        $wancSettings->alreadySave = $wancSettings->wanc_saveSettings();

        $isAllowed = $this->wancSettingsLib->currentUserAllowed();
        if (!$isAllowed) return true;

        $wancDisplaySettings = get_option('wanc_display_settings');
        $wancDisplaySettings = json_decode($wancDisplaySettings, true);

        $data = [];
        foreach ($wancDisplaySettings as $noticeType => $display) {
            $data['notice-'.$noticeType] = $display;
        }

        $data['spam_words'] = $this->wancSettingsLib->getOption('wanc_spam_words', '');
        $data['white_list'] = $this->wancSettingsLib->getOption('wanc_white_list', '');

        wanc_Views::includeViews('notification_center', $data);
        $admin_bar->add_menu(['id' => 'wanc_display_notification', 'title' => 'Notifications', 'href' => '#']);
    }

    public function addScript()
    {
        $isAllowed = $this->wancSettingsLib->currentUserAllowed();
        if ($isAllowed) {
            wp_enqueue_script('wanc_notice_script', plugins_url('wp-admin-notification-center/assets/js/notice.js?time='.time()), [], false, true);
        } else {
            wp_enqueue_script('wanc_notice_script', plugins_url('wp-admin-notification-center/assets/js/notice_not_allowed.js?time='.time()), [], false, true);
        }
        wp_enqueue_style('wanc_notice_style', plugins_url('wp-admin-notification-center/assets/css/notification_center.css?time='.time()));
        wp_enqueue_style('wanc_pre_notice_style', plugins_url('wp-admin-notification-center/assets/css/pre_notification_center.css?time='.time()));
    }
}
