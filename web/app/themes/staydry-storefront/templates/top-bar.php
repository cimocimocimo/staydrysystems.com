<div id="top-bar" class="top-bar">
  <div class="col-full">
    <?php if ( get_field( 'show_accepted_payment_logos', 'top-bar' )
            && have_rows('accepted_payment_logos', 'top-bar') ) { ?>
      <div class="accepted-payment-logos column-4">
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
      <div class="site-message column-8">
        <?php if ( get_field('site_message_top_line', 'top-bar') ) { ?>
          <div class="site-message__top-line">
            <?php the_field('site_message_top_line', 'top-bar'); ?>
          </div>
        <?php } // endif ?>
        <?php if ( get_field('site_message_bottom_line', 'top-bar') ) { ?>
          <div class="site-message__bottom-line">
            <?php the_field('site_message_bottom_line', 'top-bar'); ?>
          </div>
        <?php } // endif ?>
      </div>
    <?php } // endif ?>
  </div>
</div>
