<div class="wrap">
  <h2><?php echo __("Ultimate Addons for Visual Composer","ultimate"); ?></h2>
  <div id="msg"></div>
  <div id="poststuff">
    <div id="post-body" class="metabox-holder columns-2">
      <div id="post-body-content" class="postbox-container">
        <div id="normal-sortables" class="meta-box-sortables ui-sortable">
          <div id="plugin_activation" class="postbox ">
          	<?php
				if ($_SERVER['HTTP_HOST'] == 'localhost' || $_SERVER['HTTP_HOST'] == 'localhost:8888' || $_SERVER['REMOTE_ADDR'] == '127.0.0.1' || $_SERVER['REMOTE_ADDR'] == '::1')
				{
					?>
					<div class="overlay-bg"></div>
					<div class="running-localhost">
					<h2>Activating product license on localhost setup is not necessary.</br>
					Thank you!</h2>
					</div>
					<style type="text/css">
					.overlay-bg {
						position: absolute;
						width: 98%;
						height: 100%;
						background: rgba(255,255,255,0.8);
						z-index:99;
					}
					.running-localhost {
						z-index: 99;
						position: absolute;
						width: 98%;
						height: 100%;
						top: 35%;
						margin: 0 auto;
						text-align: center;
					}
					</style>
					<?php
				}
			if(isset($_GET['action']) && $_GET['action']==='upgrade') {
				Ultimate_Admin_Area::upgradeFromMarketplace();
			} else {
				
			?>

            <h3 class="hndle">
            	<span class="dashicons-before dashicons-admin-network" style="padding-right: 5px;"></span>
            	<span>Plugin Activation</span>
            </h3>
            <?php 
				if(get_option('ultimate_updater') === 'enabled'){
					require_once('updater/updater.php');
				} else {
					delete_option('ultimate_keys');
				}
			}?>
          </div>
        </div>
      </div>
      <div id="postbox-container-1" class="postbox-container">
        <div id="normal-sortables" class="meta-box-sortables ui-sortable">
          <!-- <div id="plugin_activation" class="postbox ">
            <h3 class="hndle">
            	<span class="dashicons-before dashicons-sos" style="padding-right: 5px;"></span>
                <span>Plugin Support</span>
            </h3>
            <div class="inside">
            	<div class="main" style="text-align: center;">
                	<a href="https://www.brainstormforce.com/support/forums/forum/ultimate-addons/" target="_blank" class="button button-hero button-primary">Visit Support Forum</a>
                </div>
            </div>
          </div>
          -->
        </div>
      </div>
    </div>
  </div>
</div>
<style type="text/css">
.tooltip {
	display:none;
	position:absolute;
	border:1px solid #333;
	background-color:#161616;
	border-radius:5px;
	padding:10px;
	color:#fff;
	font-size:12px Arial;
}
</style>
<script type="text/javascript">
jQuery(document).ready(function() {
	// Tooltip only Text
	jQuery('.masterTooltip').hover(function(){
			// Hover over code
			var title = jQuery(this).attr('title');
			jQuery(this).data('tipText', title).removeAttr('title');
			jQuery('<p class="tooltip"></p>')
			.html(title)
			.appendTo('body')
			.fadeIn('slow');
	}, function() {
			// Hover out code
			jQuery(this).attr('title', jQuery(this).data('tipText'));
			jQuery('.tooltip').remove();
	}).mousemove(function(e) {
			var mousex = e.pageX + 20; //Get X coordinates
			var mousey = e.pageY + 10; //Get Y coordinates
			jQuery('.tooltip')
			.css({ top: mousey, left: mousex })
	});
});
</script>