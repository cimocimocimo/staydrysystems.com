<div class="wrap">
	<h2><?php echo __("Ultimate Addon Settings","ultimate"); ?></h2>
    <div id="msg"></div>
    <?php
	$ultimate_row = get_option('ultimate_row');
	$ultimate_animation = get_option('ultimate_animation');
	
	if($ultimate_row == "enable"){
		$checked_row = 'checked="checked"';
	} else {
		$checked_row = '';
	}
	
	if($ultimate_animation == "enable"){
		$ultimate_animation = 'checked="checked"';
	} else {
		$ultimate_animation = '';
	}
	?>
    <form method="post" id="ultimate_dashboard">
    	<input type="hidden" name="action" value="update_ultimate_options" />
    	<table class="form-table">
        	<tbody>
            	<tr valign="top">
                	<th scope="row"><?php echo __("Row backgrounds","ultimate"); ?></th>
                    <td> <input type="checkbox" <?php echo $checked_row; ?> id="ultimate_row" value="enable" name="ultimate_row" />
						 <label for="ultimate_row"><?php echo __("Enable row backgrounds","ultimate"); ?></label>
					</td>
                </tr>
                <tr valign="top">
                	<th scope="row"><?php echo __("Animation Block","ultimate"); ?></th>
                    <td> <input type="checkbox" <?php echo $ultimate_animation; ?> id="ultimate_animation" value="enable" name="ultimate_animation" />
						 <label for="ultimate_animation"><?php echo __("Disable animation on mobile devices","ultimate"); ?></label>
					</td>
                </tr>               
            </tbody>
        </table>
    </form>
	<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo __("Save Changes","ultimate");?>"></p>
</div>
<script type="text/javascript">
var submit_btn = jQuery("#submit");
submit_btn.bind('click',function(e){
	e.preventDefault();
	var data = jQuery("#ultimate_dashboard").serialize();
	console.log(data);
	jQuery.ajax({
		url: ajaxurl,
		data: data,
		dataType: 'html',
		type: 'post',
		success: function(result){
			if(result == "success"){
				jQuery("#msg").html('<div class="updated"><p>Settings updated successfully!</p></div>');
			} else {
				jQuery("#msg").html('<div class="error"><p>No settings were updated.</p></div>');
			}
		}
	});
});
</script>