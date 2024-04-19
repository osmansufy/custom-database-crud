<?php


namespace CustomDatabaseCrud\Admin;

/**
 * The admin menu handler class
 */


class AdminMenu
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'admin_menu']);
    }

    public function admin_menu()
    {
        add_menu_page(
            __('Custom Database CRUD', 'custom-database-crud'),
            __('Custom Database CRUD', 'custom-database-crud'),
            'manage_options',
            'custom-database-crud',
            [$this, 'plugin_page'],
            'dashicons-admin-generic',
            100
        );
    }

    public function plugin_page()
    {
        $action = isset($_GET['action']) ? $_GET['action'] : 'items';
        switch ($action) {
            case 'new':
                $template = CDC_PLUGIN_PATH . 'includes/views/items-new.php';
                break;

            case 'edit':
                $template = CDC_PLUGIN_PATH . 'includes/views/items-edit.php';
                break;
            default:
                $template = CDC_PLUGIN_PATH . 'includes/views/items-list.php';
                break;
        }

        if (file_exists($template)) {
            include $template;
        }
    }
}
