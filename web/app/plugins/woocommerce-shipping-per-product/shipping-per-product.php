<?php
/*
Plugin Name: WooCommerce Shipping Per Product v2
Plugin URI: http://woothemes.com/woocommerce/
Description: Per product shipping allows you to define different shipping costs for products, based on customer location. These costs can be added to other shipping methods (requires WC 2.0), or used as a standalone shipping method.
Version: 2.0.6
Author: Mike Jolley / WooThemes
Author URI: http://mikejolley.com
Requires at least: 3.3
Tested up to: 3.5

	Copyright: © 2009-2011 WooThemes.
	License: GNU General Public License v3.0
	License URI: http://www.gnu.org/licenses/gpl-3.0.html
*/

/**
 * Required functions
 */
if ( ! function_exists( 'woothemes_queue_update' ) )
	require_once( 'woo-includes/woo-functions.php' );

/**
 * Plugin updates
 */
woothemes_queue_update( plugin_basename( __FILE__ ), 'ba16bebba1d74992efc398d575bf269e', '18590' );

if ( is_woocommerce_active() ) {

	define( 'PER_PRODUCT_SHIPPING_VERSION', '2.0.5' );

	/**
	 * Includes
	 */
	if ( is_admin() )
		include_once( 'admin/product-options.php' );

	/**
	 * Installation
	 */
	register_activation_hook( __FILE__, 'install_per_product_shipping' );

	function install_per_product_shipping() {

		include_once( 'admin/install.php' );

		wc_per_product_shipping_install();

		update_option( 'per_product_shipping_version', PER_PRODUCT_SHIPPING_VERSION );
	}

	/**
	 * register_per_product_shipping_importer function.
	 *
	 * @access public
	 * @return void
	 */
	function register_per_product_shipping_importer() {
		if ( defined( 'WP_LOAD_IMPORTERS' ) )
			register_importer( 'woocommerce_per_product_shipping_csv', __( 'WooCommerce Per-product shipping rates (CSV)', 'woocommerce' ), __( 'Import <strong>per-product shipping rates</strong> to your store via a csv file.', 'woocommerce'), 'woocommerce_per_product_shipping_csv' );
	}

	add_action( 'admin_init', 'register_per_product_shipping_importer' );

	/**
	 * Localisation
	 */
	load_plugin_textdomain( 'wc_shipping_per_product', false, dirname( plugin_basename( __FILE__ ) ) . '/languages/' );

	/**
	 * woocommerce_per_product_shipping_init function.
	 *
	 * @access public
	 * @return void
	 */
	function woocommerce_per_product_shipping_init() {

		if ( ! class_exists( 'WC_Shipping_Per_Product' ) ) :

		/**
		 * WC_Shipping_Per_Product class.
		 *
		 * @extends WC_Shipping_Method
		 */
		class WC_Shipping_Per_Product extends WC_Shipping_Method {

			function __construct() {
				$this->id 					= 'per_product';
				$this->method_title 		= __( 'Per-product', 'wc_shipping_per_product' );
				$this->method_description 	= __( 'Per product shipping allows you to define different shipping costs for products, based on customer location. These costs can be added to other shipping methods, or used as a standalone shipping method.', 'wc_shipping_per_product' );

				// Load the form fields.
				$this->init_form_fields();

				// Load the settings.
				$this->init_settings();

				// Define user set variables
		        $this->enabled			= $this->settings['enabled'];
				$this->title 			= $this->settings['title'];
				$this->availability 	= $this->settings['availability'];
				$this->countries 		= $this->settings['countries'];
				$this->tax_status		= $this->settings['tax_status'];
				$this->cost 		  	= $this->settings['cost'];
				$this->fee 			  	= $this->settings['fee'];
				$this->order_fee		= $this->settings['order_fee'];

				// Actions
				add_action( 'woocommerce_update_options_shipping_' . $this->id, array( &$this, 'process_admin_options' ) );
		    }

			/**
		     * Initialise Gateway Settings Form Fields
		     *
		     * @access public
		     * @return void
		     */
		    function init_form_fields() {
		    	global $woocommerce;

		    	$this->form_fields = array(
		    		'enabled' => array(
							'title' 		=> __( 'Standalone Method', 'wc_shipping_per_product' ),
							'type' 			=> 'checkbox',
							'label' 		=> __( 'Enable per-product shipping as a standalone shipping method', 'wc_shipping_per_product' ),
							'default' 		=> 'yes'
						),
					'enabled' => array(
							'title' 		=> __( 'Standalone Method', 'wc_shipping_per_product' ),
							'type' 			=> 'checkbox',
							'label' 		=> __( 'Enable per-product shipping as a standalone shipping method', 'wc_shipping_per_product' ),
							'default' 		=> 'yes'
						),
					'title' => array(
							'title' 		=> __( 'Method Title', 'wc_shipping_per_product' ),
							'type' 			=> 'text',
							'description' 	=> __( 'This controls the title which the user sees during checkout.', 'wc_shipping_per_product' ),
							'default'		=> __( 'Product Shipping', 'wc_shipping_per_product' )
						),
					'tax_status' => array(
							'title' 		=> __( 'Tax Status', 'wc_shipping_per_product' ),
							'type' 			=> 'select',
							'description' 	=> '',
							'default' 		=> 'taxable',
							'options'		=> array(
								'taxable' 	=> __( 'Taxable', 'wc_shipping_per_product' ),
								'none' 		=> __( 'None', 'wc_shipping_per_product' ),
							),
						),
					'cost' => array(
							'title' 		=> __( 'Default product cost (per product)', 'woocommerce' ),
							'type' 			=> 'text',
							'description'	=> __( 'Cost excluding tax for products without defined costs. Enter an amount, e.g. 2.50.', 'woocommerce' ),
							'default' 		=> '',
						),
					'fee' => array(
							'title' 		=> __( 'Handling Fee (per product)', 'woocommerce' ),
							'type' 			=> 'text',
							'description'	=> __( 'Fee excluding tax. Enter an amount, e.g. 2.50, or a percentage, e.g. 5%. Leave blank to disable.', 'woocommerce' ),
							'default'		=> '',
						),
					'order_fee' => array(
							'title' 		=> __( 'Handling Fee (per order)', 'woocommerce' ),
							'type' 			=> 'text',
							'description'	=> __( 'Fee excluding tax. Enter an amount, e.g. 2.50, or a percentage, e.g. 5%. Leave blank to disable.', 'woocommerce' ),
							'default'		=> '',
						),
					'availability' => array(
							'title' 		=> __( 'Method availability', 'wc_shipping_per_product' ),
							'type' 			=> 'select',
							'default' 		=> 'all',
							'class'			=> 'availability',
							'options'		=> array(
								'all' 		=> __('All allowed countries', 'wc_shipping_per_product'),
								'specific' 	=> __('Specific Countries', 'wc_shipping_per_product')
							)
						),
					'countries' => array(
							'title' 		=> __( 'Specific Countries', 'wc_shipping_per_product' ),
							'type' 			=> 'multiselect',
							'class'			=> 'chosen_select',
							'css'			=> 'width: 450px;',
							'default' 		=> '',
							'options'		=> $woocommerce->countries->get_allowed_countries()
						)
					);
		    }

		    /**
		     * Calculate shipping when this method is used standalone.
		     *
		     * @access public
		     * @return void
		     */
		    function calculate_shipping( $package=array() ) {
		    	global $woocommerce;

		    	$_tax 	= new WC_Tax();
				$taxes 	= array();
		    	$shipping_cost 	= 0;

		    	// This shipping method loops through products, adding up the cost
		    	if ( sizeof( $package['contents'] ) > 0 ) {
    				foreach ( $package['contents'] as $item_id => $values ) {
						if ( $values['quantity'] > 0 ) {
							if ( $values['data']->needs_shipping() ) {

								$rule = false;
								$item_shipping_cost = 0;

								if ( $values['variation_id'] )
									$rule = woocommerce_per_product_shipping_get_matching_rule( $values['variation_id'], $package );

								if ( $rule === false )
									$rule = woocommerce_per_product_shipping_get_matching_rule( $values['product_id'], $package );

								if ( $rule ) {
									$item_shipping_cost += $rule->rule_item_cost * $values['quantity'];
									$item_shipping_cost += $rule->rule_cost;
								} elseif ( $this->cost === '0' || $this->cost > 0 ) {
									// Use default
									$item_shipping_cost += $this->cost * $values['quantity'];
								} else {
									// NO default and nothing found - abort
									return;
								}

								// Fee
								$item_shipping_cost += $this->get_fee( $this->fee, $item_shipping_cost ) * $values['quantity'];

								$shipping_cost += $item_shipping_cost;

								if ( $this->tax_status == 'taxable' ) {

									$rates		= $_tax->get_shipping_tax_rates( $values['data']->get_tax_class() );
									$item_taxes = $_tax->calc_shipping_tax( $item_shipping_cost, $rates );

									// Sum the item taxes
									foreach ( array_keys( $taxes + $item_taxes ) as $key ) {
										$taxes[ $key ] = ( isset( $item_taxes[ $key ] ) ? $item_taxes[ $key ] : 0 ) + ( isset( $taxes[ $key ] ) ? $taxes[ $key ] : 0);
									}
								}

							}
						}
					}
				}

				// Add order shipping cost + tax
				if ( $this->order_fee ) {

					$order_fee = $this->get_fee( $this->order_fee, $shipping_cost );

					$shipping_cost += $order_fee;

					if ( get_option('woocommerce_calc_taxes') == 'yes' && $this->tax_status == 'taxable' ) {

						$rates 		= $_tax->get_shipping_tax_rates();
						$item_taxes = $_tax->calc_shipping_tax( $order_fee, $rates );

						// Sum the item taxes
						foreach ( array_keys( $taxes + $item_taxes ) as $key ) {
							$taxes[ $key ] = ( isset( $item_taxes[ $key ] ) ? $item_taxes[ $key ] : 0 ) + ( isset( $taxes[ $key ] ) ? $taxes[ $key ] : 0);
						}
					}
				}

				// Add rate
				$this->add_rate(array(
					'id' 	=> $this->id,
					'label' => $this->title,
					'cost' 	=> $shipping_cost,
					'taxes' => $taxes // We calc tax in the method
				));
		    }
		}

		endif;
	}

	add_action('woocommerce_shipping_init', 'woocommerce_per_product_shipping_init');


	/**
	 * woocommerce_per_product_shipping_get_matching_rule function.
	 *
	 * @access public
	 * @param mixed $product_id
	 * @param mixed $package
	 * @return false|null
	 */
	function woocommerce_per_product_shipping_get_matching_rule( $product_id, $package, $standalone = true ) {
    	global $wpdb;

    	$product_id = apply_filters( 'woocommerce_per_product_shipping_get_matching_rule_product_id', $product_id );

    	if ( get_post_meta( $product_id, '_per_product_shipping', true ) !== 'yes' )
    		return false;

    	if ( ! $standalone && get_post_meta( $product_id, '_per_product_shipping_add_to_all', true ) !== 'yes' )
    		return null; // No rates, don't fallback to parent product if variable

    	$country 	= $package['destination']['country'];
		$state 		= $package['destination']['state'];
		$postcode 	= $package['destination']['postcode'];

		// Define valid postcodes
		$valid_postcodes 	= array( '', $postcode );

		// Work out possible valid wildcard postcodes
		$postcode_length	= strlen( $postcode );
		$wildcard_postcode	= $postcode;

		for ( $i = 0; $i < $postcode_length; $i ++ ) {
			$wildcard_postcode = substr( $wildcard_postcode, 0, -1 );
			$valid_postcodes[] = $wildcard_postcode . '*';
		}

		// Rules array
		$rules = array();

		// Get rules matching product, country and state
	    $matching_rule = $wpdb->get_row(
	    	$wpdb->prepare(
	    		"
	    		SELECT * FROM {$wpdb->prefix}woocommerce_per_product_shipping_rules
	    		WHERE product_id = %d
	    		AND rule_country IN ( '', %s )
	    		AND rule_state IN ( '', %s )
	    		AND rule_postcode IN ( '" . implode( "','", $valid_postcodes ) . "' )
	    		ORDER BY rule_order
	    		LIMIT 1
	    		" , $product_id, strtoupper( $country ), strtoupper( $state )
	    	)
	    );

	    return $matching_rule;
    }

	/**
	 * woocommerce_per_product_shipping function.
	 *
	 * @access public
	 * @param mixed $methods
	 * @return void
	 */
	function woocommerce_per_product_shipping( $methods ) {
		$methods[] = 'WC_Shipping_Per_Product';
		return $methods;
	}

	add_filter('woocommerce_shipping_methods', 'woocommerce_per_product_shipping' );

	/**
	 * woocommerce_per_product_shipping_addition function.
	 *
	 * @access public
	 * @return void
	 */
	function woocommerce_per_product_shipping_addition( $rates, $package ) {
    	global $woocommerce;

    	$_tax = new WC_Tax();

    	if ( $rates ) {
	    	foreach ( $rates as $rate_id => $rate ) {

	    		// Skip free shipping
	    		if ( $rate->cost == 0 && apply_filters( 'woocommerce_per_product_shipping_skip_free_method_' . $rate->method_id, true ) ) {
	    			continue;
	    		}

	    		// Skip self
	    		if ( $rate->method_id == 'per_product' ) {
	    			continue;
	    		}

		    	if ( sizeof( $package['contents'] ) > 0 ) {
    				foreach ( $package['contents'] as $item_id => $values ) {
						if ( $values['quantity'] > 0 ) {
							if ( $values['data']->needs_shipping() ) {

								$item_shipping_cost = 0;

								$rule = false;

								if ( $values['variation_id'] )
									$rule = woocommerce_per_product_shipping_get_matching_rule( $values['variation_id'], $package, false );

								if ( $rule === false )
									$rule = woocommerce_per_product_shipping_get_matching_rule( $values['product_id'], $package, false );

								if ( empty( $rule ) )
									continue;

								$item_shipping_cost += $rule->rule_item_cost * $values['quantity'];
								$item_shipping_cost += $rule->rule_cost;

								$rate->cost += $item_shipping_cost;

								if ( $woocommerce->shipping->shipping_methods[ $rate->method_id ]->tax_status == 'taxable' ) {

									$tax_rates	= $_tax->get_shipping_tax_rates( $values['data']->get_tax_class() );
									$item_taxes = $_tax->calc_shipping_tax( $item_shipping_cost, $tax_rates );

									// Sum the item taxes
									foreach ( array_keys( $rate->taxes + $item_taxes ) as $key ) {
										$rate->taxes[ $key ] = ( isset( $item_taxes[ $key ] ) ? $item_taxes[ $key ] : 0 ) + ( isset( $rate->taxes[ $key ] ) ? $rate->taxes[ $key ] : 0 );
									}
								}
							}
						}
					}
				}
	    	}
    	}

    	return $rates;
	}

	add_filter( 'woocommerce_package_rates', 'woocommerce_per_product_shipping_addition', 10, 2 );
}