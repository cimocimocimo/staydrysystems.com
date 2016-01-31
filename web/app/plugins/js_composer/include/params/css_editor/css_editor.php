<?php

if ( ! class_exists( 'WPBakeryVisualComposerCssEditor' ) ) {
	class WPBakeryVisualComposerCssEditor {
		protected $settings = array();
		protected $value = '';
		protected $layers = array( 'margin', 'border', 'padding', 'content' );
		protected $positions = array( 'top', 'right', 'bottom', 'left' );

		function __construct() {
		}

		/**
		 * Setters/Getters {{
		 */
		function settings( $settings = null ) {
			if ( is_array( $settings ) ) $this->settings = $settings;
			return $this->settings;
		}

		function setting( $key ) {
			return isset( $this->settings[$key] ) ? $this->settings[$key] : '';
		}

		function value( $value = null ) {
			if ( is_string( $value ) ) {
				$this->value = $value;
			}
			return $this->value;
		}

		function params( $values = null ) {
			if ( is_array( $values ) ) $this->params = $values;
			return $this->params;
		}

		// }}
		function render() {
			$output = '<div class="vc-css-editor row vc-row" data-css-editor="true">';
			$output .= $this->onionLayout();
			$output .= '<div class="col-xs-5 vc_span5 vc-settings">'
			  . '    <label>' . __( 'Border', 'js_composer' ) . '</label> '
			  . '    <div class="color-group"><input type="text" name="border_color" value="" class="vc-color-control"></div>'
			  . '    <div class="vc-border-style"><select name="border_style" class="vc-border-style">' . $this->getBorderStyleOptions() . '</select></div>'
			  . '    <label>' . __( 'Background', 'js_composer' ) . '</label>'
			  . '    <div class="color-group"><input type="text" name="background_color" value="" class="vc-color-control"></div>'
			  . '    <div class="vc-background-image">' . $this->getBackgroundImageControl() . '<div class="clearfix"></div></div>'
			  . '    <div class="vc-background-style"><select name="background_style" class="vc-background-style">' . $this->getBackgroundStyleOptions() . '</select></div>'
			  . '    <label>' . __( 'Box controls', 'js_composer' ) . '</label>'
			  . '    <label class="vc-checkbox"><input type="checkbox" name="simply" class="vc-simplify" value=""> ' . __( 'Simplify controls', 'js_composer' ) . '</label>'
			  . '</div>';
			$output .= '<input name="' . $this->setting( 'param_name' ) . '" class="wpb_vc_param_value  ' . $this->setting( 'param_name' ) . ' ' . $this->setting( 'type' ) . '_field" type="hidden" value="' . esc_attr( $this->value() ) . '"/>';
			$output .= '</div><div class="clearfix"></div>';
			$output .= '<script type="text/html" id="vc-css-editor-image-block">'
			  . '<li class="added">'
			  . '  <div class="inner" style="width: 75px; height: 75px; overflow: hidden;text-align: center;">'
			  . '    <img src="{{ img.url }}?id={{ img.id }}" data-image-id="{{ img.id }}" class="vc-ce-image<# if(!_.isUndefined(img.css_class)) {#> {{ img.css_class }}<# }#>">'
			  . '  </div>'
			  . '  <a href="#" class="icon-remove"></a>'
			  . '</li>'
			  . '</script>';
			$output .= '<script type="text/javascript" src="' . vc_asset_url( 'js/params/css_editor.js' ) . '"></script>';
			return apply_filters( 'vc_css_editor', $output );
		}

		function getBackgroundImageControl() {
			return '<ul class="vc-image">'
			  . '</ul>'
			  . '<a href="#" class="vc-add-image">' . __( 'Add image', 'js_composer' ) . '</a>';
		}

		function getBorderStyleOptions() {
			$output = '<option value="">' . __( 'Theme defaults', 'js_composer' ) . '</option>';
			$styles = array( 'solid', 'dotted', 'dashed', 'none', 'hidden', 'double', 'groove', 'ridge', 'inset', 'outset', 'initial', 'inherit' );
			foreach ( $styles as $style ) {
				$output .= '<option value="' . $style . '">' . __( ucfirst( $style ), 'js_composer' ) . '</option>';
			}
			return $output;
		}

		function getBackgroundStyleOptions() {
			$output = '<option value="">' . __( 'Theme defaults', 'js_composer' ) . '</option>';
			$styles = array(
				__( "Cover", 'wpb' ) => 'cover',
				__( 'Contain', 'wpb' ) => 'contain',
				__( 'No Repeat', 'wpb' ) => 'no-repeat',
				__( 'Repeat', 'wpb' ) => 'repeat'
			);
			foreach ( $styles as $name => $style ) {
				$output .= '<option value="' . $style . '">' . $name . '</option>';
			}
			return $output;
		}

		function onionLayout() {
			$output = '<div class="vc-layout-onion col-xs-7 vc_span7">'
			  . '    <div class="vc-margin">' . $this->layerControls( 'margin' )
			  . '      <div class="vc-border">' . $this->layerControls( 'border', 'width' )
			  . '          <div class="vc-padding">' . $this->layerControls( 'padding' )
			  . '              <div class="vc-content"><i></i></div>'
			  . '          </div>'
			  . '      </div>'
			  . '    </div>'
			  . '</div>';
			return $output;
		}

		protected function layerControls( $name, $prefix = '' ) {
			$output = '<label>' . __( $name, 'js_composer' ) . '</label>';
			foreach ( $this->positions as $pos ) {
				$output .= '<input type="text" name="' . $name . '_' . $pos . ( $prefix != '' ? '_' . $prefix : '' ) . '" data-name="' . $name . ( $prefix != '' ? '-' . $prefix : '' ) . '-' . $pos . '" class="vc-' . $pos . '" placeholder="-" data-attribute="' . $name . '" value="">';
			}
			return $output;
		}
	}
}

function vc_css_editor_form_field( $settings, $value ) {
	$css_editor = new WPBakeryVisualComposerCssEditor();
	$css_editor->settings( $settings );
	$css_editor->value( $value );
	return $css_editor->render();

}