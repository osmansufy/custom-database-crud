<?php

/* 

Class DeActivator
*/


namespace CustomDatabaseCrud;


class DeActivator
{

    public static function deactivate()
    {

        global $wpdb;
        // Delete the table from the database
        $table_name = $wpdb->prefix . 'custom_database_crud';

        $sql = "DROP TABLE IF EXISTS $table_name";

        $wpdb->query($sql);
    }
}
