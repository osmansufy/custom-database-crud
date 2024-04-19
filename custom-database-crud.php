<?php

/**
 * Plugin Name: Custom Database CRUD
 * Description: A plugin to demonstrate CRUD operations on a custom database table.
 * Version: 1.0.0
 * Author: Osman Goni Sufy
 * Author URI: osmansufy.com
 * Text Domain: custom-database-crud
 */


if (!defined('ABSPATH')) {
    exit;
}

require_once __DIR__ . '/vendor/autoload.php';

/* 
Plugin root file is the entry point of the plugin.
*/
final class Custom_Database_CRUD
{

    const version = '1.0.0';
    private function __construct()
    {
        $this->define_constants();
        register_activation_hook(__FILE__, [$this, 'activate']);
        add_action('plugins_loaded', [$this, 'initialize_plugin']);

        // admin enqueue scripts
        add_action('admin_enqueue_scripts', [$this, 'enqueue_admin_scripts']);
    }


    public function enqueue_admin_scripts()
    {
        wp_enqueue_style('custom-database-crud-admin-css', CDC_PLUGIN_URL . 'assets/css/admin.css', [], time());
        wp_enqueue_script('custom-database-crud-admin-js', CDC_PLUGIN_URL . 'assets/js/admin.js', ['jquery'], time(), true);
        wp_localize_script('custom-database-crud-admin-js', 'cdc_ajax_object', [
            'ajax_url' => admin_url('admin-ajax.php'),
            'ajax_nonce' => wp_create_nonce('delete_record_nonce')
        ]);
    }

    public static function init()
    {
        static $instance = null;

        if ($instance === null) {
            $instance = new self();
        }

        return $instance;
    }
    public function define_constants()
    {
        define('CDC_VERSION', self::version);
        define('CDC_TEXT_DOMAIN', 'custom-database-crud');
        define('CDC_PLUGIN_PATH', plugin_dir_path(__FILE__));
        define('CDC_PLUGIN_URL', plugin_dir_url(__FILE__));
        define('CDC_PLUGIN_BASENAME', plugin_basename(__FILE__));
    }

    public function initialize_plugin()
    {
        if (is_admin()) {
            new CustomDatabaseCrud\Admin\AdminMenu();
            new CustomDatabaseCrud\Admin\AdminAction();
        }
    }

    public function activate()
    {

        CustomDatabaseCrud\Activator::activate();
    }
}


Custom_Database_CRUD::init();
