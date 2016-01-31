<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage staydry-wp-theme
 */

get_header(); ?>
<?php if ( have_posts() ) while ( have_posts() ) : the_post(); 

$custom_fields = get_post_custom($post->ID);

// load the data for the content blocks for this post into an object
$content_blocks = new block_set($post->ID);

?>

	<div>
		<div class="content-narrow page-header">
			<h1><?php the_title(); ?></h1>
		</div>
	</div>


<?php if (in_category('products')): ?>

	<div>
		<div class="content-narrow">
			<div class="main-half column">
				<div class="product-image"> 
					<?php the_post_thumbnail('medium'); ?>
				</div> 
			</div><!-- .main-half .column -->
			<div class="main-half column">
				<div class="product-description">
<?php 

print_product_description_block();

?>
				</div>
			</div><!-- .main-half .column -->
			<div class="side column">
				<?php cpo_paypal_form(); ?>
				<div class="addthis_toolbox addthis_32x32_style addthis_default_style block">
				    <a class="addthis_button_facebook"></a>
				    <a class="addthis_button_twitter"></a>
				    <a class="addthis_button_email"></a>
				    <a class="addthis_button_google"></a>
				    <a class="addthis_button_compact"></a>
				</div><!-- addthis .block --> 
			</div><!-- .side .column -->




		</div><!-- .content-narrow -->
	</div>


<?php 

// print out the content blocks for the post or page
$content_blocks->print_rows();

?>

<?php else: ?>

<div>
    <div class="content-narrow">
	    <div class="main column">
	        <div class="blog-entry-content">
    	        <?php the_content(); ?>
            </div>

<div class="blog-nav">
        <div class="column previous">
<?php previous_post_link('&laquo; %link', '%title', true, '3 and 5'); ?>
        </div>
        <div class="column next">
<?php next_post_link('%link &raquo;', '%title', true, '3 and 5'); ?>
        </div>
</div>


    	</div>
	    <div class="side column">
	
<?php get_template_part( 'blog-nav', 'side' ); ?>	
	
	<div class="addthis_toolbox addthis_32x32_style addthis_default_style block">
	    <a class="addthis_button_facebook"></a>
	    <a class="addthis_button_twitter"></a>
	    <a class="addthis_button_email"></a>
	    <a class="addthis_button_google"></a>
	    <a class="addthis_button_compact"></a>
	</div><!-- addthis .block --> 
	

        </div>
    </div><!-- .content-narrow -->
</div>


<?php endif; ?>

<?php endwhile; // end of the loop. ?>


<?php get_footer(); ?>
