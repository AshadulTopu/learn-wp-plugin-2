<?php

/**
 * Plugin Name: View Count Plugin
 * Description: A simple plugin to count views of a post.
 * Version: 1.0.0
 * Author: Ashadul Islam
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: view-count-plugin
 * Domain Path: /languages
 * Requires at least: 5.2
 * Requires PHP: 7.2
 */


class ViewCountPlugin
{
    public function __construct()
    {
        add_action('admin_menu', array($this, 'vcp_add_menu'));
        add_action('admin_init', array($this, 'vcp_register_settings')); // this function is used to register the settings fields.

        add_action('wp', array($this, 'vcp_track_view_count')); // this function is used to increment the view count when a post is viewed.
        add_filter('the_content', array($this, 'vcp_display_view_count')); // this function is used to display the view count at the end of the post content.
    }


    // ================== Admin Menu and Settings Page ================== //
    public function vcp_add_menu()
    {
        add_options_page(
            'View Count Settings',
            'View Count Settings',
            'manage_options',
            'view-count-settings',
            array($this, 'vcp_settings_page')
        );
    }

    // ================= Settings Page Callback Function ================== //
    public function vcp_settings_page()
    {
        ?>
        <div class="wrap">
            <h1>View Count Settings</h1>

            <form method="post" action="options.php">
                <?php
                settings_fields('vcp_view_count_options_group');
                do_settings_sections('view-count-settings');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }

    // ================== Register Settings Section & Fields ================== //
    public function vcp_register_settings()
    {
        // Register settings
        register_setting('vcp_view_count_options_group', 'vcp_view_count_enabled');


        // =============== Settings Section =============== //
        // Add a settings section
        add_settings_section(
            'vcp_view_count_settings_section',
            'View Count Settings',
            null,
            'view-count-settings'
        );

        // =============== Settings Fields =============== //
        // Add a settings field
        add_settings_field(
            'vcp_view_count_enabled',
            'Enable View Count',
            array($this, 'vcp_enable_view_count_callback'),
            'view-count-settings',
            'vcp_view_count_settings_section'
        );
    }


    // ================= Settings Fields Callback Functions ================== //
    // Callback function for the enable view count field
    public function vcp_enable_view_count_callback()
    {
        $enabled = get_option('vcp_view_count_enabled', 'yes');
        ?>
        <select name="vcp_view_count_enabled" id="vcp_view_count_enabled">
            <option value="yes" <?php selected('yes', $enabled); ?>>Yes</option>
            <option value="no" <?php selected('no', $enabled); ?>>No</option>
        </select>
        <?php
    }



    // ================= View Count Functionality ================== //
    public function vcp_track_view_count()
    {
        if (is_single()) {

            global $post;
            $post_id = $post->ID;

            $views = get_post_meta($post_id, 'vcp_view_count', true);
            $views = $views ? $views : 0;
            update_post_meta($post_id, 'vcp_view_count', $views + 1);
        }
    }

    // this function is used to display the view count at the end of the post content.
    public function vcp_display_view_count($content)
    {
        if (is_single()) {

            $show_views = get_option('vcp_view_count_enabled', 'yes');

            if ($show_views === 'yes') {

                global $post;
                $views = get_post_meta($post->ID, 'vcp_view_count', true);
                $views = $views ? $views : 0;
                return $content . '<p>Views: ' . $views . '</p>';
            }
        }
        return $content;
    }

}

new ViewCountPlugin();