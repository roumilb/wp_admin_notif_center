<?php

defined('WP_UNINSTALL_PLUGIN') || exit;

function wanc_uninstall_site(): void
{
    global $wpdb;

    $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}wanc_notice"); // phpcs:ignore WordPress.DB.DirectDatabaseQuery

    foreach (['wanc_version', 'wanc_display_settings', 'wanc_display_settings_roles', 'wanc_spam_words', 'wanc_white_list'] as $option) {
        delete_option($option);
    }
}

if (!is_multisite()) {
    wanc_uninstall_site();

    return;
}

foreach (get_sites(['fields' => 'ids', 'number' => 0]) as $wanc_site_id) {
    switch_to_blog($wanc_site_id);
    wanc_uninstall_site();
    restore_current_blog();
}
