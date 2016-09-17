<?php

// remove the sidebar from the site globally
add_action( 'after_setup_theme', function(){
    remove_action( 'storefront_sidebar', 'storefront_get_sidebar', 10 );
}, 0);
