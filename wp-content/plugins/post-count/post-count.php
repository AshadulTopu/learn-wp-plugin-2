<?php
/**
 * Plugin Name: Post Count
 * Description: A simple plugin to count posts.
 * Version: 1.0
 * Author: Ashadul Islam
 */


class PostCount
{
    public function __construct()
    {
        add_action('admin_menu', array($this, 'pc_add_menu'));
    }

    public function pc_add_menu()
    {
        add_options_page( // this function is used to add a submenu under the "Settings" menu in the WordPress admin dashboard.
            'Post Count Settings',
            'Post Count Settings',
            'manage_options',
            'post-count-settings',
            array($this, 'pc_count_settings_page')
        );
    }

    public function pc_count_settings_page()
    {
        ?>
        <div class="wrap">
            <h1>Post Count Settings</h1>

            <form action="post" action="options.php">
                <?php
                settings_fields('post_count_options_group');
                do_settings_sections('post-count-settings');
                submit_button();
                ?>
            </form>
        </div>

        <?php
    }
}

new PostCount();