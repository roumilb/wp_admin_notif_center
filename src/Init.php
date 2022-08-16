<?php


namespace WANC;

use \WANC\Controllers\Settings;
use \WANC\Controllers\NotificationCenter;

class Init
{
    const WANC_SLUG_MENU = 'wp-admin-notification-center';

    public function __construct()
    {
        add_action('admin_menu', [$this, 'registerWancOptionsPage']);
        new Settings();
        new NotificationCenter();
    }

    public function registerWancOptionsPage()
    {
        add_menu_page(
            'Notification Center',
            __('Notification Center', 'wanc'),
            'manage_options',
            self::WANC_SLUG_MENU,
            [new Settings(), 'optionsPage']
        );
    }
}