<?php

namespace Elementor;

class ElementsKit_Extend_Sticky {

    public function __construct() {
		add_action( 'elementor/element/section/section_advanced/after_section_end', [ $this, 'register_controls' ], 6 );
		add_action( 'elementor/element/common/_section_style/after_section_end', [ $this, 'register_controls' ], 6 );
		
		// Flexbox Container support
		add_action( 'elementor/element/container/section_layout/after_section_end', array( $this, 'register_controls' ) );

		// Migrate ekit_sticky_on option
		add_action('init', function() {
			if(get_transient('ekit_sticky_on_option_migrate') || !version_compare(\ElementsKit::version(), '3.4.1', '>')) {
				return;
			};

			set_transient('ekit_sticky_on_option_migrate', \ElementsKit::version());
			$this->sticky_on_option_migrate_config();
		});
	}

	public function register_controls( Controls_Stack $element ) {
		$element->start_controls_section(
			'section_scroll_effect',
			[
				'label' => esc_html__( 'ElementsKit Sticky', 'elementskit' ),
				'tab' => Controls_Manager::TAB_ADVANCED,
			]
		);

		$element->add_control(
			'ekit_sticky',
			[
				'label' => esc_html__( 'Sticky', 'elementskit' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => esc_html__( 'None', 'elementskit' ),
					'top' 				=> esc_html__( 'Top', 'elementskit' ),
					'bottom' 			=> esc_html__( 'Bottom', 'elementskit' ),
					'column' 			=> esc_html__( 'Column', 'elementskit' ),
					'show_on_scroll_up' => esc_html__( 'Show on Scroll Up', 'elementskit' ),
				],
				'prefix_class'	=> 'ekit-sticky--',
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'ekit_sticky_until',
			[
				'label' => esc_html__( 'Sticky Until', 'elementskit' ),
				'description' => esc_html__( 'Section id without starting hash, example "section1".', 'elementskit'),
				'type' => Controls_Manager::TEXT,
				'default' => '',
				'condition' => [
					'ekit_sticky!' => ['', 'column'],
				],
				'render_type' => 'none',
				'frontend_available' => true,
			]
		);

		$element->add_responsive_control(
			'ekit_sticky_offset',
			[
				'label' => esc_html__( 'Sticky Offset', 'elementskit' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'required' => true,
				'condition' => [
					'ekit_sticky!' => '',
				],
				'render_type' => 'template',
				'frontend_available' => true,
			]
		);

		$element->add_control(
			'ekit_sticky_color',
			[
				'label' => esc_html__( 'Sticky Background Color', 'elementskit' ),
				'type' => Controls_Manager::COLOR,
				'condition' => [
					'ekit_sticky!' => ['', 'column'],
				],
				'selectors' => [
					'{{WRAPPER}}.ekit-sticky--effects' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->sticky_on_devices_controls($element);

		$element->add_responsive_control(
			'ekit_sticky_effect_offset',
			[
				'label' => esc_html__( 'Add "ekit-sticky--effects" Class Offset', 'elementskit' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => 'px',
					'size' => 0,
				],
				'required' => true,
				'condition' => [
					'ekit_sticky!' => '',
				],
				'render_type' => 'template',
				'frontend_available' => true,
			]
		);

		$element->end_controls_section();
	}

	protected function sticky_on_devices_controls($element) {
		// The 'Hide On X' controls are displayed from largest to smallest, while the method returns smallest to largest.
		$active_devices = Plugin::$instance->breakpoints->get_active_devices_list( [ 'reverse' => true ] );
		$active_breakpoints = Plugin::$instance->breakpoints->get_active_breakpoints();

		foreach ( $active_devices as $breakpoint_key ) {
			$label = 'desktop' === $breakpoint_key ? esc_html__( 'Desktop', 'elementskit' ) : $active_breakpoints[ $breakpoint_key ]->get_label();
			$element->add_control(
				'ekit_sticky_on_' . $breakpoint_key,
				[
					/* translators: %s: Device name. */
					'label' => sprintf( __( 'Sticky On %s', 'elementskit' ), $label ),
					'type' => Controls_Manager::SWITCHER,
					'default' => $breakpoint_key,
					'label_on' => esc_html__( 'On', 'elementskit' ),
					'label_off' => esc_html__( 'Off', 'elementskit' ),
					'return_value' => $breakpoint_key,
					'condition' => [
						'ekit_sticky!' => '',
					],
					'frontend_available' => true,
				]
			);
		}
	}

	/**
	 * Migrate ekit_sticky_on option
	 *
	 * Migrate the ekit_sticky_on select option to switch control
	 *
	 * @todo will be removed
	 * @since 3.3.0
	 */
	public function sticky_on_option_migrate_config() {
		global $wpdb;

		$post_ids = $wpdb->get_col(
			'SELECT `post_id` FROM `' . $wpdb->postmeta . '` WHERE `meta_key` = "_elementor_data" AND `meta_value` LIKE \'%"ekit_sticky_on"%\';'
		);
	
		if (empty($post_ids)) {
			return;
		};
	
		foreach ($post_ids as $post_id) {
			$do_update = false;
			$document  = \Elementor\Plugin::$instance->documents->get($post_id);
	
			if ($document) {
				$data = $document->get_elements_data();
			}
	
			if (empty($data)) {
				continue;
			}
	
			$data = \Elementor\Plugin::$instance->db->iterate_data($data, function ($element) use (&$do_update) {
				if (empty($element['settings']['ekit_sticky_on'])) {
					return $element;
				}
	
				if (!empty($element['settings']['ekit_sticky_on'])) {
					$devices = explode('_', $element['settings']['ekit_sticky_on']);
					$active_devices = Plugin::$instance->breakpoints->get_active_devices_list( [ 'reverse' => true ] );
	
					foreach ($active_devices as $breakpoint_key) {
						$element['settings']['ekit_sticky_on_' . $breakpoint_key] = in_array($breakpoint_key, $devices) ? $breakpoint_key : '';
						$do_update = true;
					}
	
					$element['settings']['ekit_sticky_on_widescreen'] = 'widescreen';
				}
	
				// cleanup old unused settings.
				if (!empty($element['settings']['ekit_sticky_on'])) {
					unset($element['settings']['ekit_sticky_on']);
				}
	
				return $element;
			});
	
			// Only update if needed.
			if (!$do_update) {
				continue;
			}
	
			// We need the `wp_slash` in order to avoid the unslashing during the `update_post_meta`
			$json_value = wp_slash(wp_json_encode($data));
	
			update_metadata('post', $post_id, '_elementor_data', $json_value);
	
			// Clear WP cache for next step.
			wp_cache_flush();
		} // End foreach().
	}
}
