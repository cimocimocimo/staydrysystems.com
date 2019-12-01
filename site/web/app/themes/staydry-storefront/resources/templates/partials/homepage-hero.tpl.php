<?php

    use function Tonik\Theme\App\template;

?>
<div class="homepage-hero">
    <div class="homepage-hero__headline-desktop">
        <h2><?= $headline ?></h2>
    </div>

    <div class="homepage-hero__stage-desktop">
        <div class="homepage-hero__panels">

            <?= template('partials/homepage-hero-panel', [
                'class' => 'panel__problem',
                    'heading' => 'The<br>Problems',
                    'image' => $problem_image,
                    'features' => $problem_features,
                    'icon_path' => 'images/red-x.svg',
                    'feature_class' => 'problem',
                ]) ?>

            <?= template('partials/homepage-hero-panel', [
                'class' => 'panel__solution',
                    'heading' => 'Our<br>Solution',
                    'image' => $solution_image,
                    'features' => $solution_features,
                    'icon_path' => 'images/green-check.svg',
                    'feature_class' => 'solution',
                ]) ?>

        </div>
    </div>
    <div class="homepage-hero__stage-mobile">
        <div class="homepage-hero__panels">

            <?= template('partials/homepage-hero-panel', [
                'class' => 'panel__problem',
                    'heading' => 'The<br>Problems',
                    'image' => $problem_image,
                    'features' => $problem_features,
                    'icon_path' => 'images/red-x.svg',
                    'feature_class' => 'problem',
                ]) ?>

            <?= template('partials/homepage-hero-panel', [
                'class' => 'panel__solution',
                    'heading' => 'Our<br>Solution',
                    'image' => $solution_image,
                    'features' => $solution_features,
                    'icon_path' => 'images/green-check.svg',
                    'feature_class' => 'solution',
                ]) ?>

        </div>
    </div>

    <div class="homepage-hero__headline-handheld">
        <h2><?= $headline ?></h2>
    </div>
    <div class="homepage-hero__subheading">
        <h3><?= $subheading ?></h3>
    </div>
</div>
