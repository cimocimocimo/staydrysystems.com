<?php
// load the parent style.css
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );
function theme_enqueue_styles() {
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}

// Unhook Storefront theme credit
remove_action('storefront_footer', 'storefront_credit', 20);
?>
