<div id="vc-template-html">
    <?php echo $this->parseShortcodesString($this->getTemplateContent()); ?>
    <div data-type="files">
        <?php
            _print_styles();
            print_head_scripts();
            print_late_styles();
            print_footer_scripts();
        ?>
    </div>
</div>
<div id="vc-template-data"><?php echo esc_html_e(json_encode($this->post_shortcodes)) ?></div>

