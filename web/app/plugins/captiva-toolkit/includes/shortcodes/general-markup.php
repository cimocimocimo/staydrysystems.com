<?php

// [captiva_dropcap]
if( ! function_exists( 'captiva_dropcap' ) ) :
function captiva_dropcap( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'style' => 'normal',
		'font_size' => '50px'
	), $atts ) );

	return "<span class=\"captiva-dropcap captiva-dropcap--$style\" style=\"font-size: $font_size; line-height: $font_size; width: $font_size; height: $font_size;\">". do_shortcode( $content ) ."</span>";
}
add_shortcode( 'captiva_dropcap', 'captiva_dropcap' );
endif;

// [captiva_divider]
if( ! function_exists( 'captiva_divider' ) ) :
function captiva_divider( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'style' => 'plain'
	), $atts ) );
	return '<hr class="captiva-divider captiva-divider--'.$style.'">';
}
add_shortcode( 'captiva_divider', 'captiva_divider' );
endif;

// [captiva_social_icons]

if (!function_exists('captiva_social_icons')) {
function captiva_social_icons($atts, $content = null) {
    $args = array(
        "icon"                              => "",
        "url"                              => "",
    );

    extract(shortcode_atts($args, $atts));

    $output 			= "";
    $output .= "<span class='captiva-social-icon-container'>";

    if($url != ""){
    	$output .= "<a href='".$url."'>";
    }

    $output .= "<i class='fa ".$icon."'></i>";    

    if($url != ""){
    	$output .= "</a>";
    }

    $output .= "</span>"; 
    return $output;
}
}
add_shortcode('captiva_social_icons', 'captiva_social_icons');


/* Person Profile shortcode */

if (!function_exists('captiva_person')) {
function captiva_person($atts, $content = null) {
    $args = array(
		"person_img"				=> "",
		"person_name"				=> "",
		"person_title"				=> "",
		"person_desc"				=> "",
		"person_social_icon_1"		=> "",
		"person_social_icon_1_url"	=> "",
		"person_social_icon_2"		=> "",
		"person_social_icon_2_url"	=> "",
		"person_social_icon_3"		=> "",
		"person_social_icon_3_url"	=> "",
		"person_social_icon_4"		=> "",
		"person_social_icon_4_url"	=> "",
		"person_social_icon_5"		=> "",
		"person_social_icon_5_url"	=> "",		
    );
        
    extract(shortcode_atts($args, $atts));
	if(is_numeric($person_img)) {
		$person_img_src = wp_get_attachment_url( $person_img );
	} else {
		$person_img_src = $person_img;
	}
 
    $output =  "<div class='captiva-person'>";
		$output .=  "<div class='captiva-person-inner'>";
			if($person_img != "") {
				$output .=  "<div class='captiva-person-img'>";
					$output .=  "<img src='$person_img_src' alt='' />";
				$output .=  "</div>";
			}
			$output .=  "<div class='captiva-person-text'>";
				$output .=  "<div class='captiva-person-text-inner'>";
					$output .=  "<div class='captiva-team-title-container'>";
						$output .=  "<h4 class='captiva-person-name'>";
							$output .= $person_name;
						$output .=  "</h4>";
						if($person_title != "") {
							$output .= "<span class='captiva-person-title'>" . $person_title . "</span>";
						}
					$output .=  "</div>";
					$output .=  "<p class='captiva-person-desc'>" . $person_desc . "</p>";
				$output .=  "</div>";
				$output .=  "<div class='captiva-person-social-container'>";
				if($person_social_icon_1 != "") {
					$output .=  do_shortcode('[captiva_social_icons icon="'. $person_social_icon_1 .'" size="fa-lg" url="' . $person_social_icon_1_url  . '"]');
				}
				if($person_social_icon_2 != "") {
					$output .=  do_shortcode('[captiva_social_icons icon="'. $person_social_icon_2 .'" size="fa-lg" url="' . $person_social_icon_2_url  . '"]');
				}
				if($person_social_icon_3 != "") {
					$output .=  do_shortcode('[captiva_social_icons icon="'. $person_social_icon_3 .'" size="fa-lg" url="' . $person_social_icon_3_url  . '"]');
				}
				if($person_social_icon_4 != "") {
					$output .=  do_shortcode('[captiva_social_icons icon="'. $person_social_icon_4 .'" size="fa-lg" url="' . $person_social_icon_4_url  . '"]');
				}
				if($person_social_icon_5 != "") {
					$output .=  do_shortcode('[captiva_social_icons icon="'. $person_social_icon_5 .'" size="fa-lg" url="' . $person_social_icon_5_url  . '"]');
				}
				
				$output .=  "</div>";
			$output .=  "</div>";
		$output .=  "</div>";
	$output .=  "</div>";
    return $output;

}
}
add_shortcode('captiva_person', 'captiva_person');

