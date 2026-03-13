<?php
/**
 * Plugin Name: Shortcode Plugin
 * Description: A simple shortcode plugin.
 * Version: 1.0.0
 * Author: Ashadul Islam
 * Author URI: https://ashadul-islam.com
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}


class ShortcodePlugin
{
    public function __construct()
    {
        add_shortcode('recent_posts', array($this, 'recent_posts_shortcode'));
    }



    // This function generates the output for the [recent_posts] shortcode, and it accepts attributes for the number of posts to display and the category to filter by.
    public function recent_posts_shortcode($atts)
    {

        // This line sets default values for the shortcode attributes. If the user does not provide values for 'post_per_page' or 'category', it will default to 5 posts and no category filter.
        $atts = shortcode_atts(array(
            'post_per_page' => 5,
            'category' => '',
            'pagination' => false,

        ), $atts, 'recent_posts');


        // pagination check
        // if ($atts['pagination'] == 'true') {
        $paged = get_query_var('paged') ? get_query_var('paged') : 1;
        // $query_args['paged'] = $paged;
        // }


        // This block of code creates a new WP_Query to fetch the recent posts based on the provided attributes. It checks if there are any posts returned by the query and generates an unordered list of post titles with links to the respective posts. If no posts are found, it returns a message indicating that no recent posts were found.
        $query_args = array(
            'post_type' => 'post',
            'posts_per_page' => intval($atts['post_per_page']),
            'category_name' => sanitize_text_field($atts['category']),
            // 'pagination' => filter_var($atts['pagination'], FILTER_VALIDATE_BOOLEAN),
            'paged' => $paged,
        );

        $query = new WP_Query($query_args);


        // Check if the query returned any posts and generate the output accordingly or return a message indicating that no recent posts were found.
        if ($query->have_posts()) {
            $output = '<ul>';
            while ($query->have_posts()) {
                $query->the_post();
                $output .= '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
            }
            $output .= '</ul>';
            // Pagination
            if ($atts['pagination'] == true) {
                $big = 999999999; // an unlikely integer
                $output .= '<div class="pagination">';
                $output .= paginate_links(array(
                    'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                    'format' => '?paged=%#%',
                    // 'current' => max(1, get_query_var('paged')),
                    'current' => $paged,
                    'total' => $query->max_num_pages,
                ));
                $output .= '</div>';
            }

            wp_reset_postdata();
            return $output;
        } else {
            return '<p>No recent posts found.</p>';
        }
    }


}

new ShortcodePlugin();