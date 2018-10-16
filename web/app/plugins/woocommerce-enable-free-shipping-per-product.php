<?php
/**
 * Plugin Name: WooCommerce Enable Free Shipping on a Per Product Basis
 * Plugin URI: https://gist.github.com/BFTrick/d4a21524a8f7b25ec296
 * Description: Enable free shipping for certain products
 * Author: Patrick Rauland & eugenf
 * Author URI: http://speakinginbytes.com/
 * Version: 1.0.2
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program. If not, see <http://www.gnu.org/licenses/>.
 *
 */


if ( ! class_exists( 'WC_Enable_Free_Shipping' ) ) :

class WC_Enable_Free_Shipping {

	protected static $instance = null;

	/**
	 * Initialize the plugin.
	 *
	 * @since 1.0
	 */
	private function __construct() {
		// add our check
		add_filter( 'woocommerce_shipping_free_shipping_is_available', array( $this, 'patricks_enable_free_shipping' ), 20 );
	}


	/**
	 * Enable free shipping for orders with products that have the free-shipping shipping class slug
	 *
	 * @param  bool $is_available
	 * @return bool
	 * @since  1.0
	 */
	public function patricks_enable_free_shipping( $is_available ) {

		global $woocommerce;

		// set the shipping classes that are eligible
		$eligible = array( 'free-shipping' );

		// get cart contents
		$cart_items = $woocommerce->cart->get_cart();

		// loop through the items checking to make sure they all have the right class
		foreach ( $cart_items as $key => $item ) {
			if ( ! in_array( $item['data']->get_shipping_class(), $eligible ) ) {
				// this item doesn't have the right class. return default availability
				return $is_available;
			}
		}

		// nothing out of the ordinary return true
		return true;
	}


	/**
	 * Return an instance of this class.
	 *
	 * @return object A single instance of this class.
	 * @since  1.0
	 */
	public static function get_instance() {
		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}


}

add_action( 'init', array( 'WC_Enable_Free_Shipping', 'get_instance' ), 0 );

function show_free_shipping() {

    global $product;

    if ( !is_product() ) {
        return;
    }

    if ( $product->get_shipping_class() == 'free-shipping' ) {
        echo '<span class="product-free-shipping-label">Free Shipping</span>';
    }
}

add_action('woocommerce_single_product_summary', 'show_free_shipping', 15);

endif;

