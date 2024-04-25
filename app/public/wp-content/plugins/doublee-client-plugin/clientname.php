<?php
/**
 * Plugin name: Client Name Plugin
 * Description: Custom post types, taxonomies, and functionality for Client Name.
 *
 * Author:      		Double-E Design
 * Author URI:  		https://www.doubleedesign.com.au
 * Version:     		1.0.0
 * Requires at least: 	6.3.2
 * Requires PHP: 		8.1.9
 * Text Domain: 		clientname
 *
 * @package ClientName
 */

// Load the plugin files
require_once('class-clientname.php');

/**
 * Create activation and deactivation hooks and functions, so we can do things
 * when the plugin is activated, deactivated, or uninstalled.
 * These need to be in this plugin root file to work, so to run our plugin's functions from within its
 * classes, we simply call a function (from the plugin class) inside the function that needs to be here.
 * @return void
 */
function activate_clientname(): void {
	ClientName::activate();
}
function deactivate_clientname(): void {
	ClientName::deactivate();
}
function uninstall_clientname(): void {
	ClientName::uninstall();
}
register_activation_hook(__FILE__, 'activate_clientname');
register_deactivation_hook(__FILE__, 'deactivate_clientname');
register_uninstall_hook(__FILE__, 'uninstall_clientname');


// Load and run the rest of the plugin
new ClientName();


/**
 * Log actions and filters that are run.
 * For debugging purposes only; comment out when not in use!
 * @wp-hook
 *
 * @return void
 */
function doublee_log_all_actions(): void {
	foreach($GLOBALS['wp_actions'] as $item => $count) {
		error_log(print_r($item, true));
	}
	foreach($GLOBALS['wp_filter'] as $item => $count) {
		error_log(print_r($item, true));
	}
}
//add_action('shutdown', 'doublee_log_all_actions');
