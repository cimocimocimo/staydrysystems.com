<?php

/**
 * captiva functions and definitions
 * Tread very carefully in here and don't upset the wp functions monkeys as they have wings like those ones in the Wizard of Oz and will track you down and probably bite you - they like to be left alone.
 *
 * @package captiva
 */
global $captiva_options;


/**
 * Global Paths
 */
define( 'CAP_FILEPATH', get_template_directory() );
define( 'CAP_THEMEURI', get_template_directory_uri() );
define( 'CAP_BOOTSTRAP_JS', get_template_directory_uri() . '/inc/core/bootstrap/dist/js' );
define( 'CAP_JS', get_template_directory_uri() . '/js' );
define( 'CAP_CORE', get_template_directory() . '/inc/core' );


/**
 * Constants for Plugins
 */
define( 'ULTIMATE_USE_BUILTIN', true);


/**
 * Load Globals
 */
require_once(CAP_CORE . '/functions/javascript.php');
require_once(CAP_CORE . '/functions/get-the-image.php');
require_once(CAP_CORE . '/menus/wp_bootstrap_navwalker.php');
require_once(CAP_CORE . '/menus/megadropdown.php');


/**
 * TGM Plugin Activation
 */
require_once (CAP_CORE . '/functions/class-tgm-plugin-activation.php');
add_action( 'tgmpa_register', 'captiva_register_required_plugins' );
function captiva_register_required_plugins() {

    /**
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
    $plugins = array(
        array(
            'name'                  => 'Advanced Custom Fields', // The plugin name
            'slug'                  => 'advanced-custom-fields', // The plugin slug (typically the folder name)
            'required'              => true, // If false, the plugin is only 'recommended' instead of required
        ),
        array(
            'name'                  => 'Advanced Sidebar Menu', // The plugin name
            'slug'                  => 'advanced-sidebar-menu', // The plugin slug (typically the folder name)
            'required'              => true, // If false, the plugin is only 'recommended' instead of required
        ),        
        array(
            'name'                  => 'Captiva Toolkit', // The plugin name
            'slug'                  => 'captiva-toolkit', // The plugin slug (typically the folder name)
            'source'                => get_stylesheet_directory() . '/inc/plugins/captiva-toolkit.1.2.4.zip', // The plugin source
            'required'              => true, // If false, the plugin is only 'recommended' instead of required
            'version'               => '1.2.4', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
            'force_activation'      => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
            'force_deactivation'    => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
            'external_url'          => '', // If set, overrides default API URL and points to an external URL
        ),
        array(
            'name'                  => 'Contact Form 7', // The plugin name
            'slug'                  => 'contact-form-7', // The plugin slug (typically the folder name)
            'required'              => false, // If false, the plugin is only 'recommended' instead of required
        ),
        array(
            'name'                  => 'Envato Toolkit', // The plugin name
            'slug'                  => 'envato-wordpress-toolkit-master', // The plugin slug (typically the folder name)
            'source'                => get_stylesheet_directory() . '/inc/plugins/envato-wordpress-toolkit-master.zip', // The plugin source
            'required'              => true, // If false, the plugin is only 'recommended' instead of required
            'version'               => '1.6.3', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
            'force_activation'      => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
            'force_deactivation'    => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
            'external_url'          => '', // If set, overrides default API URL and points to an external URL
        ),
        array(
            'name'                  => 'Layer Slider', // The plugin name
            'slug'                  => 'LayerSlider', // The plugin slug (typically the folder name)
            'source'                => get_stylesheet_directory() . '/inc/plugins/layersliderwp-5.1.1.installable.zip', // The plugin source
            'required'              => false, // If false, the plugin is only 'recommended' instead of required
            'version'               => '5.1.1', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
            'force_activation'      => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
            'force_deactivation'    => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
            'external_url'          => '', // If set, overrides default API URL and points to an external URL
        ),
        array(
            'name'                  => 'MailChimp', // The plugin name
            'slug'                  => 'mailchimp', // The plugin slug (typically the folder name)
            'required'              => false, // If false, the plugin is only 'recommended' instead of required
        ),
        array(
            'name'                  => 'Regenerate Thumbnails', // The plugin name
            'slug'                  => 'regenerate-thumbnails', // The plugin slug (typically the folder name)
            'required'              => false, // If false, the plugin is only 'recommended' instead of required
        ),
        array(
            'name'                  => 'Ultimate Addons for Visual Composer', // The plugin name
            'slug'                  => 'Ultimate_VC_Addons', // The plugin slug (typically the folder name)
            'source'                => get_stylesheet_directory() . '/inc/plugins/Ultimate_VC_Addons.zip', // The plugin source
            'required'              => false, // If false, the plugin is only 'recommended' instead of required
            'version'               => '3.3.2', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
            'force_activation'      => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
            'force_deactivation'    => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
            'external_url'          => '', // If set, overrides default API URL and points to an external URL
        ),
        array(
            'name'      => 'WooCommerce',
            'slug'      => 'woocommerce',
            'required'  => true,
        ),  
        array(
            'name'                  => 'WooSidebars', // The plugin name
            'slug'                  => 'woosidebars', // The plugin slug (typically the folder name)
            'required'              => true, // If false, the plugin is only 'recommended' instead of required
        ),
        array(
            'name'                  => 'WPBakery Visual Composer', // The plugin name
            'slug'                  => 'js_composer', // The plugin slug (typically the folder name)
            'source'                => get_stylesheet_directory() . '/inc/plugins/js_composer.zip', // The plugin source
            'required'              => true, // If false, the plugin is only 'recommended' instead of required
            'version'               => '4.2.3', // E.g. 1.0.0. If set, the active plugin must be this version or higher, otherwise a notice is presented
            'force_activation'      => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch
            'force_deactivation'    => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins
            'external_url'          => '', // If set, overrides default API URL and points to an external URL
        ),
        array(
            'name'                  => 'YITH WooCommerce Ajax Navigation', // The plugin name
            'slug'                  => 'yith-woocommerce-ajax-navigation', // The plugin slug (typically the folder name)
            'required'              => false, // If false, the plugin is only 'recommended' instead of required
        ),
        array(
            'name'                  => 'YITH WooCommerce Ajax Search', // The plugin name
            'slug'                  => 'yith-woocommerce-ajax-search', // The plugin slug (typically the folder name)
            'required'              => false, // If false, the plugin is only 'recommended' instead of required
        ),
            array(
            'name'                  => 'YITH WooCommerce Wishlist', // The plugin name
            'slug'                  => 'yith-woocommerce-wishlist', // The plugin slug (typically the folder name)
            'required'              => false, // If false, the plugin is only 'recommended' instead of required
        ),
        array(
            'name'                  => 'WP Retina 2x', // The plugin name
            'slug'                  => 'wp-retina-2x', // The plugin slug (typically the folder name)
            'required'              => false, // If false, the plugin is only 'recommended' instead of required
        ),
    );

    /**
     * Array of configuration settings. Amend each line as needed.
     * If you want the default strings to be available under your own theme domain,
     * leave the strings uncommented.
     * Some of the strings are added into a sprintf, so see the comments at the
     * end of each line for what each argument will be.
     */
    $config = array(
        'domain'            => 'captiva',                   // Text domain - likely want to be the same as your theme.
        'default_path'      => '',                          // Default absolute path to pre-packaged plugins
        'parent_menu_slug'  => 'themes.php',                // Default parent menu slug
        'parent_url_slug'   => 'themes.php',                // Default parent URL slug
        'menu'              => 'install-required-plugins',  // Menu slug
        'has_notices'       => true,                        // Show admin notices or not
        'is_automatic'      => false,                       // Automatically activate plugins after installation or not
        'message'           => '',                          // Message to output right before the plugins table
        'strings'           => array(
            'page_title'                                => __( 'Install Required Plugins', '' ),
            'menu_title'                                => __( 'Install Plugins', 'captiva' ),
            'installing'                                => __( 'Installing Plugin: %s', 'captiva' ), // %1$s = plugin name
            'oops'                                      => __( 'Something went wrong with the plugin API.', 'captiva' ),
            'notice_can_install_required'               => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ), // %1$s = plugin name(s)
            'notice_can_install_recommended'            => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ), // %1$s = plugin name(s)
            'notice_cannot_install'                     => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ), // %1$s = plugin name(s)
            'notice_can_activate_required'              => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
            'notice_can_activate_recommended'           => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s)
            'notice_cannot_activate'                    => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ), // %1$s = plugin name(s)
            'notice_ask_to_update'                      => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ), // %1$s = plugin name(s)
            'notice_cannot_update'                      => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ), // %1$s = plugin name(s)
            'install_link'                              => _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
            'activate_link'                             => _n_noop( 'Activate installed plugin', 'Activate installed plugins' ),
            'return'                                    => __( 'Return to Required Plugins Installer', 'captiva' ),
            'plugin_activated'                          => __( 'Plugin activated successfully.', 'captiva' ),
            'complete'                                  => __( 'All plugins installed and activated successfully. %s', 'captiva' ), // %1$s = dashboard link
            'nag_type'                                  => 'updated' // Determines admin notice type - can only be 'updated' or 'error'
        )
    );

    tgmpa( $plugins, $config );

}


