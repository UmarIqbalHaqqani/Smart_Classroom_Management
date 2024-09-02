<?php
namespace Elementor;

use \Elementor\ElementsKit_Widget_Protected_Content_Handler as Handler;
use \ElementsKit_Lite\Modules\Controls\Controls_Manager as ElementsKit_Controls_Manager;

if ( ! defined( 'ABSPATH' ) ) exit;
class ElementsKit_Widget_Protected_Content extends Widget_Base {

	use \ElementsKit_Lite\Widgets\Widget_Notice;

	public $base;

	public function __construct($data = [], $args = null) {

		add_action('elementor/editor/after_enqueue_scripts', function() {
			wp_enqueue_script('ekit-protected-content', Handler::get_url() . 'assets/js/script.js', ['elementor-editor'], \ElementsKit_Lite::version(), true);
		});

		parent::__construct($data, $args);
	}

    public function get_name() {
        return Handler::get_name();
    }

    public function get_title() {
        return Handler::get_title();
    }

    public function get_icon() {
        return Handler::get_icon();
    }

    public function get_categories() {
        return Handler::get_categories();
    }
    
	public function get_keywords() {
		return Handler::get_keywords();
	}

	public function get_help_url() {
		return "https://wpmet.com/doc/protected-content";
	}

    protected function register_controls() {
        $this->start_controls_section(
            'ekit_pc_content_section',
            [
                'label' => esc_html__('Content', 'elementskit'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

		$this->add_control(
			'ekit_pc_content_type',
			[
				'label' => esc_html__('Content Type', 'elementskit'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'custom_content'  => esc_html__('Custom Content', 'elementskit'),
					'elementor_template' => esc_html__('Elementor Template', 'elementskit'),
				],
				'default' => 'custom_content',
			]
		);

		$this->add_control(
			'ekit_pc_protected_content',
			[
				'label' => esc_html__('Protected Content', 'elementskit'),
				'type' => Controls_Manager::WYSIWYG,
				'label_block' => true,
				'dynamic' => [
					'active' => true
				],
				'default' => esc_html('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.'),
				'condition' => [
					'ekit_pc_content_type' => 'custom_content'
				]
			]
		);

		$this->add_control(
			'ekit_pc_elementor_template',
			[
				'label' => esc_html__('Elementor Templates', 'elementskit'),
				'type' => ElementsKit_Controls_Manager::AJAXSELECT2,
				'options' =>'ajaxselect2/elementor_template_list',
				'label_block' => true,
				'multiple'  => false,
				'condition' => [
					'ekit_pc_content_type' => 'elementor_template'
				]
			]
		);

		$this->add_control(
			'ekit_pc_elementor_template_edit',
			[
				'type' => Controls_Manager::RAW_HTML,
				'raw' => sprintf(
					'<a href="#" target="_blank" class="elementor-button ekit_elementor_template_edit_link" style="border:none;">
						<i class="eicon-pencil"></i>
						%1$s
					</a>',
					esc_html__('Edit Template', 'elementskit')
				),
				'condition' => [
					'ekit_pc_elementor_template!' => '',
					'ekit_pc_content_type' => 'elementor_template'
				]
			]
		);

		$this->end_controls_section();

		// Protection content start
		$this->start_controls_section(
            'ekit_pc_protection_type_section',
            [
                'label' => esc_html__('Protection Type', 'elementskit'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

		$this->add_control(
			'ekit_pc_protection_type',
			[
				'label'	=> esc_html__('Protection Type', 'elementskit'),
				'label_block' => false,
				'type' => Controls_Manager::SELECT,
				'options' => [
					'roles' => esc_html__('User Role', 'elementskit'),
					'password' => esc_html__('Password', 'elementskit'),				
				],
				'default' => 'password'
			]
		);

		$this->add_control(
            'ekit_pc_allowed_roles',
            [
                'label' => esc_html__('Select Roles', 'elementskit'),
				'type' => Controls_Manager::SELECT2,
				'label_block' => true,
				'multiple' => true,
				'options' => self::get_user_roles(),
				'dynamic' => [
					'active' => true
				],
				'condition'	=> [
					'ekit_pc_protection_type'	=> 'roles'
				]				
            ]
		);

		$this->add_control(
			'ekit_pc_set_password',
			[
				'label' => esc_html__('Set Password', 'elementskit'),
				'type' => Controls_Manager::TEXT,
				'input_type' => 'text',
				'dynamic' => [
					'active' => true
				],
				'condition'	=> [
					'ekit_pc_protection_type'	=> 'password'	
				]
			]
		);	
			
		$this->add_control(
			'ekit_pc_show_content',
			[
				'label' => esc_html__('Show Content', 'elementskit'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'label_on' => esc_html__('Show', 'elementskit'),
				'label_off' => esc_html__('Hide', 'elementskit'),
				'return_value' => 'yes',
				'description' => esc_html__('You can show your protected content in the editor for design purposes.', 'elementskit'),
				'condition'	=> [
					'ekit_pc_protection_type'	=> ['password']
				]
			]
		);
		
		$this->add_control(
			'ekit_pc_enable_cookie',
			[
				'label' => esc_html__('Cookie', 'elementskit'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'label_on' => esc_html__('Enable', 'elementskit'),
				'label_off' => esc_html__('Disable', 'elementskit'),
				'separator' => 'before',
				'condition'	=> [
					'ekit_pc_protection_type'	=> ['password']
				]
			]
		);

		$this->add_control(
			'ekit_pc_cookie',
			[
				'label'	=> esc_html__('Cookie', 'elementskit'),
				'label_block' => false,
				'type' => Controls_Manager::SELECT,
				'options' => [
					'time' => esc_html__('Time', 'elementskit'),
					'days' => esc_html__('Days', 'elementskit'),				
				],
				'default' => 'time',
				'condition' => [
					'ekit_pc_enable_cookie' => 'yes',
					'ekit_pc_protection_type' => ['password']
				],
			]
		);

		$this->add_control(
			'ekit_pc_cookie_time',
			[
				'label' => esc_html__('Expire Time', 'elementskit'),
				'description' => esc_html__('Enter expiration time in minutes', 'elementskit'),
				'type' => Controls_Manager::NUMBER,
				'dynamic' => [
					'active' => true,
				],
				'min' => 1,
				'default' => 1000,
				'condition' => [
					'ekit_pc_cookie' => 'time',
					'ekit_pc_enable_cookie' => 'yes',
					'ekit_pc_protection_type' => ['password']
				],
			]
		);

		$this->add_control(
			'ekit_pc_cookie_days',
			[
				'label' => esc_html__('Days', 'elementskit'),
				'description' => esc_html__('Enter expiration in days', 'elementskit'),
				'type' => Controls_Manager::NUMBER,
				'dynamic' => [
					'active' => true,
				],
				'min' => 1,
				'max' => 365,
				'default' => 1,
				'condition' => [
					'ekit_pc_cookie' => 'days',
					'ekit_pc_enable_cookie' => 'yes',
					'ekit_pc_protection_type'	=> ['password']
				],
			]
		);

		$this->end_controls_section();

		// Message Type section Start
		$this->start_controls_section(
			'ekit_pc_message_type_section',
			[
				'label' => esc_html__('Message Type' , 'elementskit'),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'ekit_pc_message_source',
			[
				'label' => esc_html__('Message Source', 'elementskit'),
				'label_block' => false,
				'type'	=> Controls_Manager::SELECT,
                'description' => esc_html__('Set a message or a saved template when the content is protected.', 'elementskit'),
				'options' => [
					'none' => esc_html__('None', 'elementskit'),
					'text' => esc_html__('Message', 'elementskit'),
					'elementor_template'	=> esc_html__('Elementor Templates', 'elementskit')
				],
				'default'		=> 'text'
			]
		);

		$this->add_control(
			'ekit_pc_message_text',
			[
				'label' => esc_html__('Text', 'elementskit'),
				'type' => Controls_Manager::WYSIWYG,
				'default' => esc_html__('You do not have permission to see this content.','elementskit'),
				'dynamic' => [
					'active' => true
				],
				'condition' => [
					'ekit_pc_message_source' => 'text'
				]
			]
		);

		$this->add_control(
			'ekit_pc_message_template',
			[
				'label' => esc_html__('Choose Elementor Template', 'elementskit'),
				'type' => ElementsKit_Controls_Manager::AJAXSELECT2,
				'options' =>'ajaxselect2/elementor_template_list',
				'label_block' => true,
				'multiple'  => false,
				'condition' => [
					'ekit_pc_message_source' => 'elementor_template',
				],
			]
		);

		$this->end_controls_section();

		// Input Field section start
		$this->start_controls_section(
			'ekit_pc_form_field_section',
			[
				'label' => esc_html__('Form Field' , 'elementskit'),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'ekit_pc_input_text',
			[
				'label' => esc_html__('Input text', 'elementskit'),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__('Enter Password', 'elementskit'),
				'placeholder' => esc_html__('Enter Password', 'elementskit'),
				'dynamic' => ['active'   => true,],
			]
		);

		$this->add_control(
			'ekit_pc_submit_text',
			[
				'label' => esc_html__('Submit text', 'elementskit'),
				'type' => Controls_Manager::TEXT,	
				'default' => esc_html__('Submit', 'elementskit'),
				'placeholder' => esc_html__('Submit', 'elementskit'),
				'dynamic' => ['active'   => true,],
			]
		);

		$this->end_controls_section();

		// error message start	
		$this->start_controls_section(
			'ekit_pc_warning_message_section',
			[
				'label' => esc_html__('Warning Message' , 'elementskit'),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'ekit_pc_show_warning',
			[
				'label' => esc_html__('Show Warning', 'elementskit'),
				'type' => Controls_Manager::SWITCHER,
				'default' => 'no',
				'label_on' => esc_html__('Show', 'elementskit'),
				'label_off' => esc_html__('Hide', 'elementskit'),
				'description' => esc_html__('You can show your incorrect message in the editor for design purposes.', 'elementskit'),
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'ekit_pc_incorrect_message',
			[
				'label' => esc_html__('Incorrect Message', 'elementskit'),
				'type' => Controls_Manager::TEXTAREA,
				'default' => esc_html__('Oops, you entered the wrong password! Please try again.', 'elementskit'),
				'placeholder' => esc_html__('Oops, you entered the wrong password! Please try again.', 'elementskit'),
				'dynamic' => ['active'   => true,],
			]
		);

		$this->end_controls_section();

		//Style Wrapper section start
		$this->start_controls_section(
			'ekit_pc_wrapper_style',
			[
				'label' => esc_html__('Wrapper', 'elementskit'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'ekit_pc_wrapper_alignment',
			[
				'label' => esc_html__('Alignment', 'elementskit'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__('Left', 'elementskit'),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__('Center', 'elementskit'),
						'icon' => 'eicon-text-align-center',
					],
					'flex-end' => [
						'title' => esc_html__('Right', 'elementskit'),
						'icon' => 'eicon-text-align-right',
					],
				],
				'toggle' => false,
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .ekit-protected-content' => 'display: flex; flex-direction: column; align-items: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'ekit_pc_wrapper_direction',
			[
				'label' => esc_html__( 'Direction', 'elementskit' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'row' => [
						'title' => esc_html__( 'Row - horizontal', 'elementskit' ),
						'icon' => 'eicon-arrow-right',
					],
					'row-reverse' => [
						'title' => esc_html__( 'Row - reversed', 'elementskit' ),
						'icon' => 'eicon-arrow-left',
					],
					'column' => [
						'title' => esc_html__( 'Column - vertical', 'elementskit' ),
						'icon' => 'eicon-arrow-down',
					],
					'column-reverse' => [
						'title' => esc_html__( 'Column - reversed', 'elementskit' ),
						'icon' => 'eicon-arrow-up',
					],
				],
				'toggle' => false,
				'default' => 'row',
				'selectors_dictionary' => [
					'row' => 'flex-direction: row; flex-wrap: wrap;',
					'row-reverse' => 'flex-direction: row-reverse; flex-wrap: wrap;',
					'column' => 'flex-direction: column;',
					'column-reverse' => 'flex-direction: column-reverse;',
				],
				'selectors' => [
					'{{WRAPPER}} .ekit-protected-content .protected-content-form-fields form' => '{{VALUE}}',
				],
			]
		);

		$this->end_controls_section();

		//Style Content section start
		$this->start_controls_section(
			'ekit_pc_content_style',
			[
				'label' => esc_html__('Content', 'elementskit'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'ekit_pc_content_type' => 'custom_content'
				]
			]
		);

		$this->add_control(
			'ekit_pc_text_color',
			[
				'label' => esc_html__('Text Color', 'elementskit'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ekit-protected-content .protected-content' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'ekit_protected_content_typography',
				'selector' => '{{WRAPPER}} .ekit-protected-content .protected-content',
			]
		);

		$this->add_responsive_control(
			'ekit_pc_padding',
			[
				'label' => esc_html__('Padding', 'elementskit'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .ekit-protected-content .protected-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],				
			]
		);

		$this->end_controls_section();

		// Message Type style start
		$this->start_controls_section(
			'ekit_pc_message_style',
			[
				'label' => esc_html__('Message Type', 'elementskit'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'ekit_pc_massage_text_color',
			[
				'label' => esc_html__('Text Color', 'elementskit'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ekit-protected-content .protected-content-message' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'ekit_protected_content_message_typography',
				'selector' => '{{WRAPPER}} .ekit-protected-content .protected-content-message',
			]
		);

		$this->add_responsive_control(
			'ekit_pc_message_margin',
			[
				'label' => esc_html__('Margin', 'elementskit'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'default' => [
					'top' => '10',
					'right' => '10',
					'bottom' => '10',
					'left' => '10',
					'unit' => 'px',
					//'isLinked' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .ekit-protected-content .protected-content-message' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],				
			]
		);

		$this->end_controls_section();

		// Form Style section 
		$this->start_controls_section(
			'ekit_pc_form_field_style',
			[
				'label' => esc_html__('Form Field' , 'elementskit'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'ekit_pc_form_input_width',
			[
				'type' => Controls_Manager::SLIDER,
				'label' => esc_html__('Width', 'elementskit'),
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'range' => [					
					'px' => [
						'min' => 100,
						'max' => 1200,
						'step' => 5,
					],
					'%' => [
						'min' => 10,
						'max' => 100,
						'step' => 1,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 250,
				],
				'render_type' => 'template',
				'selectors' => [
					'{{WRAPPER}} .ekit-protected-content .password' => 'width: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'ekit_pc_form_shadow',
				'selector' => '{{WRAPPER}} .ekit-protected-content .password',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'ekit_pc_form_input_typography',
				'selector' => '{{WRAPPER}} .ekit-protected-content .password',
			]
		);

		$this->add_control(
			'ekit_pc_form_input_placeholder',
			[
				'label' => esc_html__('Placeholder Color', 'elementskit'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ekit-protected-content .protected-content-form input::-webkit-input-placeholder' => 'color: {{VALUE}};',
				],
			]
		);

		$this->start_controls_tabs( 
			'ekit_pc_form_input_tabs' 
		);
		$this->start_controls_tab(
			'ekit_pc_form_tab_input',
			[
				'label' => esc_html__('Normal', 'elementskit'),
			]
		);

		$this->add_control(
			'ekit_pc_form_input_color',
			[
				'label' => esc_html__('Color', 'elementskit'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ekit-protected-content .password' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'ekit_pc_form_input_bg',
			[
				'label' => esc_html__('Background', 'elementskit'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ekit-protected-content .password' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'ekit_pc_form_input_border',
				'label' => esc_html__('Border', 'elementskit'),
				'selector' => '{{WRAPPER}} .ekit-protected-content .password',
			]
		);

		$this->add_responsive_control(
			'ekit_pc_form_input_border_radius',
			[
				'label' => esc_html__('Border Radius', 'elementskit'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .ekit-protected-content .password' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'ekit_pc_form_tab_input_focus',
			[
				'label' => esc_html__('Focus', 'elementskit'),
			]
		);

		$this->add_control(
			'ekit_pc_form_input_focus_color',
			[
				'label' => esc_html__('Color', 'elementskit'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ekit-protected-content .password:focus' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'ekit_pc_form_input_focus_bg',
			[
				'label' => esc_html__('Background', 'elementskit'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ekit-protected-content .password:focus' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'ekit_pc_form_input_focus_border',
				'label' => esc_html__('Border', 'elementskit'),
				'selector' => '{{WRAPPER}} .ekit-protected-content .password:focus',
			]
		);

		$this->add_responsive_control(
			'ekit_pc_form_input_focus_border_radius',
			[
				'label' => esc_html__('Border Radius', 'elementskit'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .ekit-protected-content .password:focus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control(
			'ekit_pc_form_input_padding',
			[
				'label' => esc_html__('Padding', 'elementskit'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],

				'separator' => "before",
				'selectors' => [
					'{{WRAPPER}} .ekit-protected-content .password' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],				
			]
		);
		
		$this->add_responsive_control(
			'ekit_pc_form_input_margin',
			[
				'label' => esc_html__('Margin', 'elementskit'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'default' => [
					'top' => '5',
					'right' => '5',
					'bottom' => '5',
					'left' => '5',
					'unit' => 'px',
					//'isLinked' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .ekit-protected-content .password' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],				
			]
		);

		$this->end_controls_section();


		// Submit Button Style section 
		$this->start_controls_section(
			'ekit_pc_submit_button_style',
			[
				'label' => esc_html__('Submit Button' , 'elementskit'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name'  => 'ekit_pc_form_button_shadow',
				'selector' => '{{WRAPPER}} .ekit-protected-content .protected-content-submit',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'ekit_pc_form_button_typography',
				'selector' => '{{WRAPPER}} .ekit-protected-content .protected-content-submit',
			]
		);

		$this->start_controls_tabs( 
			'ekit_pc_form_button_tabs' 
		);
		$this->start_controls_tab(
			'ekit_pc_form_tab_button',
			[
				'label' => esc_html__('Normal', 'elementskit'),
			]
		);

		$this->add_control(
			'ekit_pc_button_color',
			[
				'label' => esc_html__('Color', 'elementskit'),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffff',
				'selectors' => [
					'{{WRAPPER}} .ekit-protected-content .protected-content-submit' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'ekit_pc_button_background',
			[
				'label' => esc_html__('Background', 'elementskit'),
				'type' => Controls_Manager::COLOR,
				'default' => '#CC3366',
				'selectors' => [
					'{{WRAPPER}} .ekit-protected-content .protected-content-submit' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'ekit_pc_button_border',
				'label' => esc_html__('Border', 'elementskit'),
				'selector' => '{{WRAPPER}} .ekit-protected-content .protected-content-submit',
			]
		);

		$this->add_responsive_control(
			'ekit_pc_button_border_radius',
			[
				'label' => esc_html__('Border Radius', 'elementskit'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .ekit-protected-content .protected-content-submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->start_controls_tab(
			'ekit_pc_form_tab_button_hover',
			[
				'label' => esc_html__('Hover', 'elementskit'),
			]
		);

		$this->add_control(
			'ekit_pc_button_color_hover',
			[
				'label' => esc_html__('Color', 'elementskit'),
				'type' => Controls_Manager::COLOR,
				'default' => '#CC3366',
				'selectors' => [
					'{{WRAPPER}} .ekit-protected-content .protected-content-submit:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'ekit_pc_button_background_hover',
			[
				'label' => esc_html__('Background', 'elementskit'),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffff',
				'selectors' => [
					'{{WRAPPER}} .ekit-protected-content .protected-content-submit:hover' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'ekit_pc_button_border_hover',
				'label' => esc_html__('Border', 'elementskit'),
				'selector' => '{{WRAPPER}} .ekit-protected-content .protected-content-submit:hover',
			]
		);

		$this->add_responsive_control(
			'ekit_pc_button_border_radius_hover',
			[
				'label' => esc_html__('Border Radius', 'elementskit'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .ekit-protected-content .protected-content-submit:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control(
			'ekit_pc_button_padding',
			[
				'label' => esc_html__('Padding', 'elementskit'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'separator' => "before",
				'selectors' => [
					'{{WRAPPER}} .ekit-protected-content .protected-content-submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],				
			]
		);
		
		$this->add_responsive_control(
			'ekit_pc_button_margin',
			[
				'label' => esc_html__('Margin', 'elementskit'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'default' => [
					'top' => '5',
					'right' => '5',
					'bottom' => '5',
					'left' => '5',
					'unit' => 'px',
					//'isLinked' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .ekit-protected-content .protected-content-submit' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],				
			]
		);

		$this->end_controls_section();

		// Warning Style section 
		$this->start_controls_section(
			'ekit_pc_warning_message_style',
			[
				'label' => esc_html__('Warning Message' , 'elementskit'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'ekit_pc_warning_message_text_color',
			[
				'label' => esc_html__('Text Color', 'elementskit'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ekit-protected-content .protected-content-warning' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'ekit_pc_warning_message_typography',
				'selector' => '{{WRAPPER}} .ekit-protected-content .protected-content-warning',
			]
		);

		$this->add_responsive_control(
			'ekit_pc_warning_message_margin',
			[
				'label' => esc_html__('Margin', 'elementskit'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'custom' ],
				'default' => [
					'top' => '5',
					'right' => '5',
					'bottom' => '5',
					'left' => '5',
					'unit' => 'px',
					//'isLinked' => '',
				],
				'selectors' => [
					'{{WRAPPER}} .ekit-protected-content .protected-content-warning' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],				
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		?>
		<div class="ekit-wid-con">
			<div class="ekit-protected-content">
				<?php $this->render_raw(); ?>
			</div>
		</div>
		<?php
	}

	protected function render_raw() {
		$form_submitted = $validate = false;
		$settings = $this->get_settings_for_display();
		extract($settings);

		// check validation for password
		if($ekit_pc_protection_type === 'password' && (isset($_POST['nonce_' . $this->get_id()]) && wp_verify_nonce($_POST['nonce_' . $this->get_id()], 'protected_form_nonce'))) {
			$form_submitted = true;
			if(isset($_POST['password_' . $this->get_id()]) && ($ekit_pc_set_password === $_POST['password_' . $this->get_id()])) {
				$validate = true;
				// set cookie
				$this->set_cookie($settings);
			}
		}

		// check validation for roles
		if ($ekit_pc_protection_type === 'roles' && $this->current_user_can_see($settings)) {
			$validate = true;
		}

		// check validation for cookie
		if($ekit_pc_enable_cookie === 'yes' && isset($_COOKIE['protection_content_' . $this->get_id()])) {
			$validate = true;
		}
	
		// render content
		if (($this->is_editor() && $ekit_pc_show_content === 'yes') || $validate) {
			$this->get_protected_content($settings);
		} elseif ($ekit_pc_protection_type === 'password') {
			$this->get_protected_form($settings);
		}
	
		// show warning message
		if ($this->is_editor() && $ekit_pc_show_warning === 'yes' ||
			(!$this->is_editor() && $ekit_pc_protection_type === 'roles' && !$validate) ||
			(!$this->is_editor() && $ekit_pc_protection_type === 'password' && $form_submitted && !$validate)
		) {
			$this->get_protected_warning($settings);
		}
	}

	public function set_cookie($settings) {
		extract($settings);
	
		if ($ekit_pc_enable_cookie === 'yes') {
			$expiry_time = 0;
			if ($ekit_pc_cookie === 'time') {
				$expiry_time = (int)$ekit_pc_cookie_time * 60 * 1000; // Convert minutes to milliseconds
			} elseif ($ekit_pc_cookie === 'days') {
				$expiry_time = (int)$ekit_pc_cookie_days * 24 * 60 * 60 * 1000; // Convert days to milliseconds
			}
	
			echo "<script>
				var pcDate = new Date();
				pcDate.setTime(pcDate.getTime() + parseInt($expiry_time));
				var expires = 'expires=' + pcDate.toUTCString();
				document.cookie = 'protection_content_{$this->get_id()}=true;' + expires + ';';
			</script>";
		}
	}

	protected function get_protected_form($settings) {
		extract($settings); ?>
		<div class="protected-content-message">
		<?php if($ekit_pc_message_source === 'text') :
				echo wp_kses($ekit_pc_message_text, \ElementsKit_Lite\Utils::get_kses_array());
			elseif($ekit_pc_message_source === 'elementor_template' && !empty($ekit_pc_message_template)) :
				echo Plugin::$instance->frontend->get_builder_content_for_display( $ekit_pc_message_template, false );
			endif; ?>
		</div>
		<div class="protected-content-form">
			<div class="protected-content-form-fields">
				<form action="<?php the_permalink(); ?>" name="<?php echo esc_attr('protected_form_' . $this->get_id()); ?>"
					method="POST">
					<input type="hidden" name="<?php echo esc_attr('nonce_' . $this->get_id()); ?>"
						value="<?php echo esc_attr(wp_create_nonce('protected_form_nonce')); ?>">
					<input type="password" name="<?php echo esc_attr('password_' . $this->get_id()); ?>" class="password"
						placeholder="<?php echo esc_attr($ekit_pc_input_text); ?>">
					<input type="submit" name="submit" value="<?php echo esc_attr($ekit_pc_submit_text); ?>"
						class="protected-content-submit">
				</form>
			</div>
		</div>
	<?php
	}

	protected function get_protected_warning($settings) {
		extract($settings); ?>
		<div class="protected-content-warning">
			<p class="protected-content-warning-text">
				<?php echo wp_kses($ekit_pc_incorrect_message, \ElementsKit_Lite\Utils::get_kses_array()); ?>
			</p>
		</div>
		<?php
			}

			protected function get_protected_content($settings) {
				extract($settings); ?>
		<div class="protected-content">
			<?php if($ekit_pc_content_type === 'custom_content') :
					echo wp_kses($ekit_pc_protected_content, \ElementsKit_Lite\Utils::get_kses_array());
				elseif($ekit_pc_content_type === 'elementor_template') :
					echo Plugin::$instance->frontend->get_builder_content_for_display( $ekit_pc_elementor_template, false );
				endif; ?>
		</div>
	<?php 
	}

	// Get list of user role for protected content widget start
	protected function get_user_roles() {
		global $wp_roles;
		$all = $wp_roles->roles;
		$all_roles = array();
		if(!empty($all)){
			foreach($all as $key => $value){
				$all_roles[$key] = $all[$key]['name'];
			}
		}
		return $all_roles;
	}

	// Check current user role exists inside of the roles array.
	protected function current_user_can_see($settings) {
		extract($settings);

		if ( !is_user_logged_in() ) {
			return;
		}

		$user_role = wp_get_current_user()->roles ;

		return !empty( array_intersect( $user_role, $ekit_pc_allowed_roles));
	}

	// Is elementor editor
	protected function is_editor() {
		return Plugin::$instance->editor->is_edit_mode();
	}
}