<?php
/**
 * Single Product Image
 *
 * @author      WooThemes
 * @package     WooCommerce/Templates
 * @version     2.0.14
 */
if ( !defined( 'ABSPATH' ) )
    exit; // Exit if accessed directly

global $post, $woocommerce, $product;

$cap_attach_ids = $product->get_gallery_image_ids();
?>

<div class="images cap-prod-gallery">

    <script type="text/javascript">

        jQuery( document ).ready( function( $ ) {
            var sync1 = $( "#sync1" );
            var sync2 = $( "#sync2" );

            sync1.owlCarousel( {
                singleItem: true,
                slideSpeed: 200,
                navigation: true,
                navigationText: [
                    "<i class='fa fa-angle-left'></i>",
                    "<i class='fa fa-angle-right'></i>"
                ],
                pagination: false,
                afterAction: syncPosition,
                responsiveRefreshRate: 200,
            } );

            sync2.owlCarousel( {
                items: 4,
                itemsDesktop: false,
                itemsDesktopSmall: false,
                itemsTablet: [479, 3],
                itemsMobile: [320, 2],
                pagination: false,
                responsiveRefreshRate: 100,
                afterInit: function( el ) {
                    el.find( ".owl-item" ).eq( 0 ).addClass( "synced" );
                }
            } );

            function syncPosition( el ) {
                var current = this.currentItem;
                $( "#sync2" )
                        .find( ".owl-item" )
                        .removeClass( "synced" )
                        .eq( current )
                        .addClass( "synced" )
                if ( $( "#sync2" ).data( "owlCarousel" ) !== undefined ) {
                    center( current )
                }
            }

            $( "#sync2" ).on( "click", ".owl-item", function( e ) {
                e.preventDefault();
                var number = $( this ).data( "owlItem" );
                sync1.trigger( "owl.goTo", number );
            } );

            $( ".variations" ).on( 'change', 'select', function( e ) {
                sync1.trigger( "owl.goTo", 0 );
            } );

            function center( number ) {
                var sync2visible = sync2.data( "owlCarousel" ).owl.visibleItems;
                var num = number;
                var found = false;
                for ( var i in sync2visible ) {
                    if ( num === sync2visible[i] ) {
                        var found = true;
                    }
                }

                if ( found === false ) {
                    if ( num > sync2visible[sync2visible.length - 1] ) {
                        sync2.trigger( "owl.goTo", num - sync2visible.length + 2 )
                    } else {
                        if ( num - 1 === -1 ) {
                            num = 0;
                        }
                        sync2.trigger( "owl.goTo", num );
                    }
                } else if ( num === sync2visible[sync2visible.length - 1] ) {
                    sync2.trigger( "owl.goTo", sync2visible[1] )
                } else if ( num === sync2visible[0] ) {
                    sync2.trigger( "owl.goTo", num - 1 )
                }
            }

        } );

    </script>

    <div id="sync1" class="cap-prod-lvl1">

        <?php if ( has_post_thumbnail() ) : ?>

            <?php
            $cap_attach_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), false, '' );

            $cap_attach_count = count( get_children( array( 'post_parent' => $post->ID, 'post_mime_type' => 'image', 'post_type' => 'attachment' ) ) );
            ?>

            <div class="item cap-product-gallery-img">
                <a href="<?php echo $cap_attach_src[0] ?>"><span itemprop="image"><?php echo get_the_post_thumbnail( $post->ID, 'shop_single' ) ?></span>
                </a>
            </div>

<?php endif; ?> 

        <?php
        if ( $cap_attach_ids ) {

            $capthumbsinc = 0;
            $columns = apply_filters( 'woocommerce_product_thumbnails_columns', 3 );

            foreach ( $cap_attach_ids as $cap_attach_id ) {

                $classes = array( 'zoom' );

                if ( $capthumbsinc == 0 || $capthumbsinc % $columns == 0 )
                    $classes[] = 'first';

                if ( ( $capthumbsinc + 1 ) % $columns == 0 )
                    $classes[] = 'last';

                $cap_image_uri = wp_get_attachment_url( $cap_attach_id );

                if ( !$cap_image_uri )
                    continue;

                $cap_image = wp_get_attachment_image( $cap_attach_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_single' ) );
                $cap_image_class = esc_attr( implode( ' ', $classes ) );
                $cap_image_title = esc_attr( get_the_title( $cap_attach_id ) );

                printf( '<div class="item cap-product-gallery-img"><a href="%s"><span>%s</span></a></div>', wp_get_attachment_url( $cap_attach_id ), wp_get_attachment_image( $cap_attach_id, 'shop_single' ) );

                $capthumbsinc++;
            }
        }
        ?>

    </div>

        <?php
        if ( $cap_attach_ids ) {
            ?>

        <div class="cap-prod-gallery-thumbs">   
            <div id="sync2" class="cap-prod-lvl2">

        <?php if ( has_post_thumbnail() ) { ?>
                    <div class="cap-prod-gallery-thumb">
                        <div itemprop="image"><?php echo get_the_post_thumbnail( $post->ID, 'shop_single' ) ?>
                        </div>
                    </div>
    <?php } ?>

    <?php
    $capthumbsinc = 0;
    $columns = apply_filters( 'woocommerce_product_thumbnails_columns', 3 );

    foreach ( $cap_attach_ids as $cap_attach_id ) {

        $classes = array( 'zoom' );

        if ( $capthumbsinc == 0 || $capthumbsinc % $columns == 0 )
            $classes[] = 'first';

        if ( ( $capthumbsinc + 1 ) % $columns == 0 )
            $classes[] = 'last';

        $cap_image_link = wp_get_attachment_url( $cap_attach_id );

        if ( !$cap_image_link )
            continue;

        $cap_image = wp_get_attachment_image( $cap_attach_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_single' ) );

        echo '<div class="cap-prod-gallery-thumb">' . $cap_image . '</div>';

        $capthumbsinc++;
    }

    if ( $capthumbsinc < 4 ) {
        for ( $i = 1; $i < (4 - $capthumbsinc); $i++ ) {
            ?>
                        <div class="cap-prod-gallery-thumb"></div>
                        <?php
                    }
                }
                ?>

            </div>
        </div>

            <?php } ?>
</div>
