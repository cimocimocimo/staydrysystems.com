<?php

function captiva_topreviews_metaboxes( $meta_boxes ) {
    $prefix = '_cap_'; // Prefix for all fields
    $meta_boxes['topreview'] = array(
        'id' => 'topreview_metabox',
        'title' => 'Top Review Details',
        'pages' => array( 'topreviews' ), // post type
        'context' => 'normal',
        'priority' => 'high',
        'show_names' => true, // Show field names on the left
        'fields' => array(
            array(
                'name' => __( 'Name of person providing the testimonial', 'captiva' ),
                'desc' => __( 'This can also be anonymous', 'captiva' ),
                'id' => $prefix . 'topreview_name',
                'type' => 'text'
            ),
            array(
                'name' => __( 'Organization name', 'captiva' ),
                'desc' => __( 'Enter the organization your clients works for.', 'captiva' ),
                'id' => $prefix . 'topreview_org_name',
                'type' => 'text'
            ),
        ),
    );

    return $meta_boxes;
}

add_filter( 'cmb_meta_boxes', 'captiva_topreviews_metaboxes' );
