<?php
/*
Plugin Name: Hide admin notices
Description: Clear and controls your notifications in the backend of your WordPress site
Author: Rémi Leclercq
Author URI: https://github.com/roumilb
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Version: 4.0.0
Requires at least: 5.0
Requires PHP: 7.4
Text Domain: wp-admin-notification-center
*/

defined('ABSPATH') || exit;

const WANC_VERSION = '4.0.0';
const WANC_FILE = __FILE__;

require_once __DIR__.'/src/Settings.php';
require_once __DIR__.'/src/NotificationCenter.php';
require_once __DIR__.'/src/Plugin.php';

if (is_admin()) {
    new \WANC\Plugin();
}
