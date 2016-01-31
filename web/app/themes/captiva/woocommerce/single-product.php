<?php
/**
 * The Template for displaying all single products.
 *
 * Override this template by copying it to yourtheme/woocommerce/single-product.php
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */
if ( !defined( 'ABSPATH' ) )
    exit; // Exit if accessed directly
global $captiva_options;
$cap_shop_sidebar = '';

get_header( 'shop' );
?>

<section>
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <?php
                /**
                 * woocommerce_before_main_content hook
                 *
                 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
                 * @hooked woocommerce_breadcrumb - 20
                 */
                do_action( 'woocommerce_before_main_content' );
                ?>
            </div>
            <div class="col-lg-12">

<?php while ( have_posts() ) : the_post(); ?>

                    <?php

                    if ( isset( $_GET['itemsidebar'] ) ) {
                        $cap_shop_sidebar = $_GET['itemsidebar'];
                    }

                    ?>
                    
                    <?php

                    if ( $cap_shop_sidebar == 'none' ) {
                        woocommerce_get_template_part( 'content', 'single-product-no-sidebar' );
                    } elseif ( ( $captiva_options['wc_product_sidebar'] == "wc_product_right_sidebar" ) || ( $cap_shop_sidebar == 'right') ) {
                        woocommerce_get_template_part( 'content', 'single-product-sidebar-right' );
                    } elseif ( ( $captiva_options['wc_product_sidebar'] == "wc_product_left_sidebar" ) || ( $cap_shop_sidebar == 'left' ) ) {
                        woocommerce_get_template_part( 'content', 'single-product-sidebar-left' );
                    } else {
                        woocommerce_get_template_part( 'content', 'single-product-no-sidebar' );
                    }
                    ?>

<?php endwhile; // end of the loop.  ?>

                <?php
                /**
                 * woocommerce_after_main_content hook
                 *
                 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
                 */
                do_action( 'woocommerce_after_main_content' );
                ?>
            </div>

            <?php
            /**
             * woocommerce_sidebar hook
             *
             * @hooked woocommerce_get_sidebar - 10
             */
            //do_action('woocommerce_sidebar');
            ?>

        </div>
    </div>
</section>

<?php get_footer( 'shop' ); ?>