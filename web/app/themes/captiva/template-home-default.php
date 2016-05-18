<?php

/**
 * Template Name: Homepage
 *
 * @package captiva
 *
 */
get_header();
?>

<?php while ( have_posts() ) : the_post(); ?>

    <?php the_content(); ?>

<?php endwhile; // end of the loop.  ?>

<?php do_action('homepage_before_footer'); ?>

<?php get_footer(); ?>


