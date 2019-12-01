<aside class="entry-meta">
    <?php if ($show_category_tags): ?>
        <div class="vcard author">
            <?= $author->avatar ?>
            <div class="label"><?= esc_attr( __( 'Written by', 'storefront' ) ) ?></div>
            <div class="label author"><?= $author->name ?></div>
        </div>
    <?php endif; ?>
</aside>
