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

<h2>How The StayDry System Works</h2>
<p>
<object width="342" height="282">
<param name="movie" value="http://www.youtube.com/v/lWJ0d1NrbX8&#038;hl=en_US&#038;fs=1&#038;&rel=0"></param>
<param name="allowFullScreen" value="true"></param>
<param name="allowscriptaccess" value="always"></param>
<embed src="http://www.youtube.com/v/lWJ0d1NrbX8&#038;hl=en_US&#038;fs=1&#038;&rel=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="342" height="282"></embed>
</object>
</p>

<h2>StayDry in Hospitals and Hotels</h2>
<object width="342" height="282">
<param name="movie" value="http://www.youtube.com/v/X9zBEht1M2U&#038;hl=en_US&#038;fs=1&#038;&rel=0"></param>
<param name="allowFullScreen" value="true"></param>
<param name="allowscriptaccess" value="always"></param>
<embed src="http://www.youtube.com/v/X9zBEht1M2U&#038;hl=en_US&#038;fs=1&#038;&rel=0" type="application/x-shockwave-flash" allowscriptaccess="always" allowfullscreen="true" width="342" height="282"></embed>
</object>

</div>
<div class="column one-half">

<?php

$args = array( 'post__in' => array(11,9,321) );
$products = get_posts($args);

foreach ($products as $prod)
{
	$id = $prod->ID;
	$title = $prod->post_title;
	$excerpt = $prod->post_excerpt;
	$permalink = get_permalink($id);
	$img = get_the_post_thumbnail($id, 'thumbnail');
?>

	<div id="post-<?php echo $id; ?>" class="product">
	<h2><a href="<?php echo $permalink; ?>"><?php echo $title; ?></a></h2>
	<?php echo $img; ?>
		<div class="prod-desc">
		<p><?php echo $excerpt; ?></p>
		</div>
		<a href="<?php echo $permalink; ?>" class="block-link">Read More</a>
	</div>
<?php } ?>

</div>
			</div><!-- #content -->
		</div><!-- #container -->

<?php get_footer(); ?>
