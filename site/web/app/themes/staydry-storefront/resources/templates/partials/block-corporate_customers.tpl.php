<section class="corporate-customer-logos"
    aria-label="<?= $block->heading ?>">

    <?php if ($block->heading) : ?>
        <div class="corporate-customer-logos__heading">
            <h2 class="section-title"><?= $block->heading ?></h2>
        </div>
    <?php endif; ?>

    <?php if ($block->customers) : ?>
        <div class="corporate-customer-logos__logo-row">
            <?php foreach ($block->customers as $cust) : ?>
                <div class="corporate-customer-logos__logo">
                    <span
                        style="width: <?= $cust->logo_width_percent ?>%;
                        padding-top: <?= $block->max_height ?>%;">
                        <?= $cust->thumbnail ?>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>

</section>
