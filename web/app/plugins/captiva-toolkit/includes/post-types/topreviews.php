<?php

$labels = array(
    'name' => __( 'Top Reviews', 'captiva' ),
    'singular_name' => __( 'Top Review', 'captiva' ),
    'add_new' => __( 'Add New', 'captiva' ),
    'add_new_item' => __( 'Add New Top Review', 'captiva' ),
    'edit_item' => __( 'Edit Top Review', 'captiva' ),
    'new_item' => __( 'New Top Review', 'captiva' ),
    'view_item' => __( 'View Top Review', 'captiva' ),
    'search_items' => __( 'Search Top Reviews', 'captiva' ),
    'not_found' => __( 'No Top Reviews found', 'captiva' ),
    'not_found_in_trash' => __( 'No Top Reviews in trash', 'captiva' ),
    'parent_item_colon' => ''
);

$args = array(
    'labels' => $labels,
    'public' => false,
    'exclude_from_search' => true,
    'publicly_queryable' => false,
    'rewrite' => array( 'slug' => 'topreviews' ),
    'show_ui' => true,
    'query_var' => true,
    'capability_type' => 'post',
    'hierarchical' => false,
    'menu_position' => 35,
    'menu_icon' => 'dashicons-groups',
    'has_archive' => false,
    'supports' => array( 'title', 'editor' )
);

register_post_type( 'topreviews', $args );

function captiva_topreviews_edit_columns( $columns ) {
    $columns = array(
        "cb" => "<input type=\"checkbox\" />",
        "title" => __( 'Top Review Title', 'captiva' ),
        "date" => __( 'Date', 'captiva' )
    );
    return $columns;
}

add_filter( 'manage_edit-topreviews_columns', 'captiva_topreviews_edit_columns' );
