<div class="col-full">
    <div class="<?= $block->meta->class ?>__headings">
        <?php if ($block->heading) : ?>
            <h2 class="<?= $block->meta->class ?>__heading"><?= $block->heading ?></h2>
        <?php endif; ?>

        <?php if ($block->subheading) : ?>
            <h3 class="<?= $block->meta->class ?>__subheading"><?= $block->subheading ?></h3>
        <?php endif; ?>

        <?php if ($block->content) : ?>
            <?= apply_filters('the_content', $block->content) ?>
        <?php endif; ?>
    </div>

    <?= $block->shortcode_content ?>
</div>
