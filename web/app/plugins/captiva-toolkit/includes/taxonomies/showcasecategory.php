<?php

    $labels = array(
        'name' => _x('Showcase Categories', 'taxonomy general name', 'captiva'),
        'singular_name' => _x('Showcase Category', 'taxonomy singular name', 'captiva'),
        'search_items' => __('Search Showcase Categories', 'captiva'),
        'popular_items' => __('Popular Showcase Categories', 'captiva'),
        'all_items' => __('All Showcase Categories', 'captiva'),
        'parent_item' => null,
        'parent_item_colon' => null,
        'edit_item' => __('Edit Showcase Category', 'captiva'),
        'update_item' => __('Update Showcase Category', 'captiva'),
        'add_new_item' => __('Add New Showcase Category', 'captiva'),
        'new_item_name' => __('New Showcase Category Name', 'captiva'),
        'separate_items_with_commas' => __('Separate showcase categories with commas', 'captiva'),
        'add_or_remove_items' => __('Add or remove showcase categories', 'captiva'),
        'choose_from_most_used' => __('Choose from the most used showcase categories', 'captiva'),
    );

    register_taxonomy('cap_showcasecategory', 'showcases', array(
        'label' => __('Showcase Category', 'captiva'),
        'labels' => $labels,
        'hierarchical' => true,
        'show_ui' => true,
        'query_var' => true,
        'rewrite' => array('slug' => 'showcase-category'),
    ));

?>