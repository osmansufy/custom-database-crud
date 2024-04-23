<?php


/* 

Class for display item list in admin panel extends WP_List_Table
*/


namespace CustomDatabaseCrud;


if (!class_exists('WP_List_Table')) {
    require_once(ABSPATH . 'wp-admin/includes/class-wp-list-table.php');
}

class ItemList extends \WP_List_Table
{

    public function __construct()
    {
        parent::__construct([
            'singular' => 'item',
            'plural'   => 'items',
            'ajax'     => false
        ]);
    }
    /**
     * Message to show if no designation found
     *
     * @return void
     */
    function no_items()
    {
        _e('No address found', 'custom-database-crud');
    }
    public function get_columns()
    {
        $columns = [
            'cb'      => '<input type="checkbox" />',
            'name'    => __('Name', 'custom-database-crud'),
            'email'   => __('Email', 'custom-database-crud'),
            'phone'   => __('Phone', 'custom-database-crud'),
            'address' => __('Address', 'custom-database-crud'),
            'id'      => __('ID', 'custom-database-crud'),
            'created_at' => __('Date', 'custom-database-crud'),
        ];

        return $columns;
    }

    protected function column_default($item, $column_name)
    {

        switch ($column_name) {

            case 'created_at':
                return wp_date(get_option('date_format'), strtotime($item['created_at']));

            default:
                return isset($item[$column_name]) ? $item[$column_name] : '';
        }
    }

    public function column_name($item)
    {
        $title = '<strong>' . $item['name'] . '</strong>';

        $actions = [];
        $actions['edit']   = sprintf('<a href="?page=%s&action=%s&id=%s">Edit</a>', $_REQUEST['page'], 'edit', $item['id']);
        $actions['delete'] = sprintf('<a class="cdc-delete-button" style="cursor:pointer" data-record-id="%s">Delete</a>', $item['id']);

        return $title . $this->row_actions($actions);
    }

    public function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="bulk-delete[]" value="%s" />',
            $item['id']
        );
    }

    public function get_sortable_columns()
    {
        $sortable_columns = array(
            'name' => ['name', false],
            'created_at' => ['created_at', false],
        );

        return $sortable_columns;
    }

    public function get_bulk_actions()
    {
        $actions = [
            'bulk-delete' => 'Delete'
        ];

        return $actions;
    }

    public function prepare_items()
    {
        $this->_column_headers = [
            $this->get_columns(),
            [],
            $this->get_sortable_columns(),
        ];


        $per_page     = $this->get_items_per_page('items_per_page', 2);
        $current_page = $this->get_pagenum();

        $total_items = cdc_get_items_count();


        $offset       = ($current_page - 1) * $per_page;

        $args = [
            'number' => $per_page,
            'offset' => $offset,
            'orderby' => !empty($_REQUEST['orderby']) ? $_REQUEST['orderby'] : 'id',
            'order'   => !empty($_REQUEST['order']) ? $_REQUEST['order'] : 'ASC',
        ];

        // Handle search
        if (isset($_POST['s']) && !empty($_POST['s'])) {
            $search = sanitize_text_field($_POST['s']);
            $args['search'] = $search;
        }


        // Process bulk actions
        $this->process_bulk_action();

        $this->items = cdc_get_all_items($args);
        $this->set_pagination_args([
            'total_items' => $total_items,
            'per_page'    => $per_page
        ]);
    }

    public function search_box($text, $input_id)
    {
        if (empty($_REQUEST['s']) && !$this->has_items()) {
            return;
        }

        $input_id = $input_id . '-search-input';
?>
        <p class="search-box">
            <label class="screen-reader-text" for="<?php echo $input_id ?>"><?php echo $text; ?>:</label>
            <input type="search" id="<?php echo $input_id ?>" name="s" value="<?php _admin_search_query() ?>" />
            <?php submit_button($text, 'button', false, false, ['ID' => 'search-submit']); ?>
        </p>
<?php

    }

    // Bulk action handler

    public function process_bulk_action()
    {
        if ('bulk-delete' === $this->current_action()) {
            global $wpdb;

            $ids = isset($_REQUEST['bulk-delete']) ? $_REQUEST['bulk-delete'] : [];

            if (is_array($ids)) $ids = implode(',', $ids);

            if (!empty($ids)) {
                $wpdb->query("DELETE FROM {$wpdb->prefix}custom_database_crud WHERE id IN($ids)");
            }
        }
    }
}
