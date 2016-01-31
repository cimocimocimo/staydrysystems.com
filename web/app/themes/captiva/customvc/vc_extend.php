<?php

/*
  Plugin Name: Captiva Visual Composer Extensions
  Plugin URI: http://commercegurus.com
  Description: Extensions to Visual Composer for the Captiva theme.
  Version: 1.0
  Author: Captivate
  Author URI: http://commercegurus.com
  License: GPLv2 or later
 */

// don't load directly
if ( !defined( 'ABSPATH' ) )
    die( '-1' );

/*
  Display notice if Visual Composer is not installed or activated.
 */
if ( !defined( 'WPB_VC_VERSION' ) ) {
    add_action( 'admin_notices', 'cap_vc_extend_notice' );
    return;
}

function cap_vc_extend_notice() {
    $plugin_data = get_plugin_data( __FILE__ );
    echo '
  <div class="updated">
    <p>' . sprintf( __( '<strong>%s</strong> requires <strong><a href="http://bit.ly/vcomposer" target="_blank">Visual Composer</a></strong> plugin to be installed and activated on your site.', 'captiva' ), $plugin_data['Name'] ) . '</p>
  </div>';
}

//Removing shortcodes
vc_remove_element( "vc_widget_sidebar" );
vc_remove_element( "vc_wp_search" );
vc_remove_element( "vc_wp_meta" );
vc_remove_element( "vc_wp_recentcomments" );
vc_remove_element( "vc_wp_calendar" );
vc_remove_element( "vc_wp_pages" );
vc_remove_element( "vc_wp_tagcloud" );
vc_remove_element( "vc_wp_custommenu" );
vc_remove_element( "vc_wp_text" );
vc_remove_element( "vc_wp_posts" );
vc_remove_element( "vc_wp_links" );
vc_remove_element( "vc_wp_categories" );
vc_remove_element( "vc_wp_archives" );
vc_remove_element( "vc_wp_rss" );
vc_remove_param( 'vc_separator', 'accent_color' );
vc_remove_param( 'vc_separator', 'style' );
vc_remove_param( 'vc_separator', 'el_width' );

/*
  Load plugin css and javascript files
 */
add_action( 'wp_enqueue_scripts', 'cap_vc_extend_js_css' );

function cap_vc_extend_js_css() {
    //wp_register_style( 'cap_vc_extend_style', plugins_url('cap_vc_extend.css', __FILE__) );
    //wp_enqueue_style( 'cap_vc_extend_style' );
    // If you need any javascript files on front end, here is how you can load them.
    //wp_enqueue_script( 'cap_vc_extend_js', plugins_url('cap_vc_extend.js', __FILE__), array('jquery') );
}

/*
  Lets register our shortcode with bartag base and few params (attributes):
 * foo
 * color
 * content

  [bartag foo="something" color="#FFF"] Content here [/bartag]

  More information can be found here:
  http://kb.wpbakery.com/index.php?title=Visual_Composer_tutorial
 */
add_shortcode( 'bartag', 'bartag_func' );

function bartag_func( $atts, $content = null ) {
    extract( shortcode_atts( array(
        'foo' => 'something',
        'color' => '#FFF'
                    ), $atts ) );

    $content = wpb_js_remove_wpautop( $content ); // fix unclosed/unwanted paragraph tags in $content

    return "<div style='color:{$color};' data-foo='${foo}'>{$content}</div>";
}

vc_add_param( "vc_row", array(
    "type" => "dropdown",
    "class" => "",
    "heading" => "Type",
    "param_name" => "type",
    "value" => array(
        "Fixed Page Width" => "container",
        "Full Page Width - No Container" => "full_page_width",
        "Full Page Width - Inner Container" => "full_page_width_inner_container",
    ),
) );

vc_add_param( "vc_row", array(
    "type" => "textfield",
    "class" => "",
    "heading" => "Padding Top",
    "value" => "",
    "param_name" => "padding_top",
    "description" => "",
) );

vc_add_param( "vc_row", array(
    "type" => "textfield",
    "class" => "",
    "heading" => "Padding Bottom",
    "value" => "",
    "param_name" => "padding_bottom",
    "description" => "",
) );

