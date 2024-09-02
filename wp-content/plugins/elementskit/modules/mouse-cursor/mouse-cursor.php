<?php
namespace Elementor;

use Elementor\Controls_Manager;

defined('ABSPATH') || die();

class ElementsKit_Mouse_Cursor {

	public  function __construct() {
		add_action( 'elementor/element/section/section_advanced/after_section_end', [ $this, 'register_cursor_section' ] );
		add_action( 'elementor/element/column/section_advanced/after_section_end', [ $this, 'register_cursor_section' ] );
		add_action( 'elementor/element/common/_section_style/after_section_end', [ $this, 'register_cursor_section' ] );
		add_action( 'elementor/element/container/section_layout/after_section_end', [ $this, 'register_cursor_section' ] );

		// cursor render settings
		add_action( 'elementor/frontend/before_render', [ $this, 'before_render' ] );
	}

	public function register_cursor_section($element) {

		$element->start_controls_section(
			'ekit_cursor_section',
			[
				'label' => esc_html__( 'Elementskit Mouse Cursor', 'elementskit' ),
				'tab' => Controls_Manager::TAB_ADVANCED,
			]
		);

		$element->add_control(
			'ekit_cursor_show',
			[
				'label'              => esc_html__('Enable Cursors', 'elementskit'),
				'type'               => Controls_Manager::SWITCHER,
				'return_value'       => 'yes',
				'prefix_class'       => 'ekit-cursor-enabled-',
				'render_type'        => 'template',
				'frontend_available' => true
			]
		);

		$element->start_controls_tabs(
			'ekit_cursor_tabs',
			[
				'condition' => [
					'ekit_cursor_show' => 'yes',
				],
			]
		);

		$element->start_controls_tab(
			'ekit_cursor_content_tab',
			[
				'label' => esc_html__( 'Content', 'elementskit' ),
			]
		);

		$element->add_control(
			'ekit_cursor_type',
			[
				'label'       => esc_html__('Cursor Type', 'elementskit'),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'text',
				'render_type' => 'template',
				'options'     => [
					'text'    => esc_html__('Text', 'elementskit'),
					'image'   => esc_html__('Image', 'elementskit'),
					'icon'   => esc_html__('Icon', 'elementskit'),
				],
				'frontend_available' => true
			]
		);

		$element->add_control(
			'ekit_cursor_image_src',
			[
				'label'              => esc_html__('Image', 'elementskit'),
				'type'               => Controls_Manager::MEDIA,
				'frontend_available' => true,
				'render_type'        => 'template',
				'default'            => [
					'url' => Utils::get_placeholder_image_src(),
				],
				'condition'          => [
					'ekit_cursor_type' => 'image'
				],
				'frontend_available' => true
			]
		);

		$element->add_control(
			'ekit_cursor_icons',
			[
				'label'              => esc_html__('Icons', 'elementskit'),
				'type'               => Controls_Manager::ICONS,
				'frontend_available' => true,
				'render_type'        => 'template',
				'condition'          => [
					'ekit_cursor_type' => 'icon'
				],
				'default'            => [
					'value'   => 'fas fa-laugh-wink',
					'library' => 'fa-solid',
				],
				'frontend_available' => true
			]
		);

		$element->add_control(
			'ekit_cursor_text_label',
			[
				'label'     => esc_html__('Text Label', 'elementskit'),
				'type'      => Controls_Manager::TEXT,
				'default'   => esc_html__('Elementskit Cursor', 'elementskit'),
				'label_block' => 'false',
				'selectors' => [
					'{{WRAPPER}} .ekit-cursor-enabled-yes' => '--cursor-text-label:"{{VALUE}}"'
				],
				'frontend_available' => true,
				'render_type'        => 'template',
				'condition'          => [
					'ekit_cursor_type' => 'text'
				],
				'frontend_available' => true
			]
		);

		$element->end_controls_tab();
		$element->start_controls_tab(
			'ekit_cursor_style_tab',
			[
				'label' => esc_html__( 'Style', 'elementskit' ),
			]
		);

		$element->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'ekit_cursor_text_typography',
				'label'     => esc_html__('Typography', 'elementskit'),
				'selector'  => '{{WRAPPER}} .ekit-cursor .ekit-cursor-text',
				'condition' => [
					'ekit_cursor_type' => 'text'
				]
			]
		);

		$element->add_control(
			'ekit_cursor_text_color',
			[
				'label'     => esc_html__('Color', 'elementskit'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ekit-cursor .ekit-cursor-text' => 'color: {{VALUE}}',
				],
				'condition' => [
					'ekit_cursor_type' => 'text'
				]
			]
		);

		$element->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'ekit_cursor_text_background',
				'label'     => esc_html__('Background', 'elementskit'),
				'types'     => ['classic', 'gradient'],
				'exclude'  => ['image'],
				'selector'  => '{{WRAPPER}} .ekit-cursor .ekit-cursor-text',
				'condition' => [
					'ekit_cursor_type' => 'text'
				]
			]
		);

		$element->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'ekit_cursor_text_border',
				'label'     => esc_html__('Border', 'elementskit'),
				'selector'  => '{{WRAPPER}} .ekit-cursor .ekit-cursor-text',
				'condition' => [
					'ekit_cursor_type' => 'text'
				]
			]
		);

		$element->add_responsive_control(
			'ekit_cursor_text_radius',
			[
				'label'      => esc_html__('Border Radius', 'elementskit'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .ekit-cursor .ekit-cursor-text' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'ekit_cursor_type' => 'text'
				]
			]
		);

		$element->add_responsive_control(
			'ekit_cursor_text_padding',
			[
				'label'      => esc_html__('Padding', 'elementskit'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .ekit-cursor .ekit-cursor-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'ekit_cursor_type' => 'text'
				]
			]
		);

		$element->add_responsive_control(
			'ekit_cursor_image_size',
			[
				'label'     => esc_html__('Size', 'elementskit'),
				'type'      => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 60,
				],
				'selectors' => [
					'{{WRAPPER}} .ekit-cursor .ekit-cursor-image' => 'width:{{SIZE}}{{UNIT}}; height:{{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'ekit_cursor_type' => 'image'
				]
			]
		);

		$element->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'ekit_cursor_image_border',
				'label'     => esc_html__('Border', 'elementskit'),
				'selector'  => '{{WRAPPER}} .ekit-cursor .ekit-cursor-image',
				'condition' => [
					'ekit_cursor_type' => 'image'
				]
			]
		);

		$element->add_responsive_control(
			'ekit_cursor_image_radius',
			[
				'label'      => esc_html__('Border Radius', 'elementskit'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .ekit-cursor .ekit-cursor-image' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'ekit_cursor_type' => 'image'
				]
			]
		);

		$element->add_responsive_control(
			'ekit_cursor_icons_size',
			[
				'label'     => esc_html__('Size', 'elementskit'),
				'type'      => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 200,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 60,
				],
				'selectors' => [
					'{{WRAPPER}} .ekit-cursor .ekit-cursor-icon' => 'font-size:{{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .ekit-cursor .ekit-cursor-svg' => 'width:{{SIZE}}{{UNIT}}; height:{{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'ekit_cursor_type' => 'icon'
				]
			]
		);

		$element->add_control(
			'ekit_cursor_icon_color',
			[
				'label'     => esc_html__('Color', 'elementskit'),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ekit-cursor .ekit-cursor-icon, {{WRAPPER}} .ekit-cursor .ekit-cursor-svg' => 'color: {{VALUE}}; fill: {{VALUE}}',
				],
				'condition' => [
					'ekit_cursor_type' => 'icon'
				]
			]
		);

		$element->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => 'ekit_cursor_icon_background',
				'label'     => esc_html__('Background', 'elementskit'),
				'types'     => ['classic', 'gradient'],
				'exclude'  => ['image'],
				'selector'  => '{{WRAPPER}} .ekit-cursor .ekit-cursor-icon, {{WRAPPER}} .ekit-cursor .ekit-cursor-svg',
				'condition' => [
					'ekit_cursor_type' => 'icon'
				]
			]
		);

		$element->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'      => 'ekit_cursor_icon_border',
				'label'     => esc_html__('Border', 'elementskit'),
				'selector'  => '{{WRAPPER}} .ekit-cursor .ekit-cursor-icon, {{WRAPPER}} .ekit-cursor .ekit-cursor-svg',
				'condition' => [
					'ekit_cursor_type' => 'icon'
				]
			]
		);

		$element->add_responsive_control(
			'ekit_cursor_icon_radius',
			[
				'label'      => esc_html__('Border Radius', 'elementskit'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .ekit-cursor .ekit-cursor-icon' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .ekit-cursor .ekit-cursor-svg' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'ekit_cursor_type' => 'icon'
				]
			]
		);

		$element->add_responsive_control(
			'ekit_cursor_icon_padding',
			[
				'label'      => esc_html__('Padding', 'elementskit'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .ekit-cursor .ekit-cursor-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .ekit-cursor .ekit-cursor-svg' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'ekit_cursor_type' => 'icon'
				]
			]
		);

		$element->add_responsive_control(
			'ekit_cursor_margin',
			[
				'label'      => esc_html__('Margin', 'elementskit'),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em'],
				'selectors'  => [
					'{{WRAPPER}} .ekit-cursor .ekit-cursor-settings' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$element->end_controls_tab();
		$element->end_controls_tabs();

		$element->add_control(
			'ekit_cursor_disable_default_cursor',
			[
				'label'        => __('Disable Default Cursor', 'elementskit'),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'separator'    => 'before',
				'condition' => [
					'ekit_cursor_show' => 'yes',
				],
				'selectors' => [
					'{{WRAPPER}}.ekit-cursor-enabled-yes' => 'cursor: none'
				]
			]
		);

		$element->add_control(
			'ekit_cursor_icon_align',
			[
				'label' => esc_html__( 'Alignment', 'elementskit' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'elementskit' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'elementskit' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'elementskit' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'toggle' => true,
				'condition' => [
					'ekit_cursor_show' => 'yes',
					'ekit_cursor_type' => 'text'
				],
				'selectors' => [
					'{{WRAPPER}} .ekit-cursor .ekit-cursor-text' => 'text-align: {{VALUE}};',
				],
			]
		);

		$element->end_controls_section();
	}

	public function before_render($element) {
		$settings = $element->get_settings_for_display();
	}
}
