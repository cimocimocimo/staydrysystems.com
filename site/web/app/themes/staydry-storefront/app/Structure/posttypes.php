<?php

namespace Tonik\Theme\App\Structure;

/*
|-----------------------------------------------------------
| Theme Custom Post Types
|-----------------------------------------------------------
|
| This file is for registering your theme post types.
| Custom post types allow users to easily create
| and manage various types of content.
|
*/

use function Tonik\Theme\App\config;

/**
 * Registers `corporate_customer` custom post type.
 *
 * @return void
 */
function register_corporate_customer_post_type()
{
    $text_domain = config('textdomain');

    register_post_type('corporate_customer', [
        'description' => __('Corporate customers who have purchased StayDry products.', $text_domain),
        'public' => false,
        'show_in_menu' => true,
        'show_ui' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-building',
        'supports' => ['title', 'thumbnail'],
        'labels' => [
            'name' => _x('Corp. Customers', 'post type general name', $text_domain),
            'singular_name' => _x('Corp. Customer', 'post type singular name', $text_domain),
            'menu_name' => _x('Corp. Customers', 'admin menu', $text_domain),
            'name_admin_bar' => _x('Corp. Customer', 'add new on admin bar', $text_domain),
            'add_new' => _x('Add New', 'corp. customer', $text_domain),
            'add_new_item' => __('Add New Corp. Customer', $text_domain),
            'new_item' => __('New Corp. Customer', $text_domain),
            'edit_item' => __('Edit Corp. Customer', $text_domain),
            'view_item' => __('View Corp. Customer', $text_domain),
            'all_items' => __('All Corp. Customers', $text_domain),
            'search_items' => __('Search Corp. Customers', $text_domain),
            'parent_item_colon' => __('Parent Corp. Customers:', $text_domain),
            'not_found' => __('No corporate customers found.', $text_domain),
            'not_found_in_trash' => __('No corporate customers found in Trash.', $text_domain),
        ],
    ]);
}
add_action('init', 'Tonik\Theme\App\Structure\register_corporate_customer_post_type');

/**
 * Registers `faq` custom post type and `faq_taxonomy` taxonomy.
 *
 * @return void
 */
add_action('init', function () {
    $text_domain = config('textdomain');

    register_post_type('faq', [
        'description' => __('Fequently asked questions from StayDry customers.', $text_domain),
        'supports' => array('title', 'editor', 'excerpt', ),
        'taxonomies' => array('faq_taxonomy'),
        'public' => false,
        'show_in_menu' => true,
        'show_ui' => true,
        'menu_position' => 6,
        'menu_icon' => 'dashicons-lightbulb',
        'labels' => [
            'name' => _x('FAQs', 'Post Type General Name', $text_domain),
            'singular_name' => _x('FAQ', 'Post Type Singular Name', $text_domain),
            'menu_name' => __('FAQ', $text_domain),
            'parent_item_colon' => __('Parent Item:', $text_domain),
            'all_items' => __('All Items', $text_domain),
            'view_item' => __('View Item', $text_domain),
            'add_new_item' => __('Add New FAQ Item', $text_domain),
            'add_new' => __('Add New', $text_domain),
            'edit_item' => __('Edit Item', $text_domain),
            'update_item' => __('Update Item', $text_domain),
            'search_items' => __('Search Item', $text_domain),
            'not_found' => __('Not found', $text_domain),
            'not_found_in_trash' => __('Not found in Trash', $text_domain),
        ],
    ]);

    register_taxonomy('faq_taxonomy', ['faq'], [
        'labels' => [
            'name' => _x('FAQ Categories', 'Taxonomy General Name', $text_domain),
            'singular_name' => _x('FAQ Category', 'Taxonomy Singular Name', $text_domain),
            'menu_name' => __('FAQ Categories', $text_domain),
            'all_items' => __('All FAQ Cats', $text_domain),
            'parent_item' => __('Parent FAQ Cat', $text_domain),
            'parent_item_colon' => __('Parent FAQ Cat:', $text_domain),
            'new_item_name' => __('New FAQ Cat', $text_domain),
            'add_new_item' => __('Add New FAQ Cat', $text_domain),
            'edit_item' => __('Edit FAQ Cat', $text_domain),
            'update_item' => __('Update FAQ Cat', $text_domain),
            'separate_items_with_commas' => __('Separate items with commas', $text_domain),
            'search_items' => __('Search Items', $text_domain),
            'add_or_remove_items' => __('Add or remove items', $text_domain),
            'choose_from_most_used' => __('Choose from the most used items', $text_domain),
            'not_found' => __('Not Found', $text_domain),
        ],
        'hierarchical' => true,
        'show_admin_column' => true,
        'public' => false,
        'rewrite' => false,
        'show_ui' => true,
    ]);
});
