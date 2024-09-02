<?php
namespace Elementor;

use Elementor\Controls_Manager;

defined('ABSPATH') || die();

class ElementsKit_Glass_Morphism {

	public  function __construct() {
		add_action( 'elementor/element/column/section_advanced/after_section_end', [ $this, 'register_glass_morphism_section' ], 1 );
		add_action( 'elementor/element/section/section_advanced/after_section_end', [ $this, 'register_glass_morphism_section' ], 1 );
		add_action( 'elementor/element/common/_section_style/after_section_end', [ $this, 'register_glass_morphism_section' ], 7 );
		// Flexbox Container support
		add_action( 'elementor/element/container/section_layout/after_section_end', array( $this, 'register_glass_morphism_section' ) );
	}

	public function register_glass_morphism_section($element) {

		$element->start_controls_section(
			'elementskit_glass_morphism_section',
			[
				'label' => esc_html__( 'Elementskit Glass Morphism', 'elementskit' ),
				'tab' => Controls_Manager::TAB_ADVANCED,
			]
		);

		$element->add_control(
			'ekit_glass_morphism',
			[
				'label'           => esc_html__( 'Glass Morphism', 'elementskit' ),
				'type'            => Controls_Manager::POPOVER_TOGGLE,
				'return_value'    => 'yes',
			]
		);

		$element->start_popover();

		$element->add_control(
			'ekit_glass_morphism_blur',
			[
				'label'     => esc_html__( 'Blur', 'elementskit' ),
				'type'      => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'render_type' => 'ui',
				'range'     => [
					'px' => [
						'min'  => 0,
						'max'  => 100,
						'step' => 0.1,
					]
				],
				'default'   => [
					'unit' => 'px',
					'size' => 0,
				],
				'condition' => [
					'ekit_glass_morphism' => 'yes',
				],
			]
		);

		$element->add_control(
			'ekit_glass_morphism_brightness',
			[
				'label'       => esc_html__( 'Brightness', 'elementskit' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'render_type' => 'ui',
				'range'       => [
					'px' => [
						'min'  => 0,
						'max'  => 10,
						'step' => 0.1,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 1,
				],
				'condition'   => [
					'ekit_glass_morphism' => 'yes',
				],
			]
		);

		$element->add_control(
			'ekit_glass_morphism_contrast',
			[
				'label'       => esc_html__( 'Contrast', 'elementskit' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'render_type' => 'ui',
				'range'       => [
					'px' => [
						'min'  => 0,
						'max'  => 10,
						'step' => 0.1,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 1,
				],
				'condition'   => [
					'ekit_glass_morphism' => 'yes',
				],
			]
		);

		$element->add_control(
			'ekit_glass_morphism_saturation',
			[
				'label'       => esc_html__( 'Saturation', 'elementskit' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'render_type' => 'ui',
				'range'       => [
					'px' => [
						'min'  => 0,
						'max'  => 10,
						'step' => 0.1,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 1,
				],
				'condition'   => [
					'ekit_glass_morphism' => 'yes',
				],
			]
		);

		$element->add_control(
			'ekit_glass_morphism_grayscale',
			[
				'label'       => esc_html__( 'Grayscale', 'elementskit' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'render_type' => 'ui',
				'range'       => [
					'px' => [
						'min'  => 0,
						'max'  => 10,
						'step' => 0.1,
					],
				],
				'default'     => [
					'unit' => 'px',
					'size' => 1,
				],
				'condition'   => [
					'ekit_glass_morphism' => 'yes',
				],
			]
		);

		$element->add_control(
			'ekit_glass_morphism_hue',
			[
				'label'       => esc_html__( 'Hue', 'elementskit' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'render_type' => 'ui',
				'range'       => [
					'px' => [
						'min' => 0,
						'max' => 360,
					]
				],
				'default'     => [
					'unit' => 'px',
					'size' => 0,
				],
				'selectors' => [
					'{{WRAPPER}}:not(.elementor-widget),{{WRAPPER}} > .elementor-widget-container' => 'backdrop-filter: brightness( {{ekit_glass_morphism_brightness.SIZE}} ) contrast( {{ekit_glass_morphism_contrast.SIZE}} ) saturate( {{ekit_glass_morphism_saturation.SIZE}} ) grayscale( {{ekit_glass_morphism_grayscale.SIZE}} ) blur( {{ekit_glass_morphism_blur.SIZE}}px ) hue-rotate( {{ekit_glass_morphism_hue.SIZE}}deg );
					-webkit-backdrop-filter: brightness( {{ekit_glass_morphism_brightness.SIZE}} ) contrast( {{ekit_glass_morphism_contrast.SIZE}} ) saturate( {{ekit_glass_morphism_saturation.SIZE}} ) blur( {{ekit_glass_morphism_blur.SIZE}}px ) grayscale( {{ekit_glass_morphism_grayscale.SIZE}} ) hue-rotate( {{ekit_glass_morphism_hue.SIZE}}deg )',
				],
				'condition'   => [
					'ekit_glass_morphism' => 'yes',
				],
			]
		);

		$element->end_popover();

		$element->end_controls_section();
	}
}
