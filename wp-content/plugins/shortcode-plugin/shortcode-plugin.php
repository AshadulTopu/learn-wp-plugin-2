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

    public function recent_posts_shortcode($atts)
    {
        // Set default attributes
        $atts = shortcode_atts(array(
            'post_per_page' => 5,
            'category' => '',
            'pagination' => false,
        ), $atts, 'recent_posts');

        // Get current page
        $paged = get_query_var('paged') ? get_query_var('paged') :
            (get_query_var('page') ? get_query_var('page') : 1);

        // Query arguments
        $query_args = array(
            'post_type' => 'post',
            'posts_per_page' => intval($atts['post_per_page']),
            'category_name' => sanitize_text_field($atts['category']),
            'paged' => $paged,
        );

        $query = new WP_Query($query_args);

        ob_start(); // Start output buffering

        if ($query->have_posts()) {
            echo '<ul class="recent-posts-list">';
            while ($query->have_posts()) {
                $query->the_post();
                echo '<li><a href="' . get_permalink() . '">' . get_the_title() . '</a></li>';
            }
            echo '</ul>';

            // Pagination
            if (filter_var($atts['pagination'], FILTER_VALIDATE_BOOLEAN) && $query->max_num_pages > 1) {
                echo '<div class="pagination">';

                $big = 999999999; // Need an unlikely integer

                echo paginate_links(array(
                    'base' => str_replace($big, '%#%', esc_url(get_pagenum_link($big))),
                    'format' => '?paged=%#%',
                    'current' => max(1, $paged),
                    'total' => $query->max_num_pages,
                    'prev_text' => '&laquo; Previous',
                    'next_text' => 'Next &raquo;',
                    'type' => 'plain',
                ));

                echo '</div>';
            }

            wp_reset_postdata();
        } else {
            echo '<p>No recent posts found.</p>';
        }

        return ob_get_clean(); // Return the buffered content
    }
}

new ShortcodePlugin();