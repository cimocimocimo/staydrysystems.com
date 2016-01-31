<?php

/**
 * woocommerce_tax_rates_importer function.
 *
 * @access public
 * @return void
 */
function woocommerce_per_product_shipping_csv() {

	// Load Importer API
	require_once ABSPATH . 'wp-admin/includes/import.php';

	if ( ! class_exists( 'WP_Importer' ) ) {
		$class_wp_importer = ABSPATH . 'wp-admin/includes/class-wp-importer.php';
		if ( file_exists( $class_wp_importer ) )
			require $class_wp_importer;
	}

	// includes
	require dirname( __FILE__ ) . '/importer.php';

	// Dispatch
	$importer = new WC_CSV_Per_Product_Shipping_Import();
	$importer->dispatch();
}

/**
 * per_product_shipping_product_options function.
 *
 * @access public
 * @return void
 */
function woocommerce_per_product_shipping_product_options() {
	global $post, $wpdb;

	echo '</div><div class="options_group per_product_shipping">';

	woocommerce_wp_checkbox( array( 'id' => '_per_product_shipping', 'label' => __('Per-product shipping', 'wc_shipping_per_product'), 'description' => __('Enable per-product shipping cost', 'wc_shipping_per_product')  ) );

	woocommerce_per_product_shipping_rules();
	?>
	<script type="text/javascript">
		jQuery( function() {

			function make_per_product_shipping_sortable() {
				jQuery('.per_product_shipping .rules tbody').sortable({
					items:'tr',
					cursor:'move',
					axis:'y',
					scrollSensitivity:40,
					forcePlaceholderSize: true,
					helper: 'clone',
					opacity: 0.65,
					placeholder: 'wc-metabox-sortable-placeholder',
					start:function(event,ui){
						ui.item.css('background-color','#f6f6f6');
					},
					stop:function(event,ui){
						ui.item.removeAttr('style');
					}
				});
			}

			make_per_product_shipping_sortable();

			jQuery('input#_per_product_shipping').change(function() {
				if ( jQuery('input#_per_product_shipping').is(':checked') ) {
					jQuery(this).closest( '.per_product_shipping' ).find('.rules').show();
				} else {
					jQuery(this).closest( '.per_product_shipping' ).find('.rules').hide();
				}
			}).change();

			jQuery('input.variable_is_virtual').change(function() {
				if ( jQuery(this).is(':checked') ) {
					jQuery(this).closest('.woocommerce_variation').find( '.enable_per_product_shipping' ).parent( 'label' ).hide();
				} else {
					jQuery(this).closest('.woocommerce_variation').find( '.enable_per_product_shipping' ).parent( 'label' ).show();
				}
			}).change();

			jQuery('.woocommerce_variations')
				.on( 'change', '.enable_per_product_shipping', function() {
					if ( jQuery(this).is(':checked') ) {
						jQuery(this).closest('.woocommerce_variation').find( '.per_product_shipping' ).find('.rules').show();
					} else {
						jQuery(this).closest('.woocommerce_variation').find( '.per_product_shipping' ).find('.rules').hide();
					}
				} )
				.bind( 'woocommerce_variations_added', function() {
					make_per_product_shipping_sortable();
				} );

			jQuery('.enable_per_product_shipping').change();

			jQuery('#woocommerce-product-data')
				.on( 'focus', '.per_product_shipping input', function() {
					jQuery('.per_product_shipping tr').removeClass('current');
					jQuery(this).closest('tr').addClass('current');
				} )
				.on( 'click', '.per_product_shipping .remove', function() {
					var $tbody = jQuery(this).closest('.per_product_shipping').find('tbody');
					if ( $tbody.find('tr.current').size() > 0 ) {
						$tbody.find('tr.current').find('input').val('');
						$tbody.find('tr.current').hide();
					} else {
						alert('<?php _e( 'No row selected', 'wc_shipping_per_product' ); ?>');
					}
					return false;
				} )
				.on( 'click', '.per_product_shipping .insert', function() {
					var $tbody = jQuery(this).closest('.per_product_shipping').find('tbody');
					var postid = jQuery(this).data('postid');
					var code = '<tr>\
						<td class="sort">&nbsp;</td>\
						<td class="country"><input type="text" value="" placeholder="*" name="per_product_country[' + postid + '][new][]" /></td>\
						<td class="state"><input type="text" value="" placeholder="*" name="per_product_state[' + postid + '][new][]" /></td>\
						<td class="postcode"><input type="text" value="" placeholder="*" name="per_product_postcode[' + postid + '][new][]" /></td>\
						<td class="cost"><input type="text" value="" placeholder="0.00" name="per_product_cost[' + postid + '][new][]" /></td>\
						<td class="item_cost"><input type="text" value="" placeholder="0.00" name="per_product_item_cost[' + postid + '][new][]" /></td>\
					</tr>';

					if ( $tbody.find('tr.current').size() > 0 ) {
						$tbody.find('tr.current').after( code );
					} else {
						$tbody.append( code );
					}
					return false;
				} )
				.on( 'click', '.per_product_shipping .export', function() {
					var postid = jQuery(this).data('postid');
					var csv_data = "data:application/csv;charset=utf-8,<?php _e( 'Product ID', 'wc_shipping_per_product' ); ?>,<?php _e( 'Country code', 'wc_shipping_per_product' ); ?>,<?php _e( 'State/county code', 'wc_shipping_per_product' ); ?>,<?php _e( 'Zip/postal code', 'wc_shipping_per_product' ); ?>,<?php _e( 'Cost', 'wc_shipping_per_product' ); ?>,<?php _e( 'Item Cost', 'wc_shipping_per_product' ); ?>\n";

					jQuery(this).closest('.per_product_shipping').find('tbody tr:not(.sort)').each(function() {
						var row = postid + ',';
						jQuery(this).find('input').each(function() {
							var val = jQuery(this).val();
							if ( ! val )
								val = jQuery(this).attr('placeholder');
							row = row + val + ',';
						});
						row = row.substring( 0, row.length - 1 );
						csv_data = csv_data + row + "\n";
					});

					jQuery(this).attr( 'href', encodeURI( csv_data ) );

					return true;
				} );
		});
	</script>
	<?php
}

