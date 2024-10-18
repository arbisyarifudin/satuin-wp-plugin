<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class Satuin_Elementor_Form_Widget extends Widget_Base {

    public function get_name() {
        return 'satuin-form';
    }

    public function get_title() {
        return 'Satuin Form';
    }

    public function get_icon() {
        return 'eicon-form-horizontal';
    }

    public function get_categories() {
        return [ 'general' ];
    }

    protected function _register_controls() {
        $this->start_controls_section(
            'content_section',
            [
                'label' => 'Content',
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        // Add your controls here. For example:
        $this->add_control(
            'form_id',
            [
                'label' => 'Form ID',
                'type' => Controls_Manager::TEXT,
                'input_type' => 'text',
                'placeholder' => 'Enter form ID here',
            ]
        );

        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        // Check if form_id is set
        if (empty($settings['form_id'])) {
            echo 'Error: No form ID provided.';
            return;
        }

        // Retrieve form ID from settings
        $form_id = esc_html($settings['form_id']);

        // Try to retrieve the form (adjust this logic based on how you store forms)
        $form = get_post($form_id); // Assuming forms are stored as posts

        if (!$form) {
            echo 'Error: Form not found.';
            return;
        }

        // Render the form (assuming the form content is stored in post_content)
        echo 'Form ID: ' . $form_id . '<br>';
        echo do_shortcode($form->post_content); // Assuming shortcodes are used for form rendering
    }
}

// Register the widget
\Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Satuin_Elementor_Form_Widget());
