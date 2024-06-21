<?php
/*
Plugin Name: Hide admin notices
Description: Clear and controls your notifications in the backend of your WordPress site
Author: Rémi Leclercq
Author URI: https://github.com/roumilb
License: GPLv3
Version: 3.2.0
Text Domain: wanc
Domain Path: /languages
*/

use \WANC\Init;

require __DIR__.'/vendor/autoload.php';

if (is_admin()) {
    new Init();
}