add_action( 'woocommerce_product_options_shipping', 'woocommerce_per_product_shipping_product_options' );


/**
 * woocommerce_per_product_shipping_variation_options function.
 *
 * @access public
 * @return void
 */
function woocommerce_per_product_shipping_variation_options( $loop, $variation_data, $variation ) {
	?>
	<label><input type="checkbox" class="checkbox enable_per_product_shipping" name="_per_variation_shipping[<?php echo $variation->ID; ?>]" <?php checked( get_post_meta( $variation->ID, '_per_product_shipping', true ), "yes" ); ?> /> <?php _e( 'Per-variation shipping', 'woocommerce' ); ?></label>
	<?php
}

add_action( 'woocommerce_variation_options', 'woocommerce_per_product_shipping_variation_options', 10, 3 );

/**
 * woocommerce_per_product_shipping_variation_options function.
 *
 * @access public
 * @return void
 */
function woocommerce_per_product_shipping_after_variable_attributes( $loop, $variation_data, $variation ) {

	echo '<tr class="per_product_shipping per_variation_shipping"><td colspan="2">';

	woocommerce_per_product_shipping_rules( $variation->ID );

	echo '</td></tr>';
}

add_action( 'woocommerce_product_after_variable_attributes', 'woocommerce_per_product_shipping_after_variable_attributes', 10, 3 );


/**
 * woocommerce_per_product_shipping_rules function.
 *
 * @access public
 * @return void
 */
