<?php

// StayDry Package Contents

// move this into it's own plugin some lazy afteroon
// or roll this into the cimo contetn blocks plugin


if (is_admin()) {

// this section now defines the custom meta box added to the edit post page
// this meta box is where the options for each product are stored for paypal

// creates the meta box
add_action('admin_menu', 'sdpc_add_custom_box');

// saves the data when the post is saved
add_action('save_post', 'sdpc_save_postdata');


/* Adds a custom section to the "advanced" Post and Page edit screens */
function sdpc_add_custom_box() {
	$html_id		=	'sdpc-edit-product';
	$meta_box_title	=	'Package Contents';
	$callback		=	'sdpc_edit_product_box';
	$edit_post_type	=	'post';
	$page_area		=	'side'; // can be 'normal', 'advanced', or 'side'
	$priority		=	'high';	// either 'low' or 'high'
	$callback_args	=	'';	// this and the $post object are availabe to the callback function
	add_meta_box( $html_id, $meta_box_title, $callback, $edit_post_type, $page_area, $priority, $callback_args );
}
   
/* Prints the inner fields for the custom post/page section */
function sdpc_edit_product_box($post, $args) {

	// passed arguments are found in $args['args']
	$passed_args = $args['args'];
	
	// Use nonce for verification
	echo '<input type="hidden" name="sdpc_noncename" id="sdpc_noncename" value="' . wp_create_nonce('staydry_package_contents') . '" />';

	// check for exisiting saved product data
	$package_contents = get_post_meta($post->ID, '_staydry_package_contents', true );
	if ($package_contents) { // get_post_meta returned something

   
    } else { // get_post_meta did not find any saved data
    
    	// create default data
    	
    	$package_contents = array();
    	
    } // end if $product_data
    
	// output the form content for the meta box
?>
<ul>

<?php
for ($i = 0; $i < 5; $i++)
{
?>
<li>
	<input type="text" name="_staydry_package_contents[<?php echo $i; ?>][quantity]" value="<?php 
		echo $package_contents[$i]['quantity']; ?>" size="2" /> - 
	<input type="text" name="_staydry_package_contents[<?php echo $i; ?>][item_label]" value="<?php 
		echo $package_contents[$i]['item_label']; ?>" size="25" />
</li>
<?php
}
?>	

</ul>

<?php    
} // end sdpc_edit_product_box()


/* When the post is saved, saves our custom data */
function sdpc_save_postdata( $post_id ) {

  // verify this came from the our screen and with proper authorization,
  // because save_post can be triggered at other times

	$nonce = $_POST['sdpc_noncename'];
	if ( !wp_verify_nonce( $nonce, 'staydry_package_contents' )) {
		return $post_id;
	}

	// verify if this is an auto save routine. If it is our form has not been submitted, so we dont want
	// to do anything
	if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
		return $post_id;

	// Check permissions
	if ( 'post' == $_POST['post_type'] ) {
		if ( !current_user_can( 'edit_post', $post_id ) ) {
			return $post_id;
		}
	}

  // OK, we're authenticated: we need to find and save the data

  $meta_data = $_POST['_staydry_package_contents'];

// Do something with $mydata 
  // probably using add_post_meta(), update_post_meta(), or 
  // a custom table (see Further Reading section below)

	$meta_key = '_staydry_package_contents';

	update_post_meta($post_id, $meta_key, $meta_data);

	return $meta_data;
}


} // end if is_admin()

function print_package_contents_block() {
	global $post;
	$package_contents = get_post_meta($post->ID, '_staydry_package_contents', true );
	
	if ($package_contents) {
	
	// open block
?>
<div class="round-corners package-contents block">
	<div class="header-wrap">
		<div class="header-content">

		<h4>Package Contents</h4>
		
		</div><!-- .header-content -->
	</div><!-- .header-wrap -->
	<div class="body-wrap">
		<div class="body-content">
			


<table border="0">
<?php
foreach ($package_contents as $item) {
	if ($item['quantity']) {
		echo '<tr><td>' . $item['quantity'] . 'x</td><td>' . $item['item_label'] . '</td></tr>' . "\n";
	}
}
?>
</table>
		</div> <!-- .body-content -->
	</div><!-- .body-wrap -->
</div><!-- .package-contents -->
<?php
	} // end if $package_contents
} // end print_package_contents()

function return_package_contents_block() {
global $post;
$package_contents = get_post_meta($post->ID, '_staydry_package_contents', true );
$html = '';
    
    if ($package_contents) {
    
    $html .= '<div class="round-corners package-contents block">
    	<div class="header-wrap">
    		<div class="header-content">
    
    		<h4>Package Contents</h4>
    		
    		</div><!-- .header-content -->
    	</div><!-- .header-wrap -->
    	<div class="body-wrap">
    		<div class="body-content">
    		<table border="0">';

    
        foreach ($package_contents as $item) {
        	if ($item['quantity']) {
        		$html .= '<tr><td>' . $item['quantity'] . 'x</td><td>' . $item['item_label'] . '</td></tr>' . "\n";
        	}
        }
        
    $html .= '</table>
    		</div> <!-- .body-content -->
    	</div><!-- .body-wrap -->
    </div><!-- .package-contents -->';
    
    }
    
return $html;
}

?>