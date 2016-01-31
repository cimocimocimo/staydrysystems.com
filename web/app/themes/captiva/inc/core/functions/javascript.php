<?php

/* ----------------------------------------------------------------------------------- */
/*  Register and load common JS.....
/*----------------------------------------------------------------------------------- */

function captiva_register_production_js() {
    global $cap_live_preview;

    if ( !is_admin() ) {
        // don't concat and minify these 4
        wp_enqueue_script( 'cap_waypoints', CAP_JS . '/dist/waypoints.min.js', array( 'jquery' ), '', true );
        wp_enqueue_script( 'cap_bootstrap_js', CAP_BOOTSTRAP_JS . '/bootstrap.min.js', array( 'jquery' ), '', true );
        wp_enqueue_script( 'cap_magnific-popup', CAP_JS . '/src/cond/jquery.magnific-popup.min.js', array( 'jquery' ), '', true );
        wp_enqueue_script( 'cap_owlcarousel', CAP_JS . '/src/cond/owl.carousel.min.js', array( 'jquery' ), '', false );
        wp_enqueue_script( 'cap_modernizr', CAP_JS . '/src/cond/modernizr.custom.min.js', array( 'jquery' ), '', false );

        // Minified versions of all plugins in /js/src/plugins
        wp_enqueue_script( 'cap_captiva_plugins_js', CAP_JS . '/dist/plugins.min.js', array( 'jquery' ), '', true );

        if ( isset( $cap_live_preview ) ) {
            wp_enqueue_script( 'cap-dynamicjs', CAP_JS . '/src/cond/capdynamic.php', array(), '', true );            
            wp_enqueue_script( 'cap-jqueryui', CAP_JS . '/src/cond/jquery-ui.min.js', array(), '', true );            
            wp_enqueue_script( 'cap-livepreviewjs', CAP_JS . '/src/cond/livepreview.js', array(), '', true );
        }  

        // Minified captiva.js call from /js/src/captiva.js
        wp_enqueue_script( 'cap_captiva_js', CAP_JS . '/dist/captiva.min.js', array( 'jquery' ), '', true );
    }
}

add_action( 'init', 'captiva_register_production_js' );

function captiva_register_debug_js() {
    global $cap_live_preview;

    if ( !is_admin() ) {

        //Loading from unminified source

        wp_enqueue_script( 'cap_waypoints', CAP_JS . '/dist/waypoints.min.js', array( 'jquery' ), '', true );
        wp_enqueue_script( 'cap_bootstrap_js', CAP_BOOTSTRAP_JS . '/bootstrap.min.js', array( 'jquery' ), '', true );
        wp_enqueue_script( 'cap_magnific-popup', CAP_JS . '/src/cond/jquery.magnific-popup.min.js', array( 'jquery' ), '', true );
        wp_enqueue_script( 'cap_modernizr', CAP_JS . '/src/cond/modernizr.custom.min.js', array( 'jquery' ), '', false );

        wp_enqueue_script( 'cap_chosen_js', CAP_JS . '/src/plugins/chosen.js', array( 'jquery' ), '', true );
        wp_enqueue_script( 'cap_classie_js', CAP_JS . '/src/plugins/classie.js', array( 'jquery' ), '', true );
        wp_enqueue_script( 'cap_uisearch_js', CAP_JS . '/src/plugins/uisearch.js', array( 'jquery' ), '', true );
        wp_enqueue_script( 'cap_bootstrap_select_js', CAP_JS . '/src/plugins/bootstrap-select.js', array( 'jquery' ), '', true );
        wp_enqueue_script( 'cap_owlcarousel', CAP_JS . '/src/cond/owl.carousel.min.js', array( 'jquery' ), '', false );
        wp_enqueue_script( 'cap_jrespond', CAP_JS . '/src/plugins/jRespond.min.js', array( 'jquery' ), '', true );
        wp_enqueue_script( 'cap_iosslider', CAP_JS . '/src/plugins/jquery.iosslider.min.js', array( 'jquery' ), '', true );
        wp_enqueue_script( 'cap_qtip', CAP_JS . '/src/plugins/jquery.qtip.min.js', array( 'jquery' ), '', true );
        wp_enqueue_script( 'cap_tipr', CAP_JS . '/src/plugins/tipr.min.js', array( 'jquery' ), '', true );
        wp_enqueue_script( 'cap_cookie', CAP_JS . '/src/plugins/cookie.js', array( 'jquery' ), '', true );
        wp_enqueue_script( 'cap_meanmenu', CAP_JS . '/src/plugins/jquery.meanmenu.min.js', array( 'jquery' ), '', true );
        wp_enqueue_script( 'cap_flexslider', CAP_JS . '/src/plugins/jquery.flexslider-min.js', array( 'jquery' ), '', true );
        wp_enqueue_script( 'cap_skrollr', CAP_JS . '/src/plugins/skrollr.js', array( 'jquery' ), '', true );

        if ( isset( $cap_live_preview ) ) {
            wp_enqueue_script( 'cap-dynamicjs', CAP_JS . '/src/cond/capdynamic.php', array(), '', true );            
            wp_enqueue_script( 'cap-jqueryui', CAP_JS . '/src/cond/jquery-ui.min.js', array(), '', true );            
            wp_enqueue_script( 'cap-livepreviewjs', CAP_JS . '/src/cond/livepreview.js', array(), '', true );
        }  

        // Full source captiva.js call
        wp_enqueue_script('cap_captiva_js', CAP_JS . '/src/captiva.js', array('jquery'), '', true);

    }
}
//uncomment the next line if you wish to enqueue individual js files. If you uncomment the next line you will also need to comment out
//line 30 above-> add_action( 'init', 'captiva_register_production_js' );
//add_action( 'init', 'captiva_register_debug_js' );

// load portfolio scripts only on portfolio template
function cap_showcase_js() {
    if ( (is_page_template( 'template-showcase-4col.php' )) || (is_page_template( 'template-showcase-4col-alt.php' )) || (is_page_template( 'template-showcase-3col.php' )) || (is_page_template( 'template-showcase-2col.php' )) ) {
        wp_enqueue_script( 'cap_imagesloaded', CAP_JS . '/src/cond/imagesloaded.pkgd.min.js', array( 'jquery' ), '', true );
        wp_enqueue_script( 'cap_isotope', CAP_JS . '/src/cond/isotope.pkgd.min.js', array( 'jquery' ), '1.0', false );
        wp_enqueue_script( 'cap_showcasejs', CAP_JS . '/src/cond/jquery.tfshowcase.js', array( 'jquery' ), '1.0', false );
    }
}

add_action( 'wp_enqueue_scripts', 'cap_showcase_js' );

/**
 * Enqueue scripts and styles
 */
function cap_scripts() {

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
        wp_enqueue_script( 'comment-reply' );
    }

    if ( is_singular() && wp_attachment_is_image() ) {
        wp_enqueue_script( 'captiva-keyboard-image-navigation', CAP_JS . '/src/cond/keyboard-image-navigation.js', array( 'jquery' ), '20120202' );
    }
}

add_action( 'wp_enqueue_scripts', 'cap_scripts' );
?>