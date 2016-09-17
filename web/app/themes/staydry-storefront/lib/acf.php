<?php 

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
