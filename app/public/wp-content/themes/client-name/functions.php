<?php
/**
 * Theme functions and definitions
 * @link    https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package clientname
 */

require_once('inc/cms/class-cms.php');
require_once('inc/frontend/class-frontend.php');

function init_theme(): void {
	new ClientName_CMS();
	new ClientName_Frontend();
}
add_action('init', 'init_theme', 11);

/**
 * Filter out any post types that should not have breadcrumbs
 * @param $post_types
 * @return array
 */
function clientname_breadcrumbable_post_types($post_types): array {
	return array_diff($post_types, []);
}
add_filter('breadcrumbs_filter_post_types', 'clientname_breadcrumbable_post_types');


/**
 * Define constants
 * See https://stackoverflow.com/questions/1290318/php-constants-containing-arrays if using PHP < 7
 */
function clientname_register_constants(): void {
	define('THEME_VERSION', '1.0.0');
	if (!defined('PAGE_FOR_POSTS')) {
		define('PAGE_FOR_POSTS', get_option('page_for_posts'));
	}

	if (class_exists('ACF')) {
		// Get it from options table instead of using ACF get_field()
		// due to loading order of ACF and theme
		$acf_gmaps_key = get_option('options_google_maps_api_key');
	}
	if (isset($acf_gmaps_key)) {
		define('GMAPS_KEY', $acf_gmaps_key);
	}
	else {
		define('GMAPS_KEY', '');
	}
}
add_action('after_setup_theme', 'clientname_register_constants', 20);


/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 * Priority 0 to make it available to lower priority callbacks.
 * @wp-hook
 * @global int $content_width
 */
function clientname_content_width(): void {
	$GLOBALS['content_width'] = apply_filters('clientname_content_width', 640);
}
add_action('after_setup_theme', 'clientname_content_width', 0);
