<?php


namespace WANC;


class wanc_Settings
{
    var $notices = ['success', 'info', 'warning', 'error'];

    public function __construct()
    {
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

        $wancDisplaySettings = get_option('wanc_display_settings');
        $wancDisplaySettings = json_decode($wancDisplaySettings, true);
        ?>
		<h2>WordPress Admin Notification Center Settings</h2>
		<p>By default this plugin will move all of your admin notification in the notification center</p>
		<p>In this settings page you can change that and force the display of some notifications, like errors to not miss them!</p>
		<form method="post" action="">
			<p>Notification to display in the notification center:</p>
			<ul>
                <?php foreach ($wancDisplaySettings as $notice => $displayNotice) { ?>
					<li>
						<label>
							<input type="checkbox" <?php echo empty(esc_html($displayNotice)) ? '' : 'checked'; ?> name="wanc_display[<?php echo esc_html($notice); ?>]">
                            <?php echo ucfirst(esc_html($notice)); ?> notices
						</label>
					</li>
                <?php } ?>
			</ul>
			<input type="hidden" name="wanc_display[saved]">
			<button class="button button-primary">Save settings</button>
		</form>
        <?php
    }

    public function wanc_saveSettings()
    {
        if (empty($_REQUEST) || empty($_REQUEST['wanc_display'])) return true;

        $settingsSubmited = $_REQUEST['wanc_display'];
        $settingsSubmited = array_map('sanitize_text_field', $settingsSubmited);

        $wancDisplaySettings = get_option('wanc_display_settings');
        $wancDisplaySettings = json_decode($wancDisplaySettings, true);

        foreach ($this->notices as $notice) {
            $wancDisplaySettings[$notice] = empty($settingsSubmited[$notice]) ? 0 : 1;
        }

        update_option('wanc_display_settings', json_encode($wancDisplaySettings));

        return true;
    }

    public function firstSave()
    {
        $wancDisplaySettings = get_option('wanc_display_settings');
        if (!empty($wancDisplaySettings)) return true;

        $wancNewSettings = [];

        foreach ($this->notices as $notice) {
            $wancNewSettings[$notice] = 1;
        }

        add_option('wanc_display_settings', json_encode($wancNewSettings));

        return true;
    }
}
