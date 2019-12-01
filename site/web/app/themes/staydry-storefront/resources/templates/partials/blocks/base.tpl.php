<?php

    use function Tonik\Theme\App\template;

?>
<section class="<?= $block->meta->classes ?>"
    <?php if ($block->heading) : ?>
    aria-label="<?= $block->heading ?? '' ?>"
    <?php endif; ?>
    <?php if ($block->meta->style) : ?>
    style="<?= $block->meta->style ?>"
    <?php endif; ?>>
    <?php

        template('partials/blocks/' . $block->meta->template, [
            'block' => $block,
        ]);

    ?>
</section>
