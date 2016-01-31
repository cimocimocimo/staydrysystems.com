<?php
/**
 * Uninstall CaptivaToolKit.
 *
 * @package CaptivaToolKit
 * @since  1.0
 * @author Colm Troy
 */

/* Make sure we're actually uninstalling the plugin. */
if ( !defined( 'WP_UNINSTALL_PLUGIN' ) )
	wp_die( sprintf( __( '%s should only be called when uninstalling the plugin.', 'captiva' ), '<code>' . __FILE__ . '</code>' ) );


// Delete Plugin Options
delete_option( 'captivatoolkit_options' );