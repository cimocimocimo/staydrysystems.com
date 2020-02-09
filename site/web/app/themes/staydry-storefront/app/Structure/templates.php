<?php

namespace Tonik\Theme\App\Structure;

/*
  |-----------------------------------------------------------
  | Theme Templates Actions
  |-----------------------------------------------------------
  |
  | This file purpose is to include your templates rendering
  | actions hooks, which allows you to render specific
  | partials at specific places of your theme.
  |
*/

use function Tonik\Theme\App\template;
use function Tonik\Theme\App\theme;
use Tonik\Theme\App\Structure\SD_Breadcrumb;

/**
 * Renders post thumbnail by its formats.
 *
 * @see resources/templates/index.tpl.php
 */
function render_post_thumbnail()
{
    template(['partials/post/thumbnail', get_post_format()]);
}
add_action('theme/index/post/thumbnail', 'Tonik\Theme\App\Structure\render_post_thumbnail');

/**
 * Renders empty post content where there is no posts.
 *
 * @see resources/templates/index.tpl.php
 */
function render_empty_content()
{
    template(['partials/index/content', 'none']);
}
add_action('theme/index/content/none', 'Tonik\Theme\App\Structure\render_empty_content');

/**
 * Renders post contents by its formats.
 *
 * @see resources/templates/single.tpl.php
 */
function render_post_content()
{
    template(['partials/post/content', get_post_format()]);
}
add_action('theme/single/content', 'Tonik\Theme\App\Structure\render_post_content');

/**
 * Renders sidebar content.
 *
 * @uses resources/templates/partials/sidebar.tpl.php
 * @see resources/templates/index.tpl.php
 * @see resources/templates/single.tpl.php
 */
function render_sidebar()
{
    get_sidebar();
}
add_action('theme/index/sidebar', 'Tonik\Theme\App\Structure\render_sidebar');
add_action('theme/single/sidebar', 'Tonik\Theme\App\Structure\render_sidebar');

/**
 * Renders [button] shortcode after homepage content.
 *
 * @uses resources/templates/shortcodes/button.tpl.php
 * @see resources/templates/partials/header.tpl.php
 */
function render_documentation_button()
{
    echo do_shortcode("[button href='https://github.com/tonik/tonik']Checkout documentation →[/button]");
}
add_action('theme/header/end', 'Tonik\Theme\App\Structure\render_documentation_button');

/**
 * Renders the before_header template above the main header.
 *
 * @uses resources/templates/partials/before_header.tpl.php
 */
add_action('storefront_before_header', function(){
    template('partials/before_header');
});

/**
 * Renders the FAQ tab for the single product page.
 */
function render_single_product_faq_tab () {
    // this function acts as the controller to fetch and format the data
    // then it calls template function to display to the user.

    global $post;

    $faqs = theme('faqs', [
        'filter' => [
            'product_id' => $post->ID,
        ],
    ]);

    template('partials/single-product-faq-tab', [
        'faqs' => $faqs,
    ]);
}

/**
 * Overrides the function in storefront-master theme
 *
 * Removes the category links from the post.
 *
 * @see this function in inc/storefront-template-functions.php
 */
add_action('init', function () {
    remove_action( 'storefront_loop_post', 'storefront_post_meta', 20 );
    remove_action( 'storefront_single_post', 'storefront_post_meta', 20 );
});
function staydry_storefront_post_meta() {

    $context = [];

    // Hide category and tag text for pages on Search.
    $show_category_tags = 'post' == get_post_type();
    $context['show_category_tags'] = $show_category_tags;

    if ($show_category_tags) {
        $context['author'] = (object) [
            'avatar' => get_avatar( get_the_author_meta( 'ID' ), 128 ),
            'posts_url' => esc_url(get_author_posts_url(get_the_author_meta('ID'))),
            'name' => get_the_author(),
        ];
    }

    template('partials/post-meta', $context);
}
add_action( 'storefront_loop_post', 'Tonik\Theme\App\Structure\staydry_storefront_post_meta', 20 );
add_action( 'storefront_single_post', 'Tonik\Theme\App\Structure\staydry_storefront_post_meta', 20 );

/**
 * Remove the woocommerce breadcrumb nav. We want to hide the category listings pages.
//  */
add_action('init', function () {
    remove_action( 'storefront_before_content', 'woocommerce_breadcrumb', 10 );
});


add_action('woocommerce_after_add_to_cart_form', function() {
    template('partials/product-page-shipping-notice', []);
});

function show_cart_checkout_shipping_notice() {
    template('partials/cart-checkout-shipping-notice', []);
}
add_action('woocommerce_before_checkout_form', 'Tonik\Theme\App\Structure\show_cart_checkout_shipping_notice');
add_action('woocommerce_before_cart_table', 'Tonik\Theme\App\Structure\show_cart_checkout_shipping_notice');

// remove the product category listings on the product detail pages.
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );
