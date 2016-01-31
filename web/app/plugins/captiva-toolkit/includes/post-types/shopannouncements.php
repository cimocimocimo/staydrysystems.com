<?php

$labels = array(
    'name' => __( 'Announcements', 'captiva' ),
    'singular_name' => __( 'Announcement', 'captiva' ),
    'add_new' => __( 'Add New', 'captiva' ),
    'add_new_item' => __( 'Add New Announcement', 'captiva' ),
    'edit_item' => __( 'Edit Announcement', 'captiva' ),
    'new_item' => __( 'New Announcement', 'captiva' ),
    'view_item' => __( 'View Announcement', 'captiva' ),
    'search_items' => __( 'Search Announcements', 'captiva' ),
    'not_found' => __( 'No Announcements found', 'captiva' ),
    'not_found_in_trash' => __( 'No Announcements in trash', 'captiva' ),
    'parent_item_colon' => ''
);

$args = array(
    'labels' => $labels,
    'public' => false,
    'exclude_from_search' => true,
    'publicly_queryable' => false,
    'rewrite' => array( 'slug' => 'shopannouncements' ),
    'show_ui' => true,
    'query_var' => true,
    'capability_type' => 'post',
    'hierarchical' => false,
    'menu_position' => 35,
    'menu_icon' => 'dashicons-megaphone',
    'has_archive' => false,
    'supports' => array( 'title', 'editor' )
);

register_post_type( 'shopannouncements', $args );

function captiva_shopannouncements_edit_columns( $columns ) {
    $columns = array(
        "cb" => "<input type=\"checkbox\" />",
        "title" => __( 'Shop announcement Title', 'captiva' ),
        "date" => __( 'Date', 'captiva' )
    );
    return $columns;
}

add_filter( 'manage_edit-shopannouncements_columns', 'captiva_shopannouncements_edit_columns' );
