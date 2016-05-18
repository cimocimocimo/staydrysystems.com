<?php

add_action('homepage_before_footer', function(){

?>

  <div class="cap-section" row-effect-mobile-disable="true">
    <div class="wpb_row vc_row-fluid">
      <div class="vc_span12 wpb_column column_container">
        <div class="wpb_wrapper">
          <div class="wpb_raw_code wpb_content_element wpb_raw_html">
            <div class="wpb_wrapper">
              <div class="featured-products-row">
                <div class="wrapper">
                  <h2>Featured Products</h2>
                  <a class="product clearfix" title="Deluxe Shower Curtain Kit" href="/shop/deluxe-shower-curtain-kit/">
                    <div class="image">
                      <img width="150" height="150" src="/app/uploads/2014/07/Deluxe-Shower-150x150.png" alt="Deluxe-Shower">
                    </div>
                    <div class="text">
                      <h3>Deluxe Shower Curtain Kit</h3>
                      <p>The complete Shower Door in a Bag!</p>
                    </div>
                  </a>
                  <a class="product clearfix" title="Shower Curtain Sealer" href="/shop/shower-curtain-sealer/">
                    <div class="image">
                      <img width="150" height="150" src="/app/uploads/2014/07/sealer-mid-detail-220x220-150x150.jpg" alt="sealer-mid-detail-220x220">
                    </div>
                    <div class="text">
                      <h3>Shower Curtain Sealer</h3>
                      <p>Seals your shower curtain to the wall</p>
                    </div>
                  </a>
                  <a class="product clearfix" title="Commercial Shower Curtain Kit (with mesh)" href="/commercial-shower-curtains/">
                    <div class="image">
                      <img width="150" height="150" src="/app/uploads/2014/07/private-shower_0032-540x405-150x150.jpg" alt="private-shower_0032-540x405">
                    </div>
                    <div class="text">
                      <h3>Commercial Shower Curtains</h3>
                      <p>For Hospitals and Insitutions</p>
                    </div>
                  </a>
                  <a class="product clearfix" title="Extra Long Shower Rings" href="/shop/extra-long-shower-rings/">
                    <div class="image">
                      <img width="150" height="150" src="/app/uploads/2014/07/extra-long-shower-rings-230x296-150x150.jpg" alt="extra-long-shower-rings-230x296">
                    </div>
                    <div class="text">
                      <h3>Extra-Long Shower Rings</h3>
                      <p>The perfect height without moving your shower rod</p>
                    </div>
                  </a>
                  <a class="product clearfix" title="Collapsible Shower Water Dam" href="/shop/collapsible-shower-water-dam/">
                    <div class="image">
                      <img width="150" height="150" src="/app/uploads/2014/07/wheelchair-collapsing-dam-220x220-150x150.jpg" alt="wheelchair-collapsing-dam-220x220">
                    </div>
                    <div class="text">
                      <h3>Collapsible Water Dam</h3>
                      <p>Stops water from spilling out of accesable roll-in shower stalls</p>
                    </div>
                  </a>
                  <a class="product clearfix" title="Clearance Items" href="/clearance-items/">
                    <div class="image">
                      <img width="150" height="150" src="/app/uploads/2014/07/enviro-off-angle-processed-cropped-2-230x270-150x150.jpg" alt="enviro-off-angle-processed-cropped-2-230x270">
                    </div>
                    <div class="text">
                      <h3>Clearance Items!</h3>
                      <p>Clearance items are available in limited quantities.</p>
                    </div>
                  </a>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>

  <div class="customers-container container">
    <div class="customers">
      <h2>Some of Our Satisfied Customers</h2>
      <div class="logo-row clearfix">
        <div class="customer-logo">
          <img src="<?php echo get_template_directory_uri(); ?>/staydry/images/VIHA.jpg" style="width: 188.4559599879023px; height: 100.76624799353144px; margin-top: 44px;">
        </div>
        <div class="customer-logo">
          <img src="<?php echo get_template_directory_uri(); ?>/staydry/images/super-8-motel.png" style="width: 111.51267256295124px; height: 170.29454647210386px; margin-top: 10px;">
        </div>
        <div class="customer-logo">
          <img src="<?php echo get_template_directory_uri(); ?>/staydry/images/mount-sinai-hospital-logo.png">
                  </div>
        <div class="customer-logo">
          <img src="<?php echo get_template_directory_uri(); ?>/staydry/images/Nasa-logo.gif" style="width: 149.3593165186983px; height: 127.14305637320348px; margin-top: 31px;">
        </div>
        <div class="customer-logo">
          <img src="<?php echo get_template_directory_uri(); ?>/staydry/images/mcmaster-university.jpg" style="width: 185.33633972734384px; height: 102.46236667853155px; margin-top: 44px;">
        </div>
        <div class="clear"></div>
      </div>
    </div>
  </div>
<?php

}, 50);

?>
