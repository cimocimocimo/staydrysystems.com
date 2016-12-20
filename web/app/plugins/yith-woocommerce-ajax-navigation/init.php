<?php
/**
 * Plugin Name: YITH WooCommerce Ajax Product Filter
 * Plugin URI: http://yithemes.com/
 * Description: YITH WooCommerce Ajax Product Filter offers the perfect way to filter all the products of your shop.
 * Version: 3.1.0
 * Author: YITHEMES
 * Author URI: http://yithemes.com/
 * Text Domain: yith-woocommerce-ajax-navigation
 * Domain Path: /languages/
 *
 * @author  Your Inspiration Themes
 * @package YITH WooCommerce Ajax Navigation
 * @version 1.3.2
 */
/*  Copyright 2013  Your Inspiration Themes  (email : plugins@yithemes.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
if ( ! defined( 'ABSPATH' ) ) {
    exit;
} // Exit if accessed directly

if( ! function_exists( 'install_premium_woocommerce_admin_notice' ) ) {
    /**
     * Print an admin notice if woocommerce is deactivated
     *
     * @author Andrea Grillo <andrea.grillo@yithemes.com>
     * @since 1.0
     * @return void
     * @use admin_notices hooks
     */
    function install_premium_woocommerce_admin_notice() { ?>
        <div class="error">
            <p><?php _e( 'YITH WooCommerce Ajax Product Filter is enabled but not effective. It requires WooCommerce in order to work.', 'yith-woocommerce-ajax-navigation' ); ?></p>
        </div>
        <?php
    }
}
if( ! function_exists( 'yith_deactive_free_wcan_version' ) ) {
    function yith_deactive_free_wcan_version() {
        ?>
        <div class="error">
            <p><?php _e( 'You can\'t activate the free version of YITH WooCommerce Ajax Product Filter while you are using the premium one.', 'yith-wocc' ); ?></p>
        </div>
        <?php
    }
}

load_plugin_textdomain( 'yith-woocommerce-ajax-navigation', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );


! defined( 'YITH_WCAN' )            && define( 'YITH_WCAN', true );
! defined( 'YITH_WCAN_URL' )        && define( 'YITH_WCAN_URL', plugin_dir_url( __FILE__ ) );
! defined( 'YITH_WCAN_DIR' )        && define( 'YITH_WCAN_DIR', plugin_dir_path( __FILE__ ) );
! defined( 'YITH_WCAN_VERSION' )    && define( 'YITH_WCAN_VERSION', '3.1.0' );
! defined( 'YITH_WCAN_FREE_INIT')   && define( 'YITH_WCAN_FREE_INIT', plugin_basename( __FILE__ ) );
! defined( 'YITH_WCAN_FILE' )       && define( 'YITH_WCAN_FILE', __FILE__ );

/* Plugin Framework Version Check */
if( ! function_exists( 'yit_maybe_plugin_fw_loader' ) && file_exists( YITH_WCAN_DIR . 'plugin-fw/init.php' ) ) {
    require_once( YITH_WCAN_DIR . 'plugin-fw/init.php' );
}
yit_maybe_plugin_fw_loader( YITH_WCAN_DIR  );

/**
 * Init default plugin settings
 */
if ( ! function_exists( 'yith_plugin_registration_hook' ) ) {
    require_once 'plugin-fw/yit-plugin-registration-hook.php';
}

if ( ! function_exists( 'YITH_WCAN' ) ) {
    /**
     * Unique access to instance of YITH_Vendors class
     *
     * @return YITH_Vendors|YITH_Vendors_Premium
     * @since 1.0.0
     */
    function YITH_WCAN() {
        // Load required classes and functions
        require_once( YITH_WCAN_DIR . 'includes/class.yith-wcan.php' );

        if ( defined( 'YITH_WCAN_PREMIUM' ) && file_exists( YITH_WCAN_DIR . 'includes/class.yith-wcan-premium.php' ) ) {
            require_once( YITH_WCAN_DIR . 'includes/class.yith-wcan-premium.php' );
            return YITH_WCAN_Premium::instance();
        }
        return YITH_WCAN::instance();
    }
}

if( ! function_exists( 'yith_wcan_free_install' ) ){
    function yith_wcan_free_install() {

        if ( ! function_exists( 'WC' ) ) {
            add_action( 'admin_notices', 'install_premium_woocommerce_admin_notice' );
        }
        elseif ( defined( 'YITH_WCAN_PREMIUM' ) ) {
            add_action( 'admin_notices', 'yith_deactive_free_wcan_version' );
            deactivate_plugins( plugin_basename( __FILE__ ) );
        }
        else {
            /**
             * Instance main plugin class
             */
            global $yith_wcan;
            $yith_wcan = YITH_WCAN();
        }
    }
}

add_action( 'plugins_loaded', 'yith_wcan_free_install', 11 );

register_activation_hook( YITH_WCAN_FILE, 'yith_plugin_registration_hook' );