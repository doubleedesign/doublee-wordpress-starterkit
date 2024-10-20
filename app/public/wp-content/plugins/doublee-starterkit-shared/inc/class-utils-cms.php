<?php
class Starterkit_Theme_CMS_Utils {

    public function __construct() {
    }

    /**
     * Utility function to get theme design tokens from theme.json as an associative array
     * @return array
     */
    static function get_theme(): array {
        $json = file_get_contents(get_stylesheet_directory() . '/theme-vars.json');
        if(!$json) {
            $json = file_get_contents(get_template_directory() . '/theme-vars.json');
        }

        return json_decode($json, true);
    }


    /**
     * Utility function to get font URLs from ACF Global Options page
     * @return array
     */
    static function get_font_urls(): array {
        $urls = [];

        // Using get_option to avoid ACF dependency here doesn't work for arrays
        if(function_exists('get_field')) {
            $fonts = get_field('external_font_urls', 'option');
            if($fonts) {
                foreach ($fonts as $index => $font) {
                    $font_url = $font['url'];
                    if(!str_ends_with('.css', $font_url) || str_starts_with('https://fonts.googleapis.com', $font_url)) {
                        array_push($urls, array("theme-font-$index" => $font_url));
                    }
                    else {
                        error_log("Font URL '$font_url' is not valid");
                    }
                }
            }
        }
        // If ACF is not active, get the first one (if any) to somewhat avoid completely breaking things
        else {
            $font_url = get_option('options_external_font_urls_0_url');
            if($font_url) {
                if(str_ends_with('.css', $font_url) || str_starts_with('https://fonts.googleapis.com', $font_url)) {
                    array_push($urls, array('theme-font-0' => $font_url));
                }
                else {
                    error_log("Font URL '$font_url' is not valid");
                }
            }
        }

        return $urls;
    }


    /**
     * Utility function to enqueue custom fonts from URLs from ACF Global Options page
     * with some basic validation so random JS files don't get enqueued and things like that
     * This function is to be called in the relevant hooks in the theme (frontend, admin, TinyMCE, etc)
     * @return void
     */
    static function enqueue_custom_fonts(): void {
        $urls = self::get_font_urls();
        foreach($urls as $url) {
            foreach($url as $handle => $font_url) {
                wp_enqueue_style($handle, $font_url);
            }
        }

        $fontawesome = get_option('options_font_awesome_kit_id');
        if($fontawesome) {
            wp_enqueue_script('fontawesome', 'https://kit.fontawesome.com/' . $fontawesome . '.js', [], '6.x', true);
        }
    }

}
