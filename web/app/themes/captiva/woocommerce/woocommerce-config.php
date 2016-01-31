<?php
global $captiva_options;

// Dequeue WooCommerce CSS - http://jameskoster.co.uk/snippets/disable-woocommerce-styles/

if ( version_compare( WOOCOMMERCE_VERSION, "2.1" ) >= 0 ) {
    add_filter( 'woocommerce_enqueue_styles', '__return_false' );
} else {
    define( 'WOOCOMMERCE_USE_CSS', false );
}

/* UNREGISTRER DEFAULT WOOCOMMERCE HOOKS */
$priority = has_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta' );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', $priority );

// Add and reorder woocommerce_before_shop_loop 
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_show_messages', 10 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 20 );
remove_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30 );

//add_action( 'woocommerce_before_shop_loop', 'woocommerce_show_messages', 10 );
add_action( 'woocommerce_before_shop_loop', 'wc_print_notices', 10 );
add_action( 'woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 20 );
add_action( 'woocommerce_before_shop_loop', 'captiva_product_toggle', 30 ); // Product List Toggle
add_action( 'woocommerce_before_shop_loop', 'woocommerce_pagination', 40 ); // add pagination above products
add_action( 'woocommerce_before_shop_loop', 'woocommerce_result_count', 50 );

// Add and reorder woocommerce_after_shop_loop
remove_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 10 );
add_action( 'woocommerce_after_shop_loop', 'woocommerce_catalog_ordering', 10 );
add_action( 'woocommerce_after_shop_loop', 'captiva_product_toggle', 20 ); // Product List Toggle
add_action( 'woocommerce_after_shop_loop', 'woocommerce_pagination', 30 ); // add pagination above products
add_action( 'woocommerce_after_shop_loop', 'woocommerce_result_count', 40 );

$productsperpage = '';
if ( isset( $captiva_options['products_page_count'] ) ) {
    $productsperpage = $captiva_options['products_page_count'];
}


// Show/hide product skus
$cap_display_skus = '';
if ( isset( $captiva_options['wc_product_sku'] ) ) {
    $cap_display_skus = $captiva_options['wc_product_sku'];
}

if ( $cap_display_skus == 'no' ) {
    add_filter( 'wc_product_sku_enabled', 'cap_remove_products_sku' );
    function cap_remove_products_sku( $boolean ) {
        if ( is_single() ) {
            $boolean = false;
        }
        return $boolean;
    }    
}


// Number of products per page
if ( $productsperpage ) {
    add_filter( 'loop_shop_per_page', create_function( '$cols', "return $productsperpage;" ), 20 );
}

if ( !function_exists( 'captiva_product_toggle' ) ) {

    function captiva_product_toggle() {
        global $captiva_options;
        $product_layout = $captiva_options['product_layout'];
        if ( $product_layout == 'grid-layout' ):
            ?>
            <div class="view-switcher clearfix">
                <label><?php _e( 'View as:', 'captiva' ); ?></label>
                <div class="toggleGrid"><i class="fa fa-th fa-2x"></i></div>
                <div class="toggleList"><i class="fa fa-list fa-2x"></i></div>
            </div>
        <?php elseif ( $product_layout == 'list-layout' ): ?> 
            <div class="view-switcher clearfix">
                <label><?php _e( 'View as:', 'captiva' ); ?></label>
                <div class="toggleList"><i class="fa fa-list fa-2x"></i></div>
                <div class="toggleGrid"><i class="fa fa-th fa-2x"></i></div>
            </div>
        <?php endif; ?> 

        <?php
    }

}

// Catalogue Mode
function cap_enable_catalog() {
    remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_add_to_cart', 10 );
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
    remove_action( 'woocommerce_simple_add_to_cart', 'woocommerce_simple_add_to_cart', 30 );
    remove_action( 'woocommerce_grouped_add_to_cart', 'woocommerce_grouped_add_to_cart', 30 );
    remove_action( 'woocommerce_variable_add_to_cart', 'woocommerce_variable_add_to_cart', 30 );
    remove_action( 'woocommerce_external_add_to_cart', 'woocommerce_external_add_to_cart', 30 );
}

// Hide prices
function cap_hide_prices() {
    remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_price', 10 );
    remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
}

// Enable catalog mode 
$cap_catalog = '';
if ( isset( $captiva_options['cap_catalog_mode'] ) ) {
    $cap_catalog = $captiva_options['cap_catalog_mode'];
}

// Enable hide prices
$cap_hide_prices = '';
if ( isset( $captiva_options['cap_hide_prices'] ) ) {
    $cap_hide_prices = $captiva_options['cap_hide_prices'];
}

