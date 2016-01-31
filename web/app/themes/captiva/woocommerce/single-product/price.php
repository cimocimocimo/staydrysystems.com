<?php
/**
 * Single Product Price, including microdata for SEO
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */
if ( !defined( 'ABSPATH' ) )
    exit; // Exit if accessed directly

global $post, $product;
?>
<div itemprop="offers" itemscope itemtype="http://schema.org/Offer">

    <p itemprop="price" class="price"><?php echo $product->get_price_html(); ?></p>

    <meta itemprop="priceCurrency" content="<?php echo get_woocommerce_currency(); ?>" />
    <link itemprop="availability" href="http://schema.org/<?php echo $product->is_in_stock() ? 'InStock' : 'OutOfStock'; ?>" />

    <?php
    $average = $product->get_average_rating();
    echo '<div itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">';
    echo '<div class="star-rating" title="' . sprintf( __( 'Rated %s out of 5', 'woocommerce' ), $average ) . '"><span style="width:' . ( ( $average / 5 ) * 100 ) . '%"><strong itemprop="ratingValue" class="rating">' . $average . '</strong> ' . __( 'out of 5', 'woocommerce' ) . '</span></div>';
    echo '</div>';
    echo '</div>';
    ?>
    <div class="clear"></div>