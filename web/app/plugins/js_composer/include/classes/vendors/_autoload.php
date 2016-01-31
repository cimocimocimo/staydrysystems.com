<?php
/**
 * Interface for third-party plugins classes loader.
 */
interface Vc_Vendor_Interface {
	public function load();
}
// Here comes the list of vendors
// $vendors_list = array('jwplayer');
$vendors_list = array();
// default prefix for auto loaded class
$vendor_class_prefix = 'plugins/class-vc-vendor-';
foreach($vendors_list as $vendor_name) {
	$vendor_file = vc_path_dir('VENDORS_DIR', $vendor_class_prefix.$vendor_name.'.php');
	require_once $vendor_file;
	$vendor_class_name = 'Vc_Vendor_'.ucfirst($vendor_name);
	vc_add_vendor(new $vendor_class_name());
}