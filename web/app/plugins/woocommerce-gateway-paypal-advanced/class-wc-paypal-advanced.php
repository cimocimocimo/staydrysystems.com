<?php
/**
 * Paypal Advanced Payments Gateway Class.
 *
 * @package WC_PayPal_Advanced
 */

/**
 * Payment method class.
 */
class WC_Paypal_Advanced extends WC_Payment_Gateway {

	/**
	 * Instance of WC_Logger.
	 *
	 * @since 1.24.1
	 * @version 1.24.1
	 *
	 * @var WC_Logger
	 */
	private $logger;

	/**
	 * Constructor.
	 */
	public function __construct() {
		// Necessary Properties.
		$this->id                 = 'paypal_advanced';
		$this->icon               = apply_filters( 'woocommerce_paypal_advanced_icon', '' );
		$this->has_fields         = true;
		$this->home_url           = is_ssl() ? home_url( '/','https' ): home_url( '/' ); // Set the urls (cancel or return) based on SSL.
		$this->testurl            = 'https://pilot-payflowpro.paypal.com';
		$this->liveurl            = 'https://payflowpro.paypal.com';
		$this->relay_response_url = add_query_arg( 'wc-api', 'WC_Paypal_Advanced', $this->home_url );
		$this->method_title       = __( 'PayPal Advanced', 'wc_paypaladv' );
		$this->secure_token_id    = '';
		$this->securetoken        = '';
		$this->supports           = array(
			'products',
			'refunds',
		);

		// Load the form fields.
		$this->init_form_fields();

		// Load the settings.
		$this->init_settings();

		// Define user set variables.
		$this->title                   = $this->settings['title'];
		$this->description             = $this->settings['description'];
		$this->testmode                = $this->settings['testmode'];
		$this->loginid                 = $this->settings['loginid'];
		$this->resellerid              = $this->settings['resellerid'];
		$this->transtype               = $this->settings['transtype'];
		$this->password                = $this->settings['password'];
		$this->debug                   = $this->settings['debug'];
		$this->invoice_prefix          = $this->settings['invoice_prefix'];
		$this->page_collapse_bgcolor   = $this->settings['page_collapse_bgcolor'];
		$this->page_collapse_textcolor = $this->settings['page_collapse_textcolor'];
		$this->page_button_bgcolor     = $this->settings['page_button_bgcolor'];
		$this->page_button_textcolor   = $this->settings['page_button_textcolor'];
		$this->label_textcolor         = $this->settings['label_textcolor'];

		// Determine mobilemode.
		if ( ! isset( $this->settings['mobilemode'] ) ) {
			$this->mobilemode = 'yes';
		} else {
			$this->mobilemode = $this->settings['mobilemode'];
		}

		// Determine the layout..
		switch ( $this->settings['layout'] ) {
			case 'A':
				$this->layout = 'TEMPLATEA';
				break;
			case 'B':
				$this->layout = 'TEMPLATEB';
				break;
			case 'C':
				$this->layout = 'MINLAYOUT';
				break;
		}

		// Determine the user and host address.
		$this->user     = '' == $this->settings['user'] ? $this->settings['loginid'] : $this->settings['user'];
		$this->hostaddr = 'yes' === $this->testmode  ? $this->testurl : $this->liveurl;

		// Hooks.
		add_action( 'admin_notices', array( $this, 'checks' ) ); // Checks for availability of the plugin.
		add_action( 'woocommerce_update_options_payment_gateways_' . $this->id, array( $this, 'process_admin_options' ) );
		add_action( 'woocommerce_receipt_paypal_advanced', array( $this, 'receipt_page' ) ); // Payment form hook.
		add_action( 'woocommerce_api_wc_paypal_advanced', array( $this, 'relay_response' ) ); // Payment listener/API hook.

		// Set enable property if the Paypal Adavnced supported for the user country.
		if ( ! $this->is_available() ) {
			$this->enabled = false;
		}
	}

	/**
	 * Check if required fields for configuring the gateway are filled up by the
	 * administrator.
	 *
	 * @return void
	 **/
	public function checks() {
		if ( 'no' === $this->enabled ) {
			return;
		}

		// Check required fields.
		if ( ! $this->loginid ) {

			echo '<div class="error"><p>' . sprintf( __( 'PayPal Advanced error: Please enter your PayPal Advanced Account Merchant Login <a href="%s">here</a>', 'wc_paypaladv' ), admin_url( 'admin.php?page=wc-settings&tab=checkout&section=' . strtolower( 'WC_Paypal_Advanced' ) ) ) . '</p></div>';

		} elseif ( ! $this->resellerid ) {

			echo '<div class="error"><p>' . sprintf( __( 'PayPal Advanced error: Please enter your PayPal Advanced Account Partner <a href="%s">here</a>', 'wc_paypaladv' ), admin_url( 'admin.php?page=wc-settings&tab=checkout&section=' . strtolower( 'WC_Paypal_Advanced' ) ) ) . '</p></div>';

		} elseif ( ! $this->password ) {

			echo '<div class="error"><p>' . sprintf( __( 'PayPal Advanced error: Please enter your PayPal Advanced Account Password <a href="%s">here</a>', 'wc_paypaladv' ), admin_url( 'admin.php?page=wc-settings&tab=checkout&section=' . strtolower( 'WC_Paypal_Advanced' ) ) ) . '</p></div>';
		}

		return;
	}

	/**
	 * Redirects to the url based on layout type.
	 *
	 * @param string $redirect_url Redirect URL.
	 */
	public function redirect_to( $redirect_url ) {
		// Clean.
		@ob_clean();

		// Header.
		header( 'HTTP/1.1 200 OK' );

		// Redirect to the url based on layout type.
		if ( 'MINLAYOUT' !== $this->layout ) {
			wp_redirect( $redirect_url );
		} else {
			echo "<script>window.parent.location.href='" . $redirect_url . "';</script>";
		}
		exit;
	}

	/**
	 * Performs inquiry transaction.
	 *
	 * @throws Exception Failed request or unexpected response.
	 *
	 * @param WC_Order $order    Order object.
	 * @param int      $order_id Order ID.
	 *
	 * @return result code of the inquiry transaction
	 */
	private function inquiry_transaction( $order, $order_id ) {
		// inquire transaction, whether it is really paid or not.
		$paypal_args = array(
			'USER'                                   => $this->user,
			'VENDOR'                                 => $this->loginid,
			'PARTNER'                                => $this->resellerid,
			'PWD[' . strlen( $this->password ) . ']' => $this->password,
			'ORIGID'                                 => $_POST['PNREF'],
			'TENDER'                                 => 'C',
			'TRXTYPE'                                => 'I',
			'BUTTONSOURCE'                           => 'WooThemes_Cart',
		);

		$postData = ''; // Stores the post data string.
		foreach ( $paypal_args as $key => $val ) {
			$postData .= '&' . $key . '=' . $val;
		}

		$postData = trim( $postData, '&' );

		// Using Curl post necessary information to the Paypal Site to generate
		// the secured token.
		$response = wp_remote_post(
			$this->hostaddr,
			array(
				'method'      => 'POST',
				'body'        => $postData,
				'timeout'     => 70,
				'user-agent'  => 'Woocommerce ' . WC_VERSION,
				'httpversion' => '1.1',
				'headers'     => array(
					'host' => 'www.paypal.com',
				),
			)
		);

		if ( is_wp_error( $response ) ) {
			throw new Exception( __( 'There was a problem connecting to the payment gateway.', 'wc_paypaladv' ) );
		}

		if ( empty( $response['body'] ) ) {
			throw new Exception( __( 'Empty response.', 'wc_paypaladv' ) );
		}

		// Parse and assign to array.
		$inquiry_result_arr = array(); // Stores the response in array format.
		parse_str( $response['body'], $inquiry_result_arr );

		if ( 0 == $inquiry_result_arr['RESULT'] && 'Approved' === $inquiry_result_arr['RESPMSG'] ) {
			/* translators: order number */
			$order->add_order_note( sprintf( __( 'Received successful result of Inquiry Transaction for order #%s.', 'wc_paypaladv' ), $order->get_order_number() ) );

			return 'Approved';
		}

		/* translators: 1) order number 2) error message */
		$order->add_order_note( sprintf( __( 'Received result of Inquiry Transaction for order #%1$s with error: %2$s', 'wc_paypaladv' ), $order->get_order_number(), $inquiry_result_arr['RESPMSG'] ) );
		return 'Error';
	}

	/**
	 * Handles the successful transaction.
	 *
	 * @param WC_Order $order       Order object.
	 * @param int      $order_id    Order ID.
	 * @param bool     $silent_post Silent post.
	 */
	private function success_handler( $order, $order_id, $silent_post ) {
		// Match the secure tokens to ensure it is same transaction.
		if ( get_post_meta( $order_id, '_secure_token', true ) == $_REQUEST['SECURETOKEN'] ) {
			$this->log( __( 'Relay Response Tokens Match', 'wc_paypaladv' ) );
		} else {
			// Redirect to homepage, if any invalid request or hack.
			$this->log( __( 'Relay Response Tokens Mismatch', 'wc_paypaladv' ) );

			// Redirect to the checkout page, if not silent post.
			if ( ! $silent_post ) {
				$this->redirect_to( $order->get_checkout_payment_url( true ) );
			}

			exit;
		}

		$order->add_order_note(
			sprintf(
				/* translators: 1) order number 2) transaction ID from PayPal */
				__( 'PayPal Advanced payment completed for order #%1$s. Transaction number/ID: %2$s.', 'wc_paypaladv' ),
				$order->get_order_number(),
				$_POST['PNREF']
			)
		);

		$inq_result = $this->inquiry_transaction( $order, $order_id );

		// Handle response.
		if ( 'Approved' === $inq_result ) {
			$order->payment_complete( $_POST['PNREF'] );

			WC()->cart->empty_cart();

			/* translators: order number */
			$order->add_order_note( sprintf( __( 'Payment completed for order #%s.', 'wc_paypaladv' ), $order->get_order_number() ) );

			$this->log( sprintf( __( 'Payment completed for order #%s.', 'wc_paypaladv' ), $order->get_order_number() ) );

			// Redirect to the thanks page, if not silent post.
			if ( ! $silent_post ) {
				$this->redirect_to( $this->get_return_url( $order ) );
			}
		}
	}

	/**
	 * Handles the error transaction.
	 *
	 * @param WC_Order $order       Order object.
	 * @param int      $order_id    Order ID.
	 * @param bool     $silent_post Silent Post.
	 */
	private function error_handler( $order, $order_id, $silent_post ) {
		// 12-0 messages.
		wc_clear_notices();

		// Add error.
		wc_add_notice( __( 'Error:', 'wc_paypaladv' ) . ' "' . urldecode( $_POST['RESPMSG'] ) . '"', 'error' );

		// Redirect to the checkout page, if not silent post.
		if ( false === $silent_post ) {
			$this->redirect_to( $order->get_checkout_payment_url( true ) );
		}
	}

	 /**
	  * Handles the cancel transaction.
	  *
	  * @param WC_Order $order    Order object.
	  * @param int      $order_id Order ID.
	  */
	private function cancel_handler( $order, $order_id ) {
		wp_redirect( $order->get_cancel_order_url() );
		exit;
	}

	/**
	 * Handles the decline transaction.
	 *
	 * @param WC_Order $order       Order object.
	 * @param int      $order_id    Order ID.
	 * @param bool     $silent_post Silent Post.
	 */
	private function decline_handler( $order, $order_id, $silent_post ) {
		$order->update_status( 'failed', __( 'Payment failed via PayPal Advanced because of.', 'wc_paypaladv' ) . '&nbsp;' . $_POST['RESPMSG'] );

		$this->log( sprintf( __( 'Status has been changed to failed for order %s', 'wc_paypaladv' ), $order->get_order_number() ) );

		/* translators: 1) order number 2) error message from PayPal 3) status from PayPal */
		$this->log( sprintf( __( 'Error Occurred while processing %1$s : %2$s, status: %3$s', 'wc_paypaladv' ), $order->get_order_number(), urldecode( $_POST['RESPMSG'] ), $_POST['RESULT'] ) );

		$this->error_handler( $order, $order_id, $silent_post );
	}

	/**
	 * Checks the payment transaction reponse based on that either completes
	 * the transaction or shows thows the exception and show sthe error.
	 */
	public function relay_response() {
		$silent_post = ( isset( $_REQUEST['silent'] ) && 'true' == $_REQUEST['silent'] );

		$this->log( $silent_post
			/* translators: dumped request data */
			? sprintf( __( 'Silent Relay Response Triggered: %s', 'wc_paypaladv' ), print_r( $_REQUEST, true ) )
			/* translators: dumped request data */
			: sprintf( __( 'Relay Response Triggered: %s', 'wc_paypaladv' ), print_r( $_REQUEST, true ) )
		);

		if ( ! isset( $_REQUEST['INVOICE'] ) ) {
			// If not silent post redirect it to home page otherwise just exit.
			if ( ! $silent_post ) {
				wp_redirect( home_url( '/' ) );
			}
			exit;
		}

		// Get Order ID.
		$order_id = $_REQUEST['USER1'];

		// Create order object.
		$order = new WC_Order( $order_id );

		// Check for the status of the order, if completed or processing,
		// redirect to thanks page. This case happens when silentpost is on.
		if ( $order->has_status( array( 'processing', 'completed' ) ) ) {
			$this->log( sprintf( __( 'Redirecting to Thank You Page for order %s', 'wc_paypaladv' ), $order->get_order_number() ) );

			// Redirect to the thanks page, if not silent post.
			if ( ! $silent_post ) {
				 $this->redirect_to( $this->get_return_url( $order ) );
			}
		}

		// Define RESULT, if not provided in case of cancel, define with -1.
		if ( isset( $_REQUEST['cancel_ec_trans'] ) && 'true' === $_REQUEST['cancel_ec_trans'] ) {
			 $_REQUEST['RESULT'] = -1;
		}

		// Handle the successful transaction.
		switch ( $_REQUEST['RESULT'] ) {
			case 0 :
				// Handle exceptional cases.
				if ( 'Approved' === $_REQUEST['RESPMSG'] ) {
					$this->success_handler( $order, $order_id, $silent_post );
				} elseif ( 'Declined' === $_REQUEST['RESPMSG'] ) {
					 $this->decline_handler( $order,$order_id,$silent_post );
				} else {
					 $this->error_handler( $order,$order_id,$silent_post );
				}
				break;
			case 12:
				$this->decline_handler( $order,$order_id,$silent_post );
				break;
			case -1:
				$this->cancel_handler( $order,$order_id );
				break;
			default:
				// Handles error order.
				$this->error_handler( $order, $order_id, $silent_post );
				break;
		}
	}


	/**
	 * Gets the secured token by passing all the required information to PayPal
	 * site.
	 *
	 * @param WC_Order $order Order object.
	 * @return string Secure token.
	 */
	function get_secure_token( $order ) {
		static $length_error = 0;

		$this->log( sprintf( __( 'Requesting for the Secured Token for the order %s', 'wc_paypaladv' ), $order->get_order_number() ) );

		// Generate unique id.
		$this->secure_token_id = uniqid( substr( $_SERVER['HTTP_HOST'], 0, 9 ), true );

		// Prepare paypal_ars array to pass to paypal to generate the secure token.
		$paypal_args = array();

		// Override the layout with mobile template, if browsed from mobile if the exitsing layout is C or MINLAYOUT.
		if ( ( 'MINLAYOUT' === $this->layout || 'C' === $this->layout ) && 'yes' === $this->mobilemode ) {
			$template = wp_is_mobile() ? 'MOBILE' : $this->layout;
		} else {
			$template = $this->layout;
		}

		$paypal_args = array(
			'VERBOSITY' => 'HIGH',
			'USER'      => $this->user,
			'VENDOR'    => $this->loginid,
			'PARTNER'   => $this->resellerid,

			'PWD[' . strlen( $this->password ) . ']' => $this->password,
			'SECURETOKENID'                          => $this->secure_token_id,
			'CREATESECURETOKEN'                      => 'Y',

			'TRXTYPE'    => $this->transtype,
			'CUSTREF'    => $order->get_order_number(),
			'USER1'      => $order->get_id(),
			'INVNUM'     => $this->invoice_prefix . ltrim( $order->get_order_number(), '#' ),
			'AMT'        => $order->get_total(),
			'FREIGHTAMT' => number_format( $order->get_total_shipping(), 2, '.', '' ),

			'COMPANYNAME[' . strlen( $order->get_billing_company() ) . ']' => $order->get_billing_company(),

			'CURRENCY' => get_woocommerce_currency(),
			'EMAIL'    => $order->get_billing_email(),

			'BILLTOFIRSTNAME[' . strlen( $order->get_billing_first_name() ) . ']' => $order->get_billing_first_name(),
			'BILLTOLASTNAME[' . strlen( $order->get_billing_last_name() ) . ']' => $order->get_billing_last_name(),
			'BILLTOSTREET[' . strlen( $order->get_billing_address_1() . ' ' . $order->get_billing_address_2() ) . ']' => $order->get_billing_address_1() . ' ' . $order->get_billing_address_2(),
			'BILLTOCITY[' . strlen( $order->get_billing_city() ) . ']' => $order->get_billing_city(),
			'BILLTOSTATE[' . strlen( $order->get_billing_state() ) . ']' => $order->get_billing_state(),
			'BILLTOZIP' => $order->get_billing_postcode(),
			'BILLTOCOUNTRY[' . strlen( $order->get_billing_country() ) . ']'  => $order->get_billing_country(),
			'BILLTOEMAIL' => $order->get_billing_email(),
			'BILLTOPHONENUM' => $order->get_billing_phone(),

			'SHIPTOFIRSTNAME[' . strlen( $order->get_shipping_first_name() ) . ']' => $order->get_shipping_first_name(),
			'SHIPTOLASTNAME[' . strlen( $order->get_shipping_last_name() ) . ']' => $order->get_shipping_last_name(),
			'SHIPTOSTREET[' . strlen( $order->get_shipping_address_1() . ' ' . $order->get_shipping_address_2() ) . ']' => $order->get_shipping_address_1() . ' ' . $order->get_shipping_address_2(),
			'SHIPTOCITY[' . strlen( $order->get_shipping_city() ) . ']'  => $order->get_shipping_city(),
			'SHIPTOZIP'   => $order->get_shipping_postcode(),
			'SHIPTOCOUNTRY[' . strlen( $order->get_shipping_country() ) . ']' => $order->get_shipping_country(),

			'BUTTONSOURCE' => 'WooThemes_Cart',

			'RETURNURL[' . strlen( $this->relay_response_url ) . ']' => $this->relay_response_url,

			'URLMETHOD'             => 'POST',
			'TEMPLATE'              => $template,
			'PAGECOLLAPSEBGCOLOR'   => ltrim( $this->page_collapse_bgcolor,'#' ),
			'PAGECOLLAPSETEXTCOLOR' => ltrim( $this->page_collapse_textcolor,'#' ),
			'PAGEBUTTONBGCOLOR'     => ltrim( $this->page_button_bgcolor,'#' ),
			'PAGEBUTTONTEXTCOLOR'   => ltrim( $this->page_button_textcolor, '#' ),
			'LABELTEXTCOLOR'        => ltrim( $this->settings['label_textcolor'], '#' ),
		);

		// Handle empty state exception e.g. Denmark.
		$shipping_state = $order->get_shipping_state();
		if ( empty( $shipping_state ) ) {
			// Replace with city.
			$paypal_args[ 'SHIPTOSTATE[' . strlen( $order->get_shipping_city() ) . ']' ]  = $order->get_shipping_city();
		} else {
			// Retain state.
			$paypal_args[ 'SHIPTOSTATE[' . strlen( $order->get_shipping_state() ) . ']' ]  = $order->get_shipping_state();
		}

		// Determine the ERRORURL,CANCELURL and SILENTPOSTURL.
		$cancelurl = add_query_arg( 'wc-api', 'WC_Paypal_Advanced', add_query_arg( 'cancel_ec_trans','true',$this->home_url ) );
		$paypal_args[ 'CANCELURL[' . strlen( $cancelurl ) . ']' ] = $cancelurl;

		$errorurl = add_query_arg( 'wc-api', 'WC_Paypal_Advanced', add_query_arg( 'error','true',$this->home_url ) );
		$paypal_args[ 'ERRORURL[' . strlen( $errorurl ) . ']' ] = $errorurl;

		$silentposturl = add_query_arg( 'wc-api', 'WC_Paypal_Advanced', add_query_arg( 'silent','true',$this->home_url ) );
		$paypal_args[ 'SILENTPOSTURL[' . strlen( $silentposturl ) . ']' ] = $silentposturl;

		// If prices include tax or have order discounts, send the whole order
		// as a single item.
		if ( 'yes' === $order->get_prices_include_tax() || $order->get_total_discount() > 0 || $length_error > 1 ) {

			// Don't pass items - paypal borks tax due to prices including tax.
			// PayPal has no option for tax inclusive pricing sadly. Pass 1 item
			// for the order items overall.
			$item_names = array();

			if ( sizeof( $order->get_items() ) > 0 ) {

					$paypal_args['FREIGHTAMT'] = number_format( $order->get_total_shipping() + $order->get_shipping_tax(), 2, '.', '' );

				if ( $length_error <= 1 ) {
					foreach ( $order->get_items() as $item ) {
						if ( $item['qty'] ) {
							$item_names[] = $item['name'] . ' x ' . $item['qty'];
						}
					}
				} else {
					$item_names[] = 'All selected items, refer to Woocommerce order details';
				}
				$items_str = sprintf( __( 'Order %s' , 'wc_paypaladv' ), $order->get_order_number() ) . ' - ' . implode( ', ', $item_names );
				$items_names_str = $this->paypal_advanced_item_name( $items_str );
				$items_desc_str  = $this->paypal_advanced_item_desc( $items_str );

				$paypal_args[ 'L_NAME0[' . strlen( $items_names_str ) . ']' ] = $items_names_str;
				$paypal_args[ 'L_DESC0[' . strlen( $items_desc_str ) . ']' ]  = $items_desc_str;

				$paypal_args['L_QTY0']  = 1;
				$paypal_args['L_COST0'] = number_format( $order->get_total() - round( $order->get_total_shipping() + $order->get_shipping_tax(), 2 ), 2, '.', '' );

				// Determine ITEMAMT.
				$paypal_args['ITEMAMT'] = $paypal_args['L_COST0'] * $paypal_args['L_QTY0'];
			}
		} else {

			// Tax.
			$paypal_args['TAXAMT'] = $order->get_total_tax();

			// ITEM AMT, total amount.
			$paypal_args['ITEMAMT'] = 0;
			
			$ITEMAMT = 0;

			// Cart Contents.
			$item_loop = 0;
			if ( sizeof( $order->get_items() ) > 0 ) {
				foreach ( $order->get_items() as $item ) {
					if ( ! $item['qty'] ) {
						continue;
					}

					$product = $order->get_product_from_item( $item );

					$item_name  = $item['name'];

					// Create order meta object and get the meta data as string.
					if ( version_compare( WC_VERSION, '3.0', '>=' ) ) {
						foreach ( $item->get_formatted_meta_data() as $meta ) {
							$item_name .= ' (' . $meta->display_key . ':' . $meta->value . ')';
						}
					} else {
						$item_meta = new WC_order_item_meta( $item['item_meta'] );
						if ( 0 == $length_error && $meta = $item_meta->display( true, true ) ) {
							$item_name .= ' (' . $meta . ')';
						}
					}

					$item_name = $this->paypal_advanced_item_name( $item_name );
					$paypal_args[ 'L_NAME' . $item_loop . '[' . strlen( $item_name ) . ']' ] = $item_name;
					if ( $product->get_sku() ) {
						$paypal_args[ 'L_SKU' . $item_loop ] = $product->get_sku();
					}

					$paypal_args[ 'L_QTY' . $item_loop ] = $item['qty'];
					$paypal_args[ 'L_COST' . $item_loop ] = $order->get_item_subtotal( $item, false );

					// Calculate ITEMAMT.
					$paypal_args['ITEMAMT'] += $order->get_line_total( $item, false, false ); // No tax, No Round.
					$ITEMAMT += $order->get_line_total( $item, false, false );
					$item_loop++;
				}
			}
			
			$ITEMAMT += $order->get_total_tax() + $order->get_total_shipping();
			$ITEMAMT = round( $ITEMAMT, 2 );

			// Fix rounding
			$diff = abs( ( $order->get_total() * 100 ) - ( $ITEMAMT * 100 ) );
			if ( abs( $diff ) > 0.000001 ) {
				$amt_diff = ( absint( $order->get_total() * 100 ) - absint( $ITEMAMT * 100 ) ) / 100;
				$paypal_args['ITEMAMT'] += $amt_diff;
			}
		}

		$paypal_args = apply_filters( 'woocommerce_paypal_advanced_args', $paypal_args );

		// Handle exceptions using try/catch blocks for the request to get secure tocken from paypal.
		try {

			/* prepare post data to post to the paypal site */
			$postData = '';
			$logData = '';

			foreach ( $paypal_args as $key => $val ) {
				$postData .= '&' . $key . '=' . $val;
				if ( strpos( $key,'PWD' ) === 0 ) {
					$logData .= '&PWD=XXXX';
				} else {
					$logData .= '&' . $key . '=' . $val;
				}
			}
			$postData = trim( $postData, '&' );
			$logData  = trim( $logData, '&' );

			/* translators: 1) order number 2) URL */
			$this->log( sprintf( __( 'Requesting for the Secured Token for the order %1$s with following URL and Paramaters: %2$s', 'wc_paypaladv' ), $order->get_order_number(), $this->hostaddr . '?' . $logData ) );

			// Using Curl post necessary information to the Paypal Site to generate
			// the secured token.
			$response = wp_remote_post(
				$this->hostaddr,
				array(
					'method'      => 'POST',
					'body'        => $postData,
					'timeout'     => 70,
					'user-agent'  => 'WooCommerce ' . WC_VERSION,
					'httpversion' => '1.1',
					'headers'     => array(
						'host' => 'www.paypal.com',
					),
				)
			);

			// If error occurs, throw exception with the error message.
			if ( is_wp_error( $response ) ) {
				throw new Exception( $response->get_error_message() );
			}
			if ( empty( $response['body'] ) ) {
				throw new Exception( __( 'Empty response.', 'wc_paypaladv' ) );
			}

			// Parse and assign to array.
			parse_str( $response['body'], $arr );

			// Handle response.
			if ( $arr['RESULT'] > 0 ) {
				throw new Exception(
					sprintf(
						__( 'There was an error processing your order - %s', 'wc_paypaladv' ),
						$arr['RESPMSG']
					)
				);
			} else {
				// Return the secure token.
				return $arr['SECURETOKEN'];
			}
		} catch ( Exception $e ) {
			/* translators: 1) order number 2) error message */
			$this->log( sprintf( __( 'Secured Token generation failed for the order %1$s with error: %2$s', 'wc_paypaladv' ), $order->get_order_number(), $e->getMessage() ) );

			if ( 7 != $arr['RESULT'] ) {
				wc_add_notice( __( 'Error:', 'wc_paypaladv' ) . ' "' . $e->getMessage() . '"', 'error' );
				$length_error = 0;
				return;
			} else {
				/* translators: 1) order number 2) error message */
				$this->log( sprintf( __( 'Secured Token generation failed for the order %1$s with error: %2$s', 'wc_paypaladv' ), $order->get_order_number(), $e->getMessage() ) );

				$length_error++;
				return $this->get_secure_token( $order );
			}
		}
	}

	/**
	 * Check if this gateway is enabled and available in the user's country.
	 *
	 * @return boolean True if available.
	 */
	public function is_available() {
		return 'yes' === $this->enabled;
	}

	/**
	 * Admin panel options.
	 */
	public function admin_options() {
		?>
		<h3><?php _e( 'PayPal Advanced', 'wc_paypaladv' ); ?></h3>
		<p><?php _e( 'PayPal Payments Advanced uses an iframe to seamlessly integrate PayPal hosted pages into the checkout process.', 'wc_paypaladv' ); ?></p>
		<table class="form-table">
		<?php

		if ( ! in_array( get_woocommerce_currency(), array( 'USD', 'CAD' ) ) ) {
			?>
			<div class="inline error"><p><strong><?php _e( 'Gateway Disabled', 'wc_paypaladv' ); ?></strong>: <?php _e( 'PayPal does not support your store currency.', 'wc_paypaladv' ); ?></p></div>
			<?php
		} else {
			// Generate the HTML For the settings form.
			$this->generate_settings_html();
		}
		?>
		</table><!--/.form-table-->
		<?php
	}

	/**
	 * Initialise Gateway Settings Form Fields.
	 */
	public function init_form_fields() {
		$this->form_fields = array(
			'enabled' => array(
				'title'   => __( 'Enable/Disable', 'wc_paypaladv' ),
				'type'    => 'checkbox',
				'label'   => __( 'Enable PayPal Advanced', 'wc_paypaladv' ),
				'default' => 'yes',
			),
			'title' => array(
				'title'       => __( 'Title', 'wc_paypaladv' ),
				'type'        => 'text',
				'description' => __( 'This controls the title which the user sees during checkout.', 'wc_paypaladv' ),
				'default'     => __( 'PayPal Advanced', 'wc_paypaladv' ),
			),
			'description' => array(
				'title'       => __( 'Description', 'wc_paypaladv' ),
				'type'        => 'text',
				'description' => __( 'This controls the description which the user sees during checkout.', 'wc_paypaladv' ),
				'default'     => __( 'PayPal Advanced description', 'wc_paypaladv' ),
			),
			'loginid' => array(
				'title'       => __( 'Merchant Login', 'wc_paypaladv' ),
				'type'        => 'text',
				'description' => '',
				'default'     => '',
			),
			'resellerid' => array(
				'title'       => __( 'Partner', 'wc_paypaladv' ),
				'type'        => 'text',
				'description' => __( 'Enter your PayPal Advanced Partner. If you purchased the account directly from PayPal, use PayPal.', 'wc_paypaladv' ),
				'default'     => '',
			),

			'user' => array(
				'title'       => __( 'User (or Merchant Login if no designated user is set up for the account)', 'wc_paypaladv' ),
				'type'        => 'text',
				'description' => __( 'Enter your PayPal Advanced user account for this site.', 'wc_paypaladv' ),
				'default'     => '',
			),

			'password' => array(
				'title'       => __( 'Password', 'wc_paypaladv' ),
				'type'        => 'password',
				'description' => __( 'Enter your PayPal Advanced account password.', 'wc_paypaladv' ),
				'default'     => '',
			),
			'testmode' => array(
				'title'       => __( 'PayPal sandbox', 'wc_paypaladv' ),
				'type'        => 'checkbox',
				'label'       => __( 'Enable PayPal sandbox', 'wc_paypaladv' ),
				'default'     => 'yes',
				'description' => sprintf( __( 'PayPal sandbox can be used to test payments. Sign up for a developer account <a href="%s">here</a>', 'wc_paypaladv' ), 'https://developer.paypal.com/' ),
			),
			'transtype' => array(
				'title'       => __( 'Transaction Type', 'wc_paypaladv' ),
				'type'        => 'select',
				'label'       => __( 'Transaction Type', 'wc_paypaladv' ),
				'default'     => 'S',
				'description' => '',
				'options'     => array(
					'A' => 'Authorization',
					'S' => 'Sale',
				),
			),
			'layout' => array(
				'title'       => __( 'Layout', 'wc_paypaladv' ),
				'type'        => 'select',
				'label'       => __( 'Layout', 'wc_paypaladv' ),
				'default'     => 'C',
				'description' => __( 'Layouts A and B redirect to PayPal\'s website for the user to pay. <br/>Layout C (recommended) is a secure PayPal-hosted page but is embedded on your site using an iFrame.', 'wc_paypaladv' ),
				'options'     => array(
					'A' => 'Layout A',
					'B' => 'Layout B',
					'C' => 'Layout C',
				),
			),
			'mobilemode' => array(
				'title'       => __( 'Mobile Mode', 'wc_paypaladv' ),
				'type'        => 'checkbox',
				'label'       => __( 'Display Mobile optimized form if browsed through Mobile', 'wc_paypaladv' ),
				'default'     => 'yes',
				'description' => sprintf( __( 'Disable this option if your theme is not compatible with Mobile. Otherwise You would get Silent Post Error in Layout C.', 'wc_paypaladv' ), 'https://developer.paypal.com/' ),
			),
			'invoice_prefix' => array(
				'title'       => __( 'Invoice Prefix', 'wc_paypaladv' ),
				'type'        => 'text',
				'description' => __( 'Please enter a prefix for your invoice numbers. If you use your PayPal account for multiple stores ensure this prefix is unique as PayPal will not allow orders with the same invoice number.', 'woocommerce' ),
				'default'     => 'WC-PPADV',
				'desc_tip'    => true,
			),
			'page_collapse_bgcolor' => array(
				'title'       => __( 'Page Collapse Border Color', 'wc_paypaladv' ),
				'type'        => 'text',
				'description' => __( 'Sets the color of the border around the embedded template C.', 'wc_paypaladv' ),
				'default'     => '',
				'desc_tip'    => true,
				'class'       => 'wc_paypaladv_color_field',
			),
			'page_collapse_textcolor' => array(
				'title'       => __( 'Page Collapse Text Color', 'wc_paypaladv' ),
				'type'        => 'text',
				'description' => __( 'Sets the color of the words "Pay with PayPal" and "Pay with credit or debit card".', 'wc_paypaladv' ),
				'default'     => '',
				'desc_tip'    => true,
				'class'       => 'wc_paypaladv_color_field',
			),
			'page_button_bgcolor' => array(
				'title'       => __( 'Page Button Background Color', 'wc_paypaladv' ),
				'type'        => 'text',
				'description' => __( 'Sets the background color of the Pay Now / Submit button.', 'wc_paypaladv' ),
				'default'     => '',
				'desc_tip'    => true,
				'class'       => 'wc_paypaladv_color_field',
			),
			'page_button_textcolor' => array(
				'title'       => __( 'Page Button Text Color', 'wc_paypaladv' ),
				'type'        => 'text',
				'description' => __( 'Sets the color of the text on the Pay Now / Submit button.', 'wc_paypaladv' ),
				'default'     => '',
				'desc_tip'    => true,
				'class'       => 'wc_paypaladv_color_field',
			),
			'label_textcolor' => array(
				'title'       => __( 'Label Text Color', 'wc_paypaladv' ),
				'type'        => 'text',
				'description' => __( 'Sets the color of the text for "card number", "expiration date", ..etc.', 'wc_paypaladv' ),
				'default'     => '',
				'desc_tip'    => true,
				'class'       => 'wc_paypaladv_color_field',
			),
			'debug' => array(
				'title'       => __( 'Debug Log', 'wc_paypaladv' ),
				'type'        => 'checkbox',
				'label'       => __( 'Enable logging', 'wc_paypaladv' ),
				'default'     => 'no',
				'description' => __( 'Log PayPal events, helpful in debugging when issue with transactions with the gateway', 'wc_paypaladv' ),
			),
		);
	}

	/**
	 * There are no payment fields for paypal, but we want to show the description if set.
	 *
	 * @access public
	 * @return void
	 **/
	public function payment_fields() {
		if ( $this->description ) {
			echo wpautop( wptexturize( $this->description ) );
		}
	}

	/**
	 * Process the payment.
	 *
	 * @param int $order_id Order ID.
	 */
	public function process_payment( $order_id ) {
		$order = new WC_Order( $order_id );

		// Use try/catch blocks to handle exceptions while processing the payment.
		try {
			// Get secure token.
			$this->securetoken = $this->get_secure_token( $order );

			// If valid securetoken.
			if ( '' != $this->securetoken ) {

				// Add token values to post meta and we can use it later.
				update_post_meta( $order->get_id(), '_secure_token_id', $this->secure_token_id );
				update_post_meta( $order->get_id(), '_secure_token', $this->securetoken );

				$this->log( sprintf( __( 'Secured Token generated successfully for the order %s', 'wc_paypaladv' ), $order->get_order_number() ) );

				return array(
					'result'   => 'success',
					'redirect' => $order->get_checkout_payment_url( true ),
				);
			}
		} catch ( Exception $e ) {
			wc_add_notice( __( 'Error:', 'wc_paypaladv' ) . ' "' . $e->getMessage() . '"', 'error' );

			$this->log( 'Error Occurred while processing the order ' . $order_id );
		}
	}

	/**
	 * Process a refund if supported.
	 *
	 * @param int    $order_id Order ID.
	 * @param float  $amount   Refund amount.
	 * @param string $reason   Refund reason.
	 *
	 * @return bool|wp_error True or false based on success, or a WP_Error object.
	 */
	public function process_refund( $order_id, $amount = null, $reason = '' ) {
		$order = wc_get_order( $order_id );
		if ( ! $order || ! $order->get_transaction_id() ) {
			return false;
		}

		if ( ! is_null( $amount ) && $order->get_total() > $amount ) {
			return new WP_Error( 'paypal-advanced-error', __( 'Partial refund is not supported', 'woocommerce' ) );
		}

		// refund transaction, parameters.
		$paypal_args = array(
			'USER'                                   => $this->user,
			'VENDOR'                                 => $this->loginid,
			'PARTNER'                                => $this->resellerid,
			'PWD[' . strlen( $this->password ) . ']' => $this->password,
			'ORIGID'                                 => $order->get_transaction_id(),
			'TENDER'                                 => 'C',
			'TRXTYPE'                                => 'C',
			'VERBOSITY'                              => 'HIGH',
		);

		$postData = ''; // Stores the post data string.
		foreach ( $paypal_args as $key => $val ) {
			$postData .= '&' . $key . '=' . $val;
		}

		$postData = trim( $postData, '&' );

		// Using Curl post necessary information to the Paypal Site to generate
		// the secured token .
		$response = wp_remote_post(
			$this->hostaddr,
			array(
				'method'      => 'POST',
				'body'        => $postData,
				'timeout'     => 70,
				'user-agent'  => 'Woocommerce ' . WC_VERSION,
				'httpversion' => '1.1',
				'headers'     => array(
					'host' => 'www.paypal.com',
				),
			)
		);

		if ( is_wp_error( $response ) ) {
			throw new Exception( __( 'There was a problem connecting to the payment gateway.', 'wc_paypaladv' ) );
		}

		if ( empty( $response['body'] ) ) {
			throw new Exception( __( 'Empty response.', 'wc_paypaladv' ) );
		}

		// Parse and assign to array.
		$refund_result_arr = array(); // Stores the response in array format.
		parse_str( $response['body'], $refund_result_arr );

		$this->log( sprintf( __( 'Response of the refund transaction: %s', 'wc_paypaladv' ), print_r( $refund_result_arr, true ) ) );

		if ( 0 == $refund_result_arr['RESULT'] ) {
			/* translators: transaction ID from PayPal */
			$order->add_order_note( sprintf( __( 'Successfully Refunded - Refund Transaction ID: %s.', 'woocommerce' ), $refund_result_arr['PNREF'] ) );
		} else {
			/* translators: 1) transaction ID from PayPal 2) error message */
			$order->add_order_note( sprintf( __( 'Refund Failed - Refund Transaction ID: %1$s, Error Msg: %2$s.', 'woocommerce' ), $refund_result_arr['PNREF'], $refund_result_arr['RESPMSG'] ) );

			throw new Exception( sprintf( __( 'Refund Failed - Refund Transaction ID: %1$s, Error Msg: %2$s.', 'woocommerce' ), $refund_result_arr['PNREF'], $refund_result_arr['RESPMSG'] ) );

			return false;
		}

		return true;
	}

	/**
	 * Displays IFRAME/Redirect to show the hosted page in Paypal.
	 *
	 * @param int $order_id Order ID.
	 */
	public function receipt_page( $order_id ) {
		$PF_MODE = 'yes' === $this->settings['testmode'] ? 'TEST' : 'LIVE';
		$order   = new WC_Order( $order_id );

		// Get the tokens.
		$this->secure_token_id = get_post_meta( $order->get_id(), '_secure_token_id',true );
		$this->securetoken     = get_post_meta( $order->get_id(), '_secure_token',true );

		// Log the browser and its version.
		$this->log( sprintf( __( 'Browser Info: %s', 'wc_paypaladv' ), $_SERVER['HTTP_USER_AGENT'] ) );

		// Display the form in IFRAME, if it is layout C, otherwise redirect
		// to paypal site.
		if ( 'MINLAYOUT' === $this->layout || 'C' === $this->layout ) {
			// Define the url.
			$location = 'https://payflowlink.paypal.com?mode=' . $PF_MODE . '&amp;SECURETOKEN=' . $this->securetoken . '&amp;SECURETOKENID=' . $this->secure_token_id;

			$this->log( sprintf( __( 'Show payment form(IFRAME) for the order %s as it is configured to use Layout C', 'wc_paypaladv' ), $order->get_order_number() ) );

		?>
		<iframe id="wc_paypaladv_iframe" src="<?php echo $location;?>" width="550" height="565" scrolling="no" frameborder="0" border="0" allowtransparency="true"></iframe>

		<?php
		} else {
			$location = 'https://payflowlink.paypal.com?mode=' . $PF_MODE . '&SECURETOKEN=' . $this->securetoken . '&SECURETOKENID=' . $this->secure_token_id;

			$this->log(
				sprintf(
					/* translators: 1) URL 2) order number */
					__( 'Show payment form redirecting to %1$s for the order %2$s as it is not configured to use Layout C', 'wc_paypaladv' ),
					$location,
					$order->get_order_number()
				)
			);

			wp_redirect( $location );
			exit;
		}
	}

	/**
	 * Limit the length of item names.
	 *
	 * @param string $item_name Item name.
	 *
	 * @return string Item name.
	 */
	public function paypal_advanced_item_name( $item_name ) {
		$item_name = html_entity_decode( $item_name, ENT_NOQUOTES | ENT_SUBSTITUTE, 'UTF-8' );
		if ( strlen( $item_name ) > 36 ) {
			$item_name = substr( $item_name, 0, 33 ) . '...';
		}
		return $item_name;
	}

	/**
	 * Limit the length of item description.
	 *
	 * @param string $item_desc Item description.
	 *
	 * @return string Item description.
	 */
	public function paypal_advanced_item_desc( $item_desc ) {
		 $item_desc = html_entity_decode( $item_desc, ENT_NOQUOTES | ENT_SUBSTITUTE, 'UTF-8' );
		if ( strlen( $item_desc ) > 127 ) {
			$item_desc = substr( $item_desc, 0, 124 ) . '...';
		}
		return $item_desc;
	}

	/**
	 * Get the transaction URL.
	 *
	 * @since 1.24.1
	 * @version 1.24.1
	 *
	 * @param WC_Order $order Order object.
	 *
	 * @return string Transaction URL.
	 */
	public function get_transaction_url( $order ) {
		$this->view_transaction_url = 'https://manager.paypal.com/payflowReports.do?subaction=transDetails&id=%s';

		return parent::get_transaction_url( $order );
	}

	/**
	 * Log debug message.
	 *
	 * @since 1.24.1
	 * @version 1.24.1
	 *
	 * @param string $message Message to log.
	 */
	public function log( $message ) {
		if ( 'yes' !== $this->debug ) {
			return;
		}

		if ( ! is_a( $this->logger, 'WC_Logger' ) ) {
			$this->logger = new WC_Logger();
		}

		$this->logger->add( 'paypal_advanced', $message );
	}
}

/**
 * Add the gateway to WooCommerce.
 *
 * @since 1.0.0
 *
 * @param array $methods Payment methods.
 *
 * @return array Payment methods.
 */
function add_paypal_adv_gateway( $methods ) {
	$methods[] = 'WC_Paypal_Advanced';
	return $methods;
}
add_filter( 'woocommerce_payment_gateways', 'add_paypal_adv_gateway' );
