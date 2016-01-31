<?php
/**
 * The template for displaying product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author 	WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */
global $product, $woocommerce_loop, $captiva_options;
$cap_product_flip = '';
$cap_product_flip = $captiva_options['cap_product_thumb_flip'];
$cap_attachment_ids = $product->get_gallery_attachment_ids();

// Store loop count we're currently on
if ( empty( $woocommerce_loop['loop'] ) )
    $woocommerce_loop['loop'] = 0;

// Store column count for displaying the grid
if ( empty( $woocommerce_loop['columns'] ) )
    $woocommerce_loop['columns'] = apply_filters( 'loop_shop_columns', 4 );

// Ensure visibilty
if ( !$product->is_visible() )
    return;

// Increase loop count
$woocommerce_loop['loop'] ++;
$grid_count = $captiva_options['product_grid_count'];
?>

<li class="product cap-product-wrap">	

    <?php do_action( 'woocommerce_before_shop_loop_item' ); ?>

    <div class="cap-product-img">
       <a href="<?php the_permalink(); ?>">	
            <div class="first-flip"><?php echo get_the_post_thumbnail( $post->ID, 'shop_catalog' ) ?></div>        
            <?php
            if (( $cap_attachment_ids ) && ( $cap_product_flip == 'enabled' ) ) {

                $loop = 0;

                foreach ( $cap_attachment_ids as $cap_attachment_id ) {

                    $imgsrc = wp_get_attachment_url( $cap_attachment_id );

                    if ( !$imgsrc )
                        continue;

                    $loop++;

                    printf( '<div class="back-flip back">%s</div>', wp_get_attachment_image( $cap_attachment_id, 'shop_catalog' ) );

                    if ( $loop == 1 )
                        break;
                }
            } else {
                ?>

                <div class="back-flip"><?php echo get_the_post_thumbnail( $post->ID, 'shop_catalog' ) ?></div>

                <?php
            }
            ?>
            </a>
            <div class="cap-product-cta"><!-- start after shop loop item -->
                <?php do_action( 'woocommerce_after_shop_loop_item' ); ?><!-- end after shop loop item -->
            </div>
    </div>
<div class="cap-product-meta-wrap">
    <div class="cap-product-info">
        <a href="<?php the_permalink(); ?>">
            <?php $product_cats = strip_tags( $product->get_categories( '|', '', '' ) ); ?>
            <?php if ( $captiva_options['cap_hide_categories'] == 'no' ) { ?>
                <span class="category"><?php list($firstpart) = explode( '|', $product_cats );
            echo $firstpart;
                ?></span>
            <?php }
            ?>
            
            <span class="name"><?php the_title(); ?></span>
        <?php do_action( 'woocommerce_after_shop_loop_item_title' ); ?>
        </a>
        <?php if ( in_array( 'yith-woocommerce-wishlist/init.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) { ?>
            <?php echo do_shortcode( '[yith_wcwl_add_to_wishlist]' ); ?>
<?php } ?>
    </div>

    <div class="cap-product-excerpt">
<?php the_excerpt(); ?>
    </div>
</div>
<?php woocommerce_get_template( 'loop/sale-flash.php' ); ?>
</li>
