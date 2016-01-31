<?php
/**
 * Template Name: WooCommerce Page
 * @package captiva
 */
get_header();
?>

<div class="content-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-12 col-md-12 col-sm-12">
                <header class="entry-header">
                    <h1><?php the_title(); ?></h1>
                </header>
                <?php while ( have_posts() ) : the_post(); ?>
                    <?php get_template_part( 'content', 'fullwidthpage' ); ?>
                </div>
            </div>
        </div>
        <div class="container">
            <?php
            $cap_comments_status = $captiva_options['cap_page_comments'];
            if ( $cap_comments_status == 'yes' ) {
                if ( comments_open() || '0' != get_comments_number() ) {
                    comments_template();
                }
            }
            ?>
        </div>
    <?php endwhile; // end of the loop. ?>
</div><!-- #primary -->
<?php get_footer(); ?>
