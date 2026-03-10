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


// consent
define('FIRST_PLUGIN_VERSION', '1.0.1');
define('FIRST_PLUGIN_URL', plugin_dir_url(__FILE__));
define('FIRST_PLUGIN_ASSETS', plugin_dir_url(__FILE__) . 'assets/');
define('FIRST_PLUGIN_PATH', plugin_dir_path(__FILE__));


// menu / hook
add_action('admin_menu', 'my_basics_plugin_menu');

function my_basics_plugin_menu()
{
    add_menu_page(
        'My Basics Plugin',
        'My Basics Plugin',
        'manage_options',
        'my-basics-plugin',
        'my_basics_plugin_page',
        'dashicons-admin-generic',
        6
    );

    add_submenu_page(
        'my-basics-plugin',
        'Settings',
        'Settings',
        'manage_options',
        'my-basics-plugin-settings',
        'my_basics_plugin_settings_page'
    );
}

function my_basics_plugin_page()
{
    echo '<div class="wrap"><h1>Welcome to My Basics Plugin</h1><p>This is the main page of the plugin.</p></div>';
}


// page include
function my_basics_plugin_settings_page()
{
    require_once FIRST_PLUGIN_PATH . 'views/settings.php';
}


// enqueue scripts and styles
require_once FIRST_PLUGIN_PATH . 'include/enqueue-script.php';


// admin bar
function my_admin_bar_menu_func()
{
    global $wp_admin_bar;
    // Add a new menu item to the admin bar
    $wp_admin_bar->add_menu(array(
        'id' => 'my_admin_bar_menu',
        'title' => 'My Admin Bar Menu',
        'href' => '#',
        'meta' => array(
            'title' => 'Ashadul Islam', // This is the tooltip text
            'target' => '_blank',
            'class' => 'my-custom-class',
        ),
    ));

    // Add a submenu item to the admin bar
    $wp_admin_bar->add_menu(array(
        'parent' => 'my_admin_bar_menu',
        'id' => 'my_admin_bar_submenu',
        'title' => 'My Admin Bar Submenu',
        'href' => '#',
        'meta' => array(
            'title' => 'This is the submenu', // This is the tooltip text
            'target' => '_blank',
            'class' => 'my-custom-class',
        ),
    ));

    $wp_admin_bar->add_menu(array(
        'parent' => 'my_admin_bar_menu',
        'id' => 'my_admin_bar_submenu_2',
        'title' => 'My Admin Bar Submenu 2',
        'href' => '#',
        'meta' => array(
            'title' => 'This is the submenu 2', // This is the tooltip text
            'target' => '_blank',
            'class' => 'my-custom-class',
        ),
    ));

}
add_action('admin_bar_menu', 'my_admin_bar_menu_func', 999);














// admin notice
function my_admin_notices_func()
{
    echo '<div class="notice notice-success is-dismissible"><p>My Notice</p></div>';
}
add_action('admin_notices', 'my_admin_notices_func');













// meta box
function my_meta_box_func()
{
    add_meta_box(
        'my_meta_box_id',
        'My Meta Box',
        'my_meta_box_callback',
        'post',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'my_meta_box_func');

function my_meta_box_callback($post)
{
    echo '<input type="text" name="my_meta_box_field" value="' . get_post_meta($post->ID, 'my_meta_box_field', true) . '">';
}


// save meta box
function my_meta_box_save_func($post_id)
{
    if (isset($_POST['my_meta_box_field'])) {
        update_post_meta($post_id, 'my_meta_box_field', sanitize_text_field($_POST['my_meta_box_field']));
    }
}
add_action('save_post', 'my_meta_box_save_func');

// show meta value in frontend
function my_show_meta_in_frontend_func($content)
{
    if (is_single()) {
        global $post;
        $meta_value = get_post_meta($post->ID, 'my_meta_box_field', true);
        if (!empty($meta_value)) {
            $content .= '<p>Meta Value: ' . esc_html($meta_value) . '</p>';
        }
    }
    return $content;
}
// add_filter('the_content', 'my_show_meta_in_frontend_func'); // show meta value in frontend within the content
add_filter('the_title', 'my_show_meta_in_frontend_func'); // show meta value in frontend within the title









// custom post type
function my_custom_post_type_func()
{
    register_post_type(
        'my_custom_post_type',
        array(
            'labels' => array(
                'name' => __('My Custom Post Types'),
                'singular_name' => __('My Custom Post Type')
            ),
            'public' => true,
            'has_archive' => true,
        )
    );
}
add_action('init', 'my_custom_post_type_func');


// modify custom post type admin columns
function my_custom_post_type_columns_func($columns)
{
    $columns['cb'] = '<input type="checkbox" />';
    $columns['date'] = __('Date');
    $columns['title'] = __('Title');
    $columns['content'] = __('Content');
    $columns['publisher'] = __('Publisher');
    $columns['author'] = __('Author');
    // $columns['categories'] = __('Categories');
    // $columns['tags'] = __('Tags');
    $columns['amount'] = __('Amount');
    $columns['price'] = __('Price');
    $columns['status'] = __('Status');

    unset($columns['comments']); // remove comments column

    return $columns;
}
add_filter('manage_my_custom_post_type_posts_columns', 'my_custom_post_type_columns_func');


// modify custom post type admin column content
function my_custom_post_type_column_content_func($column_name, $post_id)
{
    switch ($column_name) {
        case 'content':
            echo wp_trim_words(get_post_field('post_content', $post_id), 20, '...'); // show only first 20 words of content
            break;
        case 'publisher':
            echo get_the_author();
            break;
        // case 'categories':
        //     echo get_the_category_list(', ');
        //     break;
        // case 'tags':
        //     echo get_the_tag_list('', ', ');
        //     break;
        case 'amount':
            echo get_post_meta($post_id, 'amount', true);
            break;
        case 'price':
            echo get_post_meta($post_id, 'price', true);
            break;
        case 'status':
            echo get_post_meta($post_id, 'status', true);
            break;
    }
}
add_action('manage_my_custom_post_type_posts_custom_column', 'my_custom_post_type_column_content_func', 10, 2);


// meta box for custom post type
function my_custom_post_type_meta_box_func()
{
    add_meta_box(
        'amount_meta_box_id',
        'Amount',
        'my_amount_meta_box_callback',
        'my_custom_post_type',
        'side',
        'high'
    );
    add_meta_box(
        'price_meta_box_id',
        'Price',
        'my_price_meta_box_callback',
        'my_custom_post_type',
        'side',
        'high'
    );
    add_meta_box(
        'status_meta_box_id',
        'Status',
        'my_status_meta_box_callback',
        'my_custom_post_type',
        'side',
        'high'
    );
}
add_action('add_meta_boxes', 'my_custom_post_type_meta_box_func');

function my_amount_meta_box_callback($post)
{
    echo '<input type="text" name="amount" value="' . get_post_meta($post->ID, 'amount', true) . '">';
}

function my_price_meta_box_callback($post)
{
    echo '<input type="text" name="price" value="' . get_post_meta($post->ID, 'price', true) . '">';
}

function my_status_meta_box_callback($post)
{
    echo '<input type="text" name="status" value="' . get_post_meta($post->ID, 'status', true) . '">';
}

// save meta box values
function my_custom_post_type_meta_box_save_func($post_id)
{
    // Save amount
    if (isset($_POST['amount'])) {
        update_post_meta($post_id, 'amount', sanitize_text_field($_POST['amount']));
    }

    // Save price
    if (isset($_POST['price'])) {
        update_post_meta($post_id, 'price', sanitize_text_field($_POST['price']));
    }

    // Save status
    if (isset($_POST['status'])) {
        update_post_meta($post_id, 'status', sanitize_text_field($_POST['status']));
    }
}
add_action('save_post_my_custom_post_type', 'my_custom_post_type_meta_box_save_func');











// shortcode
function my_shortcode_func()
{
    return 'Hello, this is a shortcode! <br>';
}
add_shortcode('my_shortcode', 'my_shortcode_func');

// dynamic shortcode with attributes
function my_dynamic_shortcode_func($atts)
{
    $name = $atts['name'];
    return 'Hello, ' . $name . '! <br>';
}
add_shortcode('my_dynamic_shortcode', 'my_dynamic_shortcode_func');





