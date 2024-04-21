<?php
require_once('class-content-handling.php');
require_once('class-utils.php');

/**
 * The front-end specific functionality and customisations for the theme.
 */
class ClientName_Frontend {

    public function __construct() {
        new ClientName_Theme_Frontend_Utils();
        new ClientName_Content_Handling();

        add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend']);
        add_filter('script_loader_tag', [$this, 'script_type_module'], 10, 3);
    }


    /**
     * Enqueue front-end scripts and styles
     */
    function enqueue_frontend(): void {
        wp_enqueue_script('vue-loader', get_stylesheet_directory_uri() . '/js/vendor/vue3-sfc-loader.js', array(), '0.9.5', true);
        wp_enqueue_script('theme-vue', get_stylesheet_directory_uri() . '/js/vue-components.js', array(
            'vue-loader'
        ), THEME_VERSION, true);

        wp_enqueue_script('animate-into-view', get_stylesheet_directory_uri() . '/js/vendor/animate-into-view.js', array(), '1.0.0', true);
        wp_enqueue_script('theme-animation', get_stylesheet_directory_uri() . '/js/animation.js', array(), THEME_VERSION, true);

        wp_enqueue_style('client-name-style', get_stylesheet_uri(), array(), time());
        wp_enqueue_script('fontawesome', '//kit.fontawesome.com/c1c9431617.js', array(), '6.x', true);
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
        if (in_array($handle, ['vue', 'theme-vue', 'animate-into-view', 'theme-animation'])) {
            $tag = '<script type="module" src="' . esc_url($src) . '" id="' . $handle . '" ></script>';
        }

        return $tag;
    }

}
