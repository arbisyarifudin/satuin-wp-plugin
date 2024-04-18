<?php
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
        return 'fa fa-form';
    }

    public function get_categories() {
        return ['general'];
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

        // Render the form based on the form ID
        echo 'Render form with ID: ' . $settings['form_id'];
    }
}

// \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new Satuin_Elementor_Form_Widget());