<?php 

// Timber::get_context() needs to run after woocommerce is loaded
// binding to the init hook
add_action('init', function(){
    $context = Timber::get_context();

    // ACF is requred, check for get_field function
    if ( !function_exists('get_field') ) {
        return;
    }

    // Adds a topbar to the site
    if ( get_field( 'show_top_bar', 'top-bar' ) === true ) {
        add_action('storefront_before_header', function() use ($context){
            Timber::render('top-bar.twig', $context);
        }, 50);
    }

}, 10);