/**
 * Live Preview
 */
//$cap_live_preview = true;

if ( isset( $cap_live_preview ) ) {

    add_action('after_setup_theme', 'start_live_session', 1);
    add_action('wp_logout', 'end_live_session');
    add_action('wp_login', 'end_live_session');

    //start live session
    if (!function_exists('start_live_session')) {
        function start_live_session() {
            if(!session_id()) {
                session_start();
            }
        }
    }

    //end live session 
    if (!function_exists('end_live_session')) {
        function end_live_session() {
            session_destroy ();
        }
    }

}

/**
 * Load CSS
 */
function load_captiva_styles() {
    global $cap_live_preview;

    wp_register_style( 'cap-bootstrap', get_template_directory_uri() . '/inc/core/bootstrap/dist/css/bootstrap.min.css' );
    wp_register_style( 'cap-captiva', get_template_directory_uri() . '/css/captiva.css' );
    wp_register_style( 'cap-responsive', get_template_directory_uri() . '/css/responsive.css' );
    wp_register_style( 'cap-customcss', get_template_directory_uri() . '/custom/custom.css' );
    wp_enqueue_style( 'cap-style', get_stylesheet_uri() );
    wp_enqueue_style( 'cap-font-awesome', '//netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css', array(), '4.0.3' );
    wp_enqueue_style( 'cap-bootstrap' );
    wp_enqueue_style( 'cap-captiva' );
    wp_enqueue_style( 'cap-responsive' );  
    wp_enqueue_style( 'cap-customcss' );
    if ( isset( $cap_live_preview ) ) {
        wp_enqueue_style( 'cap-livepreviewcss', get_template_directory_uri() . '/css/livepreview.css' );
    }  
}
add_action( 'wp_enqueue_scripts', 'load_captiva_styles' );

