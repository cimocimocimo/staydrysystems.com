<?php
/**
 * The template for displaying all pages.
 * @package captiva
 */
get_header();
?>

<?php
// Get our page banner if it exists
$captiva_page_banner_image = '';
if ( function_exists( 'get_field' ) ) {
    $page_banner = get_field( 'banner_image' );
    $page_banner_height = get_field( 'page_banner_height' );
}

if ( !empty( $page_banner ) ) {
    $captiva_page_banner_image = $page_banner;
}
?>

<?php if ( $captiva_page_banner_image ) { ?>

    <?php $danchor = 'cap-strip-' . rand(); ?>

    <div class="cap-strip cap-strip-wrap fade-in animate" style="background-color:#333333!important; height:<?php echo $page_banner_height; ?>;">
        <div class="cap-strip-bg cap_parallax skrollable skrollable-between" style="background-image: url(<?php echo $captiva_page_banner_image; ?>);" data-center="background-position: 50% 50%;" data-top-bottom="background-position: 50% 0%" data-bottom-top="background-position: 50% 95%"></div>
        <div class="row">
            <div style="width: 50%;" class="cap-pos valign-center halign-center">
                <div class="cap-strip-content <?php echo $danchor; ?> light text-align-center skrollable skrollable-before" data-center-bottom="opacity: 1" data--150-top="opacity: 0" data-anchor-target=".<?php echo $danchor; ?>" style="opacity: 1;">
                    <h1><?php the_title(); ?></h1>
                </div>
            </div>
        </div>
    </div>

<?php } ?>

<div class="container">
    <div class="content">

        <?php
        if ( function_exists( 'yoast_breadcrumb' ) ) {
            yoast_breadcrumb( '<p id="breadcrumbs">', '</p>' );
        }
        ?>
        <div class="row">
            <div class="col-lg-9 col-md-9 col-md-push-3 col-lg-push-3">
                <div id="primary" class="content-area">
                    <main id="main" class="site-main" role="main">

                        <?php while ( have_posts() ) : the_post(); ?>

                            <?php get_template_part( 'content', 'page' ); ?>

                            <?php
                            $cap_comments_status = $captiva_options['cap_page_comments'];
                            if ( $cap_comments_status == 'yes' ) {
                                if ( comments_open() || '0' != get_comments_number() ) {
                                    comments_template();
                                }
                            }
                            ?>

                        <?php endwhile; // end of the loop.  ?>

                    </main><!-- #main -->
                </div><!-- #primary -->
            </div>
            <div class="col-lg-3 col-md-3 col-md-pull-9 col-lg-pull-9">
                <?php get_sidebar(); ?>
            </div>
        </div><!--/row -->
    </div><!--/content -->
</div><!--/container -->

<?php get_footer(); ?>
