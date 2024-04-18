<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Elementor form Satuin action.
 *
 * Custom Elementor form action which send data to Satuin outbound API after form submission.
 *
 * @since 1.0.0
 */
class Satuin_Elementor_Action_After_Submit extends \ElementorPro\Modules\Forms\Classes\Action_Base {

	/**
	 * Get action name.
	 *
	 * Retrieve Satuin action name.
	 *
	 * @since 1.0.0
	 * @access public
	 * @return string
	 */
	public function get_name() {
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
	public function get_label() {
		return esc_html__( 'Satuin', 'elementor-forms-satuin-action' );
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
	public function register_settings_section( $widget ) {

		$widget->start_controls_section(
			'section_satuin',
			[
				'label' => esc_html__( 'Satuin', 'elementor-forms-satuin-action' ),
				'condition' => [
					'submit_actions' => $this->get_name(),
				],
			]
		);

		$widget->add_control(
			'satuin_key',
			[
				'label' => esc_html__( 'Satuin Key', 'elementor-forms-satuin-action' ),
                'description' => esc_html__( 'Enter your Outbound API Key of your Satuin.', 'elementor-forms-satuin-action' ),
				'type' => \Elementor\Controls_Manager::SELECT,
                'options' => [
                    'default' => esc_html__( 'Default', 'elementor-forms-satuin-action' ),
                    'new' => esc_html__( 'New Key', 'elementor-forms-satuin-action' ),
                ],
                'default' => 'default',
			]
		);

        // show new section if satuin_key is new key
        $widget->add_control(
            'satuin_key_new',
            [
                'label' => esc_html__( 'New Key', 'elementor-forms-satuin-action' ),
                'type' => \Elementor\Controls_Manager::TEXT,
                'input_type' => 'text',
                'placeholder' => esc_html__( 'Enter new Satuin Key here', 'elementor-forms-satuin-action' ),
                'condition' => [
                    'satuin_key' => 'new',
                ],
            ]
        );

        $widget->add_control(
            'satuin_map_field_header',
            [
                'label' => esc_html__( 'Field Mapping', 'elementor-forms-satuin-action' ),
                'type' => \Elementor\Controls_Manager::HEADING,
            ]
        );

        $widget->add_control(
            'satuin_name_field',
            [
                'label' => esc_html__( 'Name', 'elementor-forms-satuin-action' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $widget->get_form_fields(),
            ]
        );

        $widget->add_control(
            'satuin_email_field',
            [
                'label' => esc_html__( 'Email *', 'elementor-forms-satuin-action' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $widget->get_form_fields(),
            ]
        );

        $widget->add_control(
            'satuin_number_field',
            [
                'label' => esc_html__( 'Number *', 'elementor-forms-satuin-action' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $widget->get_form_fields(),
            ]
        );

        $widget->add_control(
            'satuin_pipeline_field',
            [
                'label' => esc_html__( 'Pipeline ID', 'elementor-forms-satuin-action' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $widget->get_form_fields(),
            ]
        );

        $widget->add_control(
            'satuin_stage_field',
            [
                'label' => esc_html__( 'Stage ID', 'elementor-forms-satuin-action' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $widget->get_form_fields(),
            ]
        );

        $widget->add_control(
            'satuin_deal_name_field',
            [
                'label' => esc_html__( 'Deal Name', 'elementor-forms-satuin-action' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $widget->get_form_fields(),
            ]
        );

        $widget->add_control(
            'satuin_amount_field',
            [
                'label' => esc_html__( 'Deal Amount', 'elementor-forms-satuin-action' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $widget->get_form_fields(),
            ]
        );

        $widget->add_control(
            'satuin_notes_field',
            [
                'label' => esc_html__( 'Deal Notes', 'elementor-forms-satuin-action' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $widget->get_form_fields(),
            ]
        );

        $widget->add_control(
            'satuin_products_field',
            [
                'label' => esc_html__( 'Deal Products', 'elementor-forms-satuin-action' ),
                'type' => \Elementor\Controls_Manager::SELECT,
                'options' => $widget->get_form_fields(),
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
	public function run( $record, $ajax_handler ) {

		$settings = $record->get( 'form_settings' );

        // Get satuin_key from settings
        $ouboundApiKey = $settings['satuin_key'];
        if ($ouboundApiKey === 'default') {
            // get satuin_key from settings
            $ouboundApiKey = esc_attr(get_option('satuin_key'));
        } else {
            $ouboundApiKey = $settings['satuin_key_new'];
        }

        if ( empty( $ouboundApiKey ) ) {
            return;
        }

		// Get submitted form data.
		$raw_fields = $record->get( 'fields' );

		// Normalize form data.
		$fields = [];
		foreach ( $raw_fields as $id => $field ) {
			$fields[ $id ] = $field['value'];
		}

		// Make sure the user entered an email or number (required for Satuin)
        if ( empty( $fields[ $settings['satuin_email_field'] ] ) && empty( $fields[ $settings['satuin_number_field'] ] ) ) {
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
        if ( ! empty( $fields[ $settings['satuin_name_field'] ] ) ) {
            $satuin_data['contactName'] = $fields[ $settings['satuin_name_field'] ];
        } else {
            $satuin_data['contactName'] = 'Contact #' . date('YmdHis');
        }

        // Set the contact email.
        if ( ! empty( $fields[ $settings['satuin_email_field'] ] ) ) {
            $satuin_data['contactEmail'] = $fields[ $settings['satuin_email_field'] ];
        } else {
            $satuin_data['contactEmail'] = 'emptymail@satuin.id';
        }

        // Set the contact number.
        if ( ! empty( $fields[ $settings['satuin_number_field'] ] ) ) {
            $satuin_data['contactNumber'] = $fields[ $settings['satuin_number_field'] ];
        } else {
            $satuin_data['contactNumber'] = '+62000000000';
        }

        // Set the deal name.
        if ( ! empty( $fields[ $settings['satuin_deal_name_field'] ] ) ) {
            $satuin_data['dealName'] = $fields[ $settings['satuin_deal_name_field'] ];
        } else {
            // $satuin_data['dealName'] = 'Deal #' . date('YmdHis');
        }

        // Set the deal notes.
        if ( ! empty( $fields[ $settings['satuin_notes_field'] ] ) ) {
            $satuin_data['dealNotes'] = $fields[ $settings['satuin_notes_field'] ];
        }

        // Set the deal amount.
        if ( ! empty( $fields[ $settings['satuin_amount_field'] ] ) ) {
            $satuin_data['dealAmount'] = $fields[ $settings['satuin_amount_field'] ];
        }

        // Set the deal products.
        if ( ! empty( $fields[ $settings['satuin_products_field'] ] ) ) {
            if (is_array($fields[ $settings['satuin_products_field'] ])) {
                $satuin_data['dealProducts'][0]['name'] = $fields[ $settings['satuin_products_field'] ];
            } else {
                foreach ($fields[ $settings['satuin_products_field'] ] as $key => $value) {
                    $satuin_data['dealProducts'][$key]['name'] = $value;
                }
            }
        }

        // Set the pipeline ID.
        if ( ! empty( $fields[ $settings['satuin_pipeline_field'] ] ) ) {
            $satuin_data['pipelineID'] = $fields[ $settings['satuin_pipeline_field'] ];
        }

        // Set the stage ID.
        if ( ! empty( $fields[ $settings['satuin_stage_field'] ] ) ) {
            $satuin_data['stageID'] = $fields[ $settings['satuin_stage_field'] ];
        }

        // Check if data is for submit contact only or submit deal.
        $isDealSubmission = false;
        if ( ! empty( $satuin_data['pipelineID'] ) && ! empty( $satuin_data['stageID'] ) ) {
            $isDealSubmission = true;
        }

        $oubountActionURL = 'https://tunnel.satuin.com/outbound/contact/create?key=' . $ouboundApiKey;
        if ($isDealSubmission) {
            $oubountActionURL = 'https://tunnel.satuin.com/outbound/deal/create?key=' . $ouboundApiKey;
        }

		// Send the request.
		wp_remote_post(
			$oubountActionURL,
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
	public function on_export( $element ) {

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