<?php


namespace CustomDatabaseCrud;

class Activator
{
    public static function activate()
    {

        self::create_table();
    }

    public static function create_table()
    {
        global $wpdb;

        $table_name = $wpdb->prefix . 'custom_database_crud';

        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE IF NOT EXISTS $table_name (
            id mediumint(9) NOT NULL AUTO_INCREMENT,
            name varchar(50) NOT NULL,
            email varchar(50) NOT NULL,
            phone varchar(30) NOT NULL,
            address text NOT NULL,
            created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
            PRIMARY KEY  (id)
        ) $charset_collate;";

        if (!function_exists('dbDelta')) {
            require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        }
        dbDelta($sql);
    }
}