function woocommerce_per_product_shipping_rules( $post_id = 0 ) {
	global $post, $wpdb;

	if ( ! $post_id )
		$post_id = $post->ID;
	?>
	<div class="rules">

		<?php woocommerce_wp_checkbox( array( 'id' => '_per_product_shipping_add_to_all[' . $post_id . ']', 'label' => __('Add costs to all rates', 'wc_shipping_per_product'), 'description' => __('Add per-product shipping cost to all shipping method rates?', 'wc_shipping_per_product'), 'value' => get_post_meta( $post_id, '_per_product_shipping_add_to_all', true ) ) ); ?>

		<table class="widefat">
			<thead>
				<tr>
					<th>&nbsp;</th>
					<th><?php _e( 'Country code', 'wc_shipping_per_product' ); ?>&nbsp;<a class="tips" data-tip="<?php _e('A 2 digit country code, e.g. US. Leave blank to apply to all.', 'wc_shipping_per_product'); ?>">[?]</a></th>
					<th><?php _e( 'State/county code', 'wc_shipping_per_product' ); ?>&nbsp;<a class="tips" data-tip="<?php _e('A 2 digit state code, e.g. AL. Leave blank to apply to all.', 'wc_shipping_per_product'); ?>">[?]</a></th>
					<th><?php _e( 'Zip/postal code', 'wc_shipping_per_product' ); ?>&nbsp;<a class="tips" data-tip="<?php _e('Postcode for this rule. Wildcards (*) can be used. Leave blank to apply to all areas.', 'wc_shipping_per_product'); ?>">[?]</a></th>
					<th class="cost"><?php _e( 'Line Cost (ex. tax)', 'wc_shipping_per_product' ); ?>&nbsp;<a class="tips" data-tip="<?php _e('Decimal cost for the line as a whole.', 'wc_shipping_per_product'); ?>">[?]</a></th>
					<th class="item_cost"><?php _e( 'Item Cost (ex. tax)', 'wc_shipping_per_product' ); ?>&nbsp;<a class="tips" data-tip="<?php _e('Decimal cost for the item (multiplied by qty).', 'wc_shipping_per_product'); ?>">[?]</a></th>
				</tr>
			</thead>
			<tfoot>
				<tr>
					<th colspan="6">
						<a href="#" class="button plus insert" data-postid="<?php echo $post_id; ?>"><?php _e( 'Insert row', 'wc_shipping_per_product' ); ?></a>
						<a href="#" class="button minus remove"><?php _e( 'Remove row', 'wc_shipping_per_product' ); ?></a>

						<a href="#" download="per-product-rates-<?php echo $post_id ?>.csv" class="button export" data-postid="<?php echo $post_id; ?>"><?php _e( 'Export CSV', 'wc_shipping_per_product' ); ?></a>
						<a href="<?php echo admin_url('admin.php?import=woocommerce_per_product_shipping_csv'); ?>" class="button import"><?php _e( 'Import CSV', 'wc_shipping_per_product' ); ?></a>
					</th>
				</tr>
			</tfoot>
			<tbody>
				<?php
					$rules = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM {$wpdb->prefix}woocommerce_per_product_shipping_rules WHERE product_id = %d ORDER BY rule_order;", $post_id ) );

					foreach ( $rules as $rule ) {
						?>
						<tr>
							<td class="sort">&nbsp;</td>
							<td class="country"><input type="text" value="<?php echo esc_attr( $rule->rule_country ); ?>" placeholder="*" name="per_product_country[<?php echo $post_id; ?>][<?php echo $rule->rule_id ?>]" /></td>
							<td class="state"><input type="text" value="<?php echo esc_attr( $rule->rule_state ); ?>" placeholder="*" name="per_product_state[<?php echo $post_id; ?>][<?php echo $rule->rule_id ?>]" /></td>
							<td class="postcode"><input type="text" value="<?php echo esc_attr( $rule->rule_postcode ); ?>" placeholder="*" name="per_product_postcode[<?php echo $post_id; ?>][<?php echo $rule->rule_id ?>]" /></td>
							<td class="cost"><input type="text" value="<?php echo esc_attr( $rule->rule_cost ); ?>" placeholder="0.00" name="per_product_cost[<?php echo $post_id; ?>][<?php echo $rule->rule_id ?>]" /></td>
							<td class="item_cost"><input type="text" value="<?php echo esc_attr( $rule->rule_item_cost ); ?>" placeholder="0.00" name="per_product_item_cost[<?php echo $post_id; ?>][<?php echo $rule->rule_id ?>]" /></td>
						</tr>
						<?php
					}
				?>
			</tbody>
		</table>
	</div>
	<?php
}


/**
 * woocommerce_per_product_shipping_product_options_save function.
 *
 * @access public
 * @return void
 */
