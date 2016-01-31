<?php

function captiva_shopannouncements_metaboxes( $meta_boxes ) {
    $prefix = '_cap_'; // Prefix for all fields
    $meta_boxes['shopannouncements'] = array(
        'id' => 'shopannouncements_metabox',
        'title' => 'Shop announcement title',
        'pages' => array( 'shopannouncements' ), // post type
        'context' => 'normal',
        'priority' => 'high',
        'show_names' => true, // Show field names on the left
        'fields' => array(
            array(
                'name' => __( 'Announcement background color', 'captiva' ),
                'desc' => __( 'The announcement has a solid background color', 'captiva' ),
                'id' => $prefix . 'shopannouncement_bgcolor',
                'type' => 'colorpicker',
                'default' => '#82b965'
            ),
            array(
                'name' => __( 'Announcement text color', 'captiva' ),
                'desc' => __( 'The text color for the announcement', 'captiva' ),
                'id' => $prefix . 'shopannouncement_txtcolor',
                'type' => 'colorpicker',
                'default' => '#fff'
            ),
        ),
    );

    return $meta_boxes;
}

add_filter( 'cmb_meta_boxes', 'captiva_shopannouncements_metaboxes' );
