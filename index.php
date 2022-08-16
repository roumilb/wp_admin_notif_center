<?php
/*
Plugin Name: WP admin notification center
Description: Clear and controls your notifications in the backend of your WordPress site
Author: Rémi Leclercq
Author URI: https://github.com/roumilb
License: GPLv3
Version: 2.2
Text Domain: wanc
Domain Path: /languages
*/

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use \WANC\Init;

require __DIR__.'/vendor/autoload.php';

if (is_admin()) {
    new Init();
}
