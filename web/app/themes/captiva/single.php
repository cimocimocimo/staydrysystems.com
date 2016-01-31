<?php
/**
 * The Template for displaying all single posts.
 *
 * @package captiva
 */
global $captiva_options;
$cap_blog_sidebar = '';
if ( isset( $captiva_options['cap_blog_sidebar'] ) ) {
    $cap_blog_sidebar = $captiva_options['cap_blog_sidebar'];
}

get_header();
?>
<div class="container">
    <div class="content">
        <?php
        if ( function_exists( 'yoast_breadcrumb' ) ) {
            yoast_breadcrumb( '<p id="breadcrumbs">', '</p>' );
        }
        ?>
        <div class="row">
            <?php if ( ( $cap_blog_sidebar == 'default' ) || ( $cap_blog_sidebar == '' ) ) { ?>
                <div class="col-lg-9 col-md-9 col-md-push-3 col-lg-push-3">
                    <div id="primary" class="content-area">
                        <main id="main" class="site-main" role="main">
                            <?php while ( have_posts() ) : the_post(); ?>
                                <?php get_template_part( 'content', 'single' ); ?>
                                <?php captiva_content_nav( 'nav-below' ); ?>
                                <?php
                                // If comments are open or we have at least one comment, load up the comment template
                                if ( comments_open() || '0' != get_comments_number() )
                                    comments_template();
                                ?>
                            <?php endwhile; // end of the loop.  ?>
                        </main><!-- #main -->
                    </div><!-- #primary -->
                </div>
                <div class="col-lg-3 col-md-3 col-md-pull-9 col-lg-pull-9">
                    <?php get_sidebar(); ?>
                </div>
            <?php } else if ( $cap_blog_sidebar == 'right' ) { ?>
                <div class="col-lg-9 col-md-9">
                    <div id="primary" class="content-area">
                        <main id="main" class="site-main" role="main">
                            <?php while ( have_posts() ) : the_post(); ?>
                                <?php get_template_part( 'content', 'single' ); ?>
                                <?php captiva_content_nav( 'nav-below' ); ?>
                                <?php
                                // If comments are open or we have at least one comment, load up the comment template
                                if ( comments_open() || '0' != get_comments_number() )
                                    comments_template();
                                ?>
                            <?php endwhile; // end of the loop.  ?>
                        </main><!-- #main -->
                    </div><!-- #primary -->
                </div>
                <div class="col-lg-3 col-md-3">
                    <?php get_sidebar(); ?>
                </div>
            <?php } else if ( $cap_blog_sidebar == 'none' ) { ?>
                <div class="col-lg-12 col-md-12">
                    <div id="primary" class="content-area">
                        <main id="main" class="site-main" role="main">
                            <?php while ( have_posts() ) : the_post(); ?>
                                <?php get_template_part( 'content', 'single' ); ?>
                                <?php captiva_content_nav( 'nav-below' ); ?>
                                <?php
                                // If comments are open or we have at least one comment, load up the comment template
                                if ( comments_open() || '0' != get_comments_number() )
                                    comments_template();
                                ?>
                            <?php endwhile; // end of the loop.  ?>
                        </main><!-- #main -->
                    </div><!-- #primary -->
                </div>
            <?php } ?>
        </div><!--/row -->
    </div><!--/content -->
</div><!--/container -->

<?php get_footer(); ?>