function woocommerce_per_product_shipping_product_options_save( $parent_post_id ) {
	global $wpdb;

	// Enabled or Disabled
	if ( ! empty( $_POST['_per_product_shipping'] ) ) {
		update_post_meta( $parent_post_id, '_per_product_shipping', 'yes' );
	} else {
		delete_post_meta( $parent_post_id, '_per_product_shipping' );
		delete_post_meta( $parent_post_id, '_per_product_shipping_add_to_all' );
	}

	// Get posted post ids
	$post_ids   = isset( $_POST['per_product_country'] ) ? array_keys( $_POST['per_product_country'] ) : array();

	// Update rules
	foreach ( $post_ids as $post_id ) {

		if ( $parent_post_id == $post_id && empty( $_POST['_per_product_shipping'] ) ) {
			$wpdb->query( "DELETE FROM {$wpdb->prefix}woocommerce_per_product_shipping_rules WHERE product_id = {$post_id};" );
			continue;
		}

		$enabled    = ! empty( $_POST['_per_variation_shipping'][ $post_id ] ) ? $_POST['_per_variation_shipping'][ $post_id ] : '';
		$countries  = ! empty( $_POST['per_product_country'][ $post_id ] ) ? $_POST['per_product_country'][ $post_id ] : '';
		$states     = ! empty( $_POST['per_product_state'][ $post_id ] ) ? $_POST['per_product_state'][ $post_id ] : '';
		$postcodes  = ! empty( $_POST['per_product_postcode'][ $post_id ] ) ? $_POST['per_product_postcode'][ $post_id ] : '';
		$costs      = ! empty( $_POST['per_product_cost'][ $post_id ] ) ? $_POST['per_product_cost'][ $post_id ] : '';
		$item_costs = ! empty( $_POST['per_product_item_cost'][ $post_id ] ) ? $_POST['per_product_item_cost'][ $post_id ] : '';
		$i          = 0;

		if ( $enabled || $parent_post_id == $post_id ) {

			update_post_meta( $post_id, '_per_product_shipping', 'yes' );
			update_post_meta( $post_id, '_per_product_shipping_add_to_all', ! empty( $_POST['_per_product_shipping_add_to_all'][ $post_id ] ) ? 'yes' : 'no' );

			foreach ( $countries as $key => $value ) {

				if ( $key == 'new' ) {

					foreach ( $value as $new_key => $new_value ) {

						$i++;

						if ( empty( $countries[ $key ][ $new_key ] ) && empty( $states[ $key ][ $new_key ] ) && empty( $postcodes[ $key ][ $new_key ] ) && empty( $costs[ $key ][ $new_key ] ) && empty( $item_costs[ $key ][ $new_key ] ) ) {

							// dont save

						} else {

							$wpdb->insert(
								$wpdb->prefix . 'woocommerce_per_product_shipping_rules',
								array(
									'rule_country' 		=> esc_attr( $countries[ $key ][ $new_key ] ),
									'rule_state' 		=> esc_attr( $states[ $key ][ $new_key ] ),
									'rule_postcode' 	=> esc_attr( $postcodes[ $key ][ $new_key ] ),
									'rule_cost' 		=> esc_attr( $costs[ $key ][ $new_key ] ),
									'rule_item_cost' 	=> esc_attr( $item_costs[ $key ][ $new_key ] ),
									'rule_order'		=> $i,
									'product_id'		=> absint( $post_id )
								)
							);

						}

					}

				} else {

					$i++;

					if ( empty( $countries[ $key ] ) && empty( $states[ $key ] ) && empty( $postcodes[ $key ] ) && empty( $costs[ $key ] ) && empty( $item_costs[ $key ] ) ) {

						$wpdb->query( $wpdb->prepare( "DELETE FROM {$wpdb->prefix}woocommerce_per_product_shipping_rules WHERE product_id = %d AND rule_id = %s;", absint( $post_id ), absint( $key ) ) );

					} else {

						$wpdb->update(
							$wpdb->prefix . 'woocommerce_per_product_shipping_rules',
							array(
								'rule_country' 		=> esc_attr( $countries[ $key ] ),
								'rule_state' 		=> esc_attr( $states[ $key ] ) ,
								'rule_postcode' 	=> esc_attr( $postcodes[ $key ] ),
								'rule_cost' 		=> esc_attr( $costs[ $key ] ),
								'rule_item_cost' 	=> esc_attr( $item_costs[ $key ] ),
								'rule_order'		=> $i
							),
							array(
								'product_id' 		=> absint( $post_id ),
								'rule_id'	 		=> absint( $key )
							),
							array(
								'%s',
								'%s',
								'%s',
								'%s',
								'%s',
								'%d'
							),
							array(
								'%d',
								'%d'
							)
						);

					}

				}

			}
		} else {
			delete_post_meta( $post_id, '_per_product_shipping' );
			delete_post_meta( $post_id, '_per_product_shipping_add_to_all' );
		}
	}
}

add_action( 'woocommerce_process_product_meta', 'woocommerce_per_product_shipping_product_options_save' ) ;


/**
 * woocommerce_per_product_shipping_styles function.
 *
 * @access public
 * @return void
 */
function woocommerce_per_product_shipping_styles() {
	wp_enqueue_style( 'per_product_style', plugins_url( 'assets/css/admin.css', dirname( __FILE__ ) ) );
}

add_action( 'admin_enqueue_scripts', 'woocommerce_per_product_shipping_styles' );