<?php
/**
 * WPBakery Visual Composer main class.
 *
 * @package WPBakeryVisualComposer
 * @since   4.2
 */
/**
 * Edit form for shortcodes with ability to manage shortcode attributes in more convenient way.
 *
 * @since   4.2
 */
class Vc_Shortcode_Edit_Form {
	public function init() {
		add_action( 'wp_ajax_wpb_show_edit_form', array( &$this, 'build' ) );
		if ( vc_is_frontend_ajax() ) {
			add_filter( 'vc_single_param_edit', array( &$this, 'changeFrontendEditFormFieldParams' ) );
			add_filter( 'vc_edit_form_class', array( &$this, 'changeFrontendEditFormParams' ) );
		}
	}

	public function build() {
		$element = vc_post_param( 'element' );
		$shortCode = stripslashes( vc_post_param( 'shortcode' ) );
		visual_composer()->removeShortCode( $element );
		$settings = WPBMap::getShortCode( $element );
		new WPBakeryShortCode_Settings( $settings );
		echo do_shortcode( $shortCode );
		die();
	}

	public function changeFrontendEditFormFieldParams( $param ) {
		$css = $param['vc_single_param_edit_holder_class'];
		$index = array_search( 'vc_row-fluid', $css );
		if ( isset( $param['edit_field_class'] ) ) {
			$new_css = $param['edit_field_class'];
		} else {
			switch ( $param['type'] ) {
				case 'attach_image':
				case 'attach_images':
				case 'textarea_html':
					$new_css = 'col-md-12';
					break;
				default:
					$new_css = 'col-md-12';
			}
		}
		if ( $index === false ) {
			array_unshift( $css, $new_css );
		} else {
			$css[$index] = $new_css . " vc-column";
		}

		$param['vc_single_param_edit_holder_class'] = $css;
		return $param;
	}

	public function changeFrontendEditFormParams( $css_classes ) {
		$css = 'row vc-row';
		$index = array_search( 'vc_span12', $css_classes );
		if ( $index === false ) {
			array_unshift( $css_classes, $css );
		} else {
			$css_classes[$index] = $css;
		}
		return $css_classes;
	}
}