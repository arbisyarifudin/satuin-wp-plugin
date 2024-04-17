<?php

/**
 * Plugin Name: Satuin Plugin
 * Plugin URI:        http://satuin.id/
 * Description:       Satuin Custom Plugin
 * Version:           0.0.1
 * Requires at least: 5.7
 * Requires PHP:      7.3
 * Author:            Onero
 * Author URI:        http://onero.id
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       satuin-plugin
 * Domain Path:       /
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

add_action('admin_menu', 'satuin_menu');

function satuin_menu()
{
    add_menu_page('Satuin', 'Satuin', 'manage_options', 'satuin-settings', 'satuin_menu_setting_cb', '', 100);

    add_submenu_page('satuin-settings', 'Settings', 'Settings', 'manage_options', 'satuin-settings', 'satuin_menu_setting_cb', 100);
    add_submenu_page('satuin-settings', 'About', 'About', 'manage_options', 'satuin-about', 'satuin_menu_about_cb', 100);
}

function satuin_menu_setting_cb()
{
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    wp_enqueue_style('satuin-plugin-style', plugins_url('/css/styles.css', __FILE__));
    wp_enqueue_script('vue', plugins_url('/js/vue.min.js',  __FILE__));
    wp_enqueue_script('js-app', plugins_url('/js/app.js', __FILE__), 'js-app');
    require_once('views/setting-view.php');
}

function satuin_menu_about_cb()
{
    if (!current_user_can('manage_options')) {
        wp_die(__('You do not have sufficient permissions to access this page.'));
    }
    wp_enqueue_style('satuin-plugin-style', plugins_url('/css/styles.css', __FILE__));
    wp_enqueue_script('vue', plugins_url('/js/vue.min.js',  __FILE__));
    wp_enqueue_script('js-app', plugins_url('/js/app.js', __FILE__), 'js-app');
    require_once('views/about-view.php');
}

register_setting('satuin-settings', 'satuin_key');