// Separator

$separator_setting = array(
    'show_settings_on_create' => true,
    "controls" => '',
);
vc_map_update( 'vc_separator', $separator_setting );

vc_add_param( "vc_separator", array(
    "type" => "dropdown",
    "class" => "",
    "heading" => "Type",
    "param_name" => "type",
    "value" => array(
        "Normal" => "normal",
        "Transparent" => "transparent",
        "Full width" => "full_width",
        "Small" => "small"
    ),
    "description" => ""
) );

vc_add_param( "vc_separator", array(
    "type" => "dropdown",
    "class" => "",
    "heading" => "Position",
    "param_name" => "position",
    "value" => array(
        "Center" => "center",
        "Left" => "left",
        "Right" => "right"
    ),
    "dependency" => array( "element" => "type", "value" => array( "small" ) ),
    "description" => ""
) );

vc_add_param( "vc_separator", array(
    "type" => "colorpicker",
    "class" => "",
    "heading" => "Color",
    "param_name" => "color",
    "value" => "",
    "description" => ""
) );

vc_add_param( "vc_separator", array(
    "type" => "textfield",
    "class" => "",
    "heading" => "Thickness",
    "param_name" => "thickness",
    "value" => "",
    "description" => ""
) );
vc_add_param( "vc_separator", array(
    "type" => "textfield",
    "class" => "",
    "heading" => "Top Margin",
    "param_name" => "up",
    "value" => "",
    "description" => ""
) );
vc_add_param( "vc_separator", array(
    "type" => "textfield",
    "class" => "",
    "heading" => "Bottom Margin",
    "param_name" => "down",
    "value" => "",
    "description" => ""
) );


/*
  Lets call wpb_map function to "register" our custom shortcode within Visual Composer interface.
 */
wpb_map( array(
    "name" => __( "Captiva latest blog posts", 'captiva' ),
    "base" => "captiva_latest_posts",
    "class" => "",
    "controls" => "full",
    "icon" => "icon-wpb-cap_vc_extend",
    "category" => __( 'Captiva Shortcodes', 'captiva' ),
    "params" => array(
        array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Introduction Text", 'captiva' ),
            "param_name" => "introtext",
            "value" => __( "From the blog", 'captiva' ),
            "description" => __( "Enter the title you wish to display over your latest posts.", 'captiva' )
        ),
        array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => "Number of Posts",
            "param_name" => "posts",
            "value" => __( "8", 'captiva' ),
            "description" => __( "Enter the total number of posts you wish to display in the carousel.", 'captiva' )
        ),
    )
) );

wpb_map( array(
    "name" => __( "Captiva top reviews", 'captiva' ),
    "base" => "captiva_topreviews",
    "class" => "",
    "controls" => "full",
    "icon" => "icon-wpb-cap_vc_extend",
    "category" => __( 'Captiva Shortcodes', 'captiva' ),
    "params" => array(
        array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => "Number of Posts",
            "param_name" => "posts",
            "value" => __( "8", 'captiva' ),
            "description" => __( "Enter the total number of posts you wish to display in the carousel.", 'captiva' )
        ),
    )
) );

wpb_map( array(
    "name" => __( "Captiva Logos", 'captiva' ),
    "base" => "captiva_logos",
    "class" => "",
    "controls" => "full",
    "icon" => "icon-wpb-cap_vc_extend",
    "category" => __( 'Captiva Shortcodes', 'captiva' ),
    "params" => array(
        array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => "Number of Logos",
            "param_name" => "posts",
            "value" => __( "10", 'captiva' ),
            "description" => __( "Enter the total number of logos you wish to display in the carousel.", 'captiva' )
        ),
    )
) );

