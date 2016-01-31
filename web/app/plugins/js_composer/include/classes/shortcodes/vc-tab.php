<?php
define( 'TAB_TITLE', __( "Tab", "js_composer" ) );
require_once vc_path_dir('SHORTCODES_DIR', 'vc-column.php');

class WPBakeryShortCode_VC_Tab extends WPBakeryShortCode_VC_Column {
	protected $predefined_atts = array(
		'tab_id' => TAB_TITLE,
		'title' => ''
	);

	public function __construct( $settings ) {
		parent::__construct( $settings );
	}

	public function customAdminBlockParams() {
		return ' id="tab-' . $this->atts['tab_id'] . '"';
	}

	public function mainHtmlBlockParams( $width, $i ) {
		return 'data-element_type="' . $this->settings["base"] . '" class="wpb_' . $this->settings['base'] . ' wpb_sortable wpb_content_holder"' . $this->customAdminBlockParams();
	}

	public function containerHtmlBlockParams( $width, $i ) {
		return 'class="wpb_column_container vc_container_for_children"';
	}

	public function getColumnControls( $controls, $extended_css = '' ) {
		$controls_start = '<div class="controls controls_column' . ( ! empty( $extended_css ) ? " {$extended_css}" : '' ) . '">';
		$controls_end = '</div>';

		if ( $extended_css == 'bottom-controls' ) $control_title = sprintf( __( 'Append to this %s', 'js_composer' ), strtolower( $this->settings( 'name' ) ) );
		else $control_title = sprintf( __( 'Prepend to this %s', 'js_composer' ), strtolower( $this->settings( 'name' ) ) );

		$controls_add = ' <a class="column_add" href="#" title="' . $control_title . '"></a>';
		$controls_edit = ' <a class="column_edit" href="#" title="' . sprintf( __( 'Edit this %s', 'js_composer' ), strtolower( $this->settings( 'name' ) ) ) . '"></a>';
		$controls_clone = '<a class="column_clone" href="#" title="' . sprintf( __( 'Clone this %s', 'js_composer' ), strtolower( $this->settings( 'name' ) ) ) . '"></a>';

		$controls_delete = '<a class="column_delete" href="#" title="' . sprintf( __( 'Delete this %s', 'js_composer' ), strtolower( $this->settings( 'name' ) ) ) . '"></a>';
		return $controls_start . $controls_add . $controls_edit . $controls_clone . $controls_delete . $controls_end;
	}
}


function tab_id_settings_field( $settings, $value ) {
	$dependency = vc_generate_dependencies_attributes( $settings );
	return '<div class="my_param_block">'
	  . '<input name="' . $settings['param_name']
	  . '" class="wpb_vc_param_value wpb-textinput '
	  . $settings['param_name'] . ' ' . $settings['type'] . '_field" type="hidden" value="'
	  . $value . '" ' . $dependency . ' />'
	  . '<label>' . $value . '</label>'
	  . '</div>';
	// TODO: Add data-js-function to documentation
}

add_shortcode_param( 'tab_id', 'tab_id_settings_field' );