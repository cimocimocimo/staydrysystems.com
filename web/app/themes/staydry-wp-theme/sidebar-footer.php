<?php
/**
 * The Footer widget areas.
 *
 * @package WordPress
 * @subpackage staydry-wp-theme
 */

	/* If none of the sidebars have widgets, then let's bail early. */
	if (   ! is_active_sidebar( 'first-footer-widget-area'  )
		&& ! is_active_sidebar( 'second-footer-widget-area' )
		&& ! is_active_sidebar( 'third-footer-widget-area'  )
		&& ! is_active_sidebar( 'fourth-footer-widget-area' )
	)
		return;
	// If we get this far, we have widgets. Let do this.
	
// set up labels for the names of the possible widget areas
$column_labels = array('first', 'second', 'third', 'fourth');
$label_suffix = '-footer-widget-area';

// loop through the 4 possible widget areas and test to see if they are active if they are not active then unset the label
foreach ($column_labels as $key => $label)
{
	if ( ! is_active_sidebar( $label . $label_suffix ) ){
		unset( $column_labels[$key] );
	}
}

// based on the number of columns set the column width we'll use for the class to apply the correct css code to the divs
$num_columns = count($column_labels);
switch ($num_columns)
{
	case 4:
		$col_width = 'one-quarter';
		break;
	case 3:
		$col_width = 'one-third';
		break;
	case 2:
		$col_width = 'one-half';
		break;
	default:
		$col_width = 'single';
		break;
}
		
// we've only gotten this far because there is at least one footer widget area. do while it is
do {
$label = array_shift($column_labels);
$div_id = $label . '-footer-menu';
$div_class = $col_width . ' column';
if ( count($column_labels) == 0 )
	$div_class .= ' last';
$ul_class = 'footer-menu';

?>

				<div id="<?php echo $div_id; ?>" class="<?php echo $div_class; ?>">
						<?php dynamic_sidebar( $label . $label_suffix ); ?>
				</div><!-- #<?php echo $div_id; ?> .<?php echo $div_class; ?> -->

<?php
} while ( count($column_labels) > 0 );
?>