// Load css from theme options.
require_once(CAP_CORE . '/css/custom-css.php');


/**
 * Add Redux Framework
 */
require get_template_directory() . '/admin/admin-init.php';


/**
 * Force Visual Composer to initialize as "built into the theme". This will hide certain tabs under the Settings->Visual Composer page
 */
if ( function_exists( 'vc_set_as_theme' ) ) {
    vc_set_as_theme( $disable_updater = true );
}
    
// Initialising Shortcodes
if ( class_exists( 'WPBakeryVisualComposerAbstract' ) ) {

    function requireVcExtend() {
        require_once locate_template( '/customvc/vc_extend.php' );
    }

    add_action( 'init', 'requireVcExtend', 2 );

    // Set VC tpl override directory
    $vcdir = get_stylesheet_directory() . '/customvc/vc_templates/';
    vc_set_shortcodes_templates_dir( $vcdir );
    # vc_set_template_dir( $vcdir );

    // Remove VC nag looking for key - Captiva has an extended lic.
    if ( is_admin() ) :
        function remove_vc_nag() {
            remove_meta_box( 'vc_teaser', '' , 'side' );
        }
        add_action( 'admin_head', 'remove_vc_nag' );
    endif;

}


/**
 * Sets up theme defaults and registers support for various WordPress features.
 */
if ( !function_exists( 'captiva_setup' ) ) :

    function captiva_setup() {

        /**
         * Translations can be filed in the /languages/ directory
         * If you're building a theme based on captiva, use a find and replace
         * to change 'captiva' to the name of your theme in all the template files
         */
        load_theme_textdomain( 'captiva', get_template_directory() . '/languages' );

        /**
         * Add default posts and comments RSS feed links to head
         */
        add_theme_support( 'automatic-feed-links' );

        /**
         * This theme uses wp_nav_menu() in one location.
         */
        register_nav_menus( array(
            'primary' => __( 'Primary Menu', 'captiva' ),
            'top' => __( 'Top Menu', 'captiva' ),
            'mobile' => __( 'Mobile Menu', 'captiva' ),
        ) );

        /**
         * Custom Thumbnails
         */
        if ( function_exists( 'add_theme_support' ) ) {
            add_theme_support( 'post-thumbnails' );
            add_image_size( 'hp-latest-posts', 380, 160, true );
            add_image_size( 'showcase-page', 750, 450, true ); // Showcase Page thumbnail
            add_image_size( 'showcase-4col', 293, 186, true ); // Showcase 4Col thumbnail
            add_image_size( 'showcase-3col', 360, 234, true ); // Showcase 3Col thumbnail
            add_image_size( 'showcase-2col', 585, 431, true ); // Showcase 2Col thumbnail
            add_image_size( 'product-category-banner', 1140, 500, true );
        }

        /**
         * Enable support for Post Formats
         */
        add_theme_support( 'post-formats', array( 'aside', 'image', 'video', 'audio', 'quote', 'link' ) );

        /**
         * Setup the WordPress core custom background feature.
         */
        //add_theme_support( 'custom-background', apply_filters( 'captiva_custom_background_args', array(
        //  'default-color' => 'ffffff',
        //  'default-image' => '',
        //) ) );
    }

