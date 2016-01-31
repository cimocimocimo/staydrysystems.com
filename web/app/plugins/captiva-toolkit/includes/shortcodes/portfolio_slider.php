<?php


function cap_port_owlslider_js($owlsliderjsid){
	?>
	<script type="text/javascript">

	jQuery(document).ready(function($) {

		$("#owlslider_<?php echo $owlsliderjsid ?>").owlCarousel({
			items : 4,
			lazyLoad : true,
			navigation : true,
			navigationText: [
		  	"<i class='fa fa-angle-left'></i>",
		  	"<i class='fa fa-angle-right'></i>"
		  	]
			}); 
	});

	</script>

<?php }

// Latest Portfolio

function captiva_latest_portfolio($atts, $content = null) {
	$owlid = rand();
	extract(shortcode_atts(array(
		"introtext" => 'Our work',
		'items'  => '12',
        'orderby' => 'date',
        'order' => 'desc'
	), $atts));
	ob_start();
	?>
   
    <!-- Open Captiva Latest Portfolio items  -->
 	<?php cap_port_owlslider_js($owlid)?>

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
						    'post_type' => 'showcases',
							'post_status' => 'publish',
							'ignore_sticky_posts'   => 1,
							//'posts_per_page' => $items
						);

						$portfolios = new WP_Query( $args );

						if ( $portfolios->have_posts() ) : ?>
						            
						    <?php while ( $portfolios->have_posts() ) : $portfolios->the_post(); ?>

		                        <?php
		                        global $post;
		                        $terms = get_the_terms($post->ID, 'cap_showcasecategory');
		                        $term_list = '';
		                        $term_list_sep = '';
		                        if (is_array($terms)) {
		                            foreach ($terms as $term) {
		                                $term_list .= $term->slug;
		                                $term_list .= ' ';
		                            }

		                            $arraysep = array();
		                            foreach ($terms as $termsep) {
		                                $arraysep[] = '<span>'.$termsep->slug.'</span>';
		                            }
		                        }

		                        ?>
		                        <div <?php post_class("element-item $term_list"); ?> id="post-<?php the_ID(); ?>">
		                            <div class="cap-folio-thumb">
		                                <?php cap_folio_recentthumb(get_the_ID()); ?>
		                                <div class="cap-recent-folio-text-wrap">
		                                    <div class="cap-recent-folio-text-title">
		                                        <h2 class="cap-recent-folio-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php printf(__('Permanent Link to %s', 'captiva'), get_the_title()); ?>"> <?php the_title(); ?></a></h2>
		                                    </div>
		                                    <div class="cap-recent-folio-categories">
		                                        <?php echo implode(', ', $arraysep) ?>
		                                    </div>
		                                </div>
		                            </div>
		                        </div>
						    <?php endwhile; // end of the loop. ?>   
						<?php
						endif; 
						wp_reset_query();
						?>
			</div>
		</div>
	</section>

    <!-- Close Captiva Latest Portfolio items  -->

    <?php //} ?>

	<?php
	$content = ob_get_contents();
	ob_end_clean();
	return $content;
}

add_shortcode("captiva_latest_portfolio", "captiva_latest_portfolio");

