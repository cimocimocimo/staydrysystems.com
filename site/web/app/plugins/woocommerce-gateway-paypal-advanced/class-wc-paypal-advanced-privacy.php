<?php
if ( ! class_exists( 'WC_Abstract_Privacy' ) ) {
	return;
}

class WC_Paypal_Advanced_Privacy extends WC_Abstract_Privacy {
	/**
	 * Constructor
	 *
	 */
	public function __construct() {
		parent::__construct( __( 'Paypal Advanced', 'wc_paypaladv' ) );

		$this->add_exporter( 'woocommerce-gateway-paypal-advanced-order-data', __( 'WooCommerce PayPal Advanced Order Data', 'wc_paypaladv' ), array( $this, 'order_data_exporter' ) );
		$this->add_eraser( 'woocommerce-gateway-paypal-advanced-data', __( 'WooCommerce PayPal Advanced Data', 'wc_paypaladv' ), array( $this, 'order_data_eraser' ) );
	}

	/**
	 * Returns a list of orders that are using one of PayPal Advanced's payment methods.
	 *
	 * @param string  $email_address
	 * @param int     $page
	 *
	 * @return array WP_Post
	 */
	protected function get_orders( $email_address, $page ) {
		$user = get_user_by( 'email', $email_address ); // Check if user has an ID in the DB to load stored personal data.

		$order_query    = array(
			'payment_method' => array( 'paypal_advanced' ),
			'limit'          => 10,
			'page'           => $page,
		);

		if ( $user instanceof WP_User ) {
			$order_query['customer_id'] = (int) $user->ID;
		} else {
			$order_query['billing_email'] = $email_address;
		}

		return wc_get_orders( $order_query );
	}

	/**
	 * Gets the message of the privacy to display.
	 *
	 */
	public function get_privacy_message() {
		return wpautop( sprintf( __( 'By using this extension, you may be storing personal data or sharing data with an external service. <a href="%s" target="_blank">Learn more about how this works, including what you may want to include in your privacy policy.</a>', 'wc_paypaladv' ), 'https://docs.woocommerce.com/document/privacy-payments/#woocommerce-gateway-paypal-advanced' ) );
	}

	/**
	 * Handle exporting data for Orders.
	 *
	 * @param string $email_address E-mail address to export.
	 * @param int    $page          Pagination of data.
	 *
	 * @return array
	 */
	public function order_data_exporter( $email_address, $page = 1 ) {
		$done           = false;
		$data_to_export = array();

		$orders = $this->get_orders( $email_address, (int) $page );

		$done = true;

		if ( 0 < count( $orders ) ) {
			foreach ( $orders as $order ) {
				$data_to_export[] = array(
					'group_id'    => 'woocommerce_orders',
					'group_label' => __( 'Orders', 'wc_paypaladv' ),
					'item_id'     => 'order-' . $order->get_id(),
					'data'        => array(
						array(
							'name'  => __( 'PayPal Advanced secure token id', 'wc_paypaladv' ),
							'value' => get_post_meta( $order->get_id(), '_secure_token_id', true ),
						),
						array(
							'name'  => __( 'PayPal Advanced secure token', 'wc_paypaladv' ),
							'value' => get_post_meta( $order->get_id(), '_secure_token', true ),
						),
					),
				);
			}

			$done = 10 > count( $orders );
		}

		return array(
			'data' => $data_to_export,
			'done' => $done,
		);
	}

	/**
	 * Finds and erases order data by email address.
	 *
	 * @param string $email_address The user email address.
	 * @param int    $page  Page.
	 * @return array An array of personal data in name value pairs
	 */
	public function order_data_eraser( $email_address, $page ) {
		$orders = $this->get_orders( $email_address, (int) $page );

		$items_removed  = false;
		$items_retained = false;
		$messages       = array();

		foreach ( (array) $orders as $order ) {
			$order = wc_get_order( $order->get_id() );

			list( $removed, $retained, $msgs ) = $this->maybe_handle_order( $order );
			$items_removed  |= $removed;
			$items_retained |= $retained;
			$messages        = array_merge( $messages, $msgs );
		}

		// Tell core if we have more orders to work on still
		$done = count( $orders ) < 10;

		return array(
			'items_removed'  => $items_removed,
			'items_retained' => $items_retained,
			'messages'       => $messages,
			'done'           => $done,
		);
	}

	/**
	 * Handle eraser of data tied to Orders
	 *
	 * @param WC_Order $order
	 * @return array
	 */
	protected function maybe_handle_order( $order ) {
		$order_id               = $order->get_id();
		$paypal_secure_token_id = get_post_meta( $order_id, '_secure_token_id', true );
		$paypal_secure_token    = get_post_meta( $order_id, '_secure_token', true );

		if ( empty( $paypal_secure_token_id ) && empty( $paypal_secure_token ) ) {
			return array( false, false, array() );
		}

		delete_post_meta( $order_id, '_secure_token_id' );
		delete_post_meta( $order_id, '_secure_token' );

		return array( true, false, array( __( 'PayPal Advanced personal data erased.', 'wc_paypaladv' ) ) );
	}
}

new WC_Paypal_Advanced_Privacy();
