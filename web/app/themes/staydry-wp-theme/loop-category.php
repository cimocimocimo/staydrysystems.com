<?php
/**
 * @package WordPress
 * @subpackage staydry-wp-theme
 */
?>

<?php while ( have_posts() ) : the_post();
	if (has_post_thumbnail()){
?>
<div id="post-<?php the_ID(); ?>" <?php post_class('blog-entry'); ?>>
	<div class="column block blog-entry-thumbnail"><?php the_post_thumbnail('thumbnail') ?></div>
	<div class="column block blog-title-excerpt">		
		<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
		<?php the_excerpt(); ?>
		<a href="<?php the_permalink(); ?>" class="block-link">Read More</a>
	</div>
</div>
<?php
	} else {
?>
<div id="post-<?php the_ID(); ?>" <?php post_class('blog-entry block'); ?>>
	<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
	<?php the_excerpt(); ?>
	<a href="<?php the_permalink(); ?>" class="block-link">Read More</a>
</div>
<?php 
	}
endwhile; // End the loop. Whew. ?>