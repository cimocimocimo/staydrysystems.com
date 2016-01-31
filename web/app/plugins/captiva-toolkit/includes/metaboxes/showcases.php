<?php

function captiva_showcases_metaboxes( $meta_boxes ) {
    $prefix = '_cap_'; // Prefix for all fields
    $meta_boxes['showcase'] = array(
        'id' => 'showcase_metabox',
        'title' => 'Showcase Details',
        'pages' => array( 'showcases' ), // post type
        'context' => 'normal',
        'priority' => 'high',
        'show_names' => true, // Show field names on the left
        'fields' => array(
            array(
                'name' => __( 'Showcase url/link', 'captiva' ),
                'desc' => __( 'Where the showcase should link to. Please do not forget to include the http://', 'captiva' ),
                'id' => $prefix . 'showcase_url',
                'type' => 'text'
            ),
            array(
                'name' => __( 'Showcase url/link description', 'captiva' ),
                'desc' => __( 'This is the text description displayed when clicking on the showcase url/link.', 'captiva' ),
                'id' => $prefix . 'showcase_url_desc',
                'type' => 'text'
            ),
            array(
                'name' => __( 'Showcase image gallery', 'captiva' ),
                'desc' => __( 'Upload images and they will be shown in a slideshow..', 'captiva' ),
                'id' => $prefix . 'showcase_gallery',
                'type' => 'cap_gallery',
                'sanitization_cb' => 'cap_gallery_field_sanitise',
            ),
            array(
                'name' => __( 'Showcase Video Source', 'captiva' ),
                'desc' => __( 'As an alternative to a Showcase image gallery, you can embed a showcase video.', 'captiva' ),
                'id' => $prefix . 'showcase_video_source',
                'type' => 'select',
                'options' => array(
                    'youtube' => __( 'Youtube', 'captiva' ),
                    'vimeo' => __( 'Vimeo', 'captiva' ),
                ),
            ),
            array(
                'name' => __( 'Showcase Video ID', 'captiva' ),
                'desc' => __( 'Copy the ID of the video (E.g. http://www.youtube.com/watch?v=<strong>Sv6dMFF_yts</strong>) you want to show..', 'captiva' ),
                'id' => $prefix . 'showcase_video_embed',
                'type' => 'textarea'
            ),
        ),
    );

    return $meta_boxes;
}

add_filter( 'cmb_meta_boxes', 'captiva_showcases_metaboxes' );
