<?php

// Latest Top Reviews 
function captiva_topreviews($atts, $content = null) {
	global $woocommerce;
	extract(shortcode_atts(array(
		"introtext" => '',
		"posts" => '8',
		"columns" => '4',
		"category" => ''
	), $atts));
	ob_start();
	?>
<ul>
		<?php
			$args = array(
							'post_type' 			=> 'topreviews',
							'post_status' 			=> 'publish',
							'ignore_sticky_posts'   => 1,
							'posts_per_page' 		=> $posts
			);

			$topreviews = new WP_Query( $args );

			if ( $topreviews->have_posts() ) : ?>
			<?php while ( $topreviews->have_posts() ) : $topreviews->the_post(); ?>

			<?php 
			global $post;
			$topreview_name 		= get_post_meta( $post->ID, '_cap_topreview_name', true ); 
			$topreview_org_name 	= get_post_meta( $post->ID, '_cap_topreview_org_name', true ); 
			?>

			<li>
			<span class="icon-five-stars"></span>
			<h6><?php echo the_title(); ?></h6>
			<p class="light-text">
              <?php echo get_the_content(); ?>
              -&nbsp;<em><?php echo $topreview_name; ?></em>                    
            </p>
			</li>

		<?php endwhile; // end of the loop. ?>
		
		<?php
		endif; 
		wp_reset_query();
		?>
</ul>

<?php 

	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}

add_shortcode("captiva_topreviews", "captiva_topreviews");


