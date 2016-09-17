<?php

// remove the homepage content page from the homepage...
// after_setup_theme is used to unhook functions hooked in the parent theme.
add_action( 'after_setup_theme', function(){
    remove_action( 'homepage', 'storefront_homepage_content', 10);
    remove_action( 'homepage', 'storefront_product_categories',    20 );
    remove_action( 'homepage', 'storefront_recent_products',       30 );
    remove_action( 'homepage', 'storefront_popular_products',      50 );
    remove_action( 'homepage', 'storefront_best_selling_products', 70 );
}, 0);

// Timber::get_context() needs to run after woocommerce is loaded
// binding to the init hook
add_action('init', function(){
    $context = Timber::get_context();

    // ACF is requred, check for get_field function
    if ( !function_exists('get_field') ) {
        return;
    }

    // hero needs full width of layout. adding before content
    // 'storefront_before_content' is in the header so we need the
    // homepage check.
    add_action('storefront_before_content', function() use ($context){
        if (is_front_page()){
            Timber::render('homepage-hero.twig', $context);
        }
    }, 10);

    // show testimonials just before replacement parts
    add_action('homepage', function() use ($context){
        // get the customer testimonials
        // TODO: get the customer testimonials from a custom post type
        // add the customer testimonials to the $context
        // TODO: add the testionials to the context
        Timber::render('homepage-customer-testimonials.twig', $context);
    }, 45);

    // show the replacement parts on the homepage
    add_action('homepage', 'staydry_product_category', 50);

    // show customer logos before the sale products
    add_action('homepage', function() use ($context){
        Timber::render('homepage-customer-logos.twig', $context);
    }, 55);

}, 10);

// change settings for the blocks shown on the homepage.
// adding the action at template_redirect since that's when $wp_query is ready
// and that is required for is_front_page() to work.
add_action('template_redirect', function(){
    if (is_front_page()){
        add_filter('storefront_featured_products_args', function($args){
            $args['title'] = 'Featured Products';
            $args['limit']   = 6;
            $args['columns'] = 3;

            return $args;
        }, 10);

        add_filter('staydry_product_category_args', function($args){
            $args['title'] = 'Replacement Parts';
            $args['category'] = 'replacement-parts';
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
    }
}, 10);


