<?php
/**
 * Single Product tabs
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.0
 */
if ( !defined( 'ABSPATH' ) )
    exit; // Exit if accessed directly
global $captiva_options;

/**
 * Filter tabs and allow third parties to add their own
 *
 * Each tab is an array containing title, callback and priority.
 * @see woocommerce_default_product_tabs()
 */
$tabs = apply_filters( 'woocommerce_product_tabs', array() );

if ( !empty( $tabs ) ) :
    ?>

    <div class="woocommerce-tabs">
        <ul class="tabs">
    <?php foreach ( $tabs as $key => $tab ) : ?>

                <li class="<?php echo $key ?>_tab">
                    <a href="#tab-<?php echo $key ?>"><?php echo apply_filters( 'woocommerce_product_' . $key . '_tab_title', $tab['title'], $key ) ?></a>
                </li>

            <?php endforeach; ?>

            <?php
            // Delivery and Returns
            if ( $captiva_options['returns_tab_title'] ) {
                ?> 
                <li class="returns-tab">
                    <a href="#tab-returns"><?php echo $captiva_options['returns_tab_title'] ?></a>
                </li>	
        <?php } ?>		
        </ul>
    <?php foreach ( $tabs as $key => $tab ) : ?>

            <div class="panel entry-content" id="tab-<?php echo $key ?>">
        <?php call_user_func( $tab['callback'], $key, $tab ) ?>
            </div>

        <?php endforeach; ?>

        <?php // Delivery and Returns content
        if ( $captiva_options['returns_tab_content'] ) {
            ?> 
            <div class="panel entry-content" id="tab-returns">
            <?php echo do_shortcode( $captiva_options['returns_tab_content'] ); ?>
            </div>	
    <?php } ?>

    </div>

<?php endif; ?>