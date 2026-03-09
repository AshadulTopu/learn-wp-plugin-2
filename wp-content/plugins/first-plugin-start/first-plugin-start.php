<?php
/*
 * Plugin Name:       My Basics Plugin
 * Plugin URI:        https://example.com/plugins/the-basics/
 * Description:       Handle the basics with this plugin.
 * Version:           1.0.1
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Ashadul Islam
 * Author URI:        https://author.example.com/
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       my-basics-plugin
 * Domain Path:       /languages
 */


add_action('admin_menu', 'my_basics_plugin_menu');

function my_basics_plugin_menu()
{
    add_menu_page('My Basics Plugin', 'My Basics Plugin', 'manage_options', 'my-basics-plugin', 'my_basics_plugin_page', 'dashicons-admin-generic', 6);
}

function my_basics_plugin_page()
{
    echo '<div class="wrap"><h1>Welcome to My Basics Plugin</h1><p>This is the main page of the plugin.</p></div>';
}