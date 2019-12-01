<?php

namespace Tonik\Theme\App\Setup;

/*
|-----------------------------------------------------------
| Theme Filters
|-----------------------------------------------------------
|
| This file purpose is to include your theme various
| filters hooks, which changes output or behaviour
| of different parts of WordPress functions.
|
*/

use function Tonik\Theme\App\theme;

/**
 * Hides sidebar on index template on specific views.
 *
 * @see apply_filters('theme/index/sidebar/visibility')
 * @see apply_filters('theme/single/sidebar/visibility')
 */
function show_index_sidebar($status)
{
    if (is_404() || is_page()) {
        return false;
    }

    return $status;
}
add_filter('theme/index/sidebar/visibility', 'Tonik\Theme\App\Setup\show_index_sidebar');
add_filter('theme/single/sidebar/visibility', 'Tonik\Theme\App\Setup\show_index_sidebar');

/**
 * Shortens posts excerpts to 60 words.
 *
 * @return integer
 */
function modify_excerpt_length()
{
    return 60;
}
add_filter('excerpt_length', 'Tonik\Theme\App\Setup\modify_excerpt_length');

/**
 * Adds FAQ tab to the Single Product tabs.
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 *
 * @return array
 */
add_filter('woocommerce_product_tabs', function ($tabs) {

    global $post;

    $faqs = theme('faqs', [
        'filter' => [
            'product_id' => $post->ID,
        ],
    ]);

    if ($faqs) {
        // Add the FAQ tab after reviews.
        $tabs['faq'] = [
            'title' => 'FAQs',
            'priority' => 30,
            'callback' => 'Tonik\Theme\App\Structure\render_single_product_faq_tab',
        ];
    }

    return $tabs;
});

/**
 * Removes the storefront credit link in the footer
 */
add_filter('storefront_credit_link', '__return_false');

/**
 * Disables Gutenberg editor globally.
 */
// TODO: Try disabling this once ACF has better support for Gutenberg.
add_filter('use_block_editor_for_post', '__return_false', 10);
