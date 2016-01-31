<?php

function cap_owlslider_js_logos($owlsliderjsid){
	?>
	<script type="text/javascript">

	jQuery(document).ready(function($) {

		$("#owlslider_<?php echo $owlsliderjsid ?>").owlCarousel({
			autoPlay: 4000, 
			items : 5,
			pagination : false,
			stopOnHover : true,
			itemsDesktop : [1199,4],
			itemsDesktopSmall : [979,3]
		}); 
	});

	</script>

<?php }

// Logos 

function captiva_logos($atts, $content = null) {
	global $woocommerce;
	$owlid = rand();
	extract(shortcode_atts(array(
		"introtext" => '',
		"posts" => '10',
		"columns" => '4',
		"category" => ''
	), $atts));
	ob_start();
	?>
        
<?php cap_owlslider_js_logos($owlid)?>
<div class="captiva-logos">
	<div class="row">
		<div id="owlslider_<?php echo $owlid ?>" class="owl-carousel">
			<?php
				$args = array(
								'post_type' 			=> 'logos',
								'post_status' 			=> 'publish',
								'ignore_sticky_posts'   => 1,
								'posts_per_page' 		=> $posts
				);

				$logos = new WP_Query( $args );

				if ( $logos->have_posts() ) : ?>
				<?php while ( $logos->have_posts() ) : $logos->the_post(); ?>

				<?php 
				global $post;
				$logo_url 		= get_post_meta( $post->ID, '_cap_logo_url', true ); 
				$logo_img 	= get_post_meta( $post->ID, '_cap_logo_image', true ); 
				?>

				<div class="item">
					<a href="<?php echo $logo_url; ?>">
						<img alt="logo" src="<?php echo $logo_img; ?>">
					</a>
				</div>

			<?php endwhile; // end of the loop. ?>
			
			<?php
			endif; 
			wp_reset_query();
			?>

		</div>
	</div>
</div>

<?php 

	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}

add_shortcode("captiva_logos", "captiva_logos");


