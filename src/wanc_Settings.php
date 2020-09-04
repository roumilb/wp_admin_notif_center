<?php


namespace WANC;


class wanc_Settings
{
    var $notices = ['success', 'info', 'warning', 'error'];
    var $wancSettingsLib;

    public function __construct()
    {
        $this->wancSettingsLib = new WancSettings();
        $this->firstSave();
        add_action('admin_menu', [$this, 'registerWancOptionsPage']);
    }

    public function registerWancOptionsPage()
    {
        add_options_page('WP Admin notification Center', 'Notification Center Settings', 'manage_options', 'wp-admin-notification-center', [$this, 'wanc_options_page']);
    }

    public function wanc_options_page()
    {
        $this->wanc_saveSettings();

        $wancDisplaySettings = $this->wancSettingsLib->getOption('wanc_display_settings', []);
        $data['display_settings'] = $wancDisplaySettings;


        $wancDisplaySettingsRoles = $this->wancSettingsLib->getOption('wanc_display_settings_roles', []);
        if (empty($wancDisplaySettingsRoles)) {
            global $wp_roles;
            $all_roles = array_keys($wp_roles->roles);

            $wancDisplaySettingsRoles = [];
            foreach ($all_roles as $role) {
                $wancDisplaySettingsRoles[$role] = 1;
            }
        }
        $data['display_settings_roles'] = $wancDisplaySettingsRoles;

        wanc_Views::includeViews('options', $data);
    }

    public function wanc_saveSettings()
    {
        if (empty($_REQUEST) || empty($_REQUEST['wanc_display']) || empty($_REQUEST['wanc_roles'])) return true;

        $settingsSubmited = $_REQUEST['wanc_display'];
        $settingsSubmited = array_map('sanitize_text_field', $settingsSubmited);

        $wancDisplaySettings = $this->wancSettingsLib->getOption('wanc_display_settings', []);

        foreach ($this->notices as $notice) {
            $wancDisplaySettings[$notice] = empty($settingsSubmited[$notice]) ? 0 : 1;
        }
        $this->wancSettingsLib->updateOption('wanc_display_settings', json_encode($wancDisplaySettings));

        $settingsRolesSubmited = $_REQUEST['wanc_roles'];
        $settingsRolesSubmited = array_map('sanitize_text_field', $settingsRolesSubmited);
        $this->wancSettingsLib->updateOption('wanc_display_settings_roles', json_encode($settingsRolesSubmited));


        return true;
    }

    public function firstSave()
    {
        $wancDisplaySettings = get_option('wanc_display_settings');
        if (empty($wancDisplaySettings)) {
            $wancNewSettings = [];

            foreach ($this->notices as $notice) {
                $wancNewSettings[$notice] = 1;
            }

            $this->wancSettingsLib->updateOption('wanc_display_settings', json_encode($wancNewSettings));
        }

        return true;
    }
}
