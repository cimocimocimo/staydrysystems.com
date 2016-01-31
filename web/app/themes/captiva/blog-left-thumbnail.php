<?php
/**
 * Template Name: Blog with Left Thumbnail
 * @package captiva
 */
get_header();
?>
<div class="container">
    <div class="content">
        <div class="row">
            <div class="col-lg-9 col-md-9 col-sm-9 col-md-push-3 col-lg-push-3">
                <div id="primary" class="content-area medium-blog">
                    <header class="entry-header">
                        <h1 class="entry-title"><?php the_title(); ?></h1>
                    </header><!-- .entry-header -->
                    <?php
                    $args = array(
                        'post_status' => 'publish',
                        'post_type' => 'post',
                        'paged' => get_query_var( 'paged' ),
                    );
                    $recent_posts = new WP_Query( $args );

                    if ( $recent_posts->have_posts() ) :
                        ?>
                        <?php while ( $recent_posts->have_posts() ) : $recent_posts->the_post(); ?>
                            <div class="row animate" data-animate="fadeIn">
                                <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <?php if ( has_post_thumbnail() ) : ?>
                                            <div class="image">
                                                <a href="<?php the_permalink(); ?>" title="<?php the_title_attribute(); ?>" >
                                                    <?php the_post_thumbnail(); ?>
                                                </a>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6">
                                        <header class="entry-header">
                                            <h2 class="entry-title"><a href="<?php the_permalink(); ?>" rel="bookmark"><?php the_title(); ?></a></h2>	
                                            <?php if ( 'post' == get_post_type() ) : ?>
                                                <div class="entry-meta">
                                                    <?php captiva_posted_on(); ?>
                                                </div><!-- .entry-meta -->
                                            <?php endif; ?>
                                        </header><!-- .entry-header -->
                                        <!-- Displays the excerpt unless the post type is a Link or a Quote -->
                                        <div class="entry-content">
                                            <?php if ( has_post_format( 'link' ) ) : ?>
                                                <?php the_content(); ?>
                                            <?php elseif ( has_post_format( 'quote' ) ) : ?>
                                                <?php the_content(); ?>
                                            <?php else : ?>
                                                <?php the_excerpt( __( 'Read more', 'captiva' ) ); ?>
                                            <?php endif; ?>
                                        </div><!-- .entry-content -->
                                        <footer class="entry-meta">
                                            <?php if ( !post_password_required() && ( comments_open() || '0' != get_comments_number() ) ) : ?>
                                                <span class="comments-link"><?php comments_popup_link( __( 'Leave a comment', 'captiva' ), __( '1 Comment', 'captiva' ), __( '% Comments', 'captiva' ) ); ?></span>
                                            <?php endif; ?>
                                            <?php edit_post_link( __( 'Edit', 'captiva' ), '<span class="edit-link">', '</span>' ); ?>
                                        </footer><!-- .entry-meta -->
                                    </div>
                                </article><!-- /#post-<?php get_the_ID(); ?> -->
                            </div><!--/row -->
                        <?php endwhile; ?>
                        <?php 
                        $temp_query = $wp_query;
                        $wp_query   = NULL;
                        $wp_query   = $recent_posts;
                        wpcaptiva_numeric_posts_nav(); ?>
                    <?php else: ?>
                        <div id="post-404" class="noposts">
                            <p><?php _e( 'None found.', 'captiva' ); ?></p>
                        </div><!-- /#post-404 -->
                    <?php
                    endif;
                    wp_reset_query();
                    ?>
                </div><!-- /#content -->
            </div><!--/9 -->
            <div class="col-lg-3 col-md-3 col-md-pull-9 col-lg-pull-9">
                <?php get_sidebar(); ?>
            </div>
        </div><!--/content -->
    </div><!--/row -->
</div><!--/container -->

<?php get_footer(); ?>