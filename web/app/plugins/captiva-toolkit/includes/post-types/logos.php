<?php

$labels = array(
    'name' => __( 'Logos', 'captiva' ),
    'singular_name' => __( 'Logo', 'captiva' ),
    'add_new' => __( 'Add New', 'captiva' ),
    'add_new_item' => __( 'Add New Logo', 'captiva' ),
    'edit_item' => __( 'Edit Logo', 'captiva' ),
    'new_item' => __( 'New Logo', 'captiva' ),
    'view_item' => __( 'View Logo', 'captiva' ),
    'search_items' => __( 'Search Logos', 'captiva' ),
    'not_found' => __( 'No Logos found', 'captiva' ),
    'not_found_in_trash' => __( 'No Logos in trash', 'captiva' ),
    'parent_item_colon' => ''
);

$args = array(
    'labels' => $labels,
    'public' => false,
    'exclude_from_search' => true,
    'publicly_queryable' => false,
    'rewrite' => array( 'slug' => 'logos' ),
    'show_ui' => true,
    'query_var' => true,
    'capability_type' => 'post',
    'hierarchical' => false,
    'menu_position' => 35,
    'menu_icon' => 'dashicons-welcome-view-site',
    'has_archive' => false,
    'supports' => array( 'title', 'editor' )
);

register_post_type( 'logos', $args );

function captiva_logos_edit_columns( $columns ) {
    $columns = array(
        "cb" => "<input type=\"checkbox\" />",
        "title" => __( 'Logo Name', 'captiva' ),
        "date" => __( 'Date', 'captiva' )
    );
    return $columns;
}

add_filter( 'manage_edit-logos_columns', 'captiva_logos_edit_columns' );
