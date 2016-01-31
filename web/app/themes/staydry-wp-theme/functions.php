<?php

require_once( TEMPLATEPATH . '/cimo_paypal_options.php' );
require_once( TEMPLATEPATH . '/staydry_package_contents.php' );
require_once( TEMPLATEPATH . '/staydry_product_desc_bullets.php' );


/* disables the admin dashboard widgets I don't want
got this code from:
http://codex.wordpress.org/Dashboard_Widgets_API
*/

function remove_dashboard_widgets() {
	// Globalize the metaboxes array, this holds all the widgets for wp-admin
	global $wp_meta_boxes;

//	unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_right_now'] );
	unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_recent_comments'] );
	unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_incoming_links'] );
	unset( $wp_meta_boxes['dashboard']['normal']['core']['dashboard_plugins'] );
//	unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_quick_press'] );
	unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_recent_drafts'] );
	unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_primary'] );
	unset( $wp_meta_boxes['dashboard']['side']['core']['dashboard_secondary'] );
} 

// Hoook into the 'wp_dashboard_setup' action to register our function
add_action('wp_dashboard_setup', 'remove_dashboard_widgets' );


/* this removes some stuff in the header I don't need for this theme
Only needed if you are publishing to the blog from Windows Live Writer
http://lancesjournal.com/remove-rel-edituri-and-rel-wlwmanifest-links/
*/
add_action('init', 'remheadlink');
function remheadlink() {
	remove_action('wp_head', 'rsd_link');
	remove_action('wp_head', 'wlwmanifest_link');
}

/* remove the meta generator tag from the header */
remove_action('wp_head', 'wp_generator');


remove_action( 'wp_head', 'index_rel_link' ); // index link
remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 ); // prev link
remove_action( 'wp_head', 'start_post_rel_link', 10, 0 ); // start link
remove_action( 'wp_head', 'adjacent_posts_rel_link', 10, 0 ); // Display relational links for the posts adjacent to the current post.



function product_faq() {
    global $post;
    
    switch ($post->post_name)
    {
        case 'collapsible-shower-water-dam':
                $curr_prod_tag = 'collapsible-water-dam';            
            break;
        
        case 'shower-curtain-sealer':
                $curr_prod_tag = 'seal-it-curtain-sealer';            
            break;
        
        case 'deluxe-shower-curtain-kit':
        case 'enviro-shower-curtain-kit':
                $curr_prod_tag = 'sealtight-shower-curtain';            
            break;
            
        default:
            //code
            break;
    }
    
    $args = array(
            'category_name' => 'faq',
            'tag' => $curr_prod_tag,
            'numberposts' => -1,
        );
    $faq_posts = get_posts($args);

    $output = '<div id="faq">';

    $output .= '<h3>Frequently Asked Questions</h3>';

    foreach ($faq_posts as $question)
    {
        $output .= '<h4>' . $question->post_title . '</h4>';
        $output .= '<p>' . $question->post_content . '</p>';
    }

    $output .= '</div>';

    return $output;
}


/**
 * setup google merchent center product feed
 * http://www.seodenver.com/custom-rss-feed-in-wordpress/
 * 
 * @author Aaron Cimolini
 * @param $param
 * @return return type
 */
function google_product_feed_setup()
{
    load_template( TEMPLATEPATH . '/google-product-feed-2.php');
}

add_action('do_feed_product-feed', 'google_product_feed_setup', 10, 1);


function custom_feed_rewrite($wp_rewrite) {
    $feed_rules = array(
        'feed/(.+)' => 'index.php?feed=' . $wp_rewrite->preg_index(1),
        '(.+).xml' => 'index.php?feed='. $wp_rewrite->preg_index(1)
    );
    $wp_rewrite->rules = $feed_rules + $wp_rewrite->rules;
}
add_filter('generate_rewrite_rules', 'custom_feed_rewrite');



/** Tell WordPress to run twentyten_setup() when the 'after_setup_theme' hook is run. */
add_action( 'after_setup_theme', 'twentyten_setup' );

