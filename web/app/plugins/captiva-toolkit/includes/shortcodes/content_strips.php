<?php 

// [captiva_promo]

function cap_content_strips($params = array(), $content = null) {
  extract(shortcode_atts(array(
    'bg' => '#222',
    'bg_img' => '',
    'bg_img_type' => '',
    'fixed' => '',
    'height' => '',
    'outer_frame' => '',
    'outer_link' => '',
    'valign' => 'center',
    'halign' => 'center',
    'width' => '50%',
    'text_align' => 'center',
    'color' => 'light',
    'margin_bottom' => '',
    'animation' => 'none',
    'text_fade_out' => 'yes',
    'custom_content_css' => '',
  ), $params));
  
  $bgcolor = "";
  if (strpos($bg,'#') !== false) {
    $bgcolor = 'background-color:'.$bg.'!important; ';
  }

  $background_image_source = "";
  $background_img = "";
  $bg_img_style = "";
  if ($bg_img) {
    $background_image_source = wp_get_attachment_image_src($bg_img, '');
    $background_img = $background_image_source[0];
    $bg_img_style = 'background-image:url('.$background_img.'); ';
  }

  $height_style = "";
  if ($height) {
    $height_style = 'height:' . $height .'; ';
  }

  $open_outer_link = "";
  $close_outer_link = "";
  if ($outer_link) {
    $open_outer_link = '<a href="' . $outer_link .'">';
    $close_outer_link = '</a>';
  } 

  $fixed_style = "";
  $parallax_vars = "";
  $parallax_style = "";
  if ($bg_img_type == 'parallax') {
    $parallax_style = 'cap_parallax';
    $parallax_vars = 'data-center="background-position: 50% 50%;" data-top-bottom="background-position: 50% 0%" data-bottom-top="background-position: 50% 95%"';
  } else if ($bg_img_type == 'fixed') {
    $fixed_style = '';
  }

  $width_style = '';
  if ($width) {
    $width_style = 'style="width:' . $width . '" ';
  }

  $text_align_style = '';
  if ($text_align) {
    $text_align_style = 'text-align-' . $text_align;
  }

  $margin_bottom_style = "";
  if ($margin_bottom) {
    $margin_bottom_style = 'margin-bottom: ' . $margin_bottom .'; ';
  } 

  $animation_style = "";
  $data_animation_style = "";
  if ($animation !== "none") {
    $animation_style = 'animate';
    $data_animation_style = 'data-animate="' . $animation .'"';
  }

  $custom_css_style = "cap-strip-content";
  if ($custom_content_css) {
    $custom_css_style = $custom_content_css;
  }

  $danchor = 'cap-strip-' . rand();

  $text_fade_style = "";
  if ($text_fade_out == "yes") {
    $text_fade_style = 'data-center-bottom="opacity: 1" data--150-top="opacity: 0" data-anchor-target=".' .$danchor . '"';
  }

  $content = do_shortcode($content);

  if (( ($bg_img_type) == 'parallax') || ( ($bg_img_type == 'simple') )) {
  $hppromo = $open_outer_link . '  
    <div class="cap-strip cap-strip-wrap fade-in animate" style="'.$bgcolor . $height_style . $margin_bottom_style . '">
      <div class="cap-strip-bg '.$parallax_style.'" style="'.$bg_img_style. $fixed_style .'"'. $parallax_vars .'></div>
        <div class="row">
          <div '. $width_style .' class="cap-pos ' . ' ' . 'valign-' . $valign . ' ' . 'halign-' . $halign . ' ' . $animation_style . '" '. $data_animation_style.'>
            <div class="'. $custom_css_style . ' ' . $danchor . ' ' . $color . ' '. $text_align_style . '"' . $text_fade_style .'>'. $content .'</div>
          </div>
        </div>
    </div>' . $close_outer_link . '
  '; } else {
  $hppromo = $open_outer_link . '  
    <div class="cap-strip cap-strip-wrap fade-in animate" style="'.$bgcolor . $height_style . $margin_bottom_style . '">
      <div class="cap-strip-bg" style="'.$bg_img_style. $fixed_style .'"></div>
        <div class="row">
          <div '. $width_style .' class="cap-pos ' . ' ' . 'valign-' . $valign . ' ' . 'halign-' . $halign . ' ' . $animation_style . '" '. $data_animation_style.'>
            <div class="'. $custom_css_style . ' ' . $danchor . ' ' . $color . ' '. $text_align_style . '"' . $text_fade_style .'>'. $content .'</div>
          </div>
        </div>
    </div>' . $close_outer_link . '
  ';      
  }
  return $hppromo;
}

add_shortcode("captiva_content_strip", "cap_content_strips");
