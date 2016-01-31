<?php

function captiva_logos_metaboxes( $meta_boxes ) {
    $prefix = '_cap_'; // Prefix for all fields
    $meta_boxes['logo'] = array(
        'id' => 'logo_metabox',
        'title' => 'Logo Details',
        'pages' => array( 'logos' ), // post type
        'context' => 'normal',
        'priority' => 'high',
        'show_names' => true, // Show field names on the left
        'fields' => array(
            array(
                'name' => __( 'Logo link destination', 'captiva' ),
                'desc' => __( 'Where the logo should link to. Please do not forget to include the http://', 'captiva' ),
                'id' => $prefix . 'logo_url',
                'type' => 'text'
            ),
            array(
                'name' => __( 'Logo image', 'captiva' ),
                'desc' => __( 'Upload an image or enter a URL.', 'captiva' ),
                'id' => $prefix . 'logo_image',
                'type' => 'file',
            ),
        ),
    );

    return $meta_boxes;
}

add_filter( 'cmb_meta_boxes', 'captiva_logos_metaboxes' );