if ( ! function_exists( 'twentyten_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override twentyten_setup() in a child theme, add your own twentyten_setup to your child theme's
 * functions.php file.
 *
 * @uses add_theme_support() To add support for post thumbnails and automatic feed links.
 * @uses register_nav_menus() To add support for navigation menus.
 * @uses add_custom_background() To add support for a custom background.
 * @uses add_editor_style() To style the visual editor.
 * @uses add_custom_image_header() To add support for a custom header.
 * @uses register_default_headers() To register the default custom header images provided with the theme.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 */
function twentyten_setup() {

	// enable post thumbnails
	add_theme_support( 'post-thumbnails' );
	
	add_theme_support('automatic-feed-links');

	// Set up the main nav menu used in the site header
	register_nav_menus( array(
		'primary' => 'Primary Navigation',
	) );

}
endif;




/**
 * Sets the post excerpt length to 40 characters.
 *
 * To override this length in a child theme, remove the filter and add your own
 * function tied to the excerpt_length filter hook.
 *
 * @return int
 */
function twentyten_excerpt_length( $length ) {
	return 40;
}
add_filter( 'excerpt_length', 'twentyten_excerpt_length' );



/**
 * Remove inline styles printed when the gallery shortcode is used.
 *
 * Galleries are styled by the theme in Twenty Ten's style.css.
 *
 * @return string The gallery style filter, with the styles themselves removed.
 */
function twentyten_remove_gallery_css( $css ) {
	return preg_replace( "#<style type='text/css'>(.*?)</style>#s", '', $css );
}
add_filter( 'gallery_style', 'twentyten_remove_gallery_css' );



/**
 * Register widgetized areas, including two sidebars and four widget-ready columns in the footer.
 *
 * To override twentyten_widgets_init() in a child theme, remove the action hook and add your own
 * function tied to the init hook.
 *
 * @uses register_sidebar
 */
function twentyten_widgets_init() {
	// Area 1, located at the top of the sidebar.
	register_sidebar( array(
		'name' => __( 'Primary Widget Area', 'twentyten' ),
		'id' => 'primary-widget-area',
		'description' => __( 'The primary widget area', 'twentyten' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 2, located below the Primary Widget Area in the sidebar. Empty by default.
	register_sidebar( array(
		'name' => __( 'Secondary Widget Area', 'twentyten' ),
		'id' => 'secondary-widget-area',
		'description' => __( 'The secondary widget area', 'twentyten' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 3, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'First Footer Widget Area', 'twentyten' ),
		'id' => 'first-footer-widget-area',
		'description' => __( 'The first footer widget area', 'twentyten' ),
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 4, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Second Footer Widget Area', 'twentyten' ),
		'id' => 'second-footer-widget-area',
		'description' => __( 'The second footer widget area', 'twentyten' ),
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 5, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Third Footer Widget Area', 'twentyten' ),
		'id' => 'third-footer-widget-area',
		'description' => __( 'The third footer widget area', 'twentyten' ),
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 6, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Fourth Footer Widget Area', 'twentyten' ),
		'id' => 'fourth-footer-widget-area',
		'description' => __( 'The fourth footer widget area', 'twentyten' ),
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
/** Register sidebars by running twentyten_widgets_init() on the widgets_init hook. */
add_action( 'widgets_init', 'twentyten_widgets_init' );




/* These are my functions
see if I can do the same thing with filters, I think that's how I supposed to do it.
*/

/*
 * Fix the extra 10 pixel width issue for image captions
 * http://wordpress.org/support/topic/234109
 */
add_shortcode('wp_caption', 'fixed_img_caption_shortcode');
add_shortcode('caption', 'fixed_img_caption_shortcode');
function fixed_img_caption_shortcode($attr, $content = null) {
  // Allow plugins/themes to override the default caption template.
  $output = apply_filters('img_caption_shortcode', '', $attr, $content);
  if ( $output != '' ) return $output;
  extract(shortcode_atts(array(
			       'id'=> '',
			       'align'=> 'alignnone',
			       'width'=> '',
			       'caption' => ''), $attr));
  if ( 1 > (int) $width || empty($caption) )
    return $content;
  if ( $id ) $id = 'id="' . esc_attr($id) . '" ';
  return '<div ' . $id . 'class="wp-caption ' . esc_attr($align)
    . '" style="width: ' . ((int) $width) . 'px">'
    . do_shortcode( $content ) . '<p class="wp-caption-text">'
    . $caption . '</p></div>';
}

/*
 * This overrides the default gallery shortcode in WP and subs in our own. This allows us to format the html for the gallery as we like.
 * http://wpengineer.com/a-solution-for-the-wordpress-gallery/
 */

//deactivate WordPress function
remove_shortcode('gallery', 'gallery_shortcode');
 
//activate own function
add_shortcode('gallery', 'wpe_gallery_shortcode');
 
//the own renamed function
function wpe_gallery_shortcode($attr) {
  	global $post;

	static $instance = 0;
	$instance++;

	// Allow plugins/themes to override the default gallery template.
	$output = apply_filters('post_gallery', '', $attr);
	if ( $output != '' )
		return $output;

	// We're trusting author input, so let's at least make sure it looks like a valid orderby statement
	if ( isset( $attr['orderby'] ) ) {
		$attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
		if ( !$attr['orderby'] )
			unset( $attr['orderby'] );
	}

	extract(shortcode_atts(array(
		'order'      => 'ASC',
		'orderby'    => 'menu_order ID',
		'id'         => $post->ID,
		'itemtag'    => 'dl',
		'icontag'    => 'dt',
		'captiontag' => 'dd',
		'columns'    => 3,
		'size'       => 'thumbnail',
		'include'    => '',
		'exclude'    => ''
		), $attr));

	$id = intval($id);
	if ( 'RAND' == $order )
		$orderby = 'none';

	if ( !empty($include) ) {
		$include = preg_replace( '/[^0-9,]+/', '', $include );
		$_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

		$attachments = array();
		foreach ( $_attachments as $key => $val ) {
			$attachments[$val->ID] = $_attachments[$key];
		}
	} elseif ( !empty($exclude) ) {
		$exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
		$attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
	} else {
	  $attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', /*'post_mime_type' => 'image',*/ 'order' => $order, 'orderby' => $orderby) );
	}

	if ( empty($attachments) )
		return '';

	if ( is_feed() ) {
		$output = "\n";
		foreach ( $attachments as $att_id => $attachment )
			$output .= wp_get_attachment_link($att_id, $size, true) . "\n";
		return $output;
	}

	// check to see if tags have been set to false. If they are the defaults or have been set to a string value use that as the tag.
	if ($itemtag) $itemtag = tag_escape($itemtag);
	if ($captiontag) $captiontag = tag_escape($captiontag);
	if ($icontag) $icontag = tag_escape($icontag);
	$columns = intval($columns);

	$selector = "gallery-{$instance}";

	$output = apply_filters('gallery_style', "<div id='$selector' class='gallery galleryid-{$id}'>\n");

	$i = 0;
	foreach ( $attachments as $id => $attachment ) {
	  ++$i;
		$link = isset($attr['link']) && 'file' == $attr['link'] ? wp_get_attachment_link($id, $size, false, true) : wp_get_attachment_link($id, $size, true, true);

		if ($itemtag) {
		  $output .= "\t<{$itemtag} class='gallery-item ";
		  if ( $columns > 0 && $i % $columns == 0) $output .= "last";
		  $output .= '\'>';
		}
		if ($icontag) $output .= "<{$icontag} class='gallery-icon'>";
		$output .= "\n\t\t" . $link . "\n";
		if ($icontag) $output .= "</{$icontag}>";
		// if the attachment has a caption set
		if ( trim($attachment->post_excerpt) ) {
		  if ($captiontag) $output .= "\t\t<{$captiontag} class='gallery-caption small'>";
		  $output .= wptexturize($attachment->post_excerpt);
		  if ($captiontag) $output .= "</{$captiontag}>" . "<!-- end caption -->\n";
		}
		if ($itemtag) $output .= "\t</{$itemtag}>" . "<!-- end itemtag -->\n";
		if ( $columns > 0 && $i % $columns == 0 ) $output .= "<br />\n";
	}

	$output .= "</div><!-- end gallery -->\n";

	return $output;
}

// if we have the show_top_bar cookie, get it's value,
if ( $_COOKIE['show_top_bar'] ) {
    $value = $_COOKIE['show_top_bar'];
    if ($value == 'true') {
        $show_top_bar = true;
    } else {
        $show_top_bar = false;
    }
} else { // or create a cookie, set it to true, and set the $show_top_bar to true
    setcookie('show_top_bar', 'true', time()+3600, '/');
    $show_top_bar = true;
}

// if the query string var top_bar has been set get it's value
if ( $_GET['top_bar'] ) {
    $top_bar_action = $_GET['top_bar'];
    if ( $top_bar_action == 'show' ) {
        setcookie('show_top_bar', 'true', time()+3600, '/');
        $show_top_bar = true;
    } elseif ( $top_bar_action == 'hide' ) {
        setcookie('show_top_bar', 'false', time()+3600, '/');
        $show_top_bar = false;
    }
}

function top_bar() {
?>
    <div id="message">
        <div class="content">
            Important: Shipping affected by Canada Post strike. Click here for more information.
            <a href="/canada-post-strike/?top_bar=hide" class="block-link">More info</a>
        </div>
        <div id="close-top-bar">
            <a href="?top_bar=hide" id="close-top-bar-button">x</a>
        </div>
    </div>
<?php
}

function top_bar_expand_button() {
?>
    <div id="top-bar-expand-button">
        <a href="?top_bar=show">+</a>
    </div>
<?php
}


// random testimonial shortcode
function random_testimonial_func( $atts ) {
	extract( shortcode_atts( array(
		'purchase' => 'collapsible-water-dam',
		'align' => 'left'
	), $atts ) );
    
    $align = 'align' . $align;            

    static $post_ids_used = array();
    
    $args = array(
        'numberposts' => 1,
        'category' => $purchase,
        'post_type' => 'customer',
        'orderby' => 'rand'
    );

    do {
        $customer_post = get_posts($args);
        $current_customer_id = $customer_post[0]->ID;
    } while (in_array($current_customer_id, $post_ids_used));
    $post_ids_used[] = $current_customer_id;
    
    $quote_attribution = get_post_meta($current_customer_id, 'testimonial_attribution', true) ;


    $html='<div class="block testimonial %s">
        <div class="quote">
            %s
        </div>
        <div class="attribution">
            <div class="name">%s</div>
            <div class="organization">%s</div>
        </div>
    </div>
    ';
    
    $quote = $customer_post[0]->post_excerpt;
    $organization = $customer_post[0]->post_title;

    return sprintf($html, $align, $quote, $quote_attribution, $organization);
}
add_shortcode( 'rand_test', 'random_testimonial_func' );

?>