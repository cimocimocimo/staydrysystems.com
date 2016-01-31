<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive.
 *
 * Override this template by copying it to yourtheme/woocommerce/archive-product.php
 *
 * @author   WooThemes
 * @package  WooCommerce/Templates
 * @version     2.0.0
 */
global $captiva_options;
$taxonomy = '';
$term_id = '';
$captiva_woo_banner_image = '';
$cap_shop_cat_sidebar = '';

if ( !defined( 'ABSPATH' ) )
    exit; // Exit if accessed directly

get_header( 'shop' );
?>

<!-- Listing notices -->

<script type="text/javascript">
    jQuery(document).ready(function($) {
        $(window).load(function() {

            $('#shop-listing-notices').iosSlider({
                snapToChildren: true,
                desktopClickDrag: true,
                navPrevSelector: '.prev>',
                navNextSelector: '.next_',
                autoSlide: 'true',
                onSliderLoaded: reveal,
                responsiveSlideContainer: true,
                responsiveSlides: true,
            });

            function reveal($args) {
                $(".shop-listing-notices-wrap").css("display", "block");
            }

        });
    });
</script>

<?php
$shop_announcements = $captiva_options['cap_shop_announcements'];
if ( $shop_announcements == 'enabled' ) {
    ?>

    <div id="shop-listing-notices" class="iosslider">
        <div class="slider">
            <?php
            $args = array(
                'post_type' => 'shopannouncements',
                'ignore_sticky_posts' => 1,
                'post_status' => 'publish',
                'orderby' => 'menu_order',
                'order' => 'ASC',
                'posts_per_page' => -1
            );

            $query = new WP_Query( $args );
            while ( $query->have_posts() ) : $query->the_post();
                global $post;
                $announcement_bgcolor = get_post_meta( $post->ID, '_cap_shopannouncement_bgcolor', true );
                $announcement_txtcolor = get_post_meta( $post->ID, '_cap_shopannouncement_txtcolor', true );
                ?>
                <div class="slide shop-listing-notices-wrap col-lg-12 col-md-12" style="background-color: <?php echo $announcement_bgcolor; ?>">
                    <div class="shop-listing-notices" style="color: <?php echo $announcement_txtcolor; ?>">
                        <?php
                        the_content();
                        ?>
                    </div>
                </div>
                <?php
            endwhile;
            wp_reset_query();
            ?>
        </div>
    </div>

<?php } ?>

