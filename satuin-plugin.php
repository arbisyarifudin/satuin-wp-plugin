<?php

/**
 * Plugin Name: Satuin Plugin
 * Plugin URI:        http://satuin.id/
 * Description:       Satuin Custom Plugin
 * Version:           0.0.2
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

/* Add Satuin to Admin Menu */
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

/* Create Satuin Form and add it to Elementor widget */
add_action('elementor/widgets/widgets_registered', 'satuin_register_elementor_widget');

function satuin_register_elementor_widget()
{
    if (defined('ELEMENTOR_PATH') && class_exists('Elementor\Widget_Base')) {
        require_once('widgets/satuin-elementor-form-widget.php');

        $elementor_widget = new Satuin_Elementor_Form_Widget();

        // Let Elementor know about our widget
        Elementor\Plugin::instance()->widgets_manager->register_widget_type($elementor_widget);

        // Register the script
        wp_register_script('satuin-elementor-form-script', plugins_url('/js/satuin-elementor-form-script.js', __FILE__), ['jquery'], false, true);

        // Enqueue the script
        wp_enqueue_script('satuin-elementor-form-script');
    }
}

/* Create Satuin shortcode */
add_shortcode('satuin_form', 'satuin_form_shortcode');

function satuin_form_shortcode($atts) {
    // Generate form based on settings
    $atts = shortcode_atts(
        array(
            'form_id' => 'default_form_id',
        ),
        $atts,
        'satuin_form'
    );

    return 'Render form with ID: ' . $atts['form_id'];
}

/**
 * Add Satuin Form Action to Elementor Pro Form
 *
 * @since 1.0.0
 * @param ElementorPro\Modules\Forms\Registrars\Form_Actions_Registrar $form_actions_registrar
 * @return void
 */
function add_satuin_form_action( $form_actions_registrar ) {

	include_once( __DIR__ .  '/form-actions/satuin.php' );

	$form_actions_registrar->register( new Satuin_Elementor_Action_After_Submit() );

}
add_action( 'elementor_pro/forms/actions/register', 'add_satuin_form_action' );