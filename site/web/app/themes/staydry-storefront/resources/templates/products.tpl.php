<?php

    use function Tonik\Theme\App\template;

    get_header();

?>
<div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

        <?php if (have_posts()) : ?>
            <?php while (have_posts()) : the_post() ?>
                <?php
                    /**
                     * Functions hooked into `theme/single/content` action.
                     *
                     * @hooked Tonik\Theme\App\Structure\render_post_content - 10
                     */
                    do_action('theme/single/content');
                ?>
            <?php endwhile; ?>
        <?php endif; ?>

        <?php

            foreach ($blocks as $block) {
                // load template partial for this layout
                template('partials/blocks/base', [
                    'block' => $block,
                ]);
            }

        ?>

    </main><!-- #main -->
</div>
<?php get_footer(); ?>