<div class="container">
    <div class="row">
        <div class="col-lg-12 col-md-12">
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
    </div>
    <div class="row">

        <?php
        if ( isset( $_GET['shopsidebar'] ) ) {
            $cap_shop_cat_sidebar = $_GET['shopsidebar'];
            if ( $cap_shop_cat_sidebar == 'none' ) { ?>
                <div class="product-listing-wrapper col-lg-12">
            <?php } elseif ( $cap_shop_cat_sidebar == 'left' ) { ?>
                <div class="product-listing-wrapper col-lg-9 col-lg-push-3">
            <?php } elseif ( $cap_shop_cat_sidebar == 'right') { ?>
                <div class="product-listing-wrapper col-lg-9"> 
            <?php } 
        } else {
            if ( $captiva_options['product_listing_sidebar'] == 'left-sidebar' ) { ?>
                <div class="product-listing-wrapper col-lg-9 col-lg-push-3">    
            <?php } elseif ( $captiva_options['product_listing_sidebar'] == 'right-sidebar' ) { ?>
                <div class="product-listing-wrapper col-lg-9">
            <?php } else { ?>
            <div class="product-listing-wrapper col-lg-12"> 
            <?php } 
        }
        ?>
        
                    <?php
                    // Get our custom category banner if it exists     
                    $queried_object = '';
                    $taxonomy = '';
                    $term_id = '';

                    $queried_object = get_queried_object();
                    if ( isset( $queried_object->taxonomy ) ) {
                        $taxonomy = $queried_object->taxonomy;
                        $term_id = $queried_object->term_id;
                    }

                    if ( function_exists( 'get_field' ) ) {
                        $cat_banner = get_field( 'product_category_banner', $taxonomy . '_' . $term_id );
                    }

                    if ( !empty( $cat_banner ) ) {
                        $captiva_woo_banner_image = wp_get_attachment_image( $cat_banner, 'product-category-banner', false, array( 'class' => 'product-category-banner' ) );
                    }
                    ?>

                    <?php
                    global $post;
                    global $wp_query;
                    $cat_desc = '';
                    $cat_id = $wp_query->get_queried_object_id();
                    $cat_desc = term_description( $cat_id, 'product_cat' );
                    ?>    

                    <?php if ( ($captiva_woo_banner_image) && ($cat_desc) ) { ?>
                        <div class="category-wrapper">
                            <div class="product-category-image-normal">
                                <?php echo $captiva_woo_banner_image; ?>
                            </div>
                            <div class="product-cat-meta clearfix" data-animate="fadeInLeft">
                                <div class="product-page-title">
                                    <h1 class="page-title"><?php woocommerce_page_title(); ?></h1>
                                </div>
                                <div class="product-category-description">
                                    <?php echo $cat_desc; ?>
                                </div>
                            </div>
                        </div>
                    <?php } elseif ( $captiva_woo_banner_image ) { ?> 
                        <div class="category-wrapper">
                            <div class="product-category-image-normal">
                                <?php echo $captiva_woo_banner_image; ?>
                            </div>
                        </div>
                        <div class="product-page-title">
                            <h1 class="page-title"><?php woocommerce_page_title(); ?></h1>
                        </div>
                    <?php } else { ?>
                        <div class="product-page-title">
                            <h1 class="page-title"><?php woocommerce_page_title(); ?></h1>
                        </div>
                        <?php if ( $cat_desc ) { ?>
                            <div class="product-category-description">
                                <?php echo $cat_desc; ?>  
                            </div>
                        <?php } ?>
                    <?php } ?>
                    <?php
                    /**
                     * woocommerce_before_shop_loop hook
                     * overridden in woocommerce-config.php
                     */
                    do_action( 'woocommerce_before_shop_loop' );
                    ?>
                    <hr class="clearfix"/>
                    <?php if ( have_posts() ) : ?>
                        <?php woocommerce_product_loop_start(); ?>
                        <?php woocommerce_product_subcategories(); ?>
                        <?php while ( have_posts() ) : the_post(); ?>
                            <?php woocommerce_get_template_part( 'content', 'product' ); ?>
                        <?php endwhile; // end of the loop.  ?>
                        <?php woocommerce_product_loop_end(); ?>
                        <hr class="clearfix"/>
                        <?php
                        /**
                         * woocommerce_after_shop_loop hook
                         *
                         * @hooked woocommerce_pagination - 10
                         */
                        do_action( 'woocommerce_after_shop_loop' );
                        ?>
                    <?php elseif ( !woocommerce_product_subcategories( array( 'before' => woocommerce_product_loop_start( false ), 'after' => woocommerce_product_loop_end( false ) ) ) ) : ?>
                        <?php woocommerce_get_template( 'loop/no-products-found.php' ); ?>
                    <?php endif; ?>
                </div>

                <?php
                    if ( isset( $_GET['shopsidebar'] ) ) {

                        if ( $cap_shop_cat_sidebar == 'left' ) { ?>
                            <div class="col-lg-3 col-lg-pull-9 shop-sidebar-left">
                        <?php } elseif ( $cap_shop_cat_sidebar == 'right') { ?>
                            <div class="col-lg-3 shop-sidebar-right"> 
                        <?php } 
                        if ( ( $cap_shop_cat_sidebar == 'left' ) || ( $cap_shop_cat_sidebar == 'right' ) ) {
                            dynamic_sidebar( 'shop-sidebar' );
                            ?>
                        </div>
                        <?php }
                    } else { ?>

                <!-- close col-lg-9 -->
                <?php if ( $captiva_options['product_listing_sidebar'] == 'left-sidebar' ) { ?>
                    <div class="col-lg-3 col-lg-pull-9 shop-sidebar-left">
                    <?php } else if ( $captiva_options['product_listing_sidebar'] == 'right-sidebar' ) { ?>
                        <div class="col-lg-3 shop-sidebar-right">
                        <?php } ?>    

                        <?php
                        if ( $captiva_options['product_listing_sidebar'] == 'left-sidebar' || $captiva_options['product_listing_sidebar'] == 'right-sidebar' ) {
                            /**
                             * woocommerce_sidebar hook
                             *
                             * @hooked woocommerce_get_sidebar - 10
                             */
                            dynamic_sidebar( 'shop-sidebar' );
                            ?> 
                        </div>
                    <?php }
                    }
                ?>

                    <?php
                    /**
                     * woocommerce_after_main_content hook
                     *
                     * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
                     */
                    do_action( 'woocommerce_after_main_content' );
                    ?>
                </div>
                <!-- close row -->
            </div><!--close container -->
            <?php get_footer( 'shop' ); ?>