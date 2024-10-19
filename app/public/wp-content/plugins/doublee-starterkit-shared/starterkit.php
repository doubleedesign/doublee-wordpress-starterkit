<?php
/**
 * Plugin name: Theme Starter Kit - Common
 * Description: Parent themes can't have parent themes, but my starter kit themes share a bunch of code. This plugin is to keep that centralised and in sync.
 *
 * Author:      		Double-E Design
 * Author URI:  		https://www.doubleedesign.com.au
 * Version:     		1.0.0
 * Requires at least: 	6.3.2
 * Requires PHP: 		8.1.9
 * Text Domain: 		starterkit
 *
 * @package Starterkit
 */

require_once('inc/class-frontend.php');
require_once('inc/class-menus.php');
require_once('inc/class-admin.php');
require_once('inc/class-tinymce.php');
require_once('inc/class-site-health.php');
require_once('inc/class-utils-cms.php');
require_once('inc/class-utils-frontend.php');
require_once('inc/class-content-handling.php');

function init_theme_foundation(): void {
    new Starterkit_Site_Health();
    new Starterkit_Menus();
}
add_action('init', 'init_theme_foundation', 10);

function init_more_theme_stuff(): void {
    new Starterkit_Common_Frontend();
    new Starterkit_Shared_Admin();
    new Starterkit_Shared_TinyMCE();
    new Starterkit_Theme_CMS_Utils();
    new Starterkit_Theme_Frontend_Utils();
    new Starterkit_Content_Handling();
}
add_action('after_setup_theme', 'init_more_theme_stuff', 10);
