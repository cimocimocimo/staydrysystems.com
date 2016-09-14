<?php
/**
 * Sage includes
 *
 * The $sage_includes array determines the code library included in your theme.
 * Add or remove files to the array as needed. Supports child theme overrides.
 *
 * Please note that missing files will produce a fatal error.
 *
 * @link https://github.com/roots/sage/pull/1042
 */
$sage_includes = [
    'lib/assets.php', // Scripts and stylesheets
    'lib/setup.php', // Theme setup
];

foreach ($sage_includes as $file) {
    if (!$filepath = locate_template($file)) {
        trigger_error(sprintf(__('Error locating %s for inclusion', 'sage'), $file), E_USER_ERROR);
    }

    require_once $filepath;
}
unset($file, $filepath);

// allow SVG files in media upload
add_filter('upload_mimes', function($mimes) {
    $mimes['svg'] = 'image/svg+xml';
    return $mimes;
});

// remove the Storefront theme credit.
add_filter('storefront_credit_link', function(){
    return false;
});

add_filter('storefront_copyright_text', function($value){
    return sprintf('%s - %s', $value, get_bloginfo('description'));
});

// Adds ACF options page to appearance
if( function_exists('acf_add_options_page') ) {
    // add sub page
    acf_add_options_sub_page(array(
        'page_title' => 'Top Bar Settings',
        'menu_title' => 'Top Bar',
        'menu_slug' => 'top-bar',
        'parent_slug' => 'themes.php',
        'autoload' => true,
        'post_id' => 'top-bar',
    ));
}

// setup the Timber contexts
$timber = new \Timber\Timber();
add_filter( 'timber_context', function($context){
    // add top-bar to all contexts
    $context['top_bar'] = get_fields('top-bar');
    foreach ($context['top_bar']['accepted_payment_logos'] as $key => $image){
        $context['top_bar']['accepted_payment_logos'][$key] = new TimberImage($image['logo_image']['ID']);
    }
    return $context;
});

add_action('init', function(){
    $context = Timber::get_context();

    // Adds a topbar to the site
    if ( function_exists('get_field') ) {
        if ( get_field( 'show_top_bar', 'top-bar' ) === true ) {
            add_action('storefront_before_header', function() use ($context){
                Timber::render('top-bar.twig', $context);
            }, 50);
        }
    }

    add_action('storefront_before_content', function() use ($context){
        if (!is_front_page()){
            return;
        }
        Timber::render('homepage.twig', $context);
    }, 10);

}, 10);


// remove the homepage content page from the homepage...
add_action( 'after_setup_theme', function(){
    remove_action( 'homepage', 'storefront_homepage_content', 10);
    remove_action( 'homepage', 'storefront_product_categories',    20 );
    remove_action( 'homepage', 'storefront_recent_products',       30 );
    remove_action( 'homepage', 'storefront_popular_products',      50 );
    remove_action( 'homepage', 'storefront_best_selling_products', 70 );

}, 0);

add_filter('storefront_featured_products_args', function($args){
    $args['title'] = 'Featured Products';
    $args['limit']   = 6;
    $args['columns'] = 3;

    return $args;
}, 10);

add_filter('storefront_on_sale_products_args', function($args){
    $args['title'] = 'Clearance Items';
    $args['limit']   = 6;
    $args['columns'] = 3;

    return $args;
}, 10);

/* add_action( 'storefront_homepage_after_product_categories', function(){
 *
 * }, 10);*/
