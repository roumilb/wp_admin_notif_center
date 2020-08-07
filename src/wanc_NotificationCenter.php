<?php


namespace WANC;


class wanc_NotificationCenter
{
    public function __construct()
    {
        add_action('admin_enqueue_scripts', [$this, 'addScript']);
        add_action('admin_bar_menu', [$this, 'addItemInAdminBar'], 100);
    }

    public function addItemInAdminBar($admin_bar)
    {
        $wancDisplaySettings = get_option('wanc_display_settings');
        $wancDisplaySettings = json_decode($wancDisplaySettings, true);

        $data = [];
        foreach ($wancDisplaySettings as $noticeType => $display) {
            $data['notice-'.$noticeType] = $display;
        }
        wanc_Views::includeViews('notification_center', $data);
        $admin_bar->add_menu(['id' => 'wanc_display_notification', 'title' => 'Notifications', 'href' => '#']);
    }

    public function addScript()
    {
        wp_enqueue_script('wanc_notice_script', plugins_url('wp-admin-notification-center/assets/js/notice.js?time='.time()), [], false, true);
        wp_enqueue_style('wanc_notice_style', plugins_url('wp-admin-notification-center/assets/css/notification_center.css?time='.time()));
    }
}
