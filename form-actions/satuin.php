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

    public function get_name()
    {
        return 'satuin';
    }

    public function get_label()
    {
        return esc_html__('Satuin', 'elementor-forms-satuin-action');
    }

    public function register_settings_section($widget)
    {
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
                    // 'send_email' => esc_html__('Send Email', 'elementor-forms-satuin-action'),
                ],
                'default' => 'submit_contact',
            ]
        );

        // $widget->add_control(
        //     'satuin_email_template_id',
        //     [
        //         'label' => esc_html__('Email Template ID', 'elementor-forms-satuin-action'),
        //         'type' => \Elementor\Controls_Manager::TEXT,
        //         'input_type' => 'text',
        //         'placeholder' => esc_html__('Enter template ID', 'elementor-forms-satuin-action'),
        //         'condition' => [
        //             'satuin_select_action' => 'send_email',
        //         ],
        //         'ai' => [
        //             'active' => false,
        //         ],
        //     ]
        // );

        $widget->add_control(
            'satuin_auto_followup_option',
            [
                'label' => esc_html__('Use Auto Follow up', 'elementor-forms-satuin-action'),
                'description' => esc_html__('
                    Enable this option to automatically send follow up to the contact after 1 ~ 5 minutes. 
                ', 'elementor-forms-satuin-action'),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'disabled' => esc_html__('Disabled', 'elementor-forms-satuin-action'),
                    'whatsapp' => esc_html__('WhatsApp', 'elementor-forms-satuin-action'),
                    // 'email' => esc_html__('Email', 'elementor-forms-satuin-action'),
                ],
                'default' => 'disabled',
                'condition' => [
                    'satuin_select_action' => 'submit_deal',
                ],
            ]
        );

        $widget->add_control(
            'satuin_auto_followup_message',
            [
                'label' => esc_html__('Auto Follow up Message', 'elementor-forms-satuin-action'),
                'type' => \Elementor\Controls_Manager::TEXTAREA,
                'input_type' => 'text',
                'placeholder' => esc_html__('Enter message', 'elementor-forms-satuin-action'),
                'condition' => [
                    'satuin_auto_followup_option' => ['whatsapp', 'email'],
                    'satuin_select_action' => 'submit_deal',
                ],
                'ai' => [
                    'active' => true,
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
            'satuin_salutation_field',
            [
                'label' => esc_html__('Salutation', 'elementor-forms-satuin-action'),
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
            ]
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

    public function run($record, $ajax_handler)
    {
        setcookie('satuin_action', 'running', 0, COOKIEPATH, COOKIE_DOMAIN, true);
        
        $settings = $record->get('form_settings');
        
        add_option('satuin_key', 'default_value');

        $ouboundApiKey = @$settings['satuin_key'];
        if ($ouboundApiKey === 'default') {
            $ouboundApiKey = esc_attr(get_option('satuin_key'));
        } else {
            $ouboundApiKey = @$settings['satuin_key_custom'];
        }

        setcookie('satuin_action_apikey', $ouboundApiKey, 0, COOKIEPATH, COOKIE_DOMAIN, true);

        if (empty($ouboundApiKey)) {
            return;
        }

        $raw_fields = $record->get('fields');
        $fields = [];
        foreach ($raw_fields as $id => $field) {
            $fields[$id] = $field['value'];
        }

        if (empty($fields[$settings['satuin_email_field']]) && empty($fields[$settings['satuin_number_field']])) {
            return;
        }

        setcookie('satuin_action_fields', json_encode($fields), 0, COOKIEPATH, COOKIE_DOMAIN, true);

        $satuin_data = [
            'contactName' => '',
            'contactEmail' => '',
            'contactNumber' => '',
            'contactSalutation' => '',
            'dealName' => '',
            'dealNotes' => '',
            'dealAmount' => '',
            'dealProducts' => [],
            'pipelineID' => '',
            'stageID' => '',
            // 'emailTemplateID' => '',
            'autoFollowupOption' => 'disabled',
            'autoFollowupMessage' => '',
        ];

        if (!empty($fields[$settings['satuin_name_field']])) {
            $satuin_data['contactName'] = $fields[$settings['satuin_name_field']];
        } else {
            $satuin_data['contactName'] = 'Contact #' . date('YmdHis');
        }

        if (!empty($fields[$settings['satuin_email_field']])) {
            $satuin_data['contactEmail'] = $fields[$settings['satuin_email_field']];
        } else {
            $satuin_data['contactEmail'] = 'emptymail@satuin.id';
        }

        if (!empty($fields[$settings['satuin_number_field']])) {
            $satuin_data['contactNumber'] = $fields[$settings['satuin_number_field']];
        } else {
            $satuin_data['contactNumber'] = '+62000000000';
        }

        if (!empty($fields[$settings['satuin_salutation_field']])) {
            $satuin_data['contactSalutation'] = $fields[$settings['satuin_salutation_field']];
        }

        if (!empty($fields[$settings['satuin_deal_name_field']])) {
            $satuin_data['dealName'] = $fields[$settings['satuin_deal_name_field']];
        }

        if (!empty($fields[$settings['satuin_notes_field']])) {
            $satuin_data['dealNotes'] = $fields[$settings['satuin_notes_field']];
        }

        if (!empty($fields[$settings['satuin_amount_field']])) {
            $satuin_data['dealAmount'] = $fields[$settings['satuin_amount_field']];
        }

        if (!empty($fields[$settings['satuin_products_field']])) {
            if (is_array($fields[$settings['satuin_products_field']])) {
                foreach ($fields[$settings['satuin_products_field']] as $key => $value) {
                    $satuin_data['dealProducts'][$key]['name'] = $value;
                }
            } else {
                $satuin_data['dealProducts'][0]['name'] = $fields[$settings['satuin_products_field']];
            }
        }

        if (!empty($fields[$settings['satuin_pipeline_field']])) {
            $satuin_data['pipelineID'] = $fields[$settings['satuin_pipeline_field']];
        }

        if (!empty($fields[$settings['satuin_stage_field']])) {
            $satuin_data['stageID'] = $fields[$settings['satuin_stage_field']];
        }

        // if (!empty(@$settings['satuin_email_template_id'])) {
        //     $satuin_data['emailTempla teID'] = $settings['satuin_email_template_id'];
        // }

        if (!empty(@$settings['satuin_auto_followup_option'])) {
            $satuin_data['autoFollowupOption'] = $settings['satuin_auto_followup_option'];
        }
        if (!empty(@$settings['satuin_auto_followup_message'])) {
            $satuin_data['autoFollowupMessage'] = $settings['satuin_auto_followup_message'];
        }

        $additionals = [
            'referrerOrigin' => isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '',
            'referrerURL' => isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : '',
        ];

        foreach ($fields as $key => $value) {
            if (!in_array($key, array_keys($satuin_data))) {
                $additionals[$key] = $value;
            }
        }

        unset($additionals['dealProduct']);
        $satuin_data['additionals'] = $additionals;

        setcookie('satuin_action_data', json_encode($satuin_data), 0, COOKIEPATH, COOKIE_DOMAIN, true);

        // $baseUrl = (APP_MODE === 'development') ? 'tunnel-dev.satuin.id' : 'tunnel.satuin.id';
        $baseUrl = 'tunnel.satuin.id';

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
        setcookie('satuin_action_url', $ouboundActionURL, 0, COOKIEPATH, COOKIE_DOMAIN, true);

        $args = [
            'body' => $satuin_data,
        ];

        $args = apply_filters('elementor_pro/forms/satuin/request_args', $args, $record);

        $errors = [];
        try {
            $response = wp_remote_post($ouboundActionURL, $args);
            $responseBody = wp_remote_retrieve_body($response);
            $responseBodyArray = json_decode($responseBody, true);

            setcookie('satuin_action_response_body', json_encode($responseBodyArray), 0, COOKIEPATH, COOKIE_DOMAIN, true);

            if (isset($responseBodyArray['status']) && $responseBodyArray['status'] == 400) {
                if (!empty($responseBodyArray['messages'])) {
                    foreach ($responseBodyArray['messages'] as $message) {
                        $errors[] = $message;
                    }
                } else {
                    $errors[] = $responseBodyArray['message'];
                }
            }

            setcookie('satuin_action_api_response', $responseBody, 0, COOKIEPATH, COOKIE_DOMAIN, true);
        } catch (Exception $e) {
            setcookie('satuin_action_api_error', $e->getMessage(), 0, COOKIEPATH, COOKIE_DOMAIN, true);
            error_log('Satuin outbound API error: ' . $e->getMessage());
        }

        do_action('elementor_pro/forms/satuin/response', $response, $record);

        if (200 !== (int) wp_remote_retrieve_response_code($response)) {
            $errorMessage = '';
            foreach ($errors as $error) {
                $errorMessage .= $error . ' ';
            }
            throw new Exception("Outbound API error: " . $errorMessage);
        }
    }

    private function sendRequest($url, $data)
    {
        $args = [
            'headers' => [
                'Content-Type' => 'application/json',
            ],
            'body' => wp_json_encode($data),
            'timeout' => 90,  // Adjust the timeout if necessary
        ];

        $response = wp_remote_post($url, $args);

        if (is_wp_error($response)) {
            // Log the error message for debugging
            error_log('Satuin API request failed: ' . $response->get_error_message());

            // Return a detailed message instead of crashing
            throw new Exception('Error contacting Satuin API: ' . $response->get_error_message());
        }

        // Retrieve the response body and status code
        $response_body = wp_remote_retrieve_body($response);
        $response_code = wp_remote_retrieve_response_code($response);

        // Check if the response code is not 200
        if ($response_code !== 200) {
            // Log the response and the invalid response code
            error_log('Satuin API returned invalid response code: ' . $response_code);
            error_log('Satuin API response body: ' . $response_body);

            throw new Exception('Invalid response from Satuin API: ' . $response_code);
        }

        // Handle the response body (convert JSON to array for further processing if needed)
        $response_data = json_decode($response_body, true);

        // Ensure that the API response body is valid
        if (!is_array($response_data) || isset($response_data['error'])) {
            error_log('Satuin API error in response: ' . print_r($response_data, true));

            // Throw a more descriptive error
            throw new Exception('API error: ' . (isset($response_data['error']) ? $response_data['error'] : 'Unknown error'));
        }

        return $response_data; // Success
    }

    public function on_export($element)
    {
        unset(
            $element['satuin_key'],
            $element['satuin_key_custom'],
            $element['satuin_name_field'],
            $element['satuin_email_field'],
            $element['satuin_number_field'],
            $element['satuin_pipeline_field'],
            $element['satuin_stage_field'],
            $element['satuin_deal_name_field'],
            $element['satuin_amount_field'],
            $element['satuin_notes_field'],
            $element['satuin_products_field']
        );

        return $element;
    }
}
