<?php

/* 
File responsible for displaying the form for editing an item in the database table.
*/


if (!defined('ABSPATH')) {
    exit;
}


global $wpdb;


$table_name = $wpdb->prefix . 'custom_database_crud';


if (isset($_POST['submit'])) {
    $id = isset($_POST['id']) ? intval($_POST['id']) : '';
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
    ];

    $where = ['id' => $id];

    $updated =  $wpdb->update(
        $table_name,
        $data,
        $where,
        ['%s', '%s', '%s', '%s'],
        ['%d']
    );

    if (!$updated) {
        if ($wpdb->last_error !== '') {
            echo "<div class='notice notice-error'><p>" . __('wpdb error: ', 'custom-database-crud') . $wpdb->last_error . "</p></div>";
        }
        return;
    }
    // set a transient to display a success message
    set_transient('item_updated', true, 60);
    // Redirect to the items list page
    wp_redirect(admin_url('admin.php?page=custom-database-crud'), 301, 'edit');
}


$id = isset($_GET['id']) ? intval($_GET['id']) : '';

$item = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $id), ARRAY_A);


if (!$item) {
    wp_redirect(admin_url('admin.php?page=custom-database-crud'), 301);
    exit;
}

?>

<div class="wrap">
    <h1 class="wp-heading-inline"><?php _e('Edit Item', 'custom-database-crud'); ?></h1>
    <a href="<?php echo admin_url('admin.php?page=custom-database-crud'); ?>" class="page-title-action"><?php _e('Back to Items', 'custom-database-crud'); ?></a>

    <form method="post">
        <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
        <table class="form-table">
            <tbody>
                <tr class="row-name">
                    <th scope="row">
                        <label for="name"><?php _e('Name', 'custom-database-crud'); ?></label>
                    </th>
                    <td>
                        <input type="text" name="name" id="name" value="<?php echo esc_attr($item['name']); ?>" class="regular-text">
                    </td>
                </tr>
                <tr class="row-email">
                    <th scope="row">
                        <label for="email"><?php _e('Email', 'custom-database-crud'); ?></label>
                    </th>
                    <td>
                        <input type="email" name="email" id="email" value="<?php echo esc_attr($item['email']); ?>" class="regular-text">
                    </td>
                </tr>
                <tr class="row-phone">
                    <th scope="row">
                        <label for="phone"><?php _e('Phone', 'custom-database-crud'); ?></label>
                    </th>
                    <td>
                        <input type="text" name="phone" id="phone" value="<?php echo esc_attr($item['phone']); ?>" class="regular-text">
                    </td>
                </tr>
                <tr class="row-address">
                    <th scope="row">
                        <label for="address"><?php _e('Address', 'custom-database-crud'); ?></label>
                    </th>
                    <td>
                        <textarea name="address" id="address" class="large-text"><?php echo esc_textarea($item['address']); ?></textarea>
                    </td>
                </tr>
            </tbody>
        </table>

        <?php wp_nonce_field('edit-item'); ?>


        <p class="submit">
            <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php _e('Update Item', 'custom-database-crud'); ?>">
        </p>
    </form>

</div>
<?php
// Path: wp-content/plugins/custom-database-crud/includes/views/items-new.php