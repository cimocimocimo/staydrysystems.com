<?php

function cap_owlslider_js_testimonials($owlsliderjsid){
	?>
	<script type="text/javascript">

	jQuery(document).ready(function($) {

		$("#owlslider_<?php echo $owlsliderjsid ?>").owlCarousel({
			items : 1,
			lazyLoad : true,
			navigation : false,
			pagination: true,
			slideSpeed: 500, 
			autoPlay: 5000,
			singleItem: true,
		}); 
	});

	</script>

<?php }

// Latest Testimonials. 

function captiva_testimonials($atts, $content = null) {
	global $woocommerce;
	$owlid = rand();
	extract(shortcode_atts(array(
		"introtext" => '',
		"posts" => '8',
		"columns" => '4',
		"category" => ''
	), $atts));
	ob_start();
	?>
        
<?php cap_owlslider_js_testimonials($owlid)?>
<div class="testimonials-wrap">
	<div id="owlslider_<?php echo $owlid ?>" class="owl-carousel">
		<?php
			$args = array(
							'post_type' 			=> 'testimonials',
							'post_status' 			=> 'publish',
							'ignore_sticky_posts'   => 1,
							'posts_per_page' 		=> $posts
			);

			$testimonials = new WP_Query( $args );

			if ( $testimonials->have_posts() ) : ?>
			<?php while ( $testimonials->have_posts() ) : $testimonials->the_post(); ?>

			<?php 
			global $post;
			$testimonial_image 		= get_post_meta( $post->ID, '_cap_testimonial_image', true );
			$testimonial_name 		= get_post_meta( $post->ID, '_cap_testimonial_name', true ); 
			$testimonial_org_name 	= get_post_meta( $post->ID, '_cap_testimonial_org_name', true ); 
			?>

			<div class="item">
				<img alt="<?php echo $testimonial_name; ?>" src="<?php echo $testimonial_image; ?>">
				<p><?php echo get_the_content(); ?></p>
				<span><?php echo $testimonial_name; ?>, <?php echo $testimonial_org_name; ?></span>
			</div>

		<?php endwhile; // end of the loop. ?>
		
		<?php
		endif; 
		wp_reset_query();
		?>

	</div>
</div>

<?php 

	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}

add_shortcode("captiva_testimonials", "captiva_testimonials");


