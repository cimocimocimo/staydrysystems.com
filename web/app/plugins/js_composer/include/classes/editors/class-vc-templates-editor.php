<?php
/**
 * The templates manager for VC.
 *
 * The templates manager provides ability to copy and reuse existing pages. Save templates for later use.
 *
 * @since 4.2
 */
class Vc_Templates_Editor {
	/**
	 * Add ajax hooks.
	 */
	public function init() {
		add_action( 'wp_ajax_wpb_save_template', array( &$this, 'save' ) );
		add_action( 'wp_ajax_wpb_load_template', array( &$this, 'load' ) );
		add_action( 'wp_ajax_wpb_load_inline_template', array( &$this, 'loadInline' ) );
		add_action( 'wp_ajax_wpb_load_template_shortcodes', array( &$this, 'loadTemplateShortcodes' ) );
		add_action( 'wp_ajax_wpb_delete_template', array( &$this, 'delete' ) );
	}

	public function save() {
		$template_name = vc_post_param( 'template_name' );
		$template = vc_post_param( 'template' );

		if ( ! isset( $template_name ) || $template_name == "" || ! isset( $template ) || $template == "" ) {
			echo 'Error: TPL-01';
			die();
		}

		$template_arr = array( "name" => stripslashes( $template_name ), "template" => stripslashes( $template ) );
		$option_name = 'wpb_js_templates';
		$saved_templates = get_option( $option_name );


		$template_id = sanitize_title( $template_name ) . "_" . rand();
		if ( $saved_templates == false ) {
			$deprecated = '';
			$autoload = 'no';
			//
			$new_template = array();
			$new_template[$template_id] = $template_arr;
			//
			add_option( $option_name, $new_template, $deprecated, $autoload );
		} else {
			$saved_templates[$template_id] = $template_arr;
			update_option( $option_name, $saved_templates );
		}

		echo vc_backend_editor()->getLayout()->getNavBar()->getTemplateMenu( true );

		//delete_option('wpb_js_templates');

		die();
	}

	public function load() {
		$template_id = vc_post_param( 'template_id' );

		if ( ! isset( $template_id ) || $template_id == "" ) {
			echo 'Error: TPL-02';
			die();
		}

		$option_name = 'wpb_js_templates';
		$saved_templates = get_option( $option_name );

		$content = $saved_templates[$template_id]['template'];
		// $content = str_ireplace('\"', '"', $content);
		//echo $content;
		$pattern = get_shortcode_regex();
		$content = preg_replace_callback( "/{$pattern}/s", 'vc_convert_shortcode', $content );
		echo do_shortcode( $content );

		die();
	}

	public function loadInline() {
		vc_frontend_editor()->getTemplateShortcodes();
		die();
	}

	public function loadTemplateShortcodes() {
		$template_id = vc_post_param( 'template_id' );

		if ( ! isset( $template_id ) || $template_id == "" ) {
			echo 'Error: TPL-02';
			die();
		}

		$option_name = 'wpb_js_templates';
		$saved_templates = get_option( $option_name );

		$content = $saved_templates[$template_id]['template'];
		//echo $content;
		$pattern = get_shortcode_regex();
		$content = preg_replace_callback( "/{$pattern}/s", 'vc_convert_shortcode', $content );
		echo $content;
		die();
	}

	public function delete() {
		$template_id = vc_post_param( 'template_id' );

		if ( ! isset( $template_id ) || $template_id == "" ) {
			echo 'Error: TPL-03';
			die();
		}

		$option_name = 'wpb_js_templates';
		$saved_templates = get_option( $option_name );
		unset( $saved_templates[$template_id] );
		if ( count( $saved_templates ) > 0 ) {
			update_option( $option_name, $saved_templates );
		} else {
			delete_option( $option_name );
		}

		echo vc_backend_editor()->getLayout()->getNavBar()->getTemplateMenu( true );

		die();
	}
}