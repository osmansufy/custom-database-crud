<?php

/* 
Fetch all items from the database

*/

/**
 * Fetch all_items
 *
 * @param  array  $args
 *
 * @return array
 */
function cdc_get_all_items($args = [])
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'custom_database_crud';
    $defaults = [
        'number'  => 20,
        'offset'  => 0,
        'orderby' => 'id',
        'order'   => 'ASC'
    ];

    $args = wp_parse_args($args, $defaults);

    $sql = $wpdb->prepare("SELECT * FROM $table_name ");

    if (!empty($args['search'])) {
        $sql .= $wpdb->prepare(" WHERE name LIKE '%%%s%%' ", $args['search']);
    }

    if (!empty($args['orderby']) && !empty($args['order'])) {
        $sql .= ' ORDER BY ' . $args['orderby'] . ' ' . $args['order'];
    }

    $sql .= ' LIMIT ' . $args['number'] . ' OFFSET ' . $args['offset'];

    $result = $wpdb->get_results($sql, ARRAY_A);

    return $result;
}


/* 
Count all items from the database
*/

function cdc_get_items_count()
{
    global $wpdb;

    $table_name = $wpdb->prefix . 'custom_database_crud';


    $result = $wpdb->get_var("SELECT COUNT(id) FROM $table_name");

    return $result;
}
