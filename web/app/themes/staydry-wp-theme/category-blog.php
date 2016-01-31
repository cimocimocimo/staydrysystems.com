<?php
/**
 * The template for displaying Category Archive pages.
 *
 * @package WordPress
 * @subpackage staydry-wp-theme
 */

get_header(); ?>

<div id="page-header">
	<div class="content-narrow">
	    <div class="heading-block">
			<h1><?php echo single_cat_title( '', false ); ?></h1>
		</div>
	</div><!-- .content -->
</div><!-- .page-heading -->

<div class="page-content">
	<div class="content-narrow">
	<div class="main column">

<?php get_template_part( 'loop', 'category' ); ?>	

	<div class="blog-nav">
	        <div class="column previous">
	<?php previous_posts_link('&laquo; Previous Page'); ?>&nbsp;
	        </div>
	        <div class="column next">
	<?php next_posts_link('Next Page &raquo;'); ?>
	        </div>
	</div>
	</div>
	<div class="side column">
	
<?php get_template_part( 'blog-nav', 'side' ); ?>
	

</div>

		</div><!-- .content -->
</div><!-- .blog-entries -->


<?php get_footer(); ?>
