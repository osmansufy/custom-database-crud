<?php

/* 
File responsible for displaying the list of items.
*/


if (!defined('ABSPATH')) {
    exit;
}
// Check if the updated transient is set
if (get_transient('item_updated')) :
    // Display success message
?>
    <div class="notice notice-success">
        <p>Record updated successfully!</p>
    </div>
<?php
    // Delete the transient to clear the message
    delete_transient('item_updated');
endif;

// check if a new item is added and display a success message

if (get_transient('item_added')) :
?>
    <div class="notice notice-success">
        <p>Record added successfully!</p>
    </div>
<?php
    // Delete the transient to clear the message
    delete_transient('item_added');
endif;



global $wpdb;


$table_name = $wpdb->prefix . 'custom_database_crud';


$items = $wpdb->get_results("SELECT * FROM $table_name", ARRAY_A);


?>

<div class="wrap cdc-db-item-list">
    <h1 class="wp-heading-inline"><?php _e('Items', 'custom-database-crud'); ?></h1>
    <a href="<?php echo admin_url('admin.php?page=custom-database-crud&action=new'); ?>" class="page-title-action "><?php _e('Add New', 'custom-database-crud'); ?></a>
    <hr class="wp-header-end">
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th><?php _e('ID', 'custom-database-crud'); ?></th>
                <th><?php _e('Name', 'custom-database-crud'); ?></th>
                <th><?php _e('Email', 'custom-database-crud'); ?></th>
                <th><?php _e('Phone', 'custom-database-crud'); ?></th>
                <th><?php _e('Address', 'custom-database-crud'); ?></th>
                <th><?php _e('Created At', 'custom-database-crud'); ?></th>
                <th><?php _e('Actions', 'custom-database-crud'); ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($items as $item) : ?>
                <tr id="cdc-table-row-<?php echo $item['id']; ?>">
                    <td><?php echo $item['id']; ?></td>
                    <td><?php echo esc_html($item['name']); ?></td>
                    <td><?php echo esc_html($item['email']); ?></td>
                    <td><?php echo esc_html($item['phone']); ?></td>
                    <td><?php echo esc_html($item['address']); ?></td>
                    <td><?php echo $item['created_at']; ?></td>
                    <td>
                        <a href="<?php echo admin_url('admin.php?page=custom-database-crud&action=edit&id=' . $item['id']); ?>"><?php _e('Edit', 'custom-database-crud'); ?></a>
                        <a class="cdc-delete-button" data-record-id="<?php echo $item['id']; ?>" href="#"><?php _e('Delete', 'custom-database-crud'); ?></a>
                    </td>

                </tr>
            <?php endforeach; ?>

        </tbody>
    </table>
</div>