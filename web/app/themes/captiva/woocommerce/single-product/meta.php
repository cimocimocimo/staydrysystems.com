<?php
/**
 * Single Product Meta
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */
if ( !defined( 'ABSPATH' ) )
    exit; // Exit if accessed directly

global $post, $product, $captiva_options;
;

$cat_count = sizeof( get_the_terms( $post->ID, 'product_cat' ) );
$tag_count = sizeof( get_the_terms( $post->ID, 'product_tag' ) );
$meta_icons = $captiva_options['product_share_icons'];
?>
<div class="product_meta">
    <div class="divider"></div>

    <?php do_action( 'woocommerce_product_meta_start' ); ?>

    <?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>

        <span class="sku_wrapper"><?php _e( 'SKU:', 'woocommerce' ); ?> <span class="sku" itemprop="sku"><?php echo ( $sku = $product->get_sku() ) ? $sku : __( 'N/A', 'woocommerce' ); ?></span>.</span>

    <?php endif; ?>

    <?php echo $product->get_categories( ', ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', $cat_count, 'woocommerce' ) . ' ', '.</span>' ); ?>

    <?php echo $product->get_tags( ', ', '<span class="tagged_as">' . _n( 'Tag:', 'Tags:', $tag_count, 'woocommerce' ) . ' ', '.</span>' ); ?>

    <?php do_action( 'woocommerce_product_meta_end' ); ?>

</div>

<?php if ( $meta_icons == 'yes' ) { ?>
    <div class="social-icons">

        <a class="facebook-icon" href="http://www.facebook.com/sharer.php?u=<?php echo get_permalink(); ?>" target="_blank"><span class="icon-facebook"></span></a>

        <a class="twitter-icon" href="https://twitter.com/share?url=<?php echo get_permalink(); ?>" target="_blank"><span class="icon-twitter"></span></a>

        <a class="pinterest-icon" href="//pinterest.com/pin/create/button/?url=<?php echo get_permalink(); ?>&amp;description=<?php echo get_the_title(); ?>" target="_blank"><span class="icon-pinterest"></span></a>

        <a class="googleplus-icon" href="//plus.google.com/share?url=<?php echo get_permalink(); ?>" target="_blank"><span class="fa fa-google-plus"></span></a>

    </div>
<?php } ?>