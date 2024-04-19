<?php

namespace CustomDatabaseCrud\Admin;


class AdminAction
{

    // singleton instance
    private static $instance = null;

    public function __construct()
    {
        add_action('wp_ajax_delete_record', [$this, 'delete_record']);
    }

    public static function get_instance()
    {
        if (self::$instance == null) {
            self::$instance = new self();
        }

        return self::$instance;
    }

    public function delete_record()
    {


        // Check nonce for security
        if (!isset($_POST['security']) || !wp_verify_nonce($_POST['security'], 'delete_record_nonce')) {
            wp_die('Permission denied');
        }
        // capability check
        if (!current_user_can('manage_options')) {
            wp_die('Access Denied');
        }
        global $wpdb;

        $table_name = $wpdb->prefix . 'custom_database_crud';

        $id = isset($_POST['record_id']) ? intval($_POST['record_id']) : 0;

        if ($id > 0) {
            $wpdb->delete($table_name, ['id' => $id]);
        }

        // send the response with success message

        wp_send_json_success([
            'success' => true,
            'message' => 'Record deleted successfully',
            'deleted_id' => $id
        ]);
    }
}
