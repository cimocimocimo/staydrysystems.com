<?php

    use function Tonik\Theme\App\asset_path;

?><div class="<?= $class ?>">
    <div class="<?= $class ?>-heading-border"></div>
    <div class="<?= $class ?>-heading">
        <h3><?= $heading ?></h3>
    </div>
    <div class="<?= $class ?>-image">
        <img src="<?= $image['sizes']['large'] ?>" alt="Solution image" />
    </div>
    <div class="<?= $class ?>-features">
        <?php foreach ($features as $feature): ?>
            <div class="<?= $feature_class ?>-feature <?= $feature_class ?>-feature--icon-<?= $feature['icon_position'] ?>"
                style="
                left: <?= $feature['horizontal_position'] ?>%;
                top: <?= $feature['vertical_position'] ?>%;">
                <div class="text">
                    <span class="stroke"><?= $feature['text'] ?></span>
                    <span class="fill"><?= $feature['text'] ?></span>
                </div>
                <img src="<?= asset_path($icon_path) ?>"
                    alt="Feature Icon">

            </div>
        <?php endforeach; ?>
    </div>
</div>
