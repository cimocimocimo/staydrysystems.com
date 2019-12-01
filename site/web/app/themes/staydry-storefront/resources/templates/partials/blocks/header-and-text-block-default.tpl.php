<div class="col-full">
    <?php if ($block->heading || $block->subheading) : ?>
        <div class="<?= $block->meta->class ?>__headings-mobile">
            <?php if ($block->heading) : ?>
                <h2 class="<?= $block->meta->class ?>__heading">
                    <?= $block->heading ?>
                </h2>
            <?php endif ?>
            <?php if ($block->subheading) : ?>
                <h3 class="<?= $block->meta->class ?>__subheading">
                    <?= $block->subheading ?>
                </h3>
            <?php endif ?>
        </div>
    <?php endif ?>
    <div class="<?= $block->meta->class ?>__content-row">
        <?php if ($block->image) : ?>
            <div class="<?= $block->meta->class ?>__image">
                <img class="my_class" <?= $block->image_src_string ?> alt="text" />
            </div>
        <?php endif; ?>
        <div class="<?= $block->meta->class ?>__content">
            <?php if ($block->heading || $block->subheading) : ?>
                <div class="<?= $block->meta->class ?>__headings-desktop">
                    <?php if ($block->heading) : ?>
                        <h2 class="<?= $block->meta->class ?>__heading">
                            <?= $block->heading ?>
                        </h2>
                    <?php endif ?>
                    <?php if ($block->subheading) : ?>
                        <h3 class="<?= $block->meta->class ?>__subheading">
                            <?= $block->subheading ?>
                        </h3>
                    <?php endif ?>
                </div>
            <?php endif ?>
            <?= $block->content ?>
            <?php if ($block->call_to_action) : ?>
                <div class="<?= $block->meta->class ?>__cta">
                    <a class="button" href="<?= $block->call_to_action['url'] ?>">
                        <?php if ($block->call_to_action['title']) : ?>
                            <?= $block->call_to_action['title'] ?>
                        <?php else: ?>
                            <?= $block->call_to_action['url'] ?>
                        <?php endif; ?>
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
