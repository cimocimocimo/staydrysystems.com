<?php

function captiva_testimonial_metaboxes( $meta_boxes ) {
    $prefix = '_cap_'; // Prefix for all fields
    $meta_boxes['testimonial'] = array(
        'id' => 'testimonial_metabox',
        'title' => 'Testimonial Details',
        'pages' => array( 'testimonials' ), // post type
        'context' => 'normal',
        'priority' => 'high',
        'show_names' => true, // Show field names on the left
        'fields' => array(
            array(
                'name' => __( 'Name of person providing the testimonial', 'captiva' ),
                'desc' => __( 'This can also be anonymous', 'captiva' ),
                'id' => $prefix . 'testimonial_name',
                'type' => 'text'
            ),
            array(
                'name' => __( 'Organization name', 'captiva' ),
                'desc' => __( 'Enter the organization your clients works for.', 'captiva' ),
                'id' => $prefix . 'testimonial_org_name',
                'type' => 'text'
            ),
            array(
                'name' => __( 'Testimonial Face Profile Image', 'captiva' ),
                'desc' => __( 'Upload an image or enter a URL.', 'captiva' ),
                'id' => $prefix . 'testimonial_image',
                'type' => 'file',
            ),
        ),
    );

    return $meta_boxes;
}

add_filter( 'cmb_meta_boxes', 'captiva_testimonial_metaboxes' );
