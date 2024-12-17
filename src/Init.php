<?php


namespace WANC;

use WANC\Controllers\Notices;
use WANC\Controllers\Settings;
use WANC\Controllers\NotificationCenter;
use WANC\Services\UpdateService;

class Init
{
    const WANC_SLUG_MENU = 'wp-admin-notification-center';

    public function __construct()
    {
        add_action('admin_menu', [$this, 'registerWancOptionsPage']);
        add_action('admin_enqueue_scripts', [$this, 'addScript']);
        new Notices();
        new Settings();
        new NotificationCenter();
    }

    public function registerWancOptionsPage()
    {
        add_options_page(
            'Hide Admin Notice',
            __('Hide Admin Notice settings', 'wanc'),
            'manage_options',
            self::WANC_SLUG_MENU,
            [new Settings(), 'optionsPage'],
        );
    }

    public function addScript() {
        wp_enqueue_style('wanc_style', plugins_url('wp-admin-notification-center/assets/css/global.css?time='.time()));
    }
}