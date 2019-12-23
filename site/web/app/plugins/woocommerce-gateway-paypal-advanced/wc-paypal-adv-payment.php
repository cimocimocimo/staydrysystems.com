<?php
/**
 * Plugin Name: WooCommerce PayPal Payments Advanced Gateway
 * Plugin URI: https://woocommerce.com/products/paypal-advanced/
 * Description: A payment gateway for PayPal Payments Advanced (https://www.paypal.com/webapps/mpp/paypal-payments-advanced). A PayPal Advanced account is required for this gateway to function. Paypal Adavnced currently only supports USD and CAD.
 * Version: 1.24.5
 * Author: WooCommerce
 * Author URI: https://woocommerce.com
 * Text Domain: wc_paypaladv
 * WC tested up to: 3.4
 * WC requires at least: 2.6
 *
 * Woo: 18742:0898a0a035d9e9c0bddf4b31a3906ae9
 *
 * Paypal Payments Advanced Docs: https://cms.paypal.com
 *
 * @package WC_Paypal_Advanced
 */

/**
 * Required functions.
 */
if ( ! function_exists( 'woothemes_queue_update' ) ) {
	require_once( 'woo-includes/woo-functions.php' );
}

/**
 * Plugin updates.
 */
woothemes_queue_update( plugin_basename( __FILE__ ), '0898a0a035d9e9c0bddf4b31a3906ae9', '18742' );

/**
 * Enqueue admin scripts.
 *
 * @since 1.0.0
 *
 * @param string $hook_suffix The current admin page.
 */
function wc_paypaladv__enqueue_color_picker( $hook_suffix ) {
	wp_enqueue_style( 'wp-color-picker' );
	wp_enqueue_script( 'wc_paypaladv-script-handle', plugins_url( 'js/misc.js', __FILE__ ), array( 'wp-color-picker' ), false, true );
}
add_action( 'admin_enqueue_scripts', 'wc_paypaladv__enqueue_color_picker' );

/**
 * Plugins loaded actions.
 *
 * @since 1.0.0
 */
function woocommerce_paypaladv_init() {
	if ( ! class_exists( 'WC_Payment_Gateway' ) ) {
		return;
	}

	require_once( 'class-wc-paypal-advanced-privacy.php' );

	 // Localisation.
	load_plugin_textdomain( 'wc_paypaladv', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );

	// Include required files based on admin or site.
	require_once( plugin_dir_path( __FILE__ ) . 'class-wc-paypal-advanced.php' );
}
add_action( 'plugins_loaded', 'woocommerce_paypaladv_init' );
