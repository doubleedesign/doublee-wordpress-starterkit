<?php
/**
 * Handling of front-end styles, scripts, etc.
 */
class Starterkit_Common_Frontend {

	public function __construct() {
		add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend']);
        add_filter('script_loader_tag', [$this, 'script_type_module'], 10, 3);
        add_action('wp_head', [$this, 'set_icon_from_acf_field']);
	}


	/**
	 * Enqueue front-end scripts and styles
	 */
	function enqueue_frontend(): void {
		if(is_singular() && comments_open() && get_option('thread_comments')) {
			wp_enqueue_script('comment-reply');
		}

		wp_enqueue_style('starterkit-style', get_template_directory_uri() . '/style.css', array(), THEME_STARTERKIT_VERSION);

        wp_enqueue_script('vue-loader', get_template_directory_uri() . '/common/js/vendor/vue3-sfc-loader.js');
        wp_enqueue_script('theme-vue', get_template_directory_uri() . '/common/js/vue-components.js', array(
            'vue-loader'
        ), THEME_STARTERKIT_VERSION, true);


		if(defined('GMAPS_KEY') && GMAPS_KEY) {
			wp_enqueue_script('gmaps', 'https://maps.googleapis.com/maps/api/js?key=' . GMAPS_KEY, '', '3', true);
		}
	}


    /**
     * Add type=module to the theme scripts
     *
     * @param $tag
     * @param $handle
     * @param $src
     *
     * @return mixed|string
     */
    function script_type_module($tag, $handle, $src): mixed {
        if (in_array($handle, ['vue', 'theme-vue', 'animate-into-view'])) {
            $tag = '<script type="module" src="' . esc_url($src) . '" id="' . $handle . '" ></script>';
        }

        return $tag;
    }


    /**
     * Set the favicon from an ACF field
     * @return void
     */
    function set_icon_from_acf_field(): void {
        $favicon_id = get_field('favicon', 'option');
        if ($favicon_id) {
            $favicon_url = wp_get_attachment_url($favicon_id);
            echo '<link rel="icon" href="' . esc_url($favicon_url) . '" type="image/x-icon">';
            echo '<link rel="shortcut icon" href="' . esc_url($favicon_url) . '" type="image/x-icon">';
        }
    }
}
