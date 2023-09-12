<?php

namespace WANC;

class Database
{
    public static function getPrefix() {
        global $wpdb;

        return $wpdb->prefix;
    }

    public static function getObjects($query) {
        global $wpdb;

        $query = str_replace('#__', self::getPrefix(), $query);

        return $wpdb->get_results($query);
    }
    public static function getObject($query) {
        global $wpdb;

        $query = str_replace('#__', self::getPrefix(), $query);

        return $wpdb->get_row($query);
    }
    public static function query($query) {
        global $wpdb;

        $query = str_replace('#__', self::getPrefix(), $query);

        return $wpdb->get_row($query);
    }
}