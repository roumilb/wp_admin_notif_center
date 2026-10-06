<?php

namespace WANC;

defined('ABSPATH') || exit;

class Plugin
{
    public function __construct()
    {
        $settings = new Settings();
        new NotificationCenter($settings);

        add_action('admin_init', [$this, 'upgrade']);
    }

    public function upgrade(): void
    {
        $installedVersion = get_option('wanc_version', '0.0.0');
        if (version_compare($installedVersion, WANC_VERSION, '>=')) {
            return;
        }

        // The notice history table (3.0 to 3.2) is no longer used
        if (version_compare($installedVersion, '4.0.0', '<')) {
            global $wpdb;
            $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}wanc_notice"); // phpcs:ignore WordPress.DB.DirectDatabaseQuery
        }

        update_option('wanc_version', WANC_VERSION);
    }
}
