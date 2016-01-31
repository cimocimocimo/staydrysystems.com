<?php
/**
 * Custom functions that act independently of the theme templates
 *
 * Eventually, some of the functionality here could be replaced by core features
 *
 * @package captiva
 */

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 */
function captiva_page_menu_args( $args ) {
    $args['show_home'] = true;
    return $args;
}

add_filter( 'wp_page_menu_args', 'captiva_page_menu_args' );

/**
 * Adds custom classes to the array of body classes.
 */
function captiva_body_classes( $classes ) {
    global $captiva_options;
    $cap_sticky_menu_class = '';
    $cap_sticky_menu_class = $captiva_options['cap_sticky_menu'];
    
    // Adds a class of group-blog to blogs with more than 1 published author
    if ( is_multi_author() ) {
        $classes[] = 'group-blog';
    }

    //if ( $cap_sticky_menu_class == 'yes' ) {
        $classes[] = 'cap-sticky-enabled';
    //}

    return $classes;
}

add_filter( 'body_class', 'captiva_body_classes' );

/**
 * Filter in a link to a content ID attribute for the next/previous image links on image attachment pages
 */
function captiva_enhanced_image_navigation( $url, $id ) {
    if ( !is_attachment() && !wp_attachment_is_image( $id ) )
        return $url;

    $image = get_post( $id );
    if ( !empty( $image->post_parent ) && $image->post_parent != $id )
        $url .= '#main';

    return $url;
}

add_filter( 'attachment_link', 'captiva_enhanced_image_navigation', 10, 2 );

/**
 * Filters wp_title to print a neat <title> tag based on what is being viewed.
 */
function captiva_wp_title( $title, $sep ) {
    global $page, $paged;

    if ( is_feed() )
        return $title;

    // Add the blog name
    $title .= get_bloginfo( 'name' );

    // Add the blog description for the home/front page.
    $site_description = get_bloginfo( 'description', 'display' );
    if ( $site_description && ( is_home() || is_front_page() ) )
        $title .= " $sep $site_description";

    // Add a page number if necessary:
    if ( $paged >= 2 || $page >= 2 )
        $title .= " $sep " . sprintf( __( 'Page %s', 'captiva' ), max( $paged, $page ) );

    return $title;
}

add_filter( 'wp_title', 'captiva_wp_title', 10, 2 );

function captiva_add_menu_parent( $items ) {
    $parents = array();
    foreach ( $items as $item ) {
        if ( $item->menu_item_parent && $item->menu_item_parent > 0 ) {
            $parents[] = $item->menu_item_parent;
        }
    }
    return $items;
}

add_filter( 'wp_nav_menu_objects', 'captiva_add_menu_parent' );


/* Boxed class */
if ( !function_exists( 'boxed_class' ) ) {

    function boxed_class( $classes ) {
        global $captiva_options;
        $cap_boxed_status = '';

        if (!empty($_SESSION['cap_boxed'])){
            $cap_boxed_status = $_SESSION['cap_boxed'];
        }

        if ( ( isset( $captiva_options['container_style'] ) && $captiva_options['container_style'] == 'boxed' ) || ( $cap_boxed_status == 'yes' ) ) :
            $classes[] = 'boxed';
        else:
            $classes[] = "";
        endif;

        return $classes;
    }

}

add_filter( 'body_class', 'boxed_class' );

// Initialize some global js vars
add_action( 'wp_head', 'captiva_js_init' );
if ( !function_exists( 'captiva_js_init' ) ) {

    function captiva_js_init() {
        global $captiva_options;
        ?>
        <script type="text/javascript">
            var view_mode_default = '<?php echo $captiva_options['product_layout'] ?>';
            var cap_sticky_default = '<?php echo $captiva_options['cap_sticky_menu'] ?>';
        </script>
        <?php
    }

}

// Util function for building VC row styles - replaces default VC buildstyle function

if ( !function_exists( 'cap_build_style' ) ) {

    function cap_build_style( $bg_image = '', $bg_color = '', $bg_image_repeat = '', $font_color = '', $padding = '', $padding_bottom = '', $padding_top = '', $margin_bottom = '' ) {
        $has_image = false;
        $style = '';
        if ( ( int ) $bg_image > 0 && ( $image_url = wp_get_attachment_url( $bg_image, 'large' ) ) !== false ) {
            $has_image = true;
            $style .= "background-image: url(" . $image_url . ");";
        }
        if ( !empty( $bg_color ) ) {
            $style .= 'background-color: ' . $bg_color . ';';
        }
        if ( !empty( $bg_image_repeat ) && $has_image ) {
            if ( $bg_image_repeat === 'cover' ) {
                $style .= "background-repeat:no-repeat;background-size: cover;";
            } elseif ( $bg_image_repeat === 'contain' ) {
                $style .= "background-repeat:no-repeat;background-size: contain;";
            } elseif ( $bg_image_repeat === 'no-repeat' ) {
                $style .= 'background-repeat: no-repeat;';
            }
        }
        if ( !empty( $font_color ) ) {
            $style .= 'color: ' . $font_color . ';';
        }
        if ( $padding != '' ) {
            $style .= 'padding: ' . ( preg_match( '/(px|em|\%|pt|cm)$/', $padding ) ? $padding : $padding . 'px' ) . ';';
        }
        if ( $padding_bottom != '' ) {
            $style .= 'padding-bottom: ' . ( preg_match( '/(px|em|\%|pt|cm)$/', $padding_bottom ) ? $padding_bottom : $padding_bottom . 'px' ) . ';';
        }
        if ( $padding_top != '' ) {
            $style .= 'padding-top: ' . ( preg_match( '/(px|em|\%|pt|cm)$/', $padding_top ) ? $padding_top : $padding_top . 'px' ) . ';';
        }
        if ( $margin_bottom != '' ) {
            $style .= 'margin-bottom: ' . ( preg_match( '/(px|em|\%|pt|cm)$/', $margin_bottom ) ? $margin_bottom : $margin_bottom . 'px' ) . ';';
        }
        return empty( $style ) ? $style : ' style="' . $style . '"';
    }

}

// Hi themeforest review team! This is a safe filter for cleaning up captiva shortcode output only!
// Credits to bitfade for this solution - https://gist.github.com/bitfade/4555047
// Ref - http://themeforest.net/forums/thread/how-to-add-shortcodes-in-wp-themes-without-being-rejected/98804?page=4#996848

add_filter( "the_content", "captiva_content_filter" );

function captiva_content_filter( $content ) {

    // array of custom shortcodes requiring the fix
    $block = join( "|", array( "captiva_content_strip", "vc_button", "captiva_video_banner" ) );

    // opening tag
    $rep = preg_replace( "/(<p>)?\[($block)(\s[^\]]+)?\](<\/p>|<br \/>)?/", "[$2$3]", $content );

    // closing tag
    $rep = preg_replace( "/(<p>)?\[\/($block)](<\/p>|<br \/>)?/", "[/$2]", $rep );

    return $rep;
}