// Live Preview settings
$cap_catmode = '';
$cap_hideprices = '';

if ( !empty( $_SESSION['cap_catmode'] ) ) {
    $cap_catmode = $_SESSION['cap_catmode'];
}

if ( !empty($_SESSION['cap_hideprices'] ) ) {
    $cap_hideprices = $_SESSION['cap_hideprices'];
}

if ( ( $cap_catalog == 'enabled' ) || ( $cap_catmode == 'catmode' ) || ( $cap_hideprices == 'hideprices' ) ) {
    add_action( 'init', 'cap_enable_catalog' );
    if ( ( $cap_hide_prices == 'yes' ) || ( $cap_hideprices == 'hideprices' ) ) {
        add_action( 'init', 'cap_hide_prices' );
    }
}

if ( !function_exists( 'captiva_product_toggle' ) ) {

    function captiva_product_toggle() {
        global $captiva_options;
        $product_layout = $captiva_options['product_layout'];
        if ( $product_layout == 'grid-layout' ):
            ?>
            <div class="view-switcher clearfix">
                <label><?php _e( 'View as:', 'captiva' ); ?></label>
                <div class="toggleGrid"><i class="fa fa-th fa-2x"></i></div>
                <div class="toggleList"><i class="fa fa-list fa-2x"></i></div>
            </div>
        <?php elseif ( $product_layout == 'list-layout' ): ?> 
            <div class="view-switcher clearfix">
                <label><?php _e( 'View as:', 'captiva' ); ?></label>
                <div class="toggleList"><i class="fa fa-list fa-2x"></i></div>
                <div class="toggleGrid"><i class="fa fa-th fa-2x"></i></div>
            </div>
        <?php endif; ?> 

        <?php
    }

}

function captiva_woocommerce_cart_dropdown() {

    global $woo_options;
    global $woocommerce;
    ?>

    <ul class="tiny-cart">
        <li>
            <a class="cart_dropdown_link cart-parent" href="<?php echo $woocommerce->cart->get_cart_url(); ?>" title="<?php _e( 'View your shopping cart', 'captiva' ); ?>">

                <div class="bag-icon"></div>
                <span class='cart_subtotal'><?php echo $woocommerce->cart->get_cart_total(); ?></span>
                <?php
                echo sprintf( _n( '<span class="cart_itemstotal">(%d item)</span>', '<span class="cart_itemstotal">(%d items)</span>', $woocommerce->cart->cart_contents_count, 'captiva' ), $woocommerce->cart->cart_contents_count );
                ?>
            </a>
            <?php
            echo '<ul class="cart_list">';
            // echo '<li class="cart-title"><h3>'.__('Your Cart Contents', 'captiva').'</h3></li>';
            if ( sizeof( $woocommerce->cart->cart_contents ) > 0 ) : foreach ( $woocommerce->cart->cart_contents as $cart_item_key => $cart_item ) :
                    $_product = $cart_item['data'];
                    if ( $_product->exists() && $cart_item['quantity'] > 0 ) :
                        echo '<li class="cart_list_product"><a href="' . get_permalink( $cart_item['product_id'] ) . '">';

                        echo $_product->get_image();

                        echo apply_filters( 'woocommerce_cart_widget_product_title', $_product->get_title(), $_product ) . '</a>';

                        if ( $_product instanceof woocommerce_product_variation && is_array( $cart_item['variation'] ) ) :
                            echo woocommerce_get_formatted_variation( $cart_item['variation'] );
                        endif;

                        echo '<span class="quantities">' . $cart_item['quantity'] . ' &times; ' . woocommerce_price( $_product->get_price() ) . '</span></li>';
                    endif;
                endforeach;

            else: echo '<li class="empty">' . __( 'No products in the cart.', 'captiva' ) . '</li>';
            endif;
            if ( sizeof( $woocommerce->cart->cart_contents ) > 0 ) :
                echo '<li class="total"><strong>';

                if ( get_option( 'js_prices_include_tax' ) == 'yes' ) :
                    _e( 'Total', 'captiva' );
                else :
                    _e( 'Subtotal', 'captiva' );
                endif;

                echo ': </strong>' . $woocommerce->cart->get_cart_total();
                '</li>';

                echo '<li class="buttons"><a href="' . $woocommerce->cart->get_cart_url() . '" class="button">' . __( 'View Cart', 'captiva' ) . '</a> <a href="' . $woocommerce->cart->get_checkout_url() . '" class="button checkout">' . __( 'Checkout', 'captiva' ) . '</a></li>';
            endif;

            echo '</ul>';
            ?>
        </li>
    </ul>
<?php
}

