<?php
namespace Elementor;

use \Elementor\ElementsKit_Widget_Interactive_Links_Handler as Handler;

if (! defined( 'ABSPATH' ) ) exit;

class ElementsKit_Widget_Interactive_Links extends Widget_Base {
    use \ElementsKit_Lite\Widgets\Widget_Notice;

    public $base;

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
        return 'https://wpmet.com/doc/interactive-links/';
    }

    protected function register_controls() {
        $this->start_controls_section(
			'ekit_interactive_links_section', [
                'label' => esc_html__( 'Interactive Links', 'elementskit' ),
            ]
        );

        $this->add_control(
			'ekit_interactive_links_style',
			[
				'label' => esc_html__( 'Choose Style', 'elementskit' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'background',
				'options' => [
					'background' => esc_html__( 'Background', 'elementskit' ),
					'foreground'  => esc_html__( 'Foreground', 'elementskit' ),
				],
			]
		);

        $this->add_control(
			'ekit_interactive_links_style_effect',
			[
				'label' => esc_html__( 'Choose Effect', 'elementskit' ),
				'type' => Controls_Manager::SELECT,
				'render_type' => 'template',
				'default' => 'fade',
				'options' => [
					'fade' => esc_html__( 'Fade', 'elementskit' ),
					'slider'  => esc_html__( 'Slider', 'elementskit' ),
					'zoom-in'  => esc_html__( 'Zoom In', 'elementskit' ),
					'zoom-out'  => esc_html__( 'Zoom Out', 'elementskit' ),
					'rotate'  => esc_html__( 'Rotate', 'elementskit' ),
					'blur'  => esc_html__( 'Blur', 'elementskit' ),
				],
				'selectors' => [
					'{{WRAPPER}}' => 'overflow: hidden;',
				],
			]
		);

		$this->add_control(
            'ekit_interactive_links_icon',
            [
                'label' => esc_html__( 'Icon', 'elementskit' ),
                'type' => Controls_Manager::ICONS,
                'skin' => 'inline',
                'label_block' => false,
                'exclude_inline_options' => ['svg'],
            ]
        );

		$this->add_control(
			'ekit_interactive_links_icon_position',
			[
				'label' => esc_html__( 'Icon Position', 'elementskit' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'after',
				'options' => [
					'before' => esc_html__( 'Before', 'elementskit' ),
					'after'  => esc_html__( 'After', 'elementskit' ),
				],
				'condition' => [
					'ekit_interactive_links_icon[value]!' => ''
				]
			]
		);

		$this->add_control(
			'ekit_interactive_links_icon_hover',
			[
				'label' => esc_html__( 'Show Icon On Hover', 'elementskit' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__('Yes', 'elementskit'),
				'label_off' => esc_html__('No', 'elementskit'),
				'return_value' => '1',
				'selectors' => [
					'{{WRAPPER}} .ekit-interactive-links-list-link-title-container i' => 'opacity: 0; transition: all 0.4s ease; transform: translateX(0px);',
					'{{WRAPPER}} .ekit-interactive-links-list-link.ekit-interactive-active .ekit-interactive-links-list-link-title-container i' => 'opacity: {{VALUE}}; transform: translateX(3px);',
				],
				'condition' => [
					'ekit_interactive_links_icon[value]!' => ''
				]
			]
		);

        $repeater = new Repeater();

		$repeater->add_control(
			'title',
			[
				'label' => esc_html__('Title', 'elementskit'),
				'type' => Controls_Manager::TEXT,
				'default' => 'List Item',
				'label_block' => 'true',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'subtitle',
			[
				'label' => esc_html__('Sub Title', 'elementskit'),
				'type' => Controls_Manager::TEXT,
				'label_block' => 'true',
				'dynamic' => [
					'active' => true,
				],
			]
		);

		$repeater->add_control(
			'link',
			[
				'label' => esc_html__('Link', 'elementskit'),
				'type' => Controls_Manager::URL,
				'default' => ['url' => '#'],
				'dynamic' => [
					'active' => true,
				]
			]
		);

        $repeater->add_control(
			'image',
			[
				'label' => esc_html__('Image', 'elementskit'),
				'type' => Controls_Manager::MEDIA,
				'default' => [
					'url' => Utils::get_placeholder_image_src(),
					'id' => -1
				],
				'dynamic' => [
					'active' => true,
				]
			]
		);

		$repeater->add_control(
			'image_direction',
			[
				'label' => esc_html__( 'Direction', 'elementskit' ),
				'type' => Controls_Manager::CHOOSE,
				'description' => esc_html__( 'Direction works for foreground style', 'elementskit' ),
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Left', 'elementskit' ),
						'icon' => 'eicon-flex eicon-justify-start-h',
					],
					'flex-end' => [
						'title' => esc_html__( 'Right', 'elementskit' ),
						'icon' => 'eicon-flex eicon-justify-end-h',
					]
				],
				'selectors' => [
					'{{WRAPPER}} .ekit-wid-con .ekit-interactive-links-image{{CURRENT_ITEM}}' => 'justify-content: {{VALUE}};display: flex;',
				],
			]
		);

		$repeater->add_responsive_control(
			'image_offset',
			[
				'label' => esc_html__( 'Image Offset', 'elementskit' ),
				'type' => Controls_Manager::DIMENSIONS,
				'description' => esc_html__( 'Image Offset works for foreground style', 'elementskit' ),
				'size_units' => ['px', '%'],
				'default' => ['isLinked' => false],
				'allowed_dimensions' => ['top', 'left'],
				'selectors' => [	
					'{{WRAPPER}} .ekit-wid-con .ekit-interactive-links-image{{CURRENT_ITEM}}' => 'top: {{TOP}}{{UNIT}}; left: {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'ekit_interactive_links',
			[
				'label' => esc_html__('List Items', 'elementskit'),
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[ 'title' => esc_html__('List Item 1', 'elementskit') ],
					[ 'title' => esc_html__('List Item 2', 'elementskit') ],
					[ 'title' => esc_html__('List Item 3', 'elementskit') ],
				],
				'title_field' => '{{{ title }}}',
			]
		);

        $this->end_controls_section();

		 /** Wrapper Style Section*/
		 $this->start_controls_section(
			'ekit_interactive_links_section_wrapper_style',
			[
				'label' => esc_html__('Wrapper', 'elementskit'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'ekit_interactive_links_height',
			[
				'label' => esc_html__( 'Height (px)', 'elementskit' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					]
					],
				'selectors' => [
					'{{WRAPPER}} .ekit-wid-con .ekit-interactive-links' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();

        /** Item Style Section*/
		$this->start_controls_section(
			'ekit_interactive_links_section_item_style',
			[
				'label' => esc_html__('Item', 'elementskit'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'ekit_interactive_links_justify',
			[
				'label' => esc_html__( 'Justify Content', 'elementskit' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Top', 'elementskit' ),
						'icon' => 'eicon-flex eicon-align-start-v',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'elementskit' ),
						'icon' => 'eicon-flex eicon-align-center-v',
					],
					'flex-end' => [
						'title' => esc_html__( 'Bottom', 'elementskit' ),
						'icon' => 'eicon-flex eicon-align-end-v',
					]
				],
				'default' => 'flex-start',
				'toggle' => false,
				'selectors' => [
					'{{WRAPPER}} .ekit-wid-con .ekit-interactive-links' => 'justify-content: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'ekit_interactive_links_align',
			[
				'label' => esc_html__( 'Align Items', 'elementskit' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Left', 'elementskit' ),
						'icon' => 'eicon-flex eicon-justify-start-h',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'elementskit' ),
						'icon' => 'eicon-flex eicon-justify-center-h',
					],
					'flex-end' => [
						'title' => esc_html__( 'Right', 'elementskit' ),
						'icon' => 'eicon-flex eicon-justify-end-h',
					]
				],
				'default' => 'flex-start',
				'toggle' => false,
				'selectors' => [
					'{{WRAPPER}} .ekit-wid-con .ekit-interactive-links' => 'align-items: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'ekit_interactive_links_text_align',
			[
				'label' => esc_html__( 'Text Alignment', 'elementskit' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'start' => [
						'title' => esc_html__( 'Left', 'elementskit' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'elementskit' ),
						'icon' => 'eicon-text-align-center',
					],
					'end' => [
						'title' => esc_html__( 'Right', 'elementskit' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors'=> [
					'{{WRAPPER}} .ekit-wid-con .ekit-interactive-links-list-link' => 'align-items: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'ekit_interactive_links_space_between',
			[
				'label' => esc_html__( 'Space Between (px)', 'elementskit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'default' => [
					'unit' => 'px',
					'size' => 10,
				],
				'selectors' => [
					'{{WRAPPER}} .ekit-interactive-links-list-link:not(:last-child)' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'ekit_interactive_links_width',
			[
				'label' => esc_html__( 'Width (px)', 'elementskit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 400,
						'step' => 5,
					],
				],
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .ekit-wid-con .ekit-interactive-links-list' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'ekit_interactive_links_background',
			[
				'label' => esc_html__('Background', 'elementskit'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ekit-wid-con .ekit-interactive-links-list-link' => 'background: {{VALUE}};',
				]
			]
		);

		$this->add_control(
			'ekit_interactive_links_hover_background',
			[
				'label' => esc_html__('Hover & Active Background', 'elementskit'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ekit-wid-con .ekit-interactive-links-list-link.ekit-interactive-active' => 'background: {{VALUE}};',
				]
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'ekit_interactive_links_border',
				'label' => esc_html__( 'Border', 'elementskit' ),
				'selector' => '{{WRAPPER}} .ekit-wid-con .ekit-interactive-links-list-link'
			]
		);

		$this->add_control(
			'ekit_interactive_links_border_radius',
			[
				'label' => esc_html__('Border Radius', 'elementskit'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .ekit-wid-con .ekit-interactive-links-list-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'ekit_interactive_links_padding',
			[
				'label' => esc_html__( 'Padding', 'elementskit' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em'],
				'selectors' => [	
					'{{WRAPPER}} .ekit-wid-con .ekit-interactive-links-list-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			'ekit_interactive_links_margin',
			[
				'label' => esc_html__( 'Margin', 'elementskit' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em'],
				'selectors' => [	
					'{{WRAPPER}} .ekit-wid-con .ekit-interactive-links-list' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'ekit_interactive_links_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'elementskit' ),
				'type' => Controls_Manager::HOVER_ANIMATION,
			]
		);

        $this->end_controls_section();

        /** Image Style Section*/
		$this->start_controls_section(
			'ekit_interactive_links_section_image_style',
			[
				'label' => esc_html__('Image', 'elementskit'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'ekit_interactive_links_image_size',
				'default' => 'full',
				'condition' => [
					'ekit_interactive_links_style!' => 'background'
				]
			]
		);

		$this->add_responsive_control(
			'ekit_interactive_links_image_width',
			[
				'label' => esc_html__( 'Width', 'elementskit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
				],
				'size_units' => [ 'px', '%'],
				'selectors' => [
					'{{WRAPPER}} .ekit-wid-con .ekit-interactive-links-image img' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'ekit_interactive_links_style' => 'foreground'
				]
			]
		);

		$this->add_responsive_control(
			'ekit_interactive_links_image_height',
			[
				'label' => esc_html__( 'Height', 'elementskit' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1000,
						'step' => 5,
					],
				],
				'size_units' => [ 'px', '%'],
				'selectors' => [
					'{{WRAPPER}} .ekit-wid-con .ekit-interactive-links-image img' => 'height: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'ekit_interactive_links_style' => 'foreground'
				]
			]
		);

		$this->end_controls_section();

		/** Title Style Section*/
		$this->start_controls_section(
			'ekit_interactive_links_section_title_style',
			[
				'label' => esc_html__('Title', 'elementskit'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'ekit_interactive_links_title_color',
			[
				'label' => esc_html__('Color', 'elementskit'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ekit-interactive-links-list-link-title' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ekit-interactive-links-list-link-title-container i' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'ekit_interactive_links_title_hover_color',
			[
				'label' => esc_html__('Hover & Active Color', 'elementskit'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ekit-interactive-links-list-link.ekit-interactive-active .ekit-interactive-links-list-link-title' => 'color: {{VALUE}};',
					'{{WRAPPER}} .ekit-interactive-links-list-link.ekit-interactive-active .ekit-interactive-links-list-link-title-container i' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'ekit_interactive_links_title_typography',
				'selector' => '{{WRAPPER}} .ekit-interactive-links-list-link-title',
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'label' => esc_html__('Typography hover', 'elementskit'),
				'name' => 'ekit_interactive_links_hover_title_typography',
				'exclude' => ['font_family', 'font_weight', 'text_transform', 'font_style', 'line_height', 'letter_spacing', 'word_spacing'], 
				'selector' => '{{WRAPPER}} .ekit-interactive-links-list-link.ekit-interactive-active .ekit-interactive-links-list-link-title',
			]
		);

		$this->add_group_control(
			Group_Control_Text_Stroke::get_type(),
			[
				'name' => 'ekit_interactive_links_title_text_stroke',
				'selector' => '{{WRAPPER}} .ekit-interactive-links-list-link-title',
			]
		);

        $this->end_controls_section();

		/** Icon Style Section*/
		$this->start_controls_section(
			'ekit_interactive_links_section_icon_style',
			[
				'label' => esc_html__('Icon', 'elementskit'),
				'tab' => Controls_Manager::TAB_STYLE,
				'condition' => [
					'ekit_interactive_links_icon[value]!' => ''
				]
			]
		);

		$this->add_control(
			'ekit_interactive_links_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'elementskit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .ekit-interactive-links-list-link-title-container i' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'ekit_interactive_links_icon_space_top',
			[
				'label' => esc_html__( 'Space Between (px)', 'elementskit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'selectors' => [
					'{{WRAPPER}} .ekit-interactive-links-list-link-title-container i.interactive-icon-before' => 'margin-right: {{SIZE}}{{UNIT}}; margin-left:0{{UNIT}};',
					'{{WRAPPER}} .ekit-interactive-links-list-link-title-container i.interactive-icon-after' => 'margin-left: {{SIZE}}{{UNIT}}; margin-right:0{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();

		/** Subtitle Style Section*/
		$this->start_controls_section(
			'ekit_interactive_links_section_subtitle_style',
			[
				'label' => esc_html__('Sub Title', 'elementskit'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'ekit_interactive_links_subtitle_color',
			[
				'label' => esc_html__('Color', 'elementskit'),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .ekit-interactive-links-list-link-subtitle' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'ekit_interactive_links_subtitle_hover_color',
			[
				'label' => esc_html__('Hover & Active Color', 'elementskit'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .ekit-interactive-links-list-link.ekit-interactive-active .ekit-interactive-links-list-link-subtitle' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'ekit_interactive_links_subtitle_typography',
				'selector' => '{{WRAPPER}} .ekit-interactive-links-list-link-subtitle',
			]
		);

		$this->add_control(
			'ekit_interactive_links_subtitle_space_top',
			[
				'label' => esc_html__( 'Space Top (px)', 'elementskit' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .ekit-interactive-links-list-link-subtitle' => 'margin-top: {{SIZE}}{{UNIT}};',
				],
			]
		);

        // Include: Unlock Pro Message
        $this->insert_pro_message();
    }

    protected function render( ) {
        echo '<div class="ekit-wid-con">';
            $this->render_raw();
        echo '</div>';
    }

	protected function get_image_html($link, $settings) {
		$link['image_size_size'] = $settings['ekit_interactive_links_image_size_size'];
		$link['image_size_custom_dimension'] = $settings['ekit_interactive_links_image_size_custom_dimension'];
		$image_html = Group_Control_Image_Size::get_attachment_image_html($link, 'image_size', 'image');
		return $image_html;
	}

    protected function render_raw( ) {
        $settings = $this->get_settings_for_display();
        extract($settings);
        ?>
        <div class="ekit-interactive-links <?php echo esc_attr('interactive-'.$ekit_interactive_links_style) ?>">
            <div class="ekit-interactive-links-list">
                <?php foreach($ekit_interactive_links as $index => $link) : 
					if ( !empty($link['link']['url']) ) {
						$this->add_link_attributes( "link_$index", $link['link'] );
					}
					?>
                    <a class="ekit-interactive-links-list-link elementor-animation-<?php echo esc_attr($ekit_interactive_links_animation);?>" <?php $this->print_render_attribute_string( "link_$index" ); ?>>
						<div class="ekit-interactive-links-list-link-title-container">
							<?php !empty($ekit_interactive_links_icon['value']) && $ekit_interactive_links_icon_position == 'before' && Icons_Manager::render_icon( $settings['ekit_interactive_links_icon'], [ 'aria-hidden' => 'true', 'class' => 'interactive-icon-before'  ] ); ?>
							<span class="ekit-interactive-links-list-link-title">
								<?php echo esc_html($link['title']); ?>
							</span>
							<?php !empty($ekit_interactive_links_icon['value']) && $ekit_interactive_links_icon_position == 'after' && Icons_Manager::render_icon( $settings['ekit_interactive_links_icon'], [ 'aria-hidden' => 'true', 'class' => 'interactive-icon-after'  ] ); ?>
						</div>
						<?php if(!empty($link['subtitle'])) : ?>
							<span class="ekit-interactive-links-list-link-subtitle">
								<?php echo esc_html($link['subtitle']) ?>
							</span>
						<?php endif; ?>
                    </a>
                <?php endforeach; ?>
            </div>
            <div class="ekit-interactive-links-image-container">
				<?php if($settings['ekit_interactive_links_style'] == 'background') :
                	foreach($ekit_interactive_links as $index => $link) : 
						if(!empty($link['image']['url'])) : ?>
							<div class="ekit-interactive-links-image interactive-backgound ekit-interactive-links-image--<?php echo esc_html($ekit_interactive_links_style_effect); ?>">     
								<img src="<?php echo esc_url($link['image']['url']); ?>" alt="bg-image">
							</div>
							<!-- this markup is used for animation purposes -->
						<?php endif; 
					endforeach;
				
				elseif($settings['ekit_interactive_links_style'] == 'foreground') :
                	foreach($ekit_interactive_links as $index => $link) : 
						if(!empty($link['image']['url'])) : 
							$image_html = $this->get_image_html($link, $settings);
							?>
							<div class="ekit-interactive-links-image elementor-repeater-item-<?php echo esc_attr( $link['_id'] ); ?> ekit-interactive-links-image--<?php echo esc_html($ekit_interactive_links_style_effect); ?>">
								<?php echo wp_kses($image_html, \ElementsKit_Lite\Utils::get_kses_array()); ?>
							</div>
						<?php endif;
					endforeach;
				endif; ?>
            </div>
        </div>
        <?php
    }
}
