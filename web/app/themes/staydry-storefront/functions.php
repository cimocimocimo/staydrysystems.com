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


// Adds a topbar to the site
if ( function_exists('get_field') ) {

    if ( get_field( 'show_top_bar', 'top-bar' ) === true ) {
        add_action('storefront_before_header', 'show_top_bar', 50);
    }

}

function show_top_bar(){
    get_template_part('templates/top-bar');
}
