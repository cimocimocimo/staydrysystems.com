<?php

/**
 * Plugin Name: Captiva Toolkit
 * Plugin URI: https://wordpress.org/plugins/captiva-toolkit/
 * Description: A suite of custom post types for the Captiva theme.
 * Version: 1.2.4
 * Author: Colm Troy
 * Author URI: http://commercegurus.com
 * License: GPL2
 * Requires at least: 3.9.1
 * Tested up to: 3.9.1
 * Credits to CodeStag for providing StagTools to the community which formed the basis for the initial inspiration for this plugin - https://github.com/mauryaratan/stagtools/
 *
 * Text Domain: captiva
 * Domain Path: /languages/
 */

if ( !defined( 'ABSPATH' ) )
    exit; // Exit if accessed directly

if ( !class_exists( 'CaptivaToolKit' ) ) {
    /**
     * Setup sortable gallery custom CMB field type.
     * Credits to mustardBees for most of this https://github.com/mustardBees/cmb-field-gallery
     *
     * @return void
     */
    define( 'CAP_GALLERY_URL', plugin_dir_url( __FILE__ ) );
    define( 'CAP_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

    /**
     * Render field
     */
    function cap_gallery_field( $field, $meta ) {
        wp_enqueue_script( 'cap_gallery_init', CAP_GALLERY_URL . 'js/script.js', array( 'jquery' ), null );
        
        if ( !empty( $meta ) ) {
            $meta = implode( ',', $meta );
        }
        echo '<div class="cap-gallery">';
        echo '	<input type="hidden" id="' . $field['id'] . '" name="' . $field['id'] . '" value="' . $meta . '" />';
        echo '	<input type="button" class="button" value="' . (!empty( $field['button'] ) ? $field['button'] : 'Manage gallery' ) . '" />';
        echo '</div>';
        
        if ( !empty( $field['desc'] ) ) {
            echo '<p class="cmb_metabox_description">' . $field['desc'] . '</p>';
        }
    }
    
    add_filter( 'cmb_render_cap_gallery', 'cap_gallery_field', 10, 2 );

    /**
     * Split CSV string into an array of values
     */
    function cap_gallery_field_sanitise( $meta_value, $field ) {
        if ( empty( $meta_value ) ) {
            $meta_value = '';
        } else {
            $meta_value = explode( ',', $meta_value );
        }
        
        return $meta_value;
    }

    function cap_folio_recentthumb( $postid, $related = FALSE ) {
        $thumb = get_the_post_thumbnail( $postid, 'showcase-4col', array( 'class' => 'cap-thumbnail' ) );
        
        if ( $thumb == '' ) {
            $thumb = '<img src="' . $thumb . '" alt="' . get_the_title() . '" class="cap-thumbnail" />';
        }
        
        $output = '<span class="cap-folio-img"><a title="' . get_the_title( $postid ) . '" href="' . get_permalink( $postid ) . '">' . $thumb . '</a></span>';
        
        echo $output;
    }

    function cap_showcasethumb( $postid, $related = FALSE ) {
        if ( (is_page_template( 'template-showcase-4col.php' )) || (is_page_template( 'single-tf_showcase.php' )) ) {
            $thumb = get_the_post_thumbnail( $postid, 'showcase-4col', array( 'class' => 'cap-thumbnail' ) );
            
            if ( $thumb == '' ) {
                $thumb = '<img src="' . $thumb . '" alt="' . get_the_title() . '" class="cap-thumbnail" />';
            }
            
            $output = '<span class="cap-folio-img"><a title="' . get_the_title( $postid ) . '" href="' . get_permalink( $postid ) . '">' . $thumb . '</a></span>';
            
            echo $output;
            
        } elseif ( is_page_template( 'template-showcase-3col.php' ) ) {
            $thumb = get_the_post_thumbnail( $postid, 'showcase-3col', array( 'class' => 'cap-thumbnail' ) );
            if ( $thumb == '' ) {
                $thumb = '<img src="' . $thumb . '" alt="' . get_the_title() . '" class="cap-thumbnail" />';
            }
            
            $output = '<span class="cap-folio-img"><a title="' . get_the_title( $postid ) . '" href="' . get_permalink( $postid ) . '">' . $thumb . '</a></span>';
            
            echo $output;
            
        } elseif ( is_page_template( 'template-showcase-2col.php' ) ) {
            $thumb = get_the_post_thumbnail( $postid, 'showcase-2col', array( 'class' => 'cap-thumbnail' ) );
            if ( $thumb == '' ) {
                $thumb = '<img src="' . $thumb . '" alt="' . get_the_title() . '" class="cap-thumbnail" />';
            }
            
            $output = '<span class="cap-folio-img"><a title="' . get_the_title( $postid ) . '" href="' . get_permalink( $postid ) . '">' . $thumb . '</a></span>';
            
            echo $output;
        }
    }

    /**
     * Main CaptivaToolKit Class
     *
     * @package CaptivaToolKit
     * @version 1.0
     * @author Colm Troy
     * @link http://captivate.io
     */
    class CaptivaToolKit {

        /**
         * @var string
         */
        public $version = '1.0';

        /**
         * @var string
         */
        public $plugin_url;

        /**
         * @var string
         */
        public $plugin_path;

        /**
         * @var string
         */
        public $template_url;

        /**
         * CaptivaToolKit Constructor.
         *
         * @access public
         * @return void
         */
        public function __construct() {
            // Define version constant
            define( 'CAPTIVATOOLKIT_VERSION', $this->version );
            // Hooks
            add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), array( $this, 'action_links' ) );
            add_action( 'init', array( &$this, 'init' ) );
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
        }

        public function enqueue_styles() {
            wp_enqueue_style( 'captiva_toolkit_styles', CAP_PLUGIN_URL . 'css/captiva_toolkit.css' );
        }

        /**
         * Add custom links on plugins page.
         *
         * @access public
         * @param mixed $links
         * @return void
         */
        public function action_links( $links ) {
            $plugin_links = array(
                '<a href="' . admin_url( 'options-general.php?page=captivatoolkit' ) . '">' . __( 'Settings', 'captiva' ) . '</a>'
            );

            return array_merge( $plugin_links, $links );
        }

        /**
         * Initialise Captiva Toolkit .
         *
         * @return void
         */
        function init() {
            $this->captiva_load_textdomain();

            add_filter( 'body_class', array( &$this, 'body_class' ) );

            /**
             * Include the Custom Post Types, Taxonomies, Custom fields and Shortcodes
             */
            
            // Post Types
            include_once( 'includes/post-types/testimonials.php');
            include_once( 'includes/post-types/topreviews.php');
            include_once( 'includes/post-types/logos.php');
            include_once( 'includes/post-types/shopannouncements.php');
            include_once( 'includes/post-types/showcases.php');

            // Taxonomies
            include_once( 'includes/taxonomies/showcasecategory.php');

            // Custom fields
            include_once( 'includes/metaboxes/testimonials.php');
            include_once( 'includes/metaboxes/topreviews.php');
            include_once( 'includes/metaboxes/logos.php');
            include_once( 'includes/metaboxes/shopannouncements.php');
            include_once( 'includes/metaboxes/showcases.php');

            // Shortcodes

            include_once( 'includes/shortcodes/google_maps.php');
            include_once( 'includes/shortcodes/woocommerce_product_sliders.php');
            include_once( 'includes/shortcodes/latest_posts.php');
            include_once( 'includes/shortcodes/promo_message_box.php');
            include_once( 'includes/shortcodes/video_banner.php');
            include_once( 'includes/shortcodes/general-markup.php');
            include_once( 'includes/shortcodes/testimonials.php');
            include_once( 'includes/shortcodes/topreviews.php');
            include_once( 'includes/shortcodes/logos.php');
            include_once( 'includes/shortcodes/portfolio_slider.php');
            include_once( 'includes/shortcodes/content_strips.php');

            // Initialize the metabox class
            add_action( 'init', 'captiva_initialize_cmb_meta_boxes', 9999 );

            function captiva_initialize_cmb_meta_boxes() {
                if ( !class_exists( 'cmb_Meta_Box' ) ) {
                    require_once( 'includes/metaboxes/cmb/init.php' );
                }
            }
        }

        /**
         * Setup localisation.
         *
         * @return void
         */
        function captiva_load_textdomain() {
            // Set filter for plugin's languages directory
            $captivatoolkit_lang_dir = dirname( plugin_basename( __FILE__ ) ) . '/languages/';
            $captivatoolkit_lang_dir = apply_filters( 'captivatoolkit_languages_directory', $captivatoolkit_lang_dir );

            // Traditional WordPress plugin locale filter
            $locale = apply_filters( 'plugin_locale', get_locale(), 'captiva' );
            $mofile = sprintf( '%1$s-%2$s.mo', 'captiva', $locale );

            // Setup paths to current locale file
            $mofile_local = $captivatoolkit_lang_dir . $mofile;
            $mofile_global = WP_LANG_DIR . '/captivatoolkit/' . $mofile;

            if ( file_exists( $mofile_global ) ) {
                // Look in global /wp-content/languages/captiva-toolkit folder
                load_textdomain( 'captiva', $mofile_global );
            } elseif ( file_exists( $mofile_local ) ) {
                // Look in local /wp-content/plugins/captiva-toolkit/languages/ folder
                load_textdomain( 'captiva', $mofile_local );
            } else {
                // Load the default language files
                load_plugin_textdomain( 'captiva', false, $captivatoolkit_lang_dir );
            }
        }

        /**
         * Plugin path.
         *
         * @return string Plugin path
         */
        public function plugin_path() {
            if ( $this->plugin_path ) {
                return $this->plugin_path;
            }

            return $this->plugin_path = untrailingslashit( plugin_dir_path( __FILE__ ) );
        }

        /**
         * Plugin url.
         *
         * @return string Plugin url
         */
        public function plugin_url() {
            if ( $this->plugin_url ) {
                return $this->plugin_url;
            }
            
            return $this->plugin_url = untrailingslashit( plugins_url( '/', __FILE__ ) );
        }

        /**
         * Add captivatoolkit to body class for use on frontend.
         *
         * @since 1.0.0
         * @return array $classes List of classes
         */
        public function body_class( $classes ) {
            $classes[] = 'captivatoolkit';
            return $classes;
        }
    }

    $GLOBALS['captivatoolkit'] = new CaptivaToolKit();
}

/**
 * Flush the rewrite rules on activation
 */
function captivatoolkit_activation() {
    flush_rewrite_rules();
}

register_activation_hook( __FILE__, 'captivatoolkit_activation' );

/**
 * Also flush the rewrite rules on deactivation
 */
function captivatoolkit_deactivation() {
    flush_rewrite_rules();
}

register_deactivation_hook( __FILE__, 'captivatoolkit_activation' );