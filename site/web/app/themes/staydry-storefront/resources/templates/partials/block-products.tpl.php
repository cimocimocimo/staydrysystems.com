<section class="storefront-product-section"
    aria-label="<?= $block->heading ?>">

    <?php if ($block->heading) : ?>
        <h2 class="section-title"><?= $block->heading ?></h2>
    <?php endif; ?>

    <?php if ($block->subheading) : ?>
        <h3 class="section-subheading"><?= $block->subheading ?></h3>
    <?php endif; ?>

    <?= $block->shortcode_content ?>

</section>
