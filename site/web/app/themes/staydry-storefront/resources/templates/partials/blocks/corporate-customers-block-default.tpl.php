<div class="col-full">
    <?php if ($block->heading) : ?>
        <div class="<?= $block->meta->class ?>__heading">
            <h2 class="section-title"><?= $block->heading ?></h2>
        </div>
    <?php endif; ?>

    <?php if ($block->customers) : ?>
        <div class="<?= $block->meta->class ?>__logo-row">
            <?php foreach ($block->customers as $cust) : ?>
                <div class="<?= $block->meta->class ?>__logo">
                    <span
                        style="width: <?= $cust->logo_width_percent ?>%;
                        padding-top: <?= $block->max_height ?>%;">
                        <?= $cust->thumbnail ?>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
