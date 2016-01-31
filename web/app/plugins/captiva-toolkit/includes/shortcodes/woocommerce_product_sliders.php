<?php


function cap_owlslider_js($owlsliderjsid){
	?>
	<script>

	jQuery(document).ready(function($) {

		$("#owlslider_<?php echo $owlsliderjsid ?>").owlCarousel({

			items : 5,
			lazyLoad : true,
			navigation : true,
			itemsMobile: [768,2],
			navigationText: [
		  	"<i class='fa fa-angle-left'></i>",
		  	"<i class='fa fa-angle-right'></i>",
		  	]
		}); 

	});

	</script>

<?php }

// Latest Products. 

function captiva_woo_latest_products($atts, $content = null) {
	global $woocommerce;
	$owlid = rand();
	extract(shortcode_atts(array(
		"introtext" => 'Bestsellers',
		'products'  => '12',
        'orderby' => 'date',
        'order' => 'desc'
	), $atts));
	ob_start();
	?>
    
    <?php 
	/**
	* Check if WooCommerce is active
	**/
	if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	?>
    
    <!-- Open Captiva Latest Products  -->
 	<?php cap_owlslider_js($owlid)?>

	<section class="slider">
		<div class="row">
				<div class="col-lg-12">
					<div class="titlewrap">
						<h2><?php echo $introtext; ?></h2>
					</div>
				</div>
				<div id="owlslider_<?php echo $owlid ?>" class="owl-carousel">

					<?php
					$args = array(
					    'post_type' => 'product',
						'post_status' => 'publish',
						'ignore_sticky_posts'   => 1,
						'posts_per_page' => $products
					);
					// Hide hidden items
					$args['meta_query'][] = WC()->query->visibility_meta_query();

					$products = new WP_Query( $args );

					if ( $products->have_posts() ) : ?>
					            
					    <?php while ( $products->have_posts() ) : $products->the_post(); ?>

					    	<div class="item">
					    	<ul>
					    		<?php woocommerce_get_template_part( 'content', 'product' ); ?>
					    	</ul>
					    	</div>

					    <?php endwhile; // end of the loop. ?>
					    
					<?php
					endif; 
					wp_reset_query();
					?>
				</div>
		</div>
	</section>

    <!-- Close Captiva Latest Products  -->

    <?php } ?>

	<?php
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}

// Featured Products 

function captiva_woo_featured_products($atts, $content = null) {
	global $woocommerce;
	$owlid = rand();
	extract(shortcode_atts(array(
		"introtext" => 'Recommended for you',
		'products'  => '12',
        'orderby' => 'date',
        'order' => 'desc'
	), $atts));
	ob_start();
	?>
    
    <?php 
	/**
	* Check if WooCommerce is active
	**/
	if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	?>
    
    <!-- Open Captiva featured Products  -->
 	<?php cap_owlslider_js($owlid)?>

	<section class="slider">
		<div class="row">
			<div class="col-lg-12">
				<div class="titlewrap">
					<h2><?php echo $introtext; ?></h2>
				</div>
			</div>
			<div id="owlslider_<?php echo $owlid ?>" class="owl-carousel">
				<?php
				$args = array(
	                'post_status' => 'publish',
	                'post_type' => 'product',
					'ignore_sticky_posts'   => 1,
	                'meta_key' => '_featured',
	                'meta_value' => 'yes',
	                'posts_per_page' => $products,
					'orderby' => $orderby,
					'order' => $order,
				);
				// Hide hidden items
				$args['meta_query'][] = WC()->query->visibility_meta_query();

				$products = new WP_Query( $args );

				if ( $products->have_posts() ) : ?>
				            
				    <?php while ( $products->have_posts() ) : $products->the_post(); ?>

				    	<div class="item">
				    	<ul>
				    		<?php woocommerce_get_template_part( 'content', 'product' ); ?>
				    	</ul>
				    	</div>

				    <?php endwhile; // end of the loop. ?>
				    
				<?php
				endif; 
				wp_reset_query();
				?>
			</div>
		</div>
	</section>

    <!-- Close Captiva featured Products  -->

    <?php } ?>

	<?php
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}

add_shortcode("captiva_woo_latest_products", "captiva_woo_latest_products");
add_shortcode("captiva_woo_featured_products", "captiva_woo_featured_products");


