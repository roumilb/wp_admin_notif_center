<?php
/*
Plugin Name: WP admin notification center
Description: Clear and controls your notifications in the backend of your WordPress site
Author: Rémi Leclercq
Author URI: https://github.com/roumilb
License: GPLv3
Version: 2.1
Text Domain: wanc
Domain Path: /languages
*/

use \WANC\wanc_NotificationCenter;
use \WANC\wanc_Settings;

require __DIR__.'/vendor/autoload.php';

if (is_admin()) {
    new wanc_Settings();
    new wanc_NotificationCenter();
}
