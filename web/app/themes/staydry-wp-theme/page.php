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

// load the data for the content blocks for this post into an object
$content_blocks = new block_set($post->ID);

get_header();

?>

<div id="page-header">
	<div class="content">
		<div class="heading-block"><h1><?php the_title(); ?></h1></div>
	</div>
</div>

<div>
	<div class="content-narrow">
<?php

while (have_posts()):
    the_post();

?>

		<div id="post-<?php the_ID(); ?>" <?php post_class('column'); ?>>
<?php
        if (has_post_thumbnail()):
?>
            <div class="main-image"><?php echo get_the_post_thumbnail($post->ID, 'medium'); ?></div>
<?php
        endif;
?>

						<?php the_content(); ?>

		</div><!-- #post-## -->
<?php

endwhile;

?>
	</div><!-- #content -->
</div>
<?php

// print out the content blocks for the post or page
$content_blocks->print_rows();

get_footer();

?>