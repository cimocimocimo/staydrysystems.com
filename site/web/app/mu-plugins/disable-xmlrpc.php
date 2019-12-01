<?php
/*
  Plugin Name:  Disable XMLRPC
  Plugin URI:   http://cimolini.com/
  Description:  Disables XMLRPC request handling.
  Version:      1.0.0
  Author:       Aaron Cimolini
  Author URI:   http://cimolini.com/
  License:      MIT License
*/

// https://developer.wordpress.org/reference/hooks/xmlrpc_enabled/
add_filter( 'xmlrpc_enabled', '__return_false' );
