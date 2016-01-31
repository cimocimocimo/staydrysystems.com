    <?php
	$ultimate_keys = get_option('ultimate_keys');
	$envato_username = isset($ultimate_keys['envato_username']) ? $ultimate_keys['envato_username'] : '';
	$envato_api_key = isset($ultimate_keys['envato_api_key']) ? $ultimate_keys['envato_api_key'] : '';
	$purchase_code = isset($ultimate_keys['ultimate_purchase_code']) ? $ultimate_keys['ultimate_purchase_code'] : '';
	$process = $msg = $response = $code = $status = $disable = $verify_label = $activate_lable = '';
	if($purchase_code !== ''){
		// API Key - e09g6o7hx0zug6auhkzrnd0hkq7d6n4x
		$url = 'http://marketplace.envato.com/api/edge/brainstormforce/e09g6o7hx0zug6auhkzrnd0hkq7d6n4x/verify-purchase:'.$purchase_code.'.json';
		$json = wp_remote_get($url);
		$result = json_decode($json['body'], true);
		if(isset($result['verify-purchase']['buyer']) && $result['verify-purchase']['buyer'] == $envato_username){
			// $verify_label = '<span class="verification" style="top: -41px; position: absolute; right: 110px; padding: 1px 5px; background: green;  color: #fff; border-radius: 2px; cursor: context-menu; margin-right: 15px;"> Verified </span>';
			$activation_check = check_license_activation($ultimate_keys['ultimate_purchase_code']);
			if($activation_check !== ''){
				$activation_check = unserialize($activation_check);
			}
			$response = $activation_check['response'];
			$code = $activation_check['code'];
			$status = $activation_check['status'];
			if($status == "Activated" && $code == 200){
				$disable = 'disabled="disabled" style=" cursor: no-drop;" title="Please deactivate the license on this site to change the credentials."';
				$activate_lable = '<span class="activation" style="top: -41px; position: absolute; right: 20px; cursor: context-menu;"> Activated </span>';
			}
			if($status == 'Activated' ){
				$process = 'deactivate';
				$activate_lable = '<span class="activation" style="top: -41px; position: absolute; right: 20px;  padding: 1px 5px; background: green;  color: #fff; border-radius: 2px; cursor: context-menu;">Activated</span>';
			} elseif($status == 'Deactivated') {
				$process = "reactivate";
				$activate_lable = '<span class="activation" style="top: -41px; position: absolute; right: 20px;  padding: 1px 5px; background: red;  color: #fff; border-radius: 2px; cursor: context-menu;">Not Activated</span>';
			} else {
				$process = "activate";
				$activate_lable = '<span class="activation" style="top: -41px; position: absolute; right: 20px;  padding: 1px 5px; background: red;  color: #fff; border-radius: 2px; cursor: context-menu;">Not Activated</span>';
			}
			echo $response;
		} else {
			$msg = 'Credentials are not valid. Please try again with valid credentials.';
		}
		//echo '<div id="msg"><div class="updated"><p>'.$msg.'</p></div></div>';
	} else {
		echo '<div id="msg"></div>';
	}
	?>
    <div class="inside">
    <?php
	// echo $verify_label;
	echo $activate_lable;
	?>
    <div class="main">
    <p> Please take a moment to register your purchase. <a href="#" class="masterTooltip" title="* Premium Support - We offer lifetime bug support to all registered users.<br/>* Stay Updated - Get a notification right inside your WordPress dashboard, whenever a newer version is available. Update with a single click.">(?)</a></p>
    <form method="post" id="ultimate_updater">
    	<input type="hidden" name="action" value="update_ultimate_keys" />
    	<table class="form-table">
        	<tbody>
            	<tr valign="top">
                	<th scope="row"><?php echo __("Envato Username","ultimate"); ?></th>
                    <td> <input type="text" id="envato_username" value="<?php echo $envato_username; ?>" name="envato_username" style="width: 100%;"/><br />
						 <label style="font-size: 13px;color: #666;" for="envato_username"><?php echo __("Enter your envato username","ultimate"); ?></label>
					</td>
                </tr>
            	<tr valign="top">
                	<th scope="row"><?php echo __("API Key","ultimate"); ?></th>
                    <td> <input type="text" id="envato_api_key" value="<?php echo $envato_api_key; ?>" name="envato_api_key" style="width: 100%;"/><br />
						 <label style="font-size: 13px;color: #666;" for="envato_api_key"><?php echo __("Enter your envato API Key","ultimate"); ?></label>
					</td>
                </tr>
            	<tr valign="top">
                	<th scope="row"><?php echo __("Purchase Code","ultimate"); ?></th>
                    <td> <input type="text" id="ultimate_purchase_code" value="<?php echo $purchase_code; ?>" name="ultimate_purchase_code" style="width: 100%;"/><br />
						 <label style="font-size: 13px;color: #666;" for="ultimate_purchase_code"><?php echo __("Enter your purchase key for Ultimate Addons for Visual Composer","ultimate"); ?></label>
					</td>
                </tr>
            </tbody>
        </table>
    </form>
</div>
</div>
<div class="hndle" style="padding:10px; border-top:1px solid #eee;">
    	<input type="submit" name="submit" id="submit" class="button button-primary <?php echo $disable !== '' ? 'masterTooltip' : ''; ?>" <?php echo $disable; ?> value="<?php echo __("Save Changes","ultimate");?>">
	<?php
		if(($envato_api_key !== '') && ($envato_username !== '') && ($purchase_code !== '') && $code == 200){
	?>
        	<input type="submit" id="activate" class="button button-secondary" value="<?php echo ucfirst($process);?>" data-process="<?php echo $process; ?>">
	<?php } ?>
	</div>
<script type="text/javascript">
var submit_btn = jQuery("#submit");
var activate = jQuery("#activate");
submit_btn.bind('click',function(e){
	e.preventDefault();
	var data = jQuery("#ultimate_updater").serialize();
	jQuery.ajax({
		url: ajaxurl,
		data: data,
		dataType: 'html',
		type: 'post',
		success: function(result){
			if(result == "success"){
				jQuery("#msg").html('<div class="updated"><p>Settings updated successfully. Licence Verified!</p></div>');
				 setTimeout(function(){
					window.location = window.location;
				 },200);
				//process_activation();
			} else if(result == "failed") {
				jQuery("#msg").html('<div class="error"><p>No settings were updated.</p></div>');
			} else if(result == "credentials") {
				jQuery(".updated").remove();
				jQuery("#msg").html('<div class="error"><p>Credentials are not valid. No settings were updated!</p></div>');
			}
		}
	});
});
//function process_activation(){
activate.bind('click',function(e){
	e.preventDefault()
	var url = "aHR0cDovL3VsdGltYXRlLnNoYXJrc2xhYi5jb20vd3AtYWRtaW4vYWRtaW4tYWpheC5waHA=";
	var user = jQuery("#envato_username").val();
	var purchase_key = jQuery("#ultimate_purchase_code").val();
	var process = jQuery(this).data('process');
	var data = "action=activate_license&process="+process+"_license&purchase_code="+purchase_key+"&site_url=<?php echo get_site_url(); ?>&plugin=Ultimate%20Addons&userid="+user;
	jQuery.ajax({
		url: atob(url),
		data: data,
		crossDomain: true,
		type: 'POST',
    	dataType: 'json',
		success: function(responseData) {
			jQuery("#msg").html(responseData.response);
			console.log(responseData.response);
			setTimeout(function(){
				window.location = window.location;
			},200);
		},
		error: function (responseData, textStatus, errorThrown) {
			jQuery("#msg").html(responseData);
		}
	});
});
</script>