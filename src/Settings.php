<?php

namespace WANC;

defined('ABSPATH') || exit;

class Settings
{
    const PAGE_SLUG = 'wp-admin-notification-center';
    const NOTICE_TYPES = ['success', 'info', 'warning', 'error'];

    public function __construct()
    {
        add_action('admin_menu', [$this, 'registerPage']);
        add_action('admin_init', [$this, 'save']);
    }

    public function registerPage(): void
    {
        add_options_page(
            __('Hide admin notices', 'wp-admin-notification-center'),
            __('Hide admin notices', 'wp-admin-notification-center'),
            'manage_options',
            self::PAGE_SLUG,
            [$this, 'render']
        );
    }

    public function render(): void
    {
        $data = [
            'notice_types' => $this->getNoticeTypes(),
            'roles' => $this->getRoles(),
            'spam_words' => (string)get_option('wanc_spam_words', ''),
            'white_list' => (string)get_option('wanc_white_list', ''),
        ];

        include __DIR__.'/Views/settings.php';
    }

    public function save(): void
    {
        if (empty($_POST['wanc_nonce']) || !current_user_can('manage_options')) {
            return;
        }

        if (!wp_verify_nonce(sanitize_key($_POST['wanc_nonce']), 'wanc_save_settings')) {
            return;
        }

        $submittedTypes = isset($_POST['wanc_display']) ? array_map('sanitize_text_field', (array)wp_unslash($_POST['wanc_display'])) : [];
        $noticeTypes = [];
        foreach (self::NOTICE_TYPES as $type) {
            $noticeTypes[$type] = empty($submittedTypes[$type]) ? 0 : 1;
        }

        $submittedRoles = isset($_POST['wanc_roles']) ? array_map('sanitize_text_field', (array)wp_unslash($_POST['wanc_roles'])) : [];
        $roles = [];
        foreach (array_keys(wp_roles()->roles) as $role) {
            $roles[$role] = empty($submittedRoles[$role]) ? 0 : 1;
        }

        update_option('wanc_display_settings', wp_json_encode($noticeTypes));
        update_option('wanc_display_settings_roles', wp_json_encode($roles));
        update_option('wanc_spam_words', sanitize_text_field(wp_unslash($_POST['wanc_spam_words'] ?? '')));
        update_option('wanc_white_list', sanitize_text_field(wp_unslash($_POST['wanc_white_list'] ?? '')));

        wp_safe_redirect(admin_url('options-general.php?page='.self::PAGE_SLUG.'&updated=1'));
        exit;
    }

    /**
     * @return array<string, int> Notice type => 1 if moved to the notification center, 0 if kept in place
     */
    public function getNoticeTypes(): array
    {
        $saved = $this->getJsonOption('wanc_display_settings');

        $noticeTypes = [];
        foreach (self::NOTICE_TYPES as $type) {
            $noticeTypes[$type] = isset($saved[$type]) ? (int)$saved[$type] : 1;
        }

        return $noticeTypes;
    }

    /**
     * @return array<string, array{name: string, allowed: int}>
     */
    public function getRoles(): array
    {
        $saved = $this->getJsonOption('wanc_display_settings_roles');

        $roles = [];
        foreach (wp_roles()->roles as $role => $details) {
            $roles[$role] = [
                'name' => translate_user_role($details['name']),
                'allowed' => isset($saved[$role]) ? (int)$saved[$role] : 1,
            ];
        }

        return $roles;
    }

    public function currentUserAllowed(): bool
    {
        $userRoles = wp_get_current_user()->roles;
        if (empty($userRoles)) {
            return true;
        }

        $roles = $this->getRoles();
        foreach ($userRoles as $role) {
            if (!empty($roles[$role]['allowed'])) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string[]
     */
    public function getWordList(string $option): array
    {
        $words = array_map('trim', explode(',', (string)get_option($option, '')));

        return array_values(array_filter($words, 'strlen'));
    }

    private function getJsonOption(string $option): array
    {
        $value = json_decode((string)get_option($option, ''), true);

        return is_array($value) ? $value : [];
    }
}
