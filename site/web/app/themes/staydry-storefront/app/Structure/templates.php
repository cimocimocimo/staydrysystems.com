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
 * Overrides WC_Breadcrumb class
 *
 * This is used to change the link to the blog page rather than the category link.
 */
add_action('init', function () {
    remove_action( 'storefront_before_content', 'woocommerce_breadcrumb', 10 );
});
function staydry_woocommerce_breadcrumb($args = []) {
    $args = wp_parse_args($args,
        apply_filters('woocommerce_breadcrumb_defaults', [
            'delimiter' => '&nbsp;&#47;&nbsp;',
            'wrap_before' => '<nav class="woocommerce-breadcrumb">',
            'wrap_after'  => '</nav>',
            'before'      => '',
            'after'       => '',
            'home'        => _x( 'Home', 'breadcrumb', 'woocommerce' ),
        ])
    );

    $breadcrumbs = new SD_Breadcrumb();

    if ( ! empty( $args['home'] ) ) {
        $breadcrumbs->add_crumb( $args['home'], apply_filters( 'woocommerce_breadcrumb_home_url', home_url() ) );
    }

    $args['breadcrumb'] = $breadcrumbs->generate();

    /**
     * WooCommerce Breadcrumb hook
     *
     * @hooked WC_Structured_Data::generate_breadcrumblist_data() - 10
     */
    do_action( 'woocommerce_breadcrumb', $breadcrumbs, $args );

    wc_get_template( 'global/breadcrumb.php', $args );
}
add_action('storefront_before_content', 'Tonik\Theme\App\Structure\staydry_woocommerce_breadcrumb', 10);
