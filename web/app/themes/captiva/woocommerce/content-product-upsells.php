<?php
/**
 * The template for displaying small product content within loops.
 *
 * Override this template by copying it to yourtheme/woocommerce/content-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */
global $product, $woocommerce_loop;

$attachment_ids = $product->get_gallery_attachment_ids();
?>         

<li class="up-sell-product col-lg-12 col-md-12 col-sm-12">
    <a class="product-tooltip" href="<?php the_permalink(); ?>">
        <div class="product-image">
          <?php echo get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ) ) ?>
        </div><!-- end product-image -->
        <h5><?php the_title(); ?></h5>
    </a>
    <div class="tooltiptext">
      <?php the_title(); ?> - <?php echo $product->get_price_html(); ?>
    </div>
</li><!-- end product -->

