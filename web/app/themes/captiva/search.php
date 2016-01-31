<?php
/**
 * The template for displaying Search Results pages.
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
        <div class="row">
        <?php if ( ( $cap_blog_sidebar == 'default' ) || ( $cap_blog_sidebar == '' ) ) { ?>
            <div class="col-lg-9 col-md-9 col-md-push-3 col-lg-push-3">
                <div id="primary" class="content-area">
                    <main id="main" class="site-main" role="main">
                        <?php if ( have_posts() ) : ?>
                            <header class="page-header">
                                <h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'captiva' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
                            </header><!-- .page-header -->
                            <div class="col-lg-12 col-md-12">
                                <?php /* Start the Loop */ ?>
                                <?php while ( have_posts() ) : the_post(); ?>
                                    <?php get_template_part( 'content', 'search' ); ?>
                                <?php endwhile; ?>
                                <?php wpcaptiva_numeric_posts_nav(); ?>
                            <?php else : ?>
                                <?php get_template_part( 'no-results', 'search' ); ?>
                            <?php endif; ?>
                        </div>
                    </main><!-- #main -->
                </div><!-- #primary -->
            </div><!-- /9 -->
            <div class="col-lg-3 col-md-3 col-md-pull-9 col-lg-pull-9">
                <?php get_sidebar(); ?>
            </div>
        <?php } else if ( $cap_blog_sidebar == 'right' ) { ?>
            <div class="col-lg-9 col-md-9">
                <div id="primary" class="content-area">
                    <main id="main" class="site-main" role="main">
                        <?php if ( have_posts() ) : ?>
                            <header class="page-header">
                                <h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'captiva' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
                            </header><!-- .page-header -->
                            <div class="col-lg-12 col-md-12">
                                <?php /* Start the Loop */ ?>
                                <?php while ( have_posts() ) : the_post(); ?>
                                    <?php get_template_part( 'content', 'search' ); ?>
                                <?php endwhile; ?>
                                <?php wpcaptiva_numeric_posts_nav(); ?>
                            <?php else : ?>
                                <?php get_template_part( 'no-results', 'search' ); ?>
                            <?php endif; ?>
                        </div>
                    </main><!-- #main -->
                </div><!-- #primary -->
            </div> <!-- /9 -->
            <div class="col-lg-3 col-md-3">
                <?php get_sidebar(); ?>
            </div>
        <?php } else if ( $cap_blog_sidebar == 'none' ) { ?>
            <div class="col-lg-12 col-md-12">
                <div id="primary" class="content-area">
                    <main id="main" class="site-main" role="main">
                        <?php if ( have_posts() ) : ?>
                            <header class="page-header">
                                <h1 class="page-title"><?php printf( __( 'Search Results for: %s', 'captiva' ), '<span>' . get_search_query() . '</span>' ); ?></h1>
                            </header><!-- .page-header -->
                            <div class="col-lg-12 col-md-12">
                                <?php /* Start the Loop */ ?>
                                <?php while ( have_posts() ) : the_post(); ?>
                                    <?php get_template_part( 'content', 'search' ); ?>
                                <?php endwhile; ?>
                                <?php wpcaptiva_numeric_posts_nav(); ?>
                            <?php else : ?>
                                <?php get_template_part( 'no-results', 'search' ); ?>
                            <?php endif; ?>
                        </div>
                    </main><!-- #main -->
                </div><!-- #primary -->
            </div><!--/12 -->
        <?php } ?>
        </div><!--/row -->
    </div><!--/content -->
</div><!--/container -->

<?php get_footer(); ?>
