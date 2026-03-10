<?php
/*
 * Enqueue scripts and styles
 * @package FirstPluginStart
 * @since 1.0.0
 * @author Ashadul Islam
 * all scripts and styles are enqueued here
 */

// Enqueue scripts and styles
function first_plugin_start_enqueue_scripts()
{
    // Enqueue CSS file
    wp_enqueue_style('first-plugin-start', FIRST_PLUGIN_ASSETS . 'css/style.css');

    // Enqueue JS file with jQuery as a dependency
    wp_enqueue_script('first-plugin-start', FIRST_PLUGIN_ASSETS . 'js/script.js', array('jquery'), FIRST_PLUGIN_VERSION, true);
}
// add_action('wp_enqueue_scripts', 'first_plugin_start_enqueue_scripts'); // Enqueue admin scripts and styles for frontend only
// add_action('admin_enqueue_scripts', 'first_plugin_start_enqueue_scripts'); // Enqueue admin scripts and styles for admin area only
add_action('init', 'first_plugin_start_enqueue_scripts');   // Enqueue admin scripts and styles for frontend and admin both areas