// [captiva_services]
function captiva_services_shortcode( $params = array(), $content = null ) {
	extract( shortcode_atts( array(
				'image_url' => '',
				'title' => 'Title',
				'link_name' => '',
				'link_url' => ''
			), $params ) );

	$content = do_shortcode( $content );
	$our_services = '
		<div class="captiva-services">
			<div class="captiva-services-img-wrap"><img src="'.$image_url.'" alt="" /></div>
			<h4>'.$title.'</h4>
			<p>'.$content.'</p>
			<a href="'.$link_url.'">'.$link_name.'</a>
		</div>
	';
	return $our_services;
}
add_shortcode( 'captiva_services', 'captiva_services_shortcode' );

// [captiva_card]
if( ! function_exists( 'captiva_card' ) ) :
function captiva_card( $atts, $content = null ) {
	extract( shortcode_atts( array(
		'type' => 'visa',
	), $atts ) );


    $output = "";
    $output .= "<span class='captiva-card'>";

    if($type != ""){
    	if ($type == 'visa') {
    		$output .= '<img src="'. CAP_PLUGIN_URL .'images/cc_visa.png" alt="Visa">';
    	} 
    	elseif ($type == 'mastercard') {
    		$output .= '<img src="'. CAP_PLUGIN_URL .'images/cc_mc.png" alt="Mastercard">';
    	}
    	elseif ($type == 'amex') {
    		$output .= '<img src="' . CAP_PLUGIN_URL . 'images/cc_amex.png" alt="American Express">';
    	}
    	elseif ($type == 'paypal') {
    		$output .= '<img src="' . CAP_PLUGIN_URL . 'images/cc_paypal.png" alt="Paypal">';
    	}
    }

    $output .= "</span>"; 
    return $output;
}
add_shortcode( 'captiva_card', 'captiva_card' );
endif;


function titlewrap_shortcode( $atts, $content = null ) {

	return '<div class="titlewrap">' . do_shortcode( $content ) . '</div>';

}
add_shortcode( 'titlewrap', 'titlewrap_shortcode' );

// [section]
function section_shortcode($params = array(), $content = null) {

	$formatting = array (
	            '&nbsp;' => '', 
	            '<p></p>' => '', 
	            '<p>[' => '[', 
	            ']</p>' => ']', 
	            ']<br />' => ']',
	           	'<br>[' => '[',
	);
	$content = strtr($content, $formatting);
	$content = do_shortcode($content);
	$container = '<section>'.$content.'</section>';
	return $container;
} 
add_shortcode( 'section', 'section_shortcode' );

function container_shortcode( $atts, $content = null ) {

	return '<div class="container">' . do_shortcode( $content ) . '</div>';

}
add_shortcode( 'container', 'container_shortcode' );

function rowpc80_shortcode( $atts, $content = null ) {

	return '<div class="row pc80">' . do_shortcode( $content ) . '</div>';

}
add_shortcode( 'row80pc', 'rowpc80_shortcode' );

function third_shortcode( $atts, $content = null ) {

	return '<div class="col-lg-4">' . do_shortcode( $content ) . '</div>';

}
add_shortcode( 'athird', 'third_shortcode' );


function one_third_shortcode( $atts, $content = null ) {

	return '<div class="col-ld-4">' . do_shortcode( $content ) . '</div>';

}
add_shortcode( 'one_third', 'one_third_shortcode' );



