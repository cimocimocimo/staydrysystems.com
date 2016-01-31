<?php

$output = $el_class = $type = $bg_image = $bg_color = $bg_image_repeat = $font_color = $padding = $padding_bottom = $padding_bottom = $margin_bottom = $css = '';
extract( shortcode_atts( array(
    'el_class' => '',
    'type' => 'container',
    'bg_image' => '',
    'bg_color' => '',
    'bg_image_repeat' => '',
    'font_color' => '',
    'padding' => '',
    'padding_top' => '',
    'padding_bottom' => '',
    'margin_bottom' => '',
    'css' => ''
                ), $atts ) );

wp_enqueue_style( 'js_composer_front' );
wp_enqueue_script( 'wpb_composer_front_js' );
wp_enqueue_style( 'js_composer_custom_css' );

$el_class = $this->getExtraClass( $el_class );

if ( $type == "container" ) {
    $css_row_type = "container";
} else {
    $css_row_type = "cap-section";
}

$css_class = apply_filters( VC_SHORTCODE_CUSTOM_CSS_FILTER_TAG, 'wpb_row ' . get_row_css_class() . $el_class . vc_shortcode_custom_css_class( $css, ' ' ), $this->settings['base'] );
$style = cap_build_style( $bg_image, $bg_color, $bg_image_repeat, $font_color, $padding, $padding_bottom, $padding_top, $margin_bottom );
$output .= '<div class="' . $css_row_type . '">';
$output .= '<div class="' . $css_class . '"' . $style . '>';
if ( $type == "full_page_width_inner_container" ) {
    $output .= '<div class="container">';
}
$output .= wpb_js_remove_wpautop( $content );
if ( $type == "full_page_width_inner_container" ) {
    $output .= '</div>';
}
$output .= '</div>' . $this->endBlockComment( 'row' );
$output .= '</div>';
echo $output;
