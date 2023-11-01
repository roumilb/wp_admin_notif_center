<?php

namespace WANC;

class Database
{
    public static function getPrefix() {
        global $wpdb;

        return $wpdb->prefix;
    }

    public static function getObjects($query, $output = 'OBJECT') {
        global $wpdb;

        $query = str_replace('#__', self::getPrefix(), $query);

        return $wpdb->get_results($query, $output);
    }

    public static function getObject($query) {
        global $wpdb;

        $query = str_replace('#__', self::getPrefix(), $query);

        return $wpdb->get_row($query);
    }
    public static function query($query) {
        global $wpdb;

        $query = str_replace('#__', self::getPrefix(), $query);

        return $wpdb->query($query);
    }

    public static function insert($tableName, $data) {
        global $wpdb;

        $wpdb->insert($tableName, $data);

        return $wpdb->insert_id;
    }

    public static function update($id, $tableName, $data) {
        global $wpdb;

        return $wpdb->update($tableName, $data, ['id' => $id]);
    }

    public static function getVar($query) {
        global $wpdb;

        return $wpdb->get_var($query);
    }
}