// Handle cart in header fragment for ajax add to cart
add_filter( 'add_to_cart_fragments', 'captiva_header_add_to_cart_fragment' );

if ( !function_exists( 'captiva_header_add_to_cart_fragment' ) ) {

    function captiva_header_add_to_cart_fragment( $fragments ) {
        global $woocommerce;

        ob_start();
        ?>

        <ul class="tiny-cart">
            <li>
                <a class="cart_dropdown_link cart-parent" href="<?php echo $woocommerce->cart->get_cart_url(); ?>" title="<?php _e( 'View your shopping cart', 'captiva' ); ?>">

                    <div class="bag-icon"></div>

                    <span class='cart_subtotal'><?php echo $woocommerce->cart->get_cart_total(); ?></span>
                <?php echo sprintf( _n( '<span class="cart_itemstotal">(%d item)</span>', '<span class="cart_itemstotal">(%d items)</span>', $woocommerce->cart->cart_contents_count, 'captiva' ), $woocommerce->cart->cart_contents_count ); ?>
                </a>
                <?php
                echo '<ul class="cart_list">';
                //echo '<li class="cart-title"><h3>' . __( 'Your Cart Contents', 'captiva' ) . '</h3></li>';
                if ( sizeof( $woocommerce->cart->cart_contents ) > 0 ) : foreach ( $woocommerce->cart->cart_contents as $cart_item_key => $cart_item ) :
                        $_product = $cart_item['data'];
                        if ( $_product->exists() && $cart_item['quantity'] > 0 ) :
                            echo '<li class="cart_list_product"><a href="' . get_permalink( $cart_item['product_id'] ) . '">';

                            echo $_product->get_image();

                            echo apply_filters( 'woocommerce_cart_widget_product_title', $_product->get_title(), $_product ) . '</a>';

                            if ( $_product instanceof woocommerce_product_variation && is_array( $cart_item['variation'] ) ) :
                                echo woocommerce_get_formatted_variation( $cart_item['variation'] );
                            endif;

                            echo '<span class="quantities">' . $cart_item['quantity'] . ' &times; ' . woocommerce_price( $_product->get_price() ) . '</span></li>';
                        endif;
                    endforeach;

                else: echo '<li class="empty">' . __( 'No products in the cart.', 'captiva' ) . '</li>';
                endif;
                if ( sizeof( $woocommerce->cart->cart_contents ) > 0 ) :
                    echo '<li class="total"><strong>';

                    if ( get_option( 'js_prices_include_tax' ) == 'yes' ) :
                        _e( 'Total', 'captiva' );
                    else :
                        _e( 'Subtotal', 'captiva' );
                    endif;

                    echo ':</strong>' . $woocommerce->cart->get_cart_total();
                    '</li>';

                    echo '<li class="buttons"><a href="' . $woocommerce->cart->get_cart_url() . '" class="button">' . __( 'View Cart', 'captiva' ) . '</a> <a href="' . $woocommerce->cart->get_checkout_url() . '" class="button checkout">' . __( 'Checkout', 'captiva' ) . '</a></li>';
                endif;

                echo '</ul>';
                ?>
            </li>
        </ul>
        <?php
        $fragments['ul.tiny-cart'] = ob_get_clean();

        return $fragments;
    }

}


/* Next / Previous on Product Pages
  http://stackoverflow.com/questions/15977615/woocommerce-get-next-previous-product-same-category
 */

function next_post_link_product( $format = '%link &raquo;', $link = '%title', $in_same_cat = false, $excluded_categories = '' ) {
    adjacent_post_link_product( $format, $link, $in_same_cat, $excluded_categories, false );
}

function previous_post_link_product( $format = '&laquo; %link', $link = '%title', $in_same_cat = false, $excluded_categories = '' ) {
    adjacent_post_link_product( $format, $link, $in_same_cat, $excluded_categories, true );
}

