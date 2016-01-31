<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package captiva
 */
global $captiva_options;
$cap_below_body_widget = '';
$cap_below_body_widget = $captiva_options['cap_below_body_widget'];
$cap_footer_message = '';
$cap_footer_message = $captiva_options['cap_footer_message'];
$cap_footer_top_active = '';
$cap_footer_top_active = $captiva_options['cap_footer_top_active'];
$cap_footer_bottom_active = '';
$cap_footer_bottom_active = $captiva_options['cap_footer_bottom_active'];
$cap_footer_cards_display = '';
$cap_footer_cards_display = $captiva_options['cap_show_credit_cards'];

function display_card( $card, $status ) {
    if ( $card == '1' and $status == '1' ) {
        echo do_shortcode( '[captiva_card type="visa"]' );
    }
    if ( $card == '2' and $status == '1' ) {
        echo do_shortcode( '[captiva_card type="mastercard"]' );
    }
    if ( $card == '3' and $status == '1' ) {
        echo do_shortcode( '[captiva_card type="paypal"]' );
    }
    if ( $card == '4' and $status == '1' ) {
        echo do_shortcode( '[captiva_card type="amex"]' );
    }
}

if ( $cap_below_body_widget == 'yes' ) { ?>
<section class="below-body-widget-area">
    <div class="container">
        <?php if ( is_active_sidebar( 'below-body' ) ) { ?>
            <?php dynamic_sidebar( 'below-body' ); ?>  
        <?php } ?>
    </div>
</section>
<?php } ?>
</div><!-- close #cap-page-wrap -->
<footer class="footercontainer" role="contentinfo"> 
    <?php if ( $cap_footer_top_active == 'yes' ) { ?>
        <?php if ( is_active_sidebar( 'first-footer' ) ) : ?>
            <div class="lightwrapper">
                <div class="container">
                    <div class="row">
                        <?php dynamic_sidebar( 'first-footer' ); ?>   
                    </div><!-- /.row -->
                </div><!-- /.container -->
            </div><!-- /.lightwrapper -->
        <?php endif; ?>
    <?php } ?>

    <?php if ( $cap_footer_bottom_active == 'yes' ) { ?>
        <?php if ( is_active_sidebar( 'second-footer' ) ) : ?>
            <div class="subfooter">
                <div class="container">
                    <div class="row">
                        <?php dynamic_sidebar( 'second-footer' ); ?>            
                    </div><!-- /.row -->
                </div><!-- /.container -->
            </div><!-- /.subfooter -->
        <?php endif; ?>
    <?php } ?>

    <div class="footer">
        <div class="container">
            <div class="row">
                <div class="bottom-footer-left col-lg-6 col-md-6 col-sm-12 col-xs-12">
                    <?php
                    if ( class_exists( 'CaptivaToolKit' ) ) {
                        if ( $cap_footer_cards_display == 'show' ) {
                            echo '<div class="footer-credit-cards">';
                            $captiva_card_array = ($captiva_options['cap_show_credit_card_values']);
                            foreach ( $captiva_card_array as $card => $status ) {
                                display_card( $card, $status );
                            }
                            echo '</div>';
                        }
                    }
                    ?>
                    <?php
                    if ( $cap_footer_message ) {
                        echo '<div class="footer-copyright">';
                        echo $cap_footer_message;
                        echo '</div>';
                    }
                    ?>
                </div>
                <div class="bottom-footer-right alignright col-lg-6 col-md-6 col-sm-12 col-xs-12">
                </div>
            </div><!-- /.row -->
        </div><!-- /.container -->
    </div><!-- /.footer -->

</footer>
</div><!--/wrapper-->
<?php
    global $cap_live_preview;
    if( isset( $cap_live_preview ) ) include("live-preview.php")
?>
<?php wp_footer(); ?>
</body>
</html>