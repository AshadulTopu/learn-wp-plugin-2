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
            'pagination' => true,
        ), $atts, 'recent_posts');

        // Get current page
        if (isset($_GET['rp_paged'])) {
            $paged = max(1, intval($_GET['rp_paged']));
        } else {
            $paged = (get_query_var('paged')) ? get_query_var('paged') : (get_query_var('page') ? get_query_var('page') : 1);
        }

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

            if ($atts['pagination']) {
                $pagination_args = array(
                    'base' => add_query_arg('rp_paged', '%#%'),
                    'format' => '',
                    'current' => max(1, $paged),
                    'total' => $query->max_num_pages,
                );

                echo '<div class="pagination">';
                echo paginate_links($pagination_args);
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