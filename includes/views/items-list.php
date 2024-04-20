<?php

/* 
File responsible for displaying the list of items.
*/


if (!defined('ABSPATH')) {
    exit;
}


?>
<div class="wrap cdc-db-item-list">
    <?php
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
    ?>
    <h1 class="wp-heading-inline"><?php _e('Items', 'custom-database-crud'); ?></h1>
    <a href="<?php echo admin_url('admin.php?page=custom-database-crud&action=new'); ?>" class="page-title-action "><?php _e('Add New', 'custom-database-crud'); ?></a>
    <hr class="wp-header-end">
    <form action="" method="post">
        <?php
        $table = new CustomDatabaseCrud\ItemList();
        $table->prepare_items();
        $table->search_box('search', 'search_id');
        $table->display();
        ?>
    </form>

</div>