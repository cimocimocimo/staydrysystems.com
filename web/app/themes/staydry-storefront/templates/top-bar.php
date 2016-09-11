<?php 

function echo_acf_field_if_exists($acf_group, $block, $element){
    $acf_key = preg_replace('/[-\s]+/', '_', $block . '_' . $element);
    $css_selector = preg_replace('/[_\s]+/', '-', $block) . '__' . preg_replace('/[_\s]+/', '-', $element);
    
    if ( get_field($acf_key, $acf_group) ) {
?>
  <div class="<?php echo $css_selector ?>">
    <?php the_field($acf_key, $acf_group); ?>
  </div>
<?php
    }
}

?><div id="top-bar" class="top-bar">
  <div class="col-full">
    <?php if ( get_field( 'show_accepted_payment_logos', 'top-bar' )
            && have_rows('accepted_payment_logos', 'top-bar') ) { ?>
      <div class="accepted-payment-logos">
        <?php while ( have_rows('accepted_payment_logos', 'top-bar') ) {
            the_row();
            $logo = get_sub_field('logo_image');
        ?>
          <img src="<?php echo $logo['url']; ?>"
               title="<?php echo $logo['title']; ?>"
               alt="<?php echo $logo['alt']; ?>"
               height="32"
          />
        <?php } // endwhile ?>
      </div>
    <?php } // endif ?>
    <?php if ( get_field('show_site_message', 'top-bar') ) { ?>
      <div class="site-message">
        <?php echo_acf_field_if_exists('top-bar', 'site message', 'first line'); ?>
        <?php echo_acf_field_if_exists('top-bar', 'site message', 'second line'); ?>
      </div>
    <?php } // endif ?>
  </div>
</div>
