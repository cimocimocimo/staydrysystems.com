<?php
/**
 * JWPLayer loader.
 *
 */
Class Vc_Vendor_Jwplayer implements Vc_Vendor_Interface {
	public function load() {
		if(vc_is_frontend_editor() && class_exists('JWP6_Plugin')) {
//			add_shortcode('jwplayer', array('JWP6_Plugin', 'shortcode'));
//			add_filter('query_vars', array('JWP6_Plugin', 'register_query_vars'));
//			add_action('parse_request',  array('JWP6_Plugin', 'parse_request'), 9 );
//			add_action('wp_enqueue_scripts', array('JWP6_Plugin', 'insert_javascript'));
//			add_action('wp_head', array('JWP6_Plugin', 'insert_license_key'));
//			add_action('wp_head', array('JWP6_Plugin', 'insert_jwp6_load_event'));
		}
	}
}