endif; // captiva_setup
add_action( 'after_setup_theme', 'captiva_setup' );


/**
 * Set WooCommerce image dimensions upon activation
 */
global $pagenow;
if ( is_admin() && isset( $_GET['activated'] ) && $pagenow == 'themes.php' ) add_action( 'init', 'captiva_woocommerce_image_dimensions', 1 );

/**
 * Define image sizes
 */
function captiva_woocommerce_image_dimensions() {
    $catalog = array(
        'width'     => '204',   // px
        'height'    => '307',   // px
        'crop'      => 1        // true
    );

    $single = array(
        'width'     => '500',   // px
        'height'    => '750',   // px
        'crop'      => 1        // true
    );

    $thumbnail = array(
        'width'     => '120',   // px
        'height'    => '180',   // px
        'crop'      => 1        // false
    );

    // Image sizes
    update_option( 'shop_catalog_image_size', $catalog );       // Product category thumbs
    update_option( 'shop_single_image_size', $single );         // Single product image
    update_option( 'shop_thumbnail_image_size', $thumbnail );   // Image gallery thumbs
}


/**
 * Register widgetized area and update sidebar with default widgets
 */
function captiva_widgets_init() {

    register_sidebar( array(
        'name' => __( 'Sidebar', 'captiva' ),
        'id' => 'sidebar-1',
        'before_widget' => '<aside id="%1$s" class="widget %2$s">',
        'after_widget' => '</aside>',
        'before_title' => '<h1 class="widget-title">',
        'after_title' => '</h1>',
    ) );

    register_sidebar( array(
        'name' => __( 'Top Bar - Search box', 'captiva' ),
        'id' => 'top-bar-search',
        'before_widget' => '<div id="%1$s" class="%2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widget-title">',
        'after_title' => '</h4>',
    ) );

    register_sidebar( array(
        'name' => __( 'Shop Sidebar', 'captiva' ),
        'id' => 'shop-sidebar',
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widget-title">',
        'after_title' => '</h4>',
    ) );

    register_sidebar( array(
        'name' => __( 'Below main body', 'captiva' ),
        'id' => 'below-body',
        'before_widget' => '<div class="row"><div id="%1$s" class="col-lg-12 %2$s">',
        'after_widget' => '</div></div>',
        'before_title' => '<h4 class="widget-title">',
        'after_title' => '</h4>',
    ) );

    register_sidebar( array(
        'name' => __( 'First Footer', 'captiva' ),
        'id' => 'first-footer',
        'before_widget' => '<div id="%1$s" class="col-lg-3 col-md-3 col-sm-6 %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widget-title">',
        'after_title' => '</h4>',
    ) );

    register_sidebar( array(
        'name' => __( 'Second Footer', 'captiva' ),
        'id' => 'second-footer',
        'before_widget' => '<div id="%1$s" class="col-lg-3 col-md-3 col-sm-6 %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widget-title">',
        'after_title' => '</h4>',
    ) );
}

add_action( 'widgets_init', 'captiva_widgets_init' );


/**
 * Custom template tags for this theme.
 */
require get_template_directory() . '/inc/template-tags.php';

/**
 * Custom functions that act independently of the theme templates.
 */
require get_template_directory() . '/inc/extras.php';

/* WooCommerce */

/* ----------------------------------------------------------------------------------- */
/* Start WooThemes Functions - Please refrain from editing this section */
/* ----------------------------------------------------------------------------------- */

// Register Support
add_theme_support( 'woocommerce' );

// Set path to WooFramework and theme specific functions
$woocommerce_path = get_template_directory() . '/woocommerce/';

// WooCommerce
if ( function_exists( "is_woocommerce" ) ) {
    require_once ( $woocommerce_path . 'woocommerce-config.php' );    //woocommerce shop plugin    
}

/**
 * Set the content width based on the theme's design and stylesheet.
 */
if ( !isset( $content_width ) )
    $content_width = 640; /* pixels */

// End of core functions.

// add units to the product price if defined.
add_filter('woocommerce_get_price_html','add_price_per_unit_meta_to_price');
function add_price_per_unit_meta_to_price( $price ) {
  $price .= ' ' . get_post_meta(get_the_ID(), 'wc_price_per_unit_key', true);
  return $price;
}

include get_template_directory() . '/staydry/custom-fields.php';
include get_template_directory() . '/staydry/homepage-logo-block.php';

function addPriceSuffix($format, $currency_pos) {
	switch ( $currency_pos ) {
    case 'left' :
        $currency = get_woocommerce_currency();
        $format = '%1$s%2$s&nbsp;' . $currency;
		break;
	}

	return $format;
}
add_action('woocommerce_price_format', 'addPriceSuffix', 1, 2);