wpb_map( array(
    "name" => __( "WooCommerce Latest Products Carousel", 'captiva' ),
    "base" => "captiva_woo_latest_products",
    "class" => "",
    "controls" => "full",
    "icon" => "icon-wpb-cap_vc_extend",
    "category" => __( 'Captiva Shortcodes', 'captiva' ),
    "params" => array(
        array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Introduction Text", 'captiva' ),
            "param_name" => "introtext",
            "value" => __( "Latest products", 'captiva' ),
            "description" => __( "Enter the title you wish to display over the carousel.", 'captiva' )
        ),
        array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => "Number of Products",
            "param_name" => "products",
            "value" => __( "12", 'captiva' ),
            "description" => __( "Enter the total number of products you wish to display in the carousel.", 'captiva' )
        ),
        array(
            "type" => "dropdown",
            "holder" => "div",
            "class" => "",
            "heading" => "Order by",
            "param_name" => "orderby",
            "value" => array(
                "Date Created" => "date",
                "Date Last Modified" => "modified",
                "Page Order" => "menu_order"
            ),
            "description" => __( "Select the ordering for display of carousel items.", 'captiva' )
        ),
    )
) );

wpb_map( array(
    "name" => __( "WooCommerce Featured Products Carousel", 'captiva' ),
    "base" => "captiva_woo_featured_products",
    "class" => "",
    "controls" => "full",
    "icon" => "icon-wpb-cap_vc_extend",
    "category" => __( 'Captiva Shortcodes', 'captiva' ),
    "params" => array(
        array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Introduction Text", 'captiva' ),
            "param_name" => "introtext",
            "value" => __( "Recommended for you", 'captiva' ),
            "description" => __( "Enter the title you wish to display over the carousel.", 'captiva' )
        ),
        array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => "Number of Products",
            "value" => __( "12", 'captiva' ),
            "param_name" => "products",
            "description" => __( "Enter the total number of products you wish to display in the carousel.", 'captiva' )
        ),
        array(
            "type" => "dropdown",
            "holder" => "div",
            "class" => "",
            "heading" => "Order by",
            "param_name" => "orderby",
            "value" => array(
                "Date Created" => "date",
                "Date Last Modified" => "modified",
                "Page Order" => "menu_order"
            ),
            "description" => __( "Select the ordering for display of carousel items.", 'captiva' )
        ),
    )
) );

wpb_map( array(
    "name" => __( "Captiva Latest Showcase", 'captiva' ),
    "base" => "captiva_latest_portfolio",
    "class" => "",
    "controls" => "full",
    "icon" => "icon-wpb-cap_vc_extend",
    "category" => __( 'Captiva Shortcodes', 'captiva' ),
    "params" => array(
        array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Introduction Text", 'captiva' ),
            "param_name" => "introtext",
            "value" => __( "Our Latest Work", 'captiva' ),
            "description" => __( "Enter the title you wish to display over the carousel.", 'captiva' )
        ),
        array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Number of Items", 'captiva' ),
            "value" => __( "12", 'captiva' ),
            "param_name" => "items",
            "description" => __( "Enter the total number of items you wish to display in the carousel.", 'captiva' )
        ),
    )
) );

wpb_map( array(
    "name" => __( "Captiva Testimonials", 'captiva' ),
    "base" => "captiva_testimonials",
    "class" => "",
    "controls" => "full",
    "icon" => "icon-wpb-cap_vc_extend",
    "category" => __( 'Captiva Shortcodes', 'captiva' ),
    "params" => array(
        array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Heading", 'captiva' ),
            "param_name" => "introtext",
            "value" => __( "Testimonials from happy customers", 'captiva' ),
            "description" => __( "Enter the heading you wish to display above your testimonials.", 'captiva' )
        ),
        array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Number of Testimonials", 'captiva' ),
            "value" => __( "12", 'captiva' ),
            "param_name" => "posts",
            "description" => __( "Enter the total number of items you wish to display in the carousel.", 'captiva' )
        ),
    )
) );