function adjacent_post_link_product( $format, $link, $in_same_cat = false, $excluded_categories = '', $previous = true ) {
    if ( $previous && is_attachment() )
        $post = get_post( get_post()->post_parent );
    else
        $post = get_adjacent_post_product( $in_same_cat, $excluded_categories, $previous );

    if ( !$post ) {
        $output = '';
    } else {
        $title = $post->post_title;

        if ( empty( $post->post_title ) )
            $title = $previous ? __( 'Previous Post', 'captiva' ) : __( 'Next Post', 'captiva' );

        $title = apply_filters( 'the_title', $title, $post->ID );

        $feat_image = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );

        $image = $date = mysql2date( get_option( 'date_format' ), $post->post_date );
        $rel = $previous ? 'prev' : 'next';

        $string = '<div class="prod-dropdown"><a href="' . get_permalink( $post ) . '" rel="' . $rel . '" class="';
        $inlink = str_replace( '%title', $title, $link );
        $inlink = $string . $inlink . '"></a><div class="nav-dropdown"><a href="' . get_permalink( $post ) . '">' . get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ) ) . '</a></div></div>';
        $output = str_replace( '%link', $inlink, $format );
    }

    $adjacent = $previous ? 'previous' : 'next';

    echo apply_filters( "{$adjacent}_post_link", $output, $format, $link, $post );
}

function get_adjacent_post_product( $in_same_cat = false, $excluded_categories = '', $previous = true ) {
    global $wpdb;

    if ( !$post = get_post() )
        return null;

    $current_post_date = $post->post_date;
    $join = '';
    $posts_in_ex_cats_sql = '';
    if ( $in_same_cat || !empty( $excluded_categories ) ) {
        $join = " INNER JOIN $wpdb->term_relationships AS tr ON p.ID = tr.object_id INNER JOIN $wpdb->term_taxonomy tt ON tr.term_taxonomy_id = tt.term_taxonomy_id";

        if ( $in_same_cat ) {
            if ( !is_object_in_taxonomy( $post->post_type, 'product_cat' ) )
                return '';
            $cat_array = wp_get_object_terms( $post->ID, 'product_cat', array( 'fields' => 'ids' ) );
            if ( !$cat_array || is_wp_error( $cat_array ) )
                return '';
            $join .= " AND tt.taxonomy = 'product_cat' AND tt.term_id IN (" . implode( ',', $cat_array ) . ")";
        }

        $posts_in_ex_cats_sql = "AND tt.taxonomy = 'product_cat'";
        if ( !empty( $excluded_categories ) ) {
            if ( !is_array( $excluded_categories ) ) {
                // back-compat, $excluded_categories used to be IDs separated by " and "
                if ( strpos( $excluded_categories, ' and ' ) !== false ) {
                    _deprecated_argument( __FUNCTION__, '3.3', sprintf( __( 'Use commas instead of %s to separate excluded categories.', 'captiva' ), "'and'" ) );
                    $excluded_categories = explode( ' and ', $excluded_categories );
                } else {
                    $excluded_categories = explode( ',', $excluded_categories );
                }
            }

            $excluded_categories = array_map( 'intval', $excluded_categories );

            if ( !empty( $cat_array ) ) {
                $excluded_categories = array_diff( $excluded_categories, $cat_array );
                $posts_in_ex_cats_sql = '';
            }

            if ( !empty( $excluded_categories ) ) {
                $posts_in_ex_cats_sql = " AND tt.taxonomy = 'product_cat' AND tt.term_id NOT IN (" . implode( $excluded_categories, ',' ) . ')';
            }
        }
    }

    $adjacent = $previous ? 'previous' : 'next';
    $op = $previous ? '<' : '>';
    $order = $previous ? 'DESC' : 'ASC';

    $join = apply_filters( "get_{$adjacent}_post_join", $join, $in_same_cat, $excluded_categories );
    $where = apply_filters( "get_{$adjacent}_post_where", $wpdb->prepare( "WHERE p.post_date $op %s AND p.post_type = %s AND p.post_status = 'publish' $posts_in_ex_cats_sql", $current_post_date, $post->post_type ), $in_same_cat, $excluded_categories );
    $sort = apply_filters( "get_{$adjacent}_post_sort", "ORDER BY p.post_date $order LIMIT 1" );

    $query = "SELECT p.id FROM $wpdb->posts AS p $join $where $sort";
    $query_key = 'adjacent_post_' . md5( $query );
    $result = wp_cache_get( $query_key, 'counts' );
    if ( false !== $result ) {
        if ( $result )
            $result = get_post( $result );
        return $result;
    }

    $result = $wpdb->get_var( $query );
    if ( null === $result )
        $result = '';

    wp_cache_set( $query_key, $result, 'counts' );

    if ( $result )
        $result = get_post( $result );

    return $result;
}

/* Breadcrumb tweaks */

add_filter( 'woocommerce_breadcrumb_defaults', 'cap_breadcrumb_delimiter' );

function cap_breadcrumb_delimiter( $defaults ) {
    // Change the breadcrumb delimiter from '/' to '>'
    $defaults['delimiter'] = ' <i class="fa fa-angle-right"></i> ';
    return $defaults;
}
?>