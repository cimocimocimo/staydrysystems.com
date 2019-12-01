<?php

    use function Tonik\Theme\App\template;

    get_header();

?>
<div class="col-full">
    <h2><?= the_title() ?></h2>

    <div class="woocommerce-tabs wc-tabs-wrapper">
	    <ul class="tabs wc-tabs" role="tablist">
            <?php foreach ( $faqs as $key => $faq ) : ?>
			    <li class="<?php echo esc_attr( $key ); ?>_tab" id="tab-title-<?php echo esc_attr( $key ); ?>" role="tab" aria-controls="tab-<?php echo esc_attr( $key ); ?>">
				    <a href="#tab-<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $faq['term']->name ); ?></a>
			    </li>
		    <?php endforeach; ?>
	    </ul>

	    <?php foreach ( $faqs as $key => $faq ) : ?>
		    <div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--<?php echo esc_attr( $key ); ?> panel entry-content wc-tab" id="tab-<?php echo esc_attr( $key ); ?>" role="tabpanel" aria-labelledby="tab-title-<?php echo esc_attr( $key ); ?>">

                <h3><?= $faq['term']->name ?></h3>

                <?php foreach ($faq['posts'] as $post) : ?>
                    <h4><?= $post->post_title?></h4>
                    <?= apply_filters('the_content', $post->post_content) ?>

                    <?php if ($post->related_products) : ?>
                        <p>
                            <?php foreach ($post->related_products as $prod) : ?>
                                <a href="<?= $prod->permalink ?>"><?= $prod->title ?></a>
                            <?php endforeach; ?>
                        </p>
                    <?php endif; ?>
                <?php endforeach; ?>

		    </div>
	    <?php endforeach; ?>

        <?php

            if ($blocks) {
                foreach ($blocks as $block) {
                    // load template partial for this layout
                    template('partials/blocks/base', [
                        'block' => $block,
                    ]);
                }
            }

        ?>
    </div>
</div>
<?php get_footer(); ?>