wpb_map( array(
    "name" => __( "Captiva Google Map", 'captiva' ),
    "base" => "captiva_map",
    "class" => "",
    "controls" => "full",
    "icon" => "icon-wpb-cap_vc_extend",
    "category" => __( 'Captiva Shortcodes', 'captiva' ),
    "params" => array(
        array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Map height", 'captiva' ),
            "param_name" => "height",
            "value" => __( "300px", 'captiva' ),
            "description" => __( "Enter the height of your map", 'captiva' )
        ),
        array(
            "type" => "textarea_html",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Map content", 'captiva' ),
            "param_name" => "content",
            "value" => __( "This is a test captiva map address!", 'captiva' ),
            "description" => __( "Please enter your map content here (e.g. your address).", 'captiva' )
        ),
        array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Latitude", 'captiva' ),
            "param_name" => "lat",
            "value" => __( "40.72513", 'captiva' ),
            "description" => __( "Enter the latitude for your map location. Find Lat/long coordinates - http://goo.gl/yvyYdf", 'captiva' )
        ),
        array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Latitude", 'captiva' ),
            "param_name" => "lat",
            "value" => __( "40.72513", 'captiva' ),
            "description" => __( "Enter the latitude for your map location. Final Latl/long coordinates - http://goo.gl/yvyYdf", 'captiva' )
        ),
        array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Longitude", 'captiva' ),
            "param_name" => "long",
            "value" => __( "-73.99908", 'captiva' ),
            "description" => __( "Enter the longitude for your map location.", 'captiva' )
        ),
        array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Zoom level", 'captiva' ),
            "param_name" => "zoom",
            "value" => __( "17", 'captiva' ),
            "description" => __( "Enter a zoom level for your map.", 'captiva' )
        ),
    )
) );

wpb_map( array(
    "name" => __( "Captiva Message Box", 'captiva' ),
    "base" => "captiva_promo",
    "class" => "",
    "controls" => "full",
    "icon" => "icon-wpb-cap_vc_extend",
    "category" => __( 'Captiva Shortcodes', 'captiva' ),
    "params" => array(
        array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Height", 'captiva' ),
            "param_name" => "height",
            "value" => __( "120px", 'captiva' ),
            "description" => __( "Enter the height of your message box.", 'captiva' )
        ),
        array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Message box title", 'captiva' ),
            "param_name" => "title",
            "value" => __( "A message box title", 'captiva' ),
            "description" => __( "Enter the title you wish to display in the message box.", 'captiva' )
        ),
        array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Message box button text", 'captiva' ),
            "value" => __( "Button text", 'captiva' ),
            "param_name" => "button_text",
            "description" => __( "Enter the text you wish to display on the button in your message box.", 'captiva' )
        ),
        array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Button link", 'captiva' ),
            "value" => __( "http://", 'captiva' ),
            "param_name" => "button_url",
            "description" => __( "Enter the full hyperlink you wish to add to the message box button. Don't forget the http://!", 'captiva' )
        ),
        array(
            "type" => "colorpicker",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Message box background color", 'captiva' ),
            "value" => __( "#333", 'captiva' ),
            "param_name" => "bg",
            "description" => __( "Select a background color for your message box.", 'captiva' )
        ),
    )
) );

