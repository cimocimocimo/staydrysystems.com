<?php

$labels = array(
    'name' => __( 'Testimonials', 'captiva' ),
    'singular_name' => __( 'Testimonial', 'captiva' ),
    'add_new' => __( 'Add New', 'captiva' ),
    'add_new_item' => __( 'Add New Testimonial', 'captiva' ),
    'edit_item' => __( 'Edit Testimonial', 'captiva' ),
    'new_item' => __( 'New Testimonial', 'captiva' ),
    'view_item' => __( 'View Testimonial', 'captiva' ),
    'search_items' => __( 'Search Testimonials', 'captiva' ),
    'not_found' => __( 'No Testimonials found', 'captiva' ),
    'not_found_in_trash' => __( 'No Testimonials in trash', 'captiva' ),
    'parent_item_colon' => ''
);

$args = array(
    'labels' => $labels,
    'public' => false,
    'exclude_from_search' => true,
    'publicly_queryable' => false,
    'rewrite' => array( 'slug' => 'testimonials' ),
    'show_ui' => true,
    'query_var' => true,
    'capability_type' => 'post',
    'hierarchical' => false,
    'menu_position' => 35,
    'menu_icon' => 'dashicons-format-quote',
    'has_archive' => false,
    'supports' => array( 'title', 'editor' )
);

register_post_type( 'testimonials', $args );

function captiva_testimonials_edit_columns( $columns ) {
    $columns = array(
        "cb" => "<input type=\"checkbox\" />",
        "title" => __( 'Testimonial Title', 'captiva' ),
        "date" => __( 'Date', 'captiva' )
    );
    return $columns;
}

add_filter( 'manage_edit-testimonials_columns', 'captiva_testimonials_edit_columns' );
