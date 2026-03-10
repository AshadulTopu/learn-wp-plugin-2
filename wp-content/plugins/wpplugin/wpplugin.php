<?php

/*
 * Plugin Name:       WP Plugin
 * Plugin URI:        https://example.com/plugins/wp-plugin/
 * Description:       Learning plugin development by watching video.
 * Version:           1.0.1
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Ashadul Islam
 * Author URI:        https://author.example.com/ashadul-islam
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://example.com/my-plugin/
 * Text Domain:       wpplugin
 * Domain Path:       /languages
 */

// OOP Concept


// consent
define('WPPLUGIN_PLUGIN_VERSION', '1.0.1');
define('WPPLUGIN_PLUGIN_URL', plugin_dir_url(__FILE__));
define('WPPLUGIN_PLUGIN_ASSETS', plugin_dir_url(__FILE__) . 'assets/');
define('WPPLUGIN_PLUGIN_PATH', plugin_dir_path(__FILE__));

// define class
class Wpplugin
{

    // method -> construct method. always call when class call.
    public function __construct()
    {
        add_action('admin_menu', array($this, 'wpplugin_menu'));
    }

    public function wpplugin_menu()
    {
        add_menu_page(
            'WP Plugin',
            'WP Plugin',
            'manage_options',
            'wpplugin',
            array($this, 'wpplugin_page'),
            'dashicons-admin-generic',
            6
        );

        add_submenu_page(
            'wpplugin',
            'Settings',
            'Settings',
            'manage_options',
            'wpplugin-settings',
            array($this, 'wpplugin_settings_page')
        );
    }


    public function wpplugin_page()
    {
        echo '<div class="wrap"><h1>Welcome to My Basics Plugin</h1><p>This is the main page of the plugin.</p></div>';
    }


    // page include
    public function wpplugin_settings_page()
    {
        require_once WPPLUGIN_PLUGIN_PATH . 'views/settings.php';
    }
}


// class instance
new Wpplugin();