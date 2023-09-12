<?php


namespace WANC;

use WANC\Controllers\Notices;
use \WANC\Controllers\Settings;
use \WANC\Controllers\NotificationCenter;
use WANC\Services\SurveyService;
use WANC\Services\UpdateService;

class Init
{
    const WANC_SLUG_MENU = 'wp-admin-notification-center';

    public function __construct()
    {
        (new UpdateService())->update();

        add_action('admin_menu', [$this, 'registerWancOptionsPage']);
        add_action('admin_enqueue_scripts', [$this, 'addScript']);
        new Settings();
        new NotificationCenter();
        new SurveyService();
        new Notices();
    }

    public function registerWancOptionsPage()
    {
        add_menu_page(
            'Hide Admin Notice',
            __('Hide Admin Notice', 'wanc'),
            'manage_options',
            self::WANC_SLUG_MENU,
            [new Settings(), 'optionsPage'],
            plugins_url('wp-admin-notification-center/assets/images/logo.svg')
        );
    }

    public function addScript() {
        wp_enqueue_style('wanc_style', plugins_url('wp-admin-notification-center/assets/css/global.css?time='.time()));
    }
}