<?php

class Ga_Lib_Api_Client {

	const OAUTH2_REVOKE_ENDPOINT = 'https://accounts.google.com/o/oauth2/revoke';

	const OAUTH2_TOKEN_ENDPOINT = 'https://accounts.google.com/o/oauth2/token';

	const OAUTH2_AUTH_ENDPOINT = 'https://accounts.google.com/o/oauth2/auth';

	const OAUTH2_FEDERATED_SIGNON_CERTS_ENDPOINT = 'https://www.googleapis.com/oauth2/v1/certs';

	const GA_ACCOUNT_SUMMARIES_ENDPOINT = 'https://www.googleapis.com/analytics/v3/management/accountSummaries';

	const GA_DATA_ENDPOINT = 'https://analyticsreporting.googleapis.com/v4/reports:batchGet';

	const OAUTH2_CALLBACK_URI = 'urn:ietf:wg:oauth:2.0:oob';

	/**
	 * Pre-defined API credentials.
	 *
	 * @var array
	 */
	private $config = array(
		'access_type'      => 'offline',
		'application_name' => 'Google Analytics',
		'client_id'        => '207216681371-433ldmujuv4l0743c1j7g8sci57cb51r.apps.googleusercontent.com',
		'client_secret'    => 'y0B-K-ODB1KZOam50aMEDhyc',
		'scopes'           => array( 'https://www.googleapis.com/auth/analytics.readonly' ),
		'approval_prompt'  => 'force'
	);

	/**
	 * Keeps Access Token information.
	 *
	 * @var array
	 */
	private $token;

	/**
	 * Returns API client instance.
	 *
	 * @return Ga_Lib_Api_Client|null
	 */
	public static function get_instance() {
		static $instance = null;
		if ( $instance === null ) {
			$instance = new Ga_Lib_Api_Client();
		}

		return $instance;
	}

	/**
	 * Calls api methods.
	 *
	 * @param string $callback
	 * @param mixed $args
	 *
	 * @return mixed
	 */
	public function call( $callback, $args = null ) {
		try {
			$callback = array( get_class( $this ), $callback );
			if ( is_callable( $callback ) ) {
				if ( ! empty( $args ) ) {
					if ( is_array( $args ) ) {
						return call_user_func_array( $callback, $args );
					} else {
						return call_user_func_array( $callback, array( $args ) );
					}
				} else {
					return call_user_func( $callback );
				}
			} else {
				throw new Exception( 'Unknown method: ' . $callback );
			}
		} catch ( Exception $e ) {
			// @todo: need to add exception
			echo $e->getMessage();
		}
	}

	/**
	 * Sets access token.
	 *
	 * @param $token
	 */
	public function set_access_token( $token ) {
		$this->token = $token;
	}

	/**
	 * Returns Google Oauth2 redirect URL.
	 *
	 * @return string
	 */
	private function get_redirect_uri() {
		return self::OAUTH2_CALLBACK_URI;
	}

	/**
	 * Creates Google Oauth2 authorization URL.
	 *
	 * @return string
	 */
	public function create_auth_url() {
		$params = array(
			'response_type'   => 'code',
			'redirect_uri'    => $this->get_redirect_uri(),
			'client_id'       => urlencode( $this->config['client_id'] ),
			'scope'           => implode( " ", $this->config['scopes'] ),
			'access_type'     => urlencode( $this->config['access_type'] ),
			'approval_prompt' => urlencode( $this->config['approval_prompt'] )
		);

		return self::OAUTH2_AUTH_ENDPOINT . "?" . http_build_query( $params );
	}

	/**
	 * Sends request for Access Token during Oauth2 process.
	 *
	 * @param $access_code
	 *
	 * @return Ga_Lib_Api_Response Returns response object
	 */
	private function ga_auth_get_access_token( $access_code ) {
		$request = array(
			'code'          => $access_code,
			'grant_type'    => 'authorization_code',
			'redirect_uri'  => $this->get_redirect_uri(),
			'client_id'     => $this->config['client_id'],
			'client_secret' => $this->config['client_secret']
		);

		$response = Ga_Lib_Api_Request::get_instance()->make_request( self::OAUTH2_TOKEN_ENDPOINT, $request );

		return new Ga_Lib_Api_Response( $response );
	}

	/**
	 * Get list of the analytics accounts.
	 *
	 * @return Ga_Lib_Api_Response Returns response object
	 */
	private function ga_api_account_summaries() {
		$request  = Ga_Lib_Api_Request::get_instance();
		$request  = $this->sign( $request );
		$response = $request->make_request( self::GA_ACCOUNT_SUMMARIES_ENDPOINT );

		return new Ga_Lib_Api_Response( $response );
	}

	/**
	 * Sends request for Google Analytics data using given query parameters.
	 *
	 * @param $query_params
	 *
	 * @return Ga_Lib_Api_Response Returns response object
	 */
	private function ga_api_data( $query_params ) {
		$request  = Ga_Lib_Api_Request::get_instance();
		$request  = $this->sign( $request );
		$response = $request->make_request( self::GA_DATA_ENDPOINT, wp_json_encode( $query_params ), true );

		return new Ga_Lib_Api_Response( $response );
	}

	/**
	 * Sign request with Access Token.
	 * Adds Access Token to the request's headers.
	 *
	 * @param Ga_Lib_Api_Request $request
	 *
	 * @return Ga_Lib_Api_Request Returns response object
	 */
	private function sign( Ga_Lib_Api_Request $request ) {
		// Add the OAuth2 header to the request
		$request->set_request_headers( array( 'Authorization: Bearer ' . $this->token['access_token'] ) );

		return $request;
	}

	/**
	 * Checks if Access Token is valid.
	 *
	 * @return bool
	 */
	public function is_authorized() {
		$authorized = true;
		if ( $this->is_access_token_expired() ) {
			$authorized = false;
		}

		return $authorized;
	}

	/**
	 * Returns if the access_token is expired.
	 * @return bool Returns True if the access_token is expired.
	 */
	public function is_access_token_expired() {
		if ( null == $this->token ) {
			return true;
		}

		// Check if the token is expired in the next 30 seconds.
		$expired = ( $this->token['created'] + ( $this->token['expires_in'] - 30 ) ) < time();

		return $expired;
	}
}