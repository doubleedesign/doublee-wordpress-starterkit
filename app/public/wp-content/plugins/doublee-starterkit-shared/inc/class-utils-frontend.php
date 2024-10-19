<?php

/**
 * Static utility functions to be used in theme templates as needed
 */
class Starterkit_Theme_Frontend_Utils {

    public function __construct() {
        // This file is intended only for static utility functions,
        // but having a constructor stops the file instantiating this class from complaining "expression result is not used anywhere"
    }


    /**
     * Excerpt customiser
     * Strips headings and sets a custom length
     * * Template usage:
     * if(has_excerpt()) { the_excerpt(); }
     * * You can also use the function to shorten the manual excerpt:
     * starterkit_custom_excerpt(get_the_excerpt());
     * * Or simply shorten the content:
     * starterkit_custom_excerpt(get_the_content());
     *
     * @param $text - the string to strip headings and shorten, generally get_the_excerpt or get_the_content
     * @param $word_count - how many words to include in the output
     *
     * @return string
     */
    static function get_custom_excerpt($text, $word_count) {

        // Remove shortcode tags from the given content
        $text = strip_shortcodes($text);
        $text = apply_filters('the_content', $text);
        $text = str_replace(']]>', ']]&gt;', $text);

        // Regular expression that strips the header tags and their content
        $regex = '#(<h([1-6])[^>]*>)\s?(.*)?\s?(</h\2>)#';
        $text = preg_replace($regex, '', $text);

        // Set the word count
        $excerpt_length = apply_filters('excerpt_length', $word_count); // WP default word count is 55

        // Set the ending
        $excerpt_end = '...';                                           // The WP default is [...]
        $excerpt_more = apply_filters('excerpt_more', ' ' . $excerpt_end);

        $excerpt = wp_trim_words($text, $excerpt_length, $excerpt_more);

        return wpautop(apply_filters('wp_trim_excerpt', $excerpt));
    }


    /**
     * Shortcut function for displaying address from ACF options screen in various formats
     * @param $format
     *
     * @return string
     */
    static function get_address($format): string {
        $output = '';

        if (class_exists('ACF')) {

            // Get fields from the theme options
            $fields = get_field('contact_details', 'option');
            if ($fields) {
                $building_name = $fields['building_name'] ?? '';
                $street_address = $fields['address'];
                $suburb = $fields['suburb'];
                $state = $fields['state'];
                $postcode = $fields['postcode'];
                $phone = $fields['phone'];
                $email = $fields['email'];

                // Return in the relevant format for output
                if ($format == 'compact') {
                    $output .= '<span>';
                    $output .= $street_address . ' ';
                    $output .= $suburb . ' ';
                    $output .= $state . ' ';
                    $output .= $postcode . ' ';
                    $output .= '</span>';
                    $output .= '<span class="phone">';
                    $output .= $phone;
                    $output .= '</span>';
                }
                else if ($format == 'expanded') {
                    $output .= '<address class="address" itemscope itemtype="https://schema.org/LocalBusiness">';

                    $output .= '<div itemprop="name" class="sr-only">' . get_bloginfo('name') . '</div>';

                    if (!empty($phone)) {
                        $output .= '<div class="address__row">';
                        $output .= '<div class="address__row__icon">';
                        $output .= '<i class="fa-solid fa-mobile-screen"></i>';
                        $output .= '</div>';
                        $output .= '<div class="address__row__item">';
                        $output .= '<span itemprop="telephone">' . $phone . '</span>';
                        $output .= '</div>';
                        $output .= '</div>';
                    }

                    if(!empty($email)) {
                        $output .= '<div class="address__row">';
                        $output .= '<div class="address__row__icon">';
                        $output .= '<i class="fa-solid fa-envelope"></i>';
                        $output .= '</div>';
                        $output .= '<div class="address__row__item">';
                        $output .= '<span itemprop="email">' . $email . '</span>';
                        $output .= '</div>';
                        $output .= '</div>';
                    }

                    $output .= '<div class="address__row">';

                    $output .= '<div class="address__row__icon">';
                    $output .= '<i class="fa-sharp fa-solid fa-location-dot"></i>';
                    $output .= '</div>';

                    $output .= '<div class="address__row__item">';

                    if (!empty($building_name)) {
                        $output .= '<span itemprop="alternateName"><span class="sr-only">Located at </span>' . $building_name . '</span>';
                    }

                    $output .= '<div itemtype="http://schema.org/PostalAddress" itemscope itemprop="address">';
                    if (!empty($street_address)) {
                        $output .= '<span itemprop="streetAddress">' . $street_address . '</span>';
                    }
                    if (!empty($suburb)) {
                        $output .= '<span itemprop="addressLocality">' . $suburb . '</span>';
                    }
                    if (!empty($state)) {
                        $output .= '<span itemprop="addressRegion">' . $state . '</span>';
                    }
                    if (!empty($postcode)) {
                        $output .= '<span itemprop="postalCode">' . $postcode . '</span>';
                    }
                    $output .= '</div>';

                    $output .= '</div>';

                    $output .= '</div>';

                    $output .= '</address>';
                }
            }
        }

        return $output;
    }


    /**
     * Get post entry meta in a consistent format
     * @return string
     */
    static function get_entry_meta(): string {
        $categories = array();
        foreach (get_the_category() as $cat) {
            array_push($categories, sprintf('<a href="%s">%s</a>', get_term_link($cat->term_id), $cat->name));
        }

        $meta = '<span class="byline author">';
        $meta .= __('Posted by ', '');
        $meta .= '<a href="' . get_author_posts_url(get_the_author_meta('ID')) . '" rel="author">';
        $meta .= get_the_author();
        $meta .= '</a>';
        $meta .= ' on <time class="date">';
        $meta .= get_the_date('l, F j, Y');
        $meta .= '</time>';
        $meta .= ' in ' . implode(',', $categories);
        $meta .= '</span>';

        return $meta;
    }


    /**
     * Get the first paragraph from a given string
     *
     * @param $text - the string to get the first paragraph from
     *
     * @return string
     */
    static function get_first_paragraph($text) {
        $text = wpautop($text);
        $text = substr($text, 0, strpos($text, '</p>') + 4);

        return strip_tags($text, '<a><strong><em>');
    }


    /**
     * Return the type part of a mime type, e.g. for image/jpeg returns jpeg
     *
     * @param $mime
     *
     * @return string
     */
    static function parse_mime($mime) {
        preg_match('/.*\/(\w*)\+?.*/', $mime, $matches);

        return $matches[1];
    }


    /**
     * Split a string in half and wrap each in a span
     * useful for avoiding widows in titles
     *
     * @param $string - the string to split
     *
     * @return string - a new string with <span> tags added
     */
    static function split_text($string) {
        $word_count = str_word_count($string);
        $words = explode(' ', $string);
        $words_per_line = round($word_count / 2); // if the word count is an odd number, rounding puts the larger number of words on the top (e.g. 4 then 3)
        $first_half = array_slice($words, 0, $words_per_line);
        $second_half = array_slice($words, $words_per_line, $word_count);
        $string_one = implode(' ', $first_half);
        $string_two = implode(' ', $second_half);

        $output = '<span>' . $string_one . '</span>' . ' ';
        $output .= '<span>' . $string_two . '</span>';

        return $output;
    }

    /**
     * Get ordinal word from a given integer (up to 9)
     *
     * @param $num - the number to get the ordinal word for
     *
     * @return string
     */
    static function get_integer_to_ordinal_word($num) {
        $word = array('first', 'second', 'third', 'fourth', 'fifth', 'sixth', 'seventh', 'eighth', 'ninth', 'tenth');
        if ($num <= 9) {
            return $word[$num];
        }
        else {
            return '';
        }
    }


    /**
     * Utility function to get the name of the first ACF field of the specified type.
     *
     * @param        $field_type
     * @param string $post_id
     *
     * @return int|string
     */
    static function get_name_of_first_acf_field_name_of_type($field_type, string $post_id = ''): int|string {
        $field_name = '';

        if( ! $post_id) {
            $post_id = get_the_id();
        }

        $acf_fields = get_fields($post_id);
        if($acf_fields) {
            foreach($acf_fields as $name => $value) {
                $field_object = get_field_object($name);
                if($field_object['type'] == $field_type) {
                    $field_name = $name;
                    break;
                }
            }
        }

        return $field_name;
    }


    /**
     * Utility function to get the first direct subfield in an ACF set (flexible content or repeater) that is of the given type(s)
     * Recursively checks within nested sets for their first instance of the type when applicable
     * Returns the field (or sub-field) value ready for use by the calling function.
     * // TODO: Test this on grouped fields too.
     *
     * @param array $fields Array of ACF fields or subfields, as returned by get_field() on a flexible content or repeater field
     * @param array $types The field types we want to look for
     * @param string $parent_field_name The name of the top level field, e.g. the flexible content field.
     *                                    Optional because when looking at nested fields recursively, the original value needs to be passed again.
     *
     * @return bool|string
     */
    static function get_first_acf_subfield_value_of_type(array $fields, array $types, string $parent_field_name = ''): bool|string {
        $all_field_data = self::get_sub_field_data('content_modules', get_the_id());

        // If no fields were provided if they're not an array, bail early
        // Brought this out on its own to keep the main loop's nesting as simple and shallow as possible
        if(empty($fields)) {
            return false;
        }

        // Loop through the fields
        foreach($fields as $index => $subfield) {

            // If the subfield's value is an array, it's a nested fieldset, so we need to go another level down
            if(is_array($subfield)) {
                return self::get_first_acf_subfield_value_of_type($subfield, $types, $parent_field_name);
            }

            // We've reached content fields and can now proceed to look for our desired field types
            foreach($all_field_data as $data) {
                if(($data['name'] == $index) && (in_array($data['type'], $types)) && ( ! empty($data['value']))) {
                    return $data['value'];
                }
            }
        }

        // If a value hasn't been returned yet, there isn't one
        return false;
    }


    /**
     * Utility function to get data about sub-fields that we want to use in dh_get_first_acf_subfield_value_of_type
     * because you can't use get_sub_field_object outside of an ACF have_rows loop which was causing headaches with nested repeaters and whatnot
     *
     * @param $field_name
     * @param $post_id
     *
     * @return array
     */
    static function get_sub_field_data($field_name, $post_id): array {
        global $wpdb;
        $data = array();

        // Query the database for the field content of this field's subfields. Starts with the field slug without an underscore.
        // Returns an indexed array of meta ID, post ID, meta key, and meta value sub-arrays.
        $meta_key_search = "'" . $field_name . "%'";
        $postmeta = $wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE post_id = $post_id AND meta_key LIKE $meta_key_search ORDER BY meta_key ASC", ARRAY_A);

        // Query the database for the field keys of this field's subfields. Starts with the field slug preceded by an underscore.
        // Field keys are not unique - e.g. repeaters will have the same field key for each instance of a subfield.
        $meta_key_search = "'_" . $field_name . "%'";
        $keymeta = $wpdb->get_results("SELECT * FROM $wpdb->postmeta WHERE post_id = $post_id AND meta_key LIKE $meta_key_search ORDER BY meta_key ASC", ARRAY_A);

        // Merge the results
        $merged = array();
        foreach($postmeta as $index => $result_data) {
            $merged[] = array_merge_recursive($result_data, $keymeta[$index]);
        }

        // Because the keys are the same in the arrays we merged, this will cause the values to be a sub-array
        // Let's fix that, and don't include data we don't need
        $flattened = array();
        foreach($merged as $merged_array) {
            $flattened[] = array(
                'post_id' => $merged_array['post_id'][0],
                'value'   => $merged_array['meta_value'][0],
                'key'     => $merged_array['meta_value'][1]
            );
        }

        // Use this and some more processing to build an array of all the data we need
        $i = 0;
        foreach($flattened as $index => $raw_data) {
            if(is_array($raw_data)) {
                $object = get_field_object($raw_data['key']);
                $value = $raw_data['value'];
                $parent_key = $object['parent'];
                $parent_name = '';

                if( ! empty($parent_key)) {
                    $parent_object = get_field_object($parent_key);
                    $parent_name = $parent_object['name'];
                }

                $data[$i]['name'] = $object['name'];
                $data[$i]['value'] = $value;
                $data[$i]['type'] = $object['type'];
                $data[$i]['parent_name'] = $parent_name;

                $i++;
            }
        }

        return $data;
    }

}



