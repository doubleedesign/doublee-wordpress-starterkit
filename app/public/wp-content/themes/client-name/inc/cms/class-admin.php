<?php

class ClientName_Admin {

    public function __construct() {
        add_action('admin_notices', [$this, 'required_plugins_notification'], 11, 1);
        add_action('login_enqueue_scripts', [$this, 'login_logo']);
        add_action('admin_enqueue_scripts', [$this, 'admin_css']);
        add_action('enqueue_block_editor_assets', [$this, 'block_editor_css'], 11);
        add_action('admin_init', [$this, 'tinymce_editor_css']);
    }

    function required_plugins_notification(): void {
        $warnings = array();

        if (count($warnings) > 0) {
            echo '<div class="notice notice-warning">';
            echo '<p>The ' . wp_get_theme()->name . ' theme requires the following plugins to be installed and activated for full functionality. Without them, some features may be missing or not work as expected.</p>';
            echo '<ul>';
            foreach ($warnings as $warning) {
                echo '<li>' . $warning . '</li>';
            }
            echo '</ul>';
            echo '</div>';
        }
    }


    function login_logo(): void {
        $custom_logo_id = get_option('options_logo');
        if ($custom_logo_id) {
            $logo = wp_get_attachment_image_src($custom_logo_id, 'full');
            ?>
            <style>
				body.login.wp-core-ui {
					background-color: #051326;
				}

				#login h1 a {
					width: 75%;
					min-height: 80px;
					background-image: url('<?php echo $logo[0]; ?>') !important;
					padding-bottom: 0 !important;
					background-size: contain !important;
				}

				#login #nav a, #login #backtoblog a {
					color: white !important;
				}
            </style>
        <?php }
    }


    /**
     * Enqueue admin CSS
     *
     * @return void
     */
    function admin_css(): void {
        wp_enqueue_style('doublee-admin-css', get_stylesheet_directory_uri() . '/styles-admin.css');
    }


    /**
     * Enqueue block editor styles
     *
     * @return void
     */
    function block_editor_css(): void {
        add_theme_support('wp-block-styles');
        wp_enqueue_style('client-name-block-styles', get_stylesheet_directory_uri() . '/styles-block-editor.css');
    }


    /**
     * Enqueue TinyMCE editor styles
     *
     * @return void
     */
    function tinymce_editor_css(): void {
        add_theme_support('editor-styles');
        add_editor_style(get_stylesheet_directory() . '/styles-editor.css');
    }
}
