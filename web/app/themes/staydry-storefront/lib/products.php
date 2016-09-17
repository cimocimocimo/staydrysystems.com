<?php

// set the products per page higher so pagination isn't shown
add_filter( 'storefront_products_per_page', function(){
    return 24;
} );

// settings for the featured products block on shop page.
add_action('template_redirect', function(){
    if (is_shop()){
        add_filter('storefront_featured_products_args', function($args){
            $args['title'] = 'Featured Products';
            $args['limit']   = 6;
            $args['columns'] = 3;

            return $args;
        }, 10);

        add_filter('staydry_product_category_args', function($args){
            return [
                'limit'   => 6,
                'columns' => 3,
                'orderby' => 'date',
                'order'   => 'desc',
                'category' => 'replacement-parts',
                'title'   => __( 'Replacement Parts', 'staydry' ),
            ];
        }, 10);
    }
});

// remove the ordering select dropdown
add_action( 'after_setup_theme', function(){
    remove_action( 'woocommerce_before_shop_loop',       'woocommerce_catalog_ordering',           10 );
    remove_action( 'woocommerce_before_shop_loop',       'woocommerce_result_count',           10 );
    remove_action( 'woocommerce_after_shop_loop',       'woocommerce_catalog_ordering',           10 );
    remove_action( 'woocommerce_after_shop_loop',       'woocommerce_result_count',           10 );
}, 0);


// show products in a category
// TODO: how does storefront integrate the shortcodes into the theme?
if ( ! function_exists( 'staydry_product_category' ) ) {
    /**
     * Display products in a category
     *
     *
     */
    function staydry_product_category(){

        if ( is_woocommerce_activated() ) {

            $args = apply_filters( 'staydry_product_category_args', array(
                'limit'   => 4,
                'columns' => 4,
                'orderby' => 'date',
                'order'   => 'desc',
                'category' => '',
                'title'   => __( 'We Recommend', 'storefront' ),
            ) );

            echo '<section class="storefront-product-section staydry-product-category" aria-label="Featured Products">';

            do_action( 'staydry_before_product_category' );

            echo '<h2 class="section-title">' . wp_kses_post( $args['title'] ) . '</h2>';

            do_action( 'staydry_after_product_category_title' );

            echo storefront_do_shortcode( 'product_category', array(
                'per_page' => intval( $args['limit'] ),
                'columns'  => intval( $args['columns'] ),
                'orderby'  => esc_attr( $args['orderby'] ),
                'order'    => esc_attr( $args['order'] ),
                'category' => esc_attr( $args['category'] ),
            ) );

            do_action( 'staydry_after_product_category' );

            echo '</section>';
        }
    }
}

// on the shop page show
// featured products, just the first 6, show a link to the featured products category if more
add_action( 'woocommerce_archive_description', 'storefront_featured_products', 100 );
add_action( 'woocommerce_archive_description', 'staydry_product_category', 110 );

// add a title to the main loop and open a new section
add_action( 'woocommerce_before_shop_loop', function(){
?>
  <section class="storefront-product-section" aria-label="Other Products">
    <h2 class="section-title">Other Products</h2>
<?php
}, 10 );
add_action( 'woocommerce_before_shop_loop', function(){
?>
    <div class="woocommerce columns-3">
<?php
}, 40 );
add_action( 'woocommerce_after_shop_loop', function(){
?>
    </div>
<?php
}, 15 );
add_action( 'woocommerce_after_shop_loop', function(){
?>
  </section>    
<?php
}, 40 );

// main loop adapted to show all products that are not in featured products, or replacement parts
add_action( 'pre_get_posts', function( $query ) {
    // only run on the main shop page and in the main query
    if ( is_shop_workaround($query) && $query->is_main_query() ) {
        $query->set('meta_key', '_featured');
        $query->set('meta_value', 'no');
        $query->set('tax_query', [
            [
                'taxonomy' => 'product_cat',
                'field' => 'slug',
                'terms' => ['replacement-parts'],
                'operator' => 'NOT IN',
            ],
        ]);
    }
    
}, 10);

// see https://github.com/woothemes/woocommerce/issues/10625
// and https://core.trac.wordpress.org/ticket/21790
// and https://core.trac.wordpress.org/ticket/27015
// TODO: remove when the trac tickets are closed.
function is_shop_workaround($query){
    $front_page_id        = get_option( 'page_on_front' );
    $current_page_id      = $query->get( 'page_id' );
    $shop_page_id         = apply_filters( 'woocommerce_get_shop_page_id' , get_option( 'woocommerce_shop_page_id' ) );
    $is_static_front_page = 'page' == get_option( 'show_on_front' );

    // Detect if it's a static front page and the current page is the front page, then use our work around
    // Otherwise, just use is_shop since it works fine on other pages
    if ( $is_static_front_page && $front_page_id == $current_page_id  ) {
        error_log( 'is static front page and current page is front page' );
        $is_shop_page = ( $current_page_id == $shop_page_id ) ? true : false;
    } else {
        error_log( 'is not static front page, can use is_shop instead' );
        $is_shop_page = is_shop();
    }

    // Now we can use it in a conditional like so:
    if ($is_shop_page) {
        return true;
    }

    return false;
}
