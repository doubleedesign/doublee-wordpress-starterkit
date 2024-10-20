<?php

/**
 * Hooks and filters for automatically handling content such as excerpts
 * and behaviour like redirects
 * (as opposed to the utilities which are called within templates when wanted)
 */
class Starterkit_Content_Handling {
	public function __construct() {
		add_filter('template_include', [$this, 'reroute_to_404']);
		add_action('template_redirect', [$this, 'redirect_to_url']);
		add_filter('get_the_excerpt', [$this, 'get_excerpt_from_acf']);
	}

    /**
     * Account for content in ACF flexible modules when getting the excerpt
     * @param $excerpt
     *
     * @return false|mixed|string
     */
    function get_excerpt_from_acf($excerpt): mixed {
        if(!$excerpt) {
            // Find the first set of flexible modules, if there are any
            $field_name = Starterkit_Theme_Frontend_Utils::get_name_of_first_acf_field_name_of_type('flexible_content');
            $modules = get_field($field_name);

            // If there's modules, find the first one with a WYSIWYG or textarea field and get its value
            if($field_name && $modules) {
                $excerpt = Starterkit_Theme_Frontend_Utils::get_first_acf_subfield_value_of_type($modules, array(
                    'wysiwyg',
                    'textarea'
                ), $field_name);
            }
        }

        return $excerpt;
    }


    /**
	 * Reroute page to 404 as per ACF field page_inaccessible
	 *
	 * @param $template
	 *
	 * @return mixed|string
	 */
	function reroute_to_404($template): mixed {
		if(function_exists('get_field') && get_field('page_inaccessible')) {
			return locate_template('404.php');
		}
		else {
			return $template;
		}
	}


	/**
	 * Redirect to a URL as per ACF field page_redirect
	 */
	function redirect_to_url(): void {
		if(function_exists('get_field')) {
			$redirect = get_field('page_redirect');
			if($redirect && $redirect['url']) {
				wp_redirect($redirect['url']);
			}
		}
	}
}
