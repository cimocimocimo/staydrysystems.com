<section>
    <h2><?= $heading ?></h2>
    <?php if ($content): ?>
        <div>
            <?= $content ?>
        </div>
    <?php endif; ?>
    <?php if ($customers): ?>
        <?php foreach ($customers as $customer): ?>
        <div>
            <?= $customer['img'] ?>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</section>
