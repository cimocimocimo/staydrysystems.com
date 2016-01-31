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
				<?php if ( have_posts() ) while ( have_posts() ) : the_post(); ?>
				
								<div id="post-<?php the_ID(); ?>" <?php post_class('column'); ?>>
				
									<div class="entry-content">
										<?php the_content(); ?>
									</div><!-- .entry-content -->
								</div><!-- #post-## -->
				
				<?php endwhile; ?>
				
				</div>
	<div class="content">
<div class="column one-half">

<?php
$id = 163;
$page_custom = get_post($id);

	$title = $page_custom->post_title;
	$content = $page_custom->post_content;
	$permalink = get_permalink($id);
?>

	<div id="post-<?php echo $id; ?>">
	<h3><?php echo $title; ?></h3>
		<p><?php echo $content; ?></p>
	</div>


</div>
<div class="column one-half">

<?php
$id = 166;
$page_bulk = get_post($id);



	$title = $page_bulk->post_title;
	$content = $page_bulk->post_content;
	$permalink = get_permalink($id);
?>

<div id="post-<?php echo $id; ?>">
<h3><?php echo $title; ?></h3>
	<p><?php echo $content; ?></p>
</div>
</div>
			</div><!-- #content -->
		</div><!-- #container -->

<?php get_footer(); ?>
