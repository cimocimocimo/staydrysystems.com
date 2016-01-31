<?php

function cap_owlslider_js_posts($owlsliderjsid){
	?>
	<script type="text/javascript">

	jQuery(document).ready(function($) {

		$("#owlslider_<?php echo $owlsliderjsid ?>").owlCarousel({
			items : 3,
			lazyLoad : true,
			navigation : true,
			pagination: false,
			rewindNav: false,
			slideSpeed: 500,
			navigationText: [
		  	"<i class='fa fa-angle-left'></i>",
		  	"<i class='fa fa-angle-right'></i>"
		  	]
		}); 
	});

	</script>

<?php }

// Latest News 

function captiva_latest_posts($atts, $content = null) {
	global $woocommerce;
	$owlid = rand();
	extract(shortcode_atts(array(
    	"introtext" => 'From the blog',
    	"title" => 'Latest articles',
		"posts" => '8',
		"columns" => '4',
		"category" => ''
	), $atts));
	ob_start();
	?>
        
<!-- Open Captiva Latest Posts  -->
<?php cap_owlslider_js_posts($owlid)?>

<section class="slider">
	<div class="row">
		<div class="col-lg-12">
			<div class="titlewrap">
				<h2><?php echo $introtext; ?></h2>
			</div>
		</div>
			<div id="captiva-articles">
				<div id="owlslider_<?php echo $owlid ?>" class="owl-carousel">
					<?php
						$args = array(
							'post_status' => 'publish',
							'post_type' => 'post',
							'category_name' => $category,
							'posts_per_page' => $posts
						);

					$recentPosts = new WP_Query( $args );

					if ( $recentPosts->have_posts() ) : ?>
					        
					<?php while ( $recentPosts->have_posts() ) : $recentPosts->the_post(); ?>
					  <div class="item">
						  
								  <div class="thumb">
									    <div class="date">
										    <span class="day"><?php echo get_the_time('d', get_the_ID()); ?></span>
										    <span class="month"><?php echo get_the_time('M', get_the_ID()); ?></span>
									    </div>
									    <a href="<?php the_permalink() ?>"><?php the_post_thumbnail('hp-latest-posts'); ?></a>
								  </div><!--/.thumb -->
							 
						  		<h3><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></h3>
						  		
						  		<?php 
						  		the_excerpt();
						  		?>
								 
				        		
						  		<p class="comments"><?php echo get_comments_number( get_the_ID() ); ?> comments</p>
						  	
					  </div>
					<?php endwhile; // end of the loop. ?>
							    
					<?php
					endif; 
					wp_reset_query();
					?>
				</div>
			</div>
		</div>
</section>
<?php 

	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}

function cap_string_limit_words($string, $word_limit) {
	$words = explode(' ', $string, ($word_limit + 1));
	if(count($words) > $word_limit)
	array_pop($words);
	return implode(' ', $words);
}

add_shortcode("captiva_latest_posts", "captiva_latest_posts");


