<?php
/**
 * Plugin Name: Word Count Plugin
 * Description: A simple plugin to count words in a post.
 * Version: 1.0.0
 * Author: Ashadul Islam
 * License: GPL2 
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: word-count-plugin
 * Domain Path: /languages
 * Requires at least: 5.2
 * Requires PHP: 7.2
 * 
 */


class WordCount
{
    public function __construct()
    {
        add_action('admin_menu', array($this, 'pcp_add_menu'));
        add_action('admin_init', array($this, 'pcp_register_settings')); // this function is used to register the settings fields.

        add_filter('the_content', array($this, 'pcp_display_word_count')); // this function is used to display the word count at the end of the post content.
    }


    // ================== Admin Menu and Settings Page ================== //
    public function pcp_add_menu()
    {
        add_options_page( // this function is used to add a submenu under the "Settings" menu in the WordPress admin dashboard.
            'Word Count Settings', // this is the title of the submenu.
            'Word Count Settings', // this is the title of the menu item that will be displayed in the WordPress admin dashboard.
            'manage_options', // this is the capability required to access the submenu. In this case, only users with the "manage_options" capability (usually administrators) can access it.
            'word-count-settings', // this is the slug of the submenu. It should be unique and is used to identify the submenu.
            array($this, 'pcp_count_settings_page') // this is the callback function that will be called when the submenu is accessed.
        );
    }


    // ================= Settings Page Callback Function ================== //
    public function pcp_count_settings_page()
    {
        ?>
        <div class="wrap">
            <h1>Word Count Settings</h1>

            <form method="post" action="options.php">
                <?php
                settings_fields('word_count_options_group'); // this function is used to register the settings fields.
                do_settings_sections('word-count-settings'); // this function is used to display the settings fields.
                submit_button(); // this function is used to display the submit button.
                ?>
            </form>
        </div>

        <?php
    }


    // ================== Register Settings Section & Fields ================== //

    // this function is used to register the settings fields.
    public function pcp_register_settings()
    {
        register_setting('word_count_options_group', 'word_count_enabled'); // this function is used to register the settings field.

        register_setting('word_count_options_group', 'word_count_color'); // this function is used to register the settings field.



        // =============== Settings Section =============== //
        // section
        add_settings_section( // this function is used to register the settings section.
            'word_count_settings_section', // this is the ID of the settings section.
            'Word Count Settings', // this is the title of the settings section.
            // array($this, 'pcp_settings_section_callback'),
            null, // this is the callback function for the settings section. We can use this function to display some description for the settings section.
            'word-count-settings' // this is the slug of the settings page where we want to display the settings section.
        );


        // ============== Settings Fields =============== //
        // field
        // checkbox field for enabling/disabling word count
        add_settings_field( // this function is used to register the settings field.
            'word_count_enabled', // this is the ID of the settings field.
            'Word Count Enabled', // this is the title of the settings field.
            array($this, 'pcp_count_enabled_callback'), // this is the callback function for the settings field. We can use this function to display the input field for the settings field.
            'word-count-settings', // this is the slug of the settings page where we want to display the settings field.
            'word_count_settings_section' // this is the ID of the settings section where we want to display the settings field.
        );

        // color picker field for word count color
        add_settings_field( // this function is used to register the settings field.
            'word_count_color', // this is the ID of the settings field.
            'Word Count Color', // this is the title of the settings field.
            array($this, 'pcp_count_color_callback'), // this is the callback function for the settings field. We can use this function to display the input field for the settings field.
            'word-count-settings', // this is the slug of the settings page where we want to display the settings field.
            'word_count_settings_section' // this is the ID of the settings section where we want to display the settings field.
        );
    }


    // ================== Settings Fields Callback Functions ================== //
    // callback function for the checkbox field
    public function pcp_count_enabled_callback()
    {
        $enable = get_option('word_count_enabled', 'yes'); // this function is used to get the value of the settings field.
        ?>
        <input type="checkbox" name="word_count_enabled" value="yes" <?php checked('yes', $enable, true); ?> />
        <p>check to enable word count show at end of the post</p>
        <?php
    }

    public function pcp_count_color_callback()
    {
        $color = get_option('word_count_color', '#000000'); // this function is used to get the value of the settings field.
        ?>
        <input type="color" name="word_count_color" value="<?php echo esc_attr($color); ?>" class="word-count-color-picker" />
        <p>select the color for the word count display</p>
        <?php
    }



    // ================== Display Word Count at the End of the Post Content ================== //

    // this function is used to display the word count at the end of the post content.
    public function pcp_display_word_count($content)
    {
        $enable = get_option('word_count_enabled', 'yes'); // this function is used to get the value of the settings field.
        $color = get_option('word_count_color', '#000000'); // this function is used to get the value of the settings field.

        if ($enable === 'yes') {

            $word_count = str_word_count(strip_tags($content)); // this function is used to count the number of words in the post content. We use strip_tags() function to remove any HTML tags from the content before counting the words.

            $content .= '<p style="color:' . esc_attr($color) . ';">Word Count: ' . $word_count . '</p>'; // this line is used to append the word count at the end of the post content. We use esc_attr() function to escape the color value for security reasons.
        }

        return $content; // this line is used to return the modified content with the word count appended at the end.
    }
}

new WordCount();