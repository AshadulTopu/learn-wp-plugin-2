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
        add_action('admin_bar_menu', array($this, 'wpplugin_admin_bar_menu_func'), 999);

        add_action('add_meta_boxes', array($this, 'wpplug_meta_box_func')); // add meta box
        add_action('save_post', array($this, 'wpplugin_meta_box_save_func')); // save meta box data
        add_filter('the_content', array($this, 'wpplugin_show_meta_in_frontend_func')); // show meta value in frontend within the content
        // add_filter('the_title', array($this, 'wpplugin_show_meta_in_frontend_func')); // show meta value in frontend within the title



        add_action('init', array($this, 'wpplugin_custom_post_type_func'));

        // require_once WPPLUGIN_PLUGIN_PATH . 'include/enqueue-script.php';
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

    // load script file
    // public function load_script()
    // {
    //     // enqueue scripts and styles
    //     require_once WPPLUGIN_PLUGIN_PATH . 'include/enqueue-script.php';
    // }




    // admin bar
    public function wpplugin_admin_bar_menu_func()
    {
        global $wp_admin_bar;
        // Add a new menu item to the admin bar
        $wp_admin_bar->add_menu(array(
            'id' => 'wpplug_admin_bar_menu',
            'title' => 'wpplugin',
            'href' => '#',
            'meta' => array(
                'title' => 'Ashadul Islam', // This is the tooltip text
                'target' => '_blank',
                'class' => 'my-custom-class',
            ),
        ));

        // Add a submenu item to the admin bar
        $wp_admin_bar->add_menu(array(
            'parent' => 'wpplug_admin_bar_menu',
            'id' => 'wpplugin_admin_bar_submenu',
            'title' => 'Admin Bar Submenu',
            'href' => '#',
            'meta' => array(
                'title' => 'This is the submenu', // This is the tooltip text
                'target' => '_blank',
                'class' => 'my-custom-class',
            ),
        ));
    }

    // meta box
    public function wpplug_meta_box_func()
    {
        add_meta_box(
            'wpplugin_meta_box_id',
            'Test Meta Box',
            array($this, 'wpplugin_meta_box_callback'),  // Use array notation
            'post',
            'side',
            'high'
        );
    }

    public function wpplugin_meta_box_callback($post)
    {
        ?>
        <p>This is a test meta box added by WP Plugin.</p>
        <input type="text" name="wpplugin_post_meta_field"
            value="<?php echo get_post_meta($post->ID, 'wpplugin_post_meta_field', true); ?>">
        <?php
    }

    // save meta box
    public function wpplugin_meta_box_save_func($post_id)
    {
        if (isset($_POST['wpplugin_post_meta_field'])) {
            update_post_meta($post_id, 'wpplugin_post_meta_field', sanitize_text_field($_POST['wpplugin_post_meta_field']));
        }
    }

    // show meta value in frontend
    public function wpplugin_show_meta_in_frontend_func($content)
    {
        if (is_single()) {
            global $post;
            $meta_value = get_post_meta($post->ID, 'wpplugin_post_meta_field', true);
            if (!empty($meta_value)) {
                $content .= '<p>Meta Value: ' . esc_html($meta_value) . '</p>';
            }
        }
        return $content;
    }



    // custom post type
    public function wpplugin_custom_post_type_func()
    {
        register_post_type(
            'wpplugin_post_type',
            array(
                'labels' => array(
                    'name' => __('wpplugin Post Types'),
                    'singular_name' => __('wpplugin Post Type')
                ),
                'public' => true,
                'has_archive' => true,
            )
        );
    }

}


// class instance
new Wpplugin();