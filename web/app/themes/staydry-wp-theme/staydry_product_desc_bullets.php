<?php

// StayDry Product Descriptoin & Bullet Points

if (is_admin()) {

// this section now defines the custom meta box added to the edit post page
// this meta box is where the options for each product are stored for paypal

// creates the meta box
add_action('admin_menu', 'sdpdb_add_custom_box');

// saves the data when the post is saved
add_action('save_post', 'sdpdb_save_postdata');


/* Adds a custom section to the "advanced" Post and Page edit screens */
function sdpdb_add_custom_box() {
	$html_id		=	'sdpdb-edit-product';
	$meta_box_title	=	'Product Description & Bullet Points';
	$callback		=	'sdpdb_edit_product_box';
	$edit_post_type	=	'post';
	$page_area		=	'advanced'; // can be 'normal', 'advanced', or 'side'
	$priority		=	'high';	// either 'low' or 'high'
	$callback_args	=	'';	// this and the $post object are availabe to the callback function
	add_meta_box( $html_id, $meta_box_title, $callback, $edit_post_type, $page_area, $priority, $callback_args );
}
   
/* Prints the inner fields for the custom post/page section */
function sdpdb_edit_product_box($post, $args) {

	// passed arguments are found in $args['args']
	$passed_args = $args['args'];
	
	// Use nonce for verification
	echo '<input type="hidden" name="sdpdb_noncename" id="sdpdb_noncename" value="' . wp_create_nonce('staydry_product_description_bullets') . '" />';

	// check for exisiting saved product data
	$product_description = get_post_meta($post->ID, '_staydry_product_description_bullets', true );
	if ($product_description) { // get_post_meta returned something

   
    } else { // get_post_meta did not find any saved data
    
    	// create default data
    	
    	$product_description = array();
    	
    } // end if $product_description
    
	// output the form content for the meta box
?>
<ul>

<li>
<textarea rows="8" cols="108" name="_staydry_product_description_bullets[description]" id="product_description">
<?php echo $product_description['description']; ?>
</textarea>
</li>

<?php
for ($i = 0; $i < 5; $i++)
{
?>
<li> 
	&bull; <input type="text" name="_staydry_product_description_bullets[bullets][<?php echo $i; ?>]" value="<?php 
		echo esc_html( $product_description['bullets'][$i] ); ?>" size="170" />
</li>
<?php
}
?>	

</ul>

<?php    
} // end sdpdb_edit_product_box()


/* When the post is saved, saves our custom data */
function sdpdb_save_postdata( $post_id ) {

  // verify this came from the our screen and with proper authorization,
  // because save_post can be triggered at other times

	$nonce = $_POST['sdpdb_noncename'];
	if ( !wp_verify_nonce( $nonce, 'staydry_product_description_bullets' )) {
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

  $meta_data = $_POST['_staydry_product_description_bullets'];

// Do something with $mydata 
  // probably using add_post_meta(), update_post_meta(), or 
  // a custom table (see Further Reading section below)

	$meta_key = '_staydry_product_description_bullets';
    
	update_post_meta($post_id, $meta_key, $meta_data);

	return $meta_data;
}


} // end if is_admin()

function print_product_description_block() {
	global $post;
	$product_description = get_post_meta($post->ID, '_staydry_product_description_bullets', true );
	
	if ($product_description) {
	
	// open block
?>

<p><?php print_product_description($product_description['description']); ?></p>

<ul>
<?php
foreach ($product_description['bullets'] as $bullet)
{
	if ($bullet) {
		echo '<li>' . $bullet . '</li>' . "\n";
	}
}

?>
</ul>


<?php
	} // end if $product_description
} // end print_product_description_block()

function print_product_description($desc) {

	$desc_no_returns = preg_replace('/\r/', '', $desc);
	echo preg_replace('/(\n)+/', "</p>\n<p>", $desc_no_returns);
	
}


?>