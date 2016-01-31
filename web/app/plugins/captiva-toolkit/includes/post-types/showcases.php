<?php

$labels = array(
    'name' => __( 'Showcases', 'captiva' ),
    'singular_name' => __( 'Showcase', 'captiva' ),
    'rewrite' => array( 'slug' => __( 'showcase', 'captiva' ) ),
    'add_new' => _x( 'Add New', 'showcase', 'captiva' ),
    'add_new_item' => __( 'Add New Showcase', 'captiva' ),
    'edit_item' => __( 'Edit Showcase', 'captiva' ),
    'new_item' => __( 'New Showcase', 'captiva' ),
    'view_item' => __( 'View Showcase', 'captiva' ),
    'search_items' => __( 'Search Showcases', 'captiva' ),
    'not_found' => __( 'No showcases found', 'captiva' ),
    'not_found_in_trash' => __( 'No showcases found in Trash', 'captiva' ),
    'parent_item_colon' => ''
);

$args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true,
    'query_var' => true,
    'rewrite' => array( 'slug' => 'showcase' ),
    'capability_type' => 'post',
    'hierarchical' => false,
    'menu_position' => null,
    'menu_icon' => 'dashicons-welcome-view-site',
    'supports' => array( 'title', 'excerpt', 'editor', 'thumbnail' )
);

register_post_type( 'showcases', $args );

function captiva_showcases_edit_columns( $columns ) {
    $columns = array(
        "cb" => "<input type=\"checkbox\" />",
        "title" => __( 'Showcase Title', 'captiva' ),
        "date" => __( 'Date', 'captiva' )
    );
    return $columns;
}

add_filter( 'manage_edit-showcases_columns', 'captiva_showcases_edit_columns' );
