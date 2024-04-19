<?php

/* 
File responsible for displaying the form for adding a new item to the database table.
*/


if (!defined('ABSPATH')) {
    exit;
}


global $wpdb;

$table_name = $wpdb->prefix . 'custom_database_crud';

if (isset($_POST['submit'])) {
    // Verify nonce
    if (!isset($_POST['_wpnonce']) || !wp_verify_nonce($_POST['_wpnonce'], 'new-item')) {
        wp_die('Security check');
    }
    // capability check
    if (!current_user_can('manage_options')) {
        wp_die('Access Denied');
    }
    $name = isset($_POST['name']) ? sanitize_text_field($_POST['name']) : '';
    $email = isset($_POST['email']) ? sanitize_email($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? sanitize_text_field($_POST['phone']) : '';
    $address = isset($_POST['address']) ? sanitize_textarea_field($_POST['address']) : '';

    if (empty($name) || empty($email) || empty($phone) || empty($address)) {
        $error_message = __('All fields are required', 'custom-database-crud');

        return;
    }
    $data = [
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'address' => $address,
        'created_at' => current_time('mysql'),
    ];

    $inserted =  $wpdb->insert(
        $table_name,
        $data,
        ['%s', '%s', '%s', '%s', '%s']

    );

    if (!$inserted) {
        if ($wpdb->last_error !== '') {
            echo "<div class='notice notice-error'><p>" . __('wpdb error: ', 'custom-database-crud') . $wpdb->last_error . "</p></div>";
        }
        return;
    }

    // set a transient to display a success message
    set_transient('item_added', true, 60);


    // Redirect to the items list page
    wp_redirect(admin_url('admin.php?page=custom-database-crud'), 301, 'add');
}

?>

<div class="wrap">
    <h1 class="wp-heading-inline"><?php _e('Add New Item', 'custom-database-crud'); ?></h1>
    <a href="<?php echo admin_url('admin.php?page=custom-database-crud'); ?>" class="page-title-action"><?php _e('Back to Items', 'custom-database-crud'); ?></a>

    <form method="post">
        <table class="form-table">
            <tbody>
                <tr class="row-name">
                    <th scope="row">
                        <label for="name"><?php _e('Name', 'custom-database-crud'); ?></label>
                    </th>
                    <td>
                        <input type="text" name="name" id="name" class="regular-text" required>
                    </td>
                </tr>
                <tr class="row-email">
                    <th scope="row">
                        <label for="email"><?php _e('Email', 'custom-database-crud'); ?></label>
                    </th>
                    <td>
                        <input type="email" name="email" id="email" class="regular-text" required>
                    </td>
                </tr>
                <tr class="row-phone">
                    <th scope="row">
                        <label for="phone"><?php _e('Phone', 'custom-database-crud'); ?></label>
                    </th>
                    <td>
                        <input type="text" name="phone" id="phone" class="regular-text" required>
                    </td>
                </tr>
                <tr class="row-address">
                    <th scope="row">
                        <label for="address"><?php _e('Address', 'custom-database-crud'); ?></label>
                    </th>
                    <td>
                        <textarea name="address" id="address" class="regular-text" required></textarea>
                    </td>
                </tr>
            </tbody>
        </table>
        <!-- nonce field -->

        <?php wp_nonce_field('new-item'); ?>


        <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Add Item', 'custom-database-crud'); ?>">
    </form>
</div>

// Path: wp-content/plugins/custom-database-crud/includes/views/items-view.php