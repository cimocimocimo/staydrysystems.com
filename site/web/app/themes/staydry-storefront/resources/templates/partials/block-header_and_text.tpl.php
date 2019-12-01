<section class="<?= $block->class ?>
    <?= $block->class ?>--align-heading-<?= $block->align_heading ?>
    <?= $block->class ?>--<?= $block->color_scheme ?>-color-scheme"
    <?php if ($block->heading) : ?>
    aria-label="<?= $block->heading ?>"
    <?php endif; ?>
    <?php if ($block->background_image) : ?>
    style="background-image:url(<?= $block->background_image ?>);"
    <?php endif; ?>
>
    <div class="<?= $block->class ?>__row">
        <div class="<?= $block->class ?>__column
            <?= $block->class ?>__heading">
            <h3 class="section-title"><?= $block->heading ?></h3>
        </div>
        <div class="<?= $block->class ?>__column
            <?= $block->class ?>__content">
            <?= $block->text ?>
            <?php if ($block->call_to_action) : ?>
            <div class="<?= $block->class ?>__cta">
                <a class="button" href="<?= $block->call_to_action['url'] ?>">
                    <?= $block->call_to_action['title'] ?>
                </a>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>
