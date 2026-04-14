<?php
namespace FreskaElementorWidgets\Widgets\Discount;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;

class Widget_Discount extends Widget_Base {

	public function get_name() {
		return 'bt-discount';
	}

	public function get_title() {
		return esc_html__( 'Discount', 'freska' );
	}

	public function get_icon() {
		return 'bt-bears-icon eicon-code-highlight';
	}

	public function get_categories() {
		return [ 'freska' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Content', 'freska' ),
			]
		);

		$this->add_control(
			'discount_code',
			[
				'label' => esc_html__( 'Discount Code', 'freska' ),
				'type' => Controls_Manager::TEXT,
				'default' => 'Hellonewcustomer',
				'placeholder' => esc_html__( 'Enter your code', 'freska' ),
				'label_block' => true,
			]
		);

		$this->add_control(
			'icon',
			[
				'label' => esc_html__( 'Copy Icon', 'freska' ),
				'type' => Controls_Manager::ICONS,
				'default' => [
					'value' => 'far fa-copy',
					'library' => 'regular',
				],
			]
		);

		$this->add_control(
			'tooltip_text',
			[
				'label' => esc_html__( 'Tooltip Text', 'freska' ),
				'type' => Controls_Manager::TEXT,
				'default' => esc_html__( 'Copied!', 'freska' ),
			]
		);

		$this->end_controls_section();

		/**
		 * Style Tab - Container
		 */
		$this->start_controls_section(
			'section_style_container',
			[
				'label' => esc_html__( 'Container', 'freska' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_responsive_control(
			'align',
			[
				'label' => esc_html__( 'Alignment', 'freska' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'freska' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'freska' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'freska' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'padding',
			[
				'label' => esc_html__( 'Padding', 'freska' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'default' => [
					'top' => '5',
					'right' => '12',
					'bottom' => '5',
					'left' => '12',
					'unit' => 'px',
					'isLinked' => false,
				],
				'selectors' => [
					'{{WRAPPER}} .bt-elwg-discount' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'border',
				'label' => esc_html__( 'Border', 'freska' ),
				'selector' => '{{WRAPPER}} .bt-elwg-discount',
				'fields_options' => [
					'border' => [
						'default' => 'dashed',
					],
					'width' => [
						'default' => [
							'top' => '1',
							'right' => '1',
							'bottom' => '1',
							'left' => '1',
							'isLinked' => true,
						],
					],
					'color' => [
						'default' => '#000000',
					],
				],
			]
		);

		$this->add_control(
			'border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'freska' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'default' => [
					'top' => '100',
					'right' => '100',
					'bottom' => '100',
					'left' => '100',
					'unit' => 'px',
					'isLinked' => true,
				],
				'selectors' => [
					'{{WRAPPER}} .bt-elwg-discount' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_container_style' );

		$this->start_controls_tab(
			'tab_container_normal',
			[
				'label' => esc_html__( 'Normal', 'freska' ),
			]
		);

		$this->add_control(
			'bg_color',
			[
				'label' => esc_html__( 'Background Color', 'freska' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bt-elwg-discount' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_container_hover',
			[
				'label' => esc_html__( 'Hover', 'freska' ),
			]
		);

		$this->add_control(
			'bg_color_hover',
			[
				'label' => esc_html__( 'Background Color', 'freska' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bt-elwg-discount:hover' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'freska' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'freska' ),
				'label_off' => esc_html__( 'Off', 'freska' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'prefix_class' => 'bt-discount-animation-',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_content',
			[
				'label' => esc_html__( 'Content', 'freska' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'code_typography',
				'label' => esc_html__( 'Typography', 'freska' ),
				'selector' => '{{WRAPPER}} .bt-discount-text',
				'fields_options' => [
					'font_family' => [
						'default' => 'Nunito Sans',
					],
					'font_size' => [
						'default' => [
							'size' => 14,
							'unit' => 'px',
						],
					],
					'font_weight' => [
						'default' => '700',
					],
					'line_height' => [
						'default' => [
							'size' => 22,
							'unit' => 'px',
						],
					],
                    'text_transform' => [
                        'default' => 'capitalize',
                    ],
				],
			]
		);

		$this->add_control(
			'code_color',
			[
				'label' => esc_html__( 'Text Color', 'freska' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#1A1A1A',
				'selectors' => [
					'{{WRAPPER}} .bt-discount-text' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'freska' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#1A1A1A',
				'selectors' => [
					'{{WRAPPER}} .bt-copy-icon svg path' => 'fill: {{VALUE}};'
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'freska' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 50,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bt-copy-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .bt-copy-icon svg' => 'width: {{SIZE}}{{UNIT}}; height: auto;',
				],
			]
		);

		$this->add_responsive_control(
			'gap',
			[
				'label' => esc_html__( 'Gap', 'freska' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 4,
				],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 30,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bt-elwg-discount' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

        /**
		 * Style Tab - Tooltip
		 */
		$this->start_controls_section(
			'section_style_tooltip',
			[
				'label' => esc_html__( 'Tooltip', 'freska' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_control(
			'tooltip_bg',
			[
				'label' => esc_html__( 'Tooltip Background', 'freska' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#000000',
				'selectors' => [
					'{{WRAPPER}} .bt-copy-tooltip' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .bt-copy-tooltip:after' => 'border-top-color: {{VALUE}};',
				],
			]
		);

        $this->add_control(
			'tooltip_color',
			[
				'label' => esc_html__( 'Tooltip Text Color', 'freska' ),
				'type' => Controls_Manager::COLOR,
				'default' => '#ffffff',
				'selectors' => [
					'{{WRAPPER}} .bt-copy-tooltip' => 'color: {{VALUE}};',
				],
			]
		);

        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'tooltip_typography',
				'label' => esc_html__( 'Tooltip Typography', 'freska' ),
				'selector' => '{{WRAPPER}} .bt-copy-tooltip',
                'fields_options' => [
					'font_size' => [
						'default' => [
							'size' => 12,
							'unit' => 'px',
						],
					],
                ],
			]
		);

        $this->end_controls_section();

	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'wrapper', 'class', 'bt-elwg-discount' );
        $this->add_render_attribute( 'wrapper', 'data-code', $settings['discount_code'] );
        $this->add_render_attribute( 'wrapper', 'title', esc_html__( 'Click to copy', 'freska' ) );

		?>
		<div <?php echo $this->get_render_attribute_string( 'wrapper' ); ?>>
			<span class="bt-discount-text"><?php echo esc_html( $settings['discount_code'] ); ?></span>
			<?php if ( ! empty( $settings['icon']['value'] ) ) : ?>
				<span class="bt-copy-icon">
					<?php \Elementor\Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] ); ?>
				</span>
			<?php endif; ?>
            <span class="bt-copy-tooltip"><?php echo esc_html( $settings['tooltip_text'] ); ?></span>
		</div>
		<?php
	}

	protected function content_template() {}
}
