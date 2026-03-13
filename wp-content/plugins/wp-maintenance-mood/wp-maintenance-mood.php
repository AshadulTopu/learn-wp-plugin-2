<?php
/**
 * Plugin Name: WP Maintenance Mood
 * Description: A simple maintenance mode plugin.
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

class WPMaintenanceMood
{
    public function __construct()
    {
        add_action('admin_menu', array($this, 'wp_mm_menu'));
        add_action('admin_init', array($this, 'wp_mm_settings'));
        add_action('wp', array($this, 'wp_mm_check_maintenance_mode'));
    }


// ======================== Admin Menu ========================== //
    public function wp_mm_menu()
    {
        add_submenu_page(
            'options-general.php', // Parent slug
            'Maintenance Mood', // Page title
            'Maintenance Mood', // Menu title
            'manage_options', // Capability
            'maintenance-mood', // Menu slug
            array($this, 'maintenance_mood_page') // Callback function
        );
    }


// ======================= Admin Menu Callback / Admin Page with Settings ========================== //
    public function maintenance_mood_page()
    {
        ?>
        <div class="maintenance-mood">
            <h1>Maintenance Mood</h1>
            <form action="options.php" method="post">
                
            <table>
                    <tr>
                    <th scope="row">Enable Maintenance Mode</th>
                    <td>
                        <input type="checkbox" name="wp_mm_enable" value="1" <?php checked(get_option('wp_mm_enable'), 1); ?> />
                    </td>
                    </tr>
                    <tr>
                        <th scope="row">Maintenance Message</th>
                        <td>
                            <textarea name="wp_mm_message" rows="5" cols="50"><?php echo esc_textarea(get_option('wp_mm_message', 'We are currently undergoing maintenance. Please check back later.')); ?>
                        </textarea>
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Maintenance Duration</th>
                        <td>
                            <input type="text" name="wp_mm_duration" value="<?php echo esc_attr(get_option('wp_mm_duration', '1 hour')); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row"> Font Size </th>
                        <td>
                            <input type="text" name="wp_mm_font_size" value="<?php echo esc_attr(get_option('wp_mm_font_size', '16px')); ?>" />
                        </td>
                    </tr>
                    <tr>
                        <th scope="row">Text Color</th>
                        <td>
                            <input type="color" name="wp_mm_font_color" value="<?php echo esc_attr(get_option('wp_mm_font_color', '#000000')); ?>" />
                        </td>
                    </tr>
                </table>

                <div class="submit">
                    <?php _e(get_submit_button('Save Changes', 'primary-button', 'submit', '', ''), 'wp-maintenance-mood') ?>
                </div>

                <?php settings_fields('wp_mm_settings_group'); ?>
            </form>
        </div>
        <?php
    }



    // ======================= Register Settings ========================== //
    public function wp_mm_settings()
    {
        register_setting('wp_mm_settings_group', 'wp_mm_enable');
        register_setting('wp_mm_settings_group', 'wp_mm_message');
        register_setting('wp_mm_settings_group', 'wp_mm_duration');
        register_setting('wp_mm_settings_group', 'wp_mm_font_size');
        register_setting('wp_mm_settings_group', 'wp_mm_font_color');
    }

    // ======================= Check Maintenance Mode and Display Message ========================== //
    public function wp_mm_check_maintenance_mode()
    {
        if (get_option('wp_mm_enable')) {
            $message = get_option('wp_mm_message', 'We are currently undergoing maintenance. Please check back later.');
            $font_size = get_option('wp_mm_font_size', '16px');
            $font_color = get_option('wp_mm_font_color', '#000000');

            wp_die(
                '<div style="text-align: center; padding: 50px;">
                    <h1 style="font-size: ' . esc_attr($font_size) . '; color: ' . esc_attr($font_color) . ';">' . esc_html($message) . '</h1>
                </div>',
                'Maintenance Mode',
                array('response' => 503)
            );
        }
    }
}

new WPMaintenanceMood();