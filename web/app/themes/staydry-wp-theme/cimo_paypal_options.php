<?php

// move this into it's own plugin some lazy afteroon

// default options for the paypal form
// using globals is lame, make this thing a class

$paypal_options = array(
    'action'            =>  'https://www.paypal.com/cgi-bin/webscr',
	'cmd'				=>	'_cart',
	'business'			=>	'ENTER YOUR PAYPAL EMAIL',
	'lc'				=>	'CA',
	'currency_code'		=>	'USD',
	'button_subtype'	=>	'products',
	'no_shipping'		=>	'2',
	'rm'				=>	'1',
	'return'			=>	'http://staydrysystems.com/thank-you/',
	'cancel_return'		=>	'http://staydrysystems.com/order-cancelled/',
	'add'				=>	'1',
	'bn'				=>	'PP-ShopCartBF:btn_cart_LG.gif:NonHosted',
);

/**************************
Admin Functions
***************************/

function cpo_register_menu() {
	$page_title = "Cimo PayPal Options";
	$menu_title = "PayPal Options";
	$capability = "manage_options";
	$menu_slug = "cpo-settings";
	$function = "cpo_menu_page";

	add_options_page( $page_title, $menu_title, $capability, $menu_slug, $function); 
}

function cpo_register_settings() {
	// registers a single row in the options table 
	register_setting( 'cpo_settings_group', 'cpo_settings' );
}

	
function cpo_menu_page() {
	
	global $paypal_options;

    $curr_options = get_option('cpo_settings');

?>
<div class="wrap">
	<h2>Cimo PayPal Options</h2>
	
	<form method="post" action="options.php">
		<table class="form-table">
<?php

    // output the hidden inputs to make the settings form work with WP
    settings_fields( 'cpo_settings_group' );
    
    // loop through all the options, if they have not been set load the default value
    foreach ($paypal_options as $name => $defualt_value):
        
        $value = '';
        $curr_value = $current_options[$name];
        
        if ($curr_value) {
            $value = $curr_value;
        } else {
            $value = $default_value;
        }

?>
        	<tr valign="top">
    	        <th scope="row"><?php echo $name; ?></th>
    	        <td><input type="text" name="cpo_settings[<?php echo $name; ?>]" value="<?php echo $value; ?>" size="80"/></td>
	        </tr>
<?php

    endforeach;
	    
?>
	    </table>
	    <p class="submit">
    	    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
	    </p>
	</form>
</div>
<?php

} // end function cpo_menu_page()

// This function adds the custom box to the edit post page. it also registers the call back function generating the html for the box
function cpo_add_custom_box() {
	$html_id		=	'cpo-edit-product';
	$meta_box_title	=	'PayPal Options';
	$callback		=	'cpo_edit_product_box';
	$edit_post_type	=	'post';
	$page_area		=	'side'; // can be 'normal', 'advanced', or 'side'
	$priority		=	'low';	// either 'low' or 'high'
	$callback_args	=	'';	// this and the $post object are availabe to the callback function
	add_meta_box( $html_id, $meta_box_title, $callback, $edit_post_type, $page_area, $priority, $callback_args );
}
   
// this is the callback function that outpts the html form for the paypal options box
function cpo_edit_product_box($post, $args) {

  // passed arguments are found in $args['args']
  // $passed_args = $args['args'];
	
  // Use nonce for verification
  echo '<input type="hidden" name="cpo_noncename" id="cpo_noncename" value="' . wp_create_nonce('cimo_paypal_options') . '" />';
  
  // check for exisiting saved product data
  $product_data = get_post_meta($post->ID, '_cpo_product_data', true );
  if ($product_data == '') { // nothing found
    // create default data
    $product_data = array();	
  }
    
  // output the form content for the meta box

?>
<ul>
	<li>
		<label for="_cpo_product_data[sub_text]">Sub-Text</label>
		<input type="text" name="_cpo_product_data[sub_text]" value="<?php echo $product_data['sub_text']; ?>" size="25" />
	</li>
	<li>
		<label for="_cpo_product_data[in_stock]">In Stock?</label>
	   <input type="checkbox" name="_cpo_product_data[in_stock]" value="True" <?php if ( $product_data['in_stock'] == 'True' ){ echo "checked "; } ?>/>
	</li>
	<li>
		<label for="_cpo_product_data[price]">Price</label>
		<input type="text" name="_cpo_product_data[price]" value="<?php echo $product_data['price']; ?>" size="25" />
	</li>
	<li>
		<label for="_cpo_product_data[item_number]">Item Number</label>
		<input type="text" name="_cpo_product_data[item_number]" value="<?php echo $product_data['item_number']; ?>" size="25" />
	</li>
	<li>
		<label for="_cpo_product_data[shipping]">Base Shipping Rate</label>
		<input type="text" name="_cpo_product_data[shipping]" value="<?php echo $product_data['shipping']; ?>" size="25" />
	</li>
	<li>
		<label for="_cpo_product_data[shipping2]">Added Shipping Per Additional Item</label>
		<input type="text" name="_cpo_product_data[shipping2]" value="<?php echo $product_data['shipping2']; ?>" size="25" />
	</li>
	<li>
		<label for="_cpo_product_data[product_options]">Product Options</label>	
	</li>
	<li>
		<label for="_cpo_product_data[option_group_label]">Option Group Label</label>
		<input type="text" name="_cpo_product_data[option_group_label]" value="<?php echo $product_data['option_group_label']; ?>" size="25" />
	</li>
	<li>
	<span>Label</span><span>price</span><span>In Stock?</span>
	</li>
<?php

for ($i = 0; $i < 10; $i++):

?>
    <li>
        <input type="text" name="_cpo_product_data[product_options][<?php echo $i; ?>][label]" value="<?php echo $product_data['product_options'][$i]['label']; ?>" size="5" />
    	<input type="text" name="_cpo_product_data[product_options][<?php echo $i; ?>][price]" value="<?php echo $product_data['product_options'][$i]['price']; ?>" size="5" />
	   <input type="checkbox" name="_cpo_product_data[product_options][<?php echo $i; ?>][in_stock]" value="True" <?php if ( $product_data['product_options'][$i]['in_stock'] == 'True' ){ echo "checked "; } ?>/>
    </li>
<?php

endfor;

?>	
	<li>
		<label for="_cpo_product_data[quantity_label]">Quantity Label</label>
		<input type="text" name="_cpo_product_data[quantity_label]" value="<?php echo $product_data['quantity_label']; ?>" size="25" />
	</li>
	<li>
		<label for="_cpo_product_data[quantity_units]">Quantity Units</label>
		<input type="text" name="_cpo_product_data[quantity_units]" value="<?php echo $product_data['quantity_units']; ?>" size="25" />
	</li>
	<li>
		<label for="_cpo_product_data[min_quantity]">Min. Quantity</label>
		<input type="text" name="_cpo_product_data[min_quantity]" value="<?php echo $product_data['min_quantity']; ?>" size="25" />
	</li>
<li>
	<label for="_cpo_product_data[note]">Note</label>
	<input type="text" name="_cpo_product_data[note]" value="<?php echo $product_data['note']; ?>" size="25" />
</li>



</ul>

<?php    
} // end cpo_edit_product_box()


/* When the post is saved, saves our custom data */
function cpo_save_postdata( $post_id ) {

  // verify this came from the our screen and with proper authorization,
  // because save_post can be triggered at other times

	$nonce = $_POST['cpo_noncename'];
	if ( !wp_verify_nonce( $nonce, 'cimo_paypal_options' )) {
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
    
    $meta_data = $_POST['_cpo_product_data'];

    // need to check for the in_stock boolean
       if ( $meta_data['in_stock'] != 'True' ){
      $meta_data['in_stock'] = 'False';
         }

	 // check for product options in_stock flag
	 // loop through product_options
	 $options = $meta_data['product_options'];
	 foreach ($options as $i => $option) {
	     if ( $option['in_stock'] != 'True' ){
	         $meta_data['product_options'][$i]['in_stock'] = 'False';
	     }
	 }

	$meta_key = '_cpo_product_data';
    update_post_meta($post_id, $meta_key, $meta_data);

	return $meta_data;
        
} // end function cpo_save_postdata


/********************
Admin Code
*********************/

if (is_admin()) {
	
	// this only adds the options if they don't already exist, so this will populate the options page with defaults.
	add_option('cpo_settings', $paypal_options);

    // creates a new submenu entry in the main settings menu in admin
  	add_action('admin_menu', 'cpo_register_menu');

  	// registers the settings group. Seems to be needed to have a page of options.
	add_action('admin_init', 'cpo_register_settings');
	
	// creates the meta box for editing the paypal options on the product page
	add_action('admin_menu', 'cpo_add_custom_box');
	
	// saves the data when the post is saved
	add_action('save_post', 'cpo_save_postdata');

} // end if is_admin()


/*******************
Output functions

these are used to print out the paypal order forms on the product pages
*******************/

// this prints the form on each of the individual product pages
function cpo_paypal_form($post_id = false) {

    // if post id was not passed then use the current pages post object
    if ( ! $post_id ) {
    	global $post;
    	$post_id = $post->ID;
	}
	
	$paypal_options = get_option('cpo_settings');
	$product_data = get_post_meta($post_id, '_cpo_product_data', true );
	
	if ($product_data && $paypal_options) {
	
	
	// gather up the hidden form elements into one array
	if (isset($paypal_options['action']) && $paypal_options['action'] != '') {
		$form_action = $paypal_options['action'];
		unset( $paypal_options['action'] );
	} else {
	    return false;
	}
	$hidden_form_inputs = $paypal_options;
	
	$product_name = get_the_title($post_id);
	$hidden_form_inputs['item_name'] = $product_name;
	$hidden_form_inputs['item_number'] = $product_data['item_number'];
	$hidden_form_inputs['shipping'] = $product_data['shipping'];
	$hidden_form_inputs['shipping2'] = $product_data['shipping2'];
	if ( $product_data['price'] ) {
		$hidden_form_inputs['amount'] = $product_data['price'];
	}

	// set up the product options data if we have a group_label
	if ( $product_data['option_group_label'] ){ // need a label for the options for the PP form to work
	
		// put all the needed data for the product options into an array
		$option_group_label = $product_data['option_group_label'];
	
		// add the hidden data to the $hidden_form_inputs
		$hidden_form_inputs['on0'] = $option_group_label;
	
		$product_options = $product_data['product_options'];
		
		// check to see if there is a price set for the first option index.
		// if the price is set we will be building an option/price index in the next foreach loop
		if ($product_options[0]['price']) {
			$hidden_form_inputs['option_index'] = 0;
		}
	
		// loop through product_options to remove empty fields
		foreach ($product_options as $index => $option)
		{
			if (!$option['label']) {
				unset($product_options[$index]);
			}
			
			// if we have a price then we need to add hidden inputs to relate the option labels to the prices in paypal
			if ($option['price']) {
				$select_index_label = 'option_select' . $index;
				$amount_index_label = 'option_amount' . $index;
				$label = $option['label'];
				$amount = $option['price'];
				$hidden_form_inputs[$select_index_label] = $label;
				$hidden_form_inputs[$amount_index_label] = $amount;
			}		
		
		}
	}

	// print the form
	// open block

?>
<div class="round-corners order-form block">
	<div class="header-wrap">
		<div class="header-content">

			<h3>Order Online</h3>

		</div><!-- .header-content -->
	</div><!-- .header-wrap -->
	<div class="body-wrap">
		<div class="body-content">
			<h4><?php echo get_the_title($post_id); ?></h4>
			<p class="product-sub-text"><?php echo $product_data['sub_text']; ?></p>
			<form target="paypal" action="<?php echo $form_action; ?>" method="post">

<?php

print_input_hidden($hidden_form_inputs);

// if we have options set for this product
if ($product_data['option_group_label'] ){ // need a label for the options for the PP form to work

?>
<p class="product-options-group-label"><?php echo $product_data['option_group_label']; ?>:</p>
<div class="block-row">
<?php

    if ($product_options[0]['price']) {

        foreach ($product_options as $index => $option) {
        	print_product_option($index, $option, True);
        } // end foreach 
    
    } else {

?>
<div class="inset-box product-option-group">
	<div class="inset-header-wrap">
		<div class="inset-header-content"></div>
	</div><!-- .inset-header-wrap -->
	<div class="inset-body-wrap">
		<div class="inset-body-content">
<?php

        foreach ($product_options as $index => $option) {
        	print_product_option($index, $option);
        } // end foreach 
    
?>
		</div><!-- .inset-body-content -->
	</div><!-- .inset-body-wrap -->
</div><!-- .product-option-group -->
<?php
     
    }

?>
</div><!-- .block-row -->
<?php

} // end if $product_data['product_options']['group_label']

$quantity_label = $product_data['quantity_label'];
if ($product_data['quantity_units']){
    $quantity_label .= ' (' . $product_data['quantity_units'] . ')';
}

?>
<p class="quantity-label"><?php echo $quantity_label; ?>:</p>			

<div class="block-row">

<?php

print_quantity_field($product_data['min_quantity']);


							     if ( $product_data['in_stock'] == 'True' ){
print_submit("Add to Cart", $product_name);
							     } else {
print_out_of_stock();
							     }

?>
</div><!-- .block-row -->

</form>

<?php
		if ($product_data['note']) {
?>
<p class="product-note"><?php echo $product_data['note']; ?></p>
<?php
		}
// print closing html block
?>

		</div> <!-- .body-content -->
	</div><!-- .body-wrap -->
</div><!-- .order-form -->


<?php

	} // end if $product_data && $paypal_options

} // end cpo_paypal_form()




// this function prints out the product components order form table.
function cpo_multi_product_form($products) {

    if ( ! is_array($products) ) {
        return false;
	}

	$paypal_options = get_option('cpo_settings');
	if ( ! is_array( $paypal_options ) ) {
	    return false;
	}

      // gather the data needed for the form and put it into arrays for the html output functions
   	// gather up the hidden form elements into one array
    if (isset($paypal_options['action']) && $paypal_options['action'] != '') {
        $form_action = $paypal_options['action'];
        unset( $paypal_options['action'] );
    } else {
        return false;
    }
    $hidden_form_inputs = $paypal_options;

    foreach ($products as $prod_index => $prod)
    {
    
        $product_data = get_post_meta($prod->ID, '_cpo_product_data', true );

        if ( $product_data ) {

            $product_name = get_the_title($prod->ID);
        	$hidden_form_inputs['item_name'] = $product_name;
        	$hidden_form_inputs['item_number'] = $product_data['item_number'];
        	$hidden_form_inputs['shipping'] = $product_data['shipping'];
        	$hidden_form_inputs['shipping2'] = $product_data['shipping2'];
        	
        	if ( $product_data['price'] ) {
        		$hidden_form_inputs['amount'] = $product_data['price'];
        	}
        
        	// set up the product options data if we have a group_label
        	if ( $product_data['option_group_label'] ){ // need a label for the options for the PP form to work
        	
        		// put all the needed data for the product options into an array
        		$option_group_label = $product_data['option_group_label'];
        	
        		// add the hidden data to the $hidden_form_inputs
        		$hidden_form_inputs['on0'] = $option_group_label;
        	
        		$product_options = $product_data['product_options'];
        		
        		// check to see if there is a price set for the first option index.
        		// if the price is set we will be building an option/price index in the next foreach loop
        		if ($product_options[0]['price']) {
        			$hidden_form_inputs['option_index'] = 0;
        		}
        	
        		// loop through product_options to remove empty fields
        		foreach ($product_options as $index => $option)
        		{
        			if (!$option['label']) {
        				unset($product_options[$index]);
        				continue;
        			}
        			
        			// create an array for use in the html helper function
        			$html_select_options[$index]['value'] = $option['label'];
        			$html_select_options[$index]['text'] = $option['label'] . ' inches';
        			
        			// if we have a price then we need to add hidden inputs to relate the option labels to the prices in paypal
        			if ($option['price']) {
        				$select_index_label = 'option_select' . $index;
        				$amount_index_label = 'option_amount' . $index;
        				$label = $option['label'];
        				$amount = $option['price'];
        				$hidden_form_inputs[$select_index_label] = $label;
        				$hidden_form_inputs[$amount_index_label] = $amount;
        				
        				// append the price to the html select options
        				;
        				$html_select_options[$index]['text'] .= ' - $' . $option['price'];
        				
        			}
        			
        			// set the first option to be the selected option in the form
        			if ( $index == 0 ) {
        			    $html_select_options[$index]['selected'] = true;
        		    }
        		    
        		}
        	}
        	// end of the data setup
            ?>
<form target="paypal" action="<?php echo $form_action; ?>" method="post">
<?php print_input_hidden($hidden_form_inputs); ?>
<tr <?php if ($prod_index == 0) { echo ' class="first"'; } ?>>
<td>
<h4><?php echo get_the_title($prod->ID); ?></h4>
<p class="product-sub-text"><?php echo $product_data['sub_text']; ?></p>
</td>

<td>
<?php
// if we have options set for this product
if ($product_data['option_group_label'] ){ // need a label for the options for the PP form to work
    $html = new html_helper();
    echo '<p>' . $product_data['option_group_label'] . '</p>';
    echo $html->select( 'os0', $html_select_options );
} // end if $product_data['product_options']['group_label']
else { // we just have a product display the price
    echo '<p>$' . $product_data['price'] . '</p>';
}
?>
</td><td>
<?php

$quantity_label = $product_data['quantity_label'];
if ($product_data['quantity_units']){
    $quantity_label .= ' (' . $product_data['quantity_units'] . ')';
}

?>
<p class="quantity-label"><?php echo $quantity_label; ?>:</p>

<?php print_quantity_field($product_data['min_quantity']); ?>

</td><td>

<?php print_submit("Add to Cart", $product_name); ?>

</td>

</tr>
</form>
            <?php
        } // end if $product_data
        
    }

} // end cpo_multi_product_form()



function print_product_option($index, $option, $inset = False) {

    if ($inset) {

?>
<div class="inset-box product-option">
	<div class="inset-header-wrap">
		<div class="inset-header-content"></div>
	</div><!-- .inset-header-wrap -->
	<div class="inset-body-wrap">
		<div class="inset-body-content">			
			<div class="product-option-row">
<?php

    } else {

?>
<div class="option">
<?php 

    }
    
?>
                    <label for="product-option-<?php echo $index; ?>" class="product-option-label">
                    	<?php echo $option['label']; ?>
                    </label>
		    <?php if ($option['in_stock'] == 'True'):?>
                    <input type="radio" name="os0" value="<?php echo $option['label']; ?>" id="product-option-<?php echo $index; ?>" <?php echo ($index == 0 ? "checked " : "")?>/>
<?php
endif;


		if ($option['price']) {

?>
			</div><!-- .product-option-row -->
			<div class="product-option-row">
                <label for="product-option-<?php echo $index; ?>" class="product-option-price">
                	$<?php echo $option['price']; ?>
                </label>
			</div><!-- .product-option-row -->
<?php

		} // end if $product_data['product_options']['opt$i_price']

    if ($inset) {

?>
		</div><!-- .inset-body-content -->
	</div><!-- .inset-body-wrap -->
</div><!-- .product-option -->
<?php

    } else {
    
?>
</div><!-- .option -->
<?php
    
    }
} // end print_product_option()


function print_quantity_field($default) {
?>

<div class="quantity">
	<input name="quantity" type="text" value="<?php echo $default; ?>" size="5" maxlength="3" />
</div><!-- .quantity -->

<?php
}

function print_submit($label, $ga_tracking_label = False) {

?>
<div class="button add-to-cart">
	<div class="button-top-wrap">
		<div class="button-top-content"></div>
	</div><!-- .button-top-wrap -->
	<div class="button-body-wrap">
		<div class="button-body-content">
<?php

    $input_tag = '<input type="submit" value="%s" %s/>';
    $extra_attrs = '';
    if ($ga_tracking_label) {
        $extra_attrs .= 'onClick="_gaq.push([\'_trackEvent\', \'Products\', \'Add to Cart\', \'' . $ga_tracking_label . '\']); "';
    }
    printf( $input_tag, $label, $extra_attrs);

?>
		</div><!-- .button-body-content -->
	</div><!-- .button-body-wrap -->
</div><!-- .add-to-cart -->
<img alt="" border="0" src="https://www.paypal.com/en_US/i/scr/pixel.gif" width="1" height="1" />
<?php

} // end print_submit()


function print_out_of_stock(){

?>
<div class="button out-of-stock">
	<div class="button-top-wrap">
		<div class="button-top-content"></div>
	</div><!-- .button-top-wrap -->
	<div class="button-body-wrap">
		<div class="button-body-content">
		  <p>Out of Stock</p>
		</div><!-- .button-body-content -->
	</div><!-- .button-body-wrap -->
</div><!-- .add-to-cart -->
<?php

} // end print_out_of_stock()



function print_input_hidden($input_args) {
	foreach ($input_args as $name => $value)
	{
		echo '<input type="hidden" name="' . $name . '" value="' . $value . '" />' . "\n"; 
	}	
}

?>
