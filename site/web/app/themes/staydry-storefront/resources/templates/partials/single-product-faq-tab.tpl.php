<div id="faq-tab">
    <h2>Frequently Asked Questions</h2>
    <?php foreach ($faqs as $faq) : ?>
        <h3><?= $faq->post_title ?></h3>
        <?= apply_filters('the_content', $faq->post_content) ?>
        <?php // var_dump($faq) ?>
    <?php endforeach; ?>
</div>
