<?php
/**
 * @package WordPress
 * @subpackage staydry-wp-theme
 */
?>

<?php while ( have_posts() ) : the_post(); ?>
<div class="column one-half">

		<div id="post-<?php the_ID(); ?>" <?php post_class('product'); ?>>
		<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
		<?php the_post_thumbnail('thumbnail'); ?>
			<div class="prod-desc column">
			<?php the_excerpt(); ?>
			</div>
			<a href="<?php the_permalink(); ?>" class="block-link">Read More</a>
		</div>
</div>
<?php endwhile; // End the loop. Whew. ?>