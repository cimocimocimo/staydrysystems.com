<?php 

// [captiva_video_banner]

function cap_video_banner($params = array(), $content = null) {
  extract(shortcode_atts(array(
    'bg' => '#222',
    'bg_img' => '',
    'bg_img_type' => '',
    'video_img' => '',
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
    'video_overlay_img' => '',
    'video_webm' => '',
    'video_ogv' => '',
    'video_mp4' => '',

  ), $params));
  
  $hppromo = "";
  $bg_img_type = "fixed";
  $bgcolor = "";
  if (strpos($bg,'#') !== false) {
    $bgcolor = 'background-color:'.$bg.'!important; ';
  }

  $background_image_source = "";
  $background_img = "";
  $bg_img_style = "";
  if ($video_img) {
    $background_image_source = wp_get_attachment_image_src($video_img, '');
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

  $video_banner_style = "";
  if ($video_mp4 || $video_webm || $video_ogv) {
    $video_banner_style = '
    <div class="video-overlay" style="background-image: url(' . CAP_PLUGIN_URL . 'images/flat_cap_vid_bg.png)"></div>
       <video class="captiva-video hidden-sm hidden-xs fillWidth" style="position: absolute; min-width: 100%; min-height: 100%;" preload="auto" autoplay="" loop="loop">
            <source src="' . $video_mp4 .'" type="video/mp4">
            <source src="' . $video_webm .'" type="video/webm">
            <source src="' . $video_ogv .'" type="video/ogg">
       </video>';
  }

  $content = do_shortcode($content);
  $danchor = 'cap-strip-' . rand();

  $open_outer_frame_style_wrap = '';
  $open_outer_frame_style = '';
  $close_outer_frame_style = '';
  $close_outer_frame_style_wrap = '';


  if (( ($bg_img_type) == 'parallax') || ( ($bg_img_type == 'simple') ) || ( ($bg_img_type == 'fixed') )) {
  $hppromo = $open_outer_frame_style_wrap . $open_outer_frame_style . $open_outer_link . '  
    <div class="cap-strip cap-video-wrap cap-strip-wrap fade-in animate" style="'.$bgcolor . $height_style . $margin_bottom_style . '">
      <div class="cap-strip-bg '.$parallax_style.'" style="'.$bg_img_style. $fixed_style .'"'. $parallax_vars .'></div>
        ' . $video_banner_style . '<div class="row">
          <div '. $width_style .' class="cap-pos ' . ' ' . 'valign-' . $valign . ' ' . 'halign-' . $halign . ' ' . $animation_style . '" '. $data_animation_style.'>
            <div class="video-banner-content '. $danchor . ' ' . $color . ' '. $text_align_style . '">'. $content .'</div>
          </div>
        </div>
    </div>' . $close_outer_link . $close_outer_frame_style . $close_outer_frame_style_wrap . '
  '; ?>

    <?php
} 
  return $hppromo;  
}

add_shortcode("captiva_video_banner", "cap_video_banner");
