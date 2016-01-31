<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the wordpress construct of pages
 * and that other 'pages' on your wordpress site will use a
 * different template.
 *
 * @package WordPress
 * @subpackage staydry-wp-theme
 */

get_header(); ?>

<div id="page-header">
	<div class="content">
		<div class="heading-block"><h1 class="entry-title"><?php the_title(); ?></h1></div>
	</div>
</div>

		<div id="container">
			<div class="content">

				<div id="contact-form" class="column one-half">
					<?php echo do_shortcode('[contact-form 1 "Contact form 1"]'); ?>
				</div>

<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>

				<div id="post-<?php the_ID(); ?>" <?php post_class('column one-half'); ?>>

					<div class="entry-content">
						<?php the_content(); ?>
					</div><!-- .entry-content -->
				</div><!-- #post-## -->

<?php endwhile; ?>

			</div><!-- #content -->
		</div><!-- #container -->

<?php get_footer(); ?>
