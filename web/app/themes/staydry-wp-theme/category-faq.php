<?php
/**
 * The template for displaying Category Archive pages.
 *
 * @package WordPress
 * @subpackage staydry-wp-theme
 */

get_header(); ?>

<div id="faq">
	<div class="content-narrow">
				<h1 class="page-title"><?php echo single_cat_title( '', false ); ?></h1>

				<?php
					$category_description = category_description();
					if ( ! empty( $category_description ) )
						echo '<div class="category-desc">' . $category_description . '</div>';

				/* Run the loop for the category page to output the posts.
				 * If you want to overload this in a child theme then include a file
				 * called loop-category.php and that will be used instead.
				 */
				get_template_part( 'loop', 'faq' );
				?>
</div>	
</div>
<?php get_footer(); ?>
