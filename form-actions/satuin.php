<?php
if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

/**
 * Elementor form Satuin action.
 *
 * Custom Elementor form action which send data to Satuin outbound API after form submission.
 *
 * @since 1.0.0
 */
class Satuin_Elementor_Action_After_Submit extends \ElementorPro\Modules\Forms\Classes\Action_Base
{

    /**
     * Get action name.
     *
     * Retrieve Satuin action name.
     *
     * @since 1.0.0
     * @access public
     * @return string
     */
    public function get_name()
    {
        return 'satuin';
    }

    /**
     * Get action label.
     *
     * Retrieve Satuin action label.
     *
     * @since 1.0.0
     * @access public
     * @return string
     */
    public function get_label()
    {
        return esc_html__('Satuin', 'elementor-forms-satuin-action');
    }

    /**
     * Register action controls.
     *
     * Add input fields to allow the user to customize the action settings.
     *
     * @since 1.0.0
     * @access public
     * @param \Elementor\Widget_Base $widget
     */
    public function register_settings_section($widget)
    {

        // echo 'widget settings:';
        // echo '<pre>';
        // print_r($widget->get_settings());
        // echo '</pre>';

        $widget->start_controls_section(
            'section_satuin',
            [
                'label' => esc_html__('Satuin', 'elementor-forms-satuin-action'),
                'condition' => [
                    'submit_actions' => $this->get_name(),
                ],
            ]
        );

        $widget->add_control(
            'satuin_key',
            [
                'label' => esc_html__('Satuin Key', 'elementor-forms-satuin-action'),
                'description' => esc_html__('Use your Satuin Outbound API Key. Default key is set in Satuin settings.', 'elementor-forms-satuin-action'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'default' => esc_html__('Default', 'elementor-forms-satuin-action'),
                    'custom' => esc_html__('Custom Key', 'elementor-forms-satuin-action'),
                ],
                'default' => 'default',
            ]
        );

        // show new section if satuin_key is custom key
        $widget->add_control(
            'satuin_key_custom',
            [
                'label' => esc_html__('Custom Key', 'elementor-forms-satuin-action'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'text',
                'placeholder' => esc_html__('Enter custom key', 'elementor-forms-satuin-action'),
                'condition' => [
                    'satuin_key' => 'custom',
                ],
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $widget->add_control(
            'satuin_select_action',
            [
                'label' => esc_html__('Select Action', 'elementor-forms-satuin-action'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'submit_contact' => esc_html__('Submit Contact', 'elementor-forms-satuin-action'),
                    'submit_deal' => esc_html__('Submit Deal', 'elementor-forms-satuin-action'),
                    'send_email' => esc_html__('Send Email', 'elementor-forms-satuin-action'),
                ],
                'default' => 'submit_contact',
            ]
        );

        // show new section if satuin_select_action is send_email
        $widget->add_control(
            'satuin_email_template_id',
            [
                'label' => esc_html__('Email Template ID', 'elementor-forms-satuin-action'),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'text',
                'placeholder' => esc_html__('Enter template ID', 'elementor-forms-satuin-action'),
                'condition' => [
                    'satuin_select_action' => 'send_email',
                ],
                'ai' => [
                    'active' => false,
                ],
            ]
        );

        $widget->add_control(
            'satuin_map_field_header',
            [
                'label' => esc_html__('Field Mapping', 'elementor-forms-satuin-action'),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );

        $formFields = $widget->get_settings('form_fields');
        $formFieldsAsOptions = [];

        // var_dump($formFields);
        foreach ($formFields as $key => $value) {
            $formFieldsAsOptions[$value['custom_id']] = $value['field_label'];
        }

        $widget->add_control(
            'satuin_name_field',
            [
                'label' => esc_html__('Name', 'elementor-forms-satuin-action'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $formFieldsAsOptions,
            ]
        );

        $widget->add_control(
            'satuin_email_field',
            [
                'label' => esc_html__('Email', 'elementor-forms-satuin-action'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $formFieldsAsOptions,
            ]
        );

        $widget->add_control(
            'satuin_number_field',
            [
                'label' => esc_html__('Number', 'elementor-forms-satuin-action'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $formFieldsAsOptions,
            ]
        );

        $widget->add_control(
            'satuin_pipeline_field',
            [
                'label' => esc_html__('Pipeline ID *', 'elementor-forms-satuin-action'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $formFieldsAsOptions,
                'condition' => [
                    'satuin_select_action' => 'submit_deal',
                ],
            ],
        );

        $widget->add_control(
            'satuin_stage_field',
            [
                'label' => esc_html__('Stage ID *', 'elementor-forms-satuin-action'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $formFieldsAsOptions,
                'condition' => [
                    'satuin_select_action' => 'submit_deal',
                ],
            ]
        );

        $widget->add_control(
            'satuin_deal_name_field',
            [
                'label' => esc_html__('Deal Name', 'elementor-forms-satuin-action'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $formFieldsAsOptions,
                'condition' => [
                    'satuin_select_action' => 'submit_deal',
                ],
            ]
        );

        $widget->add_control(
            'satuin_amount_field',
            [
                'label' => esc_html__('Deal Amount', 'elementor-forms-satuin-action'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $formFieldsAsOptions,
                'condition' => [
                    'satuin_select_action' => 'submit_deal',
                ],
            ]
        );

        $widget->add_control(
            'satuin_notes_field',
            [
                'label' => esc_html__('Deal Notes', 'elementor-forms-satuin-action'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $formFieldsAsOptions,
                'condition' => [
                    'satuin_select_action' => 'submit_deal',
                ],
            ]
        );

        $widget->add_control(
            'satuin_products_field',
            [
                'label' => esc_html__('Deal Products', 'elementor-forms-satuin-action'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $formFieldsAsOptions,
                'condition' => [
                    'satuin_select_action' => 'submit_deal',
                ],
            ]
        );

        $widget->end_controls_section();
    }

    /**
     * Run action.
     *
     * Runs the Satuin action after form submission.
     *
     * @since 1.0.0
     * @access public
     * @param \ElementorPro\Modules\Forms\Classes\Form_Record  $record
     * @param \ElementorPro\Modules\Forms\Classes\Ajax_Handler $ajax_handler
     */
    public function run($record, $ajax_handler)
    {

        $settings = $record->get('form_settings');

        // var_dump($settings);

        // Get satuin_key from settings
        $ouboundApiKey = $settings['satuin_key'];
        if ($ouboundApiKey === 'default') {
            // get satuin_key from settings
            $ouboundApiKey = esc_attr(get_option('satuin_key'));
        } else {
            $ouboundApiKey = $settings['satuin_key_custom'];
        }

        if (empty($ouboundApiKey)) {
            return;
        }

        // Get submitted form data.
        $raw_fields = $record->get('fields');

        // Normalize form data.
        $fields = [];
        foreach ($raw_fields as $id => $field) {
            $fields[$id] = $field['value'];
        }

        // Make sure the user entered an email or number (required for Satuin)
        if (empty($fields[$settings['satuin_email_field']]) && empty($fields[$settings['satuin_number_field']])) {
            return;
        }

        // Prepare data to send to Satuin.
        $satuin_data = [
            'contactName' => '',
            'contactEmail' => '',
            'contactNumber' => '',
            'dealName' => '',
            'dealNotes' => '',
            'dealAmount' => '',
            'dealProducts' => '',
            'pipelineID' => '',
            'stageID' => '',
        ];

        // Set the contact name.
        if (!empty($fields[$settings['satuin_name_field']])) {
            $satuin_data['contactName'] = $fields[$settings['satuin_name_field']];
        } else {
            $satuin_data['contactName'] = 'Contact #' . date('YmdHis');
        }

        // Set the contact email.
        if (!empty($fields[$settings['satuin_email_field']])) {
            $satuin_data['contactEmail'] = $fields[$settings['satuin_email_field']];
        } else {
            $satuin_data['contactEmail'] = 'emptymail@satuin.id';
        }

        // Set the contact number.
        if (!empty($fields[$settings['satuin_number_field']])) {
            $satuin_data['contactNumber'] = $fields[$settings['satuin_number_field']];
        } else {
            $satuin_data['contactNumber'] = '+62000000000';
        }

        // Set the deal name.
        if (!empty($fields[$settings['satuin_deal_name_field']])) {
            $satuin_data['dealName'] = $fields[$settings['satuin_deal_name_field']];
        } else {
            // $satuin_data['dealName'] = 'Deal #' . date('YmdHis');
        }

        // Set the deal notes.
        if (!empty($fields[$settings['satuin_notes_field']])) {
            $satuin_data['dealNotes'] = $fields[$settings['satuin_notes_field']];
        }

        // Set the deal amount.
        if (!empty($fields[$settings['satuin_amount_field']])) {
            $satuin_data['dealAmount'] = $fields[$settings['satuin_amount_field']];
        }

        // Set the deal products.
        if (!empty($fields[$settings['satuin_products_field']])) {
            if (is_array($fields[$settings['satuin_products_field']])) {
                $satuin_data['dealProducts'][0]['name'] = $fields[$settings['satuin_products_field']];
            } else {
                foreach ($fields[$settings['satuin_products_field']] as $key => $value) {
                    $satuin_data['dealProducts'][$key]['name'] = $value;
                }
            }
        }

        // Set the pipeline ID.
        if (!empty($fields[$settings['satuin_pipeline_field']])) {
            $satuin_data['pipelineID'] = $fields[$settings['satuin_pipeline_field']];
        }

        // Set the stage ID.
        if (!empty($fields[$settings['satuin_stage_field']])) {
            $satuin_data['stageID'] = $fields[$settings['satuin_stage_field']];
        }

        // Check if data is for submit contact only or submit deal.
        // $isDealSubmission = false;
        // if ( ! empty( $satuin_data['pipelineID'] ) && ! empty( $satuin_data['stageID'] ) ) {
        //     $isDealSubmission = true;
        // }

        $baseUrl = (ENVIROMENT === 'development') ? 'tunnel-dev.satuin.id' : 'tunnel.satuin.id';

        switch ($settings['satuin_select_action']) {
            case 'submit_deal':
                $action = 'deal/create';
                break;
            case 'send_email':
                $action = 'email/send';
                break;
            default:
                $action = 'contact/create';
                break;
        }

        $ouboundActionURL = 'https://' . $baseUrl . '/outbound/' . $action . '?key=' . $ouboundApiKey;

        // Send the request.
        $result = wp_remote_post(
            $ouboundActionURL,
            [
                'body' => $satuin_data,
            ]
        );
    }

    /**
     * On export.
     *
     * Clears Satuin form settings/fields when exporting.
     *
     * @since 1.0.0
     * @access public
     * @param array $element
     */
    public function on_export($element)
    {

        unset(
            $element['satuin_key'],
            $element['satuin_name_field'],
            $element['satuin_email_field'],
            $element['satuin_number_field'],
            $element['satuin_pipeline_field'],
            $element['satuin_stage_field'],
            $element['satuin_deal_name_field'],
            $element['satuin_amount_field'],
            $element['satuin_notes_field'],
            $element['satuin_products_field'],
        );

        return $element;
    }
}
