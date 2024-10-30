<?php
/*
Plugin Name: C4D Plugin Manager
Plugin URI: http://coffee4dev.com/
Description: Create page options depend on Redux Framework for plugins, so you can manage all plugins in one place. Just hook your redux config to action c4d-plugin-manager-section.
Author: Coffee4dev.com
Author URI: http://coffee4dev.com/
Text Domain: c4d-plugin-manager
Version: 3.0.3
*/

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// Load Redux
if (!class_exists('ReduxFramework') && file_exists(dirname(__FILE__) . '/redux-framework/redux-core/framework.php')) {
	require_once(dirname(__FILE__) . '/redux-framework/redux-core/framework.php');
}

define('C4DPLUGINMANAGER_PLUGIN_URI', plugins_url('', __FILE__));
define('C4DPMANAGER_PLUGIN_URI', plugins_url('', __FILE__));

add_action( 'plugins_loaded', 'c4d_plugin_manager_redux', 10000 );
add_action( 'admin_enqueue_scripts', 'c4d_plugin_manager_scripts_admin' );
add_filter( 'plugin_row_meta', 'c4d_plugin_manager_plugin_row_meta', 10, 2 );

! is_admin() && add_action( 'init', 'c4d_plugin_manager_init' );

function c4d_plugin_manager_init() {
	global $c4d_plugin_manager;
	if (isset($c4d_plugin_manager['c4d-plugin-manager-js-css-optimize']) && $c4d_plugin_manager['c4d-plugin-manager-js-css-optimize'] && WP_DEBUG == false) {
		define('C4DPLUGINMANAGER_PLUGIN_URI', $c4d_plugin_manager['c4d-plugin-manager-js-css-optimize']);
	}
}

function c4d_plugin_manager_plugin_row_meta( $links, $file ) {
  if ( strpos( $file, basename(__FILE__) ) !== false ) {
    $new_links = array(
      'visit' => '<a href="http://coffee4dev.com">Visit Plugin Site</a>',
      'premium' => '<a href="http://coffee4dev.com">Premium Support</a>'
    );
    $links = array_merge( $links, $new_links );
  }
  return $links;
}

function c4d_plugin_manager_scripts_admin() {
  wp_enqueue_style( 'c4d-woo-bs-admin-style', C4DPLUGINMANAGER_PLUGIN_URI.'/assets/admin.css' );
}

function c4d_plugin_manager_redux() {
	
	require_once(dirname(__FILE__).'/redux-config.php');
}

function c4d_pm_remove_ver_css_js( $src, $handle ) {
	global $c4d_plugin_manager;
	if (isset($c4d_plugin_manager['c4d-plugin-manager-js-css-version']) && $c4d_plugin_manager['c4d-plugin-manager-js-css-version'] == 1) {
		$handles_with_version = [ 'style' ]; // <-- Adjust to your needs!

	    if ( strpos( $src, 'ver=' ) && ! in_array( $handle, $handles_with_version, true ) )
	        $src = remove_query_arg( 'ver', $src );

	    return $src;
	}
	return $src;
}
