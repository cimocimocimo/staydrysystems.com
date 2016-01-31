<?php
/**
 * Template Name: Showcase:: 2 columns
 * @package captiva
 */
get_header();
?>
<div class="container">
    <div class="content">
        <div class="row">
            <div class="col-lg-12 col-md-12">
                <div id="primary" class="full-width">
                    <main id="main" class="site-main" role="main">
                        <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                            <header class="entry-header">
                                <h1 class="showcase-entry-title"><?php the_title(); ?></h1>
                            </header><!-- .entry-header -->
                            <div class="entry-content clearfix">
                                <div id="filters" class="button-group clearfix">
                                    <h2><?php _e( 'Filter by', 'captiva' ); ?></h2> 
                                    <button class="button is-checked" data-filter-value="*">All</button>
                                    <?php
                                    $args = array(
                                        'orderby' => 'name',
                                        'order' => 'ASC',
                                        'number' => 20, // how many categories
                                        'taxonomy' => 'cap_showcasecategory',
                                    );

                                    $categories = get_categories( $args );
                                    foreach ( $categories as $category ) {
                                        echo '<button class="button" data-filter-value=".' . $category->slug . '">' . $category->name . '</button>';
                                    }
                                    ?>
                                </div>
                                <div class="row">
                                    <div id="showcase-wrap" class="twocolwrap">
                                        <div id="sclist-wrap" class="isotope">
                                            <?php
                                            $args = array(
                                                'post_type' => 'showcases',
                                                'orderby' => 'menu_order',
                                                'order' => 'ASC',
                                                'posts_per_page' => -1
                                            );
                                            $query = new WP_Query( $args );

                                            while ( $query->have_posts() ) : $query->the_post();
                                                ?>
                                                <?php
                                                $terms = get_the_terms( $post->ID, 'cap_showcasecategory' );
                                                $term_list = '';
                                                $term_list_sep = '';
                                                if ( is_array( $terms ) ) {
                                                    foreach ( $terms as $term ) {
                                                        $term_list .= $term->slug;
                                                        $term_list .= ' ';
                                                    }

                                                    $arraysep = array();
                                                    foreach ( $terms as $termsep ) {
                                                        $arraysep[] = '<span>' . $termsep->slug . '</span>';
                                                    }
                                                }
                                                ?>
                                                <div <?php post_class( "element-item $term_list col-lg-6 col-md-6 col-sm-6 col-xs-12" ); ?> id="post-<?php the_ID(); ?>">
                                                    <div class="cap-folio-thumb">
                                                        <?php cap_showcasethumb( get_the_ID() ); ?>
                                                        <div class="cap-folio-text-wrap">
                                                            <div class="cap-folio-text-outer">
                                                                <div class="cap-folio-text-inner">
                                                                    <div class="cap-folio-text-title">
                                                                        <h2 class="cap-folio-title"><a href="<?php the_permalink(); ?>" rel="bookmark" title="<?php printf( __( 'Permanent Link to %s', 'captiva' ), get_the_title() ); ?>"> <?php the_title(); ?></a></h2>
                                                                    </div>
                                                                    <div class="cap-folio-categories">
                                                                        <?php echo implode( ', ', $arraysep ) ?>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endwhile; ?>
                                            <?php wp_reset_postdata(); ?>
                                        </div>
                                    </div>
                                </div>
                            </div><!-- .entry-content -->
                        </article><!-- #post-## -->
                        <?php
                        $cap_comments_status = $captiva_options['cap_page_comments'];
                        if ( $cap_comments_status == 'yes' ) {
                            if ( comments_open() || '0' != get_comments_number() ) {
                                comments_template();
                            }
                        }
                        ?>   
                    </main>
                </div>
            </div>
        </div>
    </div>
</div>
<?php get_footer(); ?>