wpb_map( array(
    "name" => __( "Captiva Content Strip", 'captiva' ),
    "base" => "captiva_content_strip",
    "class" => "",
    "controls" => "full",
    "icon" => "icon-wpb-cap_vc_extend",
    "category" => __( 'Captiva Shortcodes', 'captiva' ),
    "params" => array(
        array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Content Strip Height", 'captiva' ),
            "param_name" => "height",
            "value" => __( "120px", 'captiva' ),
            "description" => __( "Enter the height of your content strip.", 'captiva' )
        ),
        array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Link to wrap around content strip", 'captiva' ),
            "param_name" => "outer_link",
            "value" => "",
            "description" => __( "Enter a hyperlink if you want the entire content strip to be clickable. Don't forget the http.", 'captiva' )
        ),
        array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Content Area Width", 'captiva' ),
            "param_name" => "width",
            "value" => __( "50%", 'captiva' ),
            "description" => __( "Enter the width of your content area. Note this is not the width of the column itself - that is controlled by the column and properties.", 'captiva' )
        ),
        array(
            "type" => "textfield",
            "class" => "",
            "heading" => "Bottom Margin",
            "param_name" => "margin_bottom",
            "value" => "",
            "description" => __( "Enter the bottom margin of your content strip.", 'captiva' )
        ),
        array(
            "type" => "colorpicker",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Message box background color", 'captiva' ),
            "value" => "#333",
            "param_name" => "bg",
            "description" => __( "Select a background color for your message box.", 'captiva' )
        ),
        array(
            "type" => "dropdown",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Vertical Position", 'captiva' ),
            "param_name" => "valign",
            "value" => array(
                "Center" => "center",
                "Top" => "top",
                "Bottom" => "bottom",
            ),
            "description" => __( "Select the vertical position of your content.", 'captiva' ),
        ),
        array(
            "type" => "dropdown",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Horizontal Position", 'captiva' ),
            "param_name" => "halign",
            "value" => array(
                "Center" => "center",
                "Left" => "left",
                "Right" => "right",
            ),
            "description" => __( "Select the horizontal position of your content.", 'captiva' ),
        ),
        array(
            "type" => "dropdown",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Text alignment", 'captiva' ),
            "param_name" => "text_align",
            "value" => array(
                "Center" => "center",
                "Left" => "left",
                "Right" => "right",
            ),
            "description" => __( "Select the alignment for your content.", 'captiva' ),
        ),
        array(
            "type" => "dropdown",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Text color", 'captiva' ),
            "param_name" => "color",
            "value" => array(
                "Light" => "light",
                "Dark" => "dark",
            ),
            "description" => __( "Select your text color preference.", 'captiva' ),
        ),
        array(
            "type" => "dropdown",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Text animation effect", 'captiva' ),
            "param_name" => "animation",
            "value" => array(
                "none" => "none",
                "bounce" => "bounce",
                "flash" => "flash",
                "pulse" => "pulse",
                "rubberBand" => "rubberBand",
                "shake" => "shake",
                "swing" => "swing",
                "tada" => "tada",
                "wobble" => "wobble",
                "bounceIn" => "bounceIn",
                "bounceInDown" => "bounceInDown",
                "bounceInLeft" => "bounceInLeft",
                "bounceInRight" => "bounceInRight",
                "bounceInUp" => "bounceInUp",
                "bounceOut" => "bounceOut",
                "bounceOutDown" => "bounceOutDown",
                "bounceOutLeft" => "bounceOutLeft",
                "bounceOutRight" => "bounceOutRight",
                "bounceOutUp" => "bounceOutUp",
                "fadeIn" => "fadeIn",
                "fadeInDown" => "fadeInDown",
                "fadeInDownBig" => "fadeInDownBig",
                "fadeInLeft" => "fadeInLeft",
                "fadeInLeftBig" => "fadeInLeftBig",
                "fadeInRight" => "fadeInRight",
                "fadeInRightBig" => "fadeInRightBig",
                "fadeInUp" => "fadeInUp",
                "fadeInUpBig" => "fadeInUpBig",
                "fadeOut" => "fadeOut",
                "fadeOutDown" => "fadeOutDown",
                "fadeOutDownBig" => "fadeOutDownBig",
                "fadeOutLeft" => "fadeOutLeft",
                "fadeOutLeftBig" => "fadeOutLeftBig",
                "fadeOutRight" => "fadeOutRight",
                "fadeOutRightBig" => "fadeOutRightBig",
                "fadeOutUp" => "fadeOutUp",
                "fadeOutUpBig" => "fadeOutUpBig",
                "flip" => "flip",
                "flipInX" => "flipInX",
                "flipInY" => "flipInY",
                "flipOutX" => "flipOutX",
                "flipOutY" => "flipOutY",
                "lightSpeedIn" => "lightSpeedIn",
                "lightSpeedOut" => "lightSpeedOut",
                "rotateIn" => "rotateIn",
                "rotateInDownLeft" => "rotateInDownLeft",
                "rotateInDownRight" => "rotateInDownRight",
                "rotateInUpLeft" => "rotateInUpLeft",
                "rotateInUpRight" => "rotateInUpRight",
                "rotateOut" => "rotateOut",
                "rotateOutDownLeft" => "rotateOutDownLeft",
                "rotateOutDownRight" => "rotateOutDownRight",
                "rotateOutUpLeft" => "rotateOutUpLeft",
                "rotateOutUpRight" => "rotateOutUpRight",
                "slideInDown" => "slideInDown",
                "slideInLeft" => "slideInLeft",
                "slideInRight" => "slideInRight",
                "slideOutLeft" => "slideOutLeft",
                "slideOutRight" => "slideOutRight",
                "slideOutUp" => "slideOutUp",
                "hinge" => "hinge",
                "rollIn" => "rollIn",
                "rollOut" => "rollOut",
            ),
            "description" => __( "Select your text animation preference.", 'captiva' ),
        ),
        array(
            "type" => "dropdown",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Text fade out on scroll?", 'captiva' ),
            "param_name" => "text_fade_out",
            "value" => array(
                "Yes" => "yes",
                "No" => "no",
            ),
            "description" => __( "Would you like text to fade out upon scroll?", 'captiva' ),
        ),
        array(
            "type" => "textfield",
            "class" => "",
            "heading" => "Custom content css class (normally .cap-content-strip)",
            "param_name" => "custom_content_css",
            "value" => "",
            "description" => __( "This is useful when you would like custom css to change how markup renders in content strips.", 'captiva' )
        ),
        array(
            "type" => "textarea_html",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Content", 'captiva' ),
            "param_name" => "content",
            "value" => __( "Enter your content here!", 'captiva' ),
            "description" => __( "This can be as simple as a page title or a full page if you want!.", 'captiva' )
        ),
        array(
            "type" => "attach_image",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Content strip background image", 'captiva' ),
            "value" => "",
            "param_name" => "bg_img",
            "description" => __( "Upload an image to be used the for the content strip background.", 'captiva' )
        ),
        array(
            "type" => "dropdown",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Type of background", 'captiva' ),
            "param_name" => "bg_img_type",
            "value" => array(
                "Simple solid background color" => "simple",
                "Parallax image" => "parallax",
            ),
            "description" => __( "Set a style/effect for your background image.", 'captiva' ),
        ),
    )
) );


wpb_map( array(
    "name" => __( "Captiva Video Banner", 'captiva' ),
    "base" => "captiva_video_banner",
    "class" => "",
    "controls" => "full",
    "icon" => "icon-wpb-cap_vc_extend",
    "category" => __( 'Captiva Shortcodes', 'captiva' ),
    "params" => array(
        array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Video Banner Height", 'captiva' ),
            "param_name" => "height",
            "value" => "120px",
            "description" => __( "Enter the height of your video banner.", 'captiva' )
        ),
        array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Link to wrap around video banner", 'captiva' ),
            "param_name" => "outer_link",
            "value" => "",
            "description" => __( "Enter a hyperlink if you want the entire video banner to be clickable. Don't forget the http.", 'captiva' )
        ),
        array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Content Area Width", 'captiva' ),
            "param_name" => "width",
            "value" => "70%",
            "description" => __( "Enter the width of your content area.", 'captiva' )
        ),
        array(
            "type" => "textfield",
            "class" => "",
            "heading" => "Bottom Margin",
            "param_name" => "margin_bottom",
            "value" => "",
            "description" => __( "Enter the bottom margin of your content strip.", 'captiva' )
        ),
        array(
            "type" => "colorpicker",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Message box background color", 'captiva' ),
            "value" => "#333",
            "param_name" => "bg",
            "description" => __( "Select a background color for your message box.", 'captiva' )
        ),
        array(
            "type" => "dropdown",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Vertical Position", 'captiva' ),
            "param_name" => "valign",
            "value" => array(
                "Center" => "center",
                "Top" => "top",
                "Bottom" => "bottom",
            ),
            "description" => __( "Select the vertical position of your content.", 'captiva' ),
        ),
        array(
            "type" => "dropdown",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Horizontal Position", 'captiva' ),
            "param_name" => "halign",
            "value" => array(
                "Center" => "center",
                "Left" => "left",
                "Right" => "right",
            ),
            "description" => __( "Select the horizontal position of your content.", 'captiva' ),
        ),
        array(
            "type" => "dropdown",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Text alignment", 'captiva' ),
            "param_name" => "text_align",
            "value" => array(
                "Center" => "center",
                "Left" => "left",
                "Right" => "right",
            ),
            "description" => __( "Select the alignment for your content.", 'captiva' ),
        ),
        array(
            "type" => "dropdown",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Text color", 'captiva' ),
            "param_name" => "color",
            "value" => array(
                "Light" => "light",
                "Dark" => "dark",
            ),
            "description" => __( "Select your text color preference.", 'captiva' ),
        ),
        array(
            "type" => "dropdown",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Text animation effect", 'captiva' ),
            "param_name" => "animation",
            "value" => array(
                "none" => "none",
                "bounce" => "bounce",
                "flash" => "flash",
                "pulse" => "pulse",
                "rubberBand" => "rubberBand",
                "shake" => "shake",
                "swing" => "swing",
                "tada" => "tada",
                "wobble" => "wobble",
                "bounceIn" => "bounceIn",
                "bounceInDown" => "bounceInDown",
                "bounceInLeft" => "bounceInLeft",
                "bounceInRight" => "bounceInRight",
                "bounceInUp" => "bounceInUp",
                "bounceOut" => "bounceOut",
                "bounceOutDown" => "bounceOutDown",
                "bounceOutLeft" => "bounceOutLeft",
                "bounceOutRight" => "bounceOutRight",
                "bounceOutUp" => "bounceOutUp",
                "fadeIn" => "fadeIn",
                "fadeInDown" => "fadeInDown",
                "fadeInDownBig" => "fadeInDownBig",
                "fadeInLeft" => "fadeInLeft",
                "fadeInLeftBig" => "fadeInLeftBig",
                "fadeInRight" => "fadeInRight",
                "fadeInRightBig" => "fadeInRightBig",
                "fadeInUp" => "fadeInUp",
                "fadeInUpBig" => "fadeInUpBig",
                "fadeOut" => "fadeOut",
                "fadeOutDown" => "fadeOutDown",
                "fadeOutDownBig" => "fadeOutDownBig",
                "fadeOutLeft" => "fadeOutLeft",
                "fadeOutLeftBig" => "fadeOutLeftBig",
                "fadeOutRight" => "fadeOutRight",
                "fadeOutRightBig" => "fadeOutRightBig",
                "fadeOutUp" => "fadeOutUp",
                "fadeOutUpBig" => "fadeOutUpBig",
                "flip" => "flip",
                "flipInX" => "flipInX",
                "flipInY" => "flipInY",
                "flipOutX" => "flipOutX",
                "flipOutY" => "flipOutY",
                "lightSpeedIn" => "lightSpeedIn",
                "lightSpeedOut" => "lightSpeedOut",
                "rotateIn" => "rotateIn",
                "rotateInDownLeft" => "rotateInDownLeft",
                "rotateInDownRight" => "rotateInDownRight",
                "rotateInUpLeft" => "rotateInUpLeft",
                "rotateInUpRight" => "rotateInUpRight",
                "rotateOut" => "rotateOut",
                "rotateOutDownLeft" => "rotateOutDownLeft",
                "rotateOutDownRight" => "rotateOutDownRight",
                "rotateOutUpLeft" => "rotateOutUpLeft",
                "rotateOutUpRight" => "rotateOutUpRight",
                "slideInDown" => "slideInDown",
                "slideInLeft" => "slideInLeft",
                "slideInRight" => "slideInRight",
                "slideOutLeft" => "slideOutLeft",
                "slideOutRight" => "slideOutRight",
                "slideOutUp" => "slideOutUp",
                "hinge" => "hinge",
                "rollIn" => "rollIn",
                "rollOut" => "rollOut",
            ),
            "description" => __( "Select your text animation preference.", 'captiva' ),
        ),
        array(
            "type" => "attach_image",
            "holder" => "div",
            "class" => "",
            "heading" => "Video image alternative for smartphones and tablets",
            "param_name" => "video_img",
            "description" => __( "Please enter the url to the image alternative for your video.", 'captiva' )
        ),
        array(
            "type" => "attach_image",
            "holder" => "div",
            "class" => "",
            "heading" => "Upload a video overlay image.",
            "param_name" => "video_overlay_img",
            "description" => __( "A video overlay image is used to improve the text contrast on your video background. Upload a 10px * 10px overlay image to change the overlay effect.", 'captiva' )
        ),
        array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => "WEBM File",
            "param_name" => "video_webm",
            "description" => __( "Please enter the url to the WEBM version of your video.", 'captiva' )
        ),
        array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => "MP4 File",
            "param_name" => "video_mp4",
            "description" => __( "Please enter the url to the MP4 version of your video.", 'captiva' )
        ),
        array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => "OGV File",
            "param_name" => "video_ogv",
            "description" => __( "Please enter the url to the OGV version of your video.", 'captiva' )
        ),
        array(
            "type" => "textarea_html",
            "holder" => "div",
            "class" => "",
            "heading" => __( "Content", 'captiva' ),
            "param_name" => "content",
            "value" => __( "Enter your content here!", 'captiva' ),
            "description" => __( "This can be as simple as a page title or a full page if you want!.", 'captiva' )
        ),
    )
) );

/* * * Person Profile Shortcode ** */

$person_icons = array(
    "" => "",
    "ADN" => "fa-adn",
    "Dribbble" => "fa-dribbble",
    "Facebook" => "fa-facebook",
    "Facebook-Sign" => "fa-facebook-sign",
    "GitHub" => "fa-github",
    "GitHub-Alt" => "fa-github-alt",
    "GitHub-Sign" => "fa-github-sign",
    "Google Plus" => "fa-google-plus",
    "Google Plus-Sign" => "fa-google-plus-sign",
    "Instagram" => "fa-instagram",
    "LinkedIn" => "fa-linkedin",
    "LinkedIn-Sign" => "fa-linkedin-sign",
    "Skype" => "fa-skype",
    "StackExchange" => "fa-stackexchange",
    "Twitter" => "fa-twitter",
    "Twitter-Sign" => "fa-twitter-sign",
    "YouTube" => "fa-youtube",
    "YouTube Play" => "fa-youtube-play",
    "YouTube-Sign" => "fa-youtube-sign"
);

vc_map( array(
    "name" => "Person",
    "base" => "captiva_person",
    "category" => __( 'Captiva Shortcodes', 'captiva' ),
    "icon" => "icon-wpb-cap_vc_extend",
    "allowed_container_element" => 'vc_row',
    "params" => array(
        array(
            "type" => "attach_image",
            "holder" => "div",
            "class" => "",
            "heading" => "Person Profile Picture",
            "param_name" => "person_img"
        ),
        array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => "Full Name",
            "param_name" => "person_name"
        ),
        array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => "Job Title - Position",
            "param_name" => "person_title"
        ),
        array(
            "type" => "textarea",
            "holder" => "div",
            "class" => "",
            "heading" => "Job Description",
            "param_name" => "person_desc"
        ),
        array(
            "type" => "dropdown",
            "class" => "",
            "heading" => "Social Media Icon 1",
            "param_name" => "person_social_icon_1",
            "value" => $person_icons,
        ),
        array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => "Social Media Icon 1 - URL",
            "param_name" => "person_social_icon_1_url"
        ),
        array(
            "type" => "dropdown",
            "class" => "",
            "heading" => "Social Media Icon 2",
            "param_name" => "person_social_icon_2",
            "value" => $person_icons,
        ),
        array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => "Social Media Icon 2 - URL",
            "param_name" => "person_social_icon_2_url"
        ),
        array(
            "type" => "dropdown",
            "class" => "",
            "heading" => "Social Media Icon 3",
            "param_name" => "person_social_icon_3",
            "value" => $person_icons,
        ),
        array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => "Social Media Icon 3 - URL",
            "param_name" => "person_social_icon_3_url"
        ),
        array(
            "type" => "dropdown",
            "class" => "",
            "heading" => "Social Media Icon 4",
            "param_name" => "team_social_icon_4",
            "value" => $person_icons,
        ),
        array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => "Social Media Icon 4 - URL",
            "param_name" => "team_social_icon_4_url"
        ),
        array(
            "type" => "dropdown",
            "class" => "",
            "heading" => "Socia Icon 5",
            "param_name" => "team_social_icon_5",
            "value" => $person_icons,
        ),
        array(
            "type" => "textfield",
            "holder" => "div",
            "class" => "",
            "heading" => "Socia Icon 5 Link",
            "param_name" => "team_social_icon_5_url"
        ),
    )
) );