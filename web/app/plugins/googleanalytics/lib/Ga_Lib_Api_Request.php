<?php

class Ga_Lib_Api_Request {

	const HEADER_CONTENT_TYPE = "application/x-www-form-urlencoded";

	const HEADER_CONTENT_TYPE_JSON = "Content-type: application/json";

	const HEADER_ACCEPT = "Accept: application/json, text/javascript, */*; q=0.01";

	const TIMEOUT = 30;

	const USER_AGENT = 'googleanalytics-wordpress-plugin';

	private $headers = array();

	function __construct() {
	}

	/**
	 * Returns API client instance.
	 *
	 * @return Ga_Lib_Api_Request|null
	 */
	public static function get_instance() {
		static $instance = null;
		if ( $instance === null ) {
			$instance = new Ga_Lib_Api_Request();
		}

		return $instance;
	}

	/**
	 * Sets request headers.
	 *
	 * @param $headers
	 */
	public function set_request_headers( $headers ) {
		if ( is_array( $headers ) ) {
			$this->headers = array_merge( $this->headers, $headers );
		} else {
			$this->headers[] = $headers;
		}
	}

	/**
	 * Perform HTTP request.
	 *
	 * @param string $url URL address
	 * @param string $rawPostBody
	 *
	 * @return string Response
	 * @throws Exception
	 */
	public function make_request( $url, $rawPostBody = null, $json = false ) {
		if ( ! function_exists( 'curl_init' ) ) {
			throw new Exception( 'cURL functions are not available' );
		}

		// Set default headers
		$this->set_request_headers( array(
			( $json ? self::HEADER_CONTENT_TYPE_JSON : self::HEADER_CONTENT_TYPE ),
			self::HEADER_ACCEPT,
			'Expect: 200-OK'
		) );

		$ch      = curl_init( $url );
		$headers = $this->headers;
		curl_setopt( $ch, CURLOPT_HTTPHEADER, $headers );

		$curl_timeout       = self::TIMEOUT;
		$php_execution_time = ini_get( 'max_execution_time' );
		if ( ! empty( $php_execution_time ) && is_numeric( $php_execution_time ) ) {
			if ( $php_execution_time < 36 && $php_execution_time > 9 ) {
				$curl_timeout = $php_execution_time - 5;
			} elseif ( $php_execution_time < 10 ) {
				$curl_timeout = 5;
			}
		}

		curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $curl_timeout );
		curl_setopt( $ch, CURLOPT_TIMEOUT, $curl_timeout );
		curl_setopt( $ch, CURLOPT_HEADER, true );
		curl_setopt( $ch, CURLINFO_HEADER_OUT, true );
		curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch, CURLOPT_USERAGENT, self::USER_AGENT );

		// POST body
		if ( ! empty( $rawPostBody ) ) {
			curl_setopt( $ch, CURLOPT_POST, true );
			curl_setopt( $ch, CURLOPT_POSTFIELDS, $rawPostBody );
		}

		// Execute request
		$response = curl_exec( $ch );

		if ( $error = curl_error( $ch ) ) {
			$errno = curl_errno( $ch );
			curl_close( $ch );
			throw new Exception( $error . ' (' . $errno . ')' );
		} else {

			$headerSize = curl_getinfo( $ch, CURLINFO_HEADER_SIZE );
			$header     = substr( $response, 0, $headerSize );
			$body       = substr( $response, $headerSize, strlen( $response ) );
			curl_close( $ch );

			return array( $header, $body );
		}
	}
}