<?php

namespace FreskaElementorWidgets\Widgets\ProductLoopItem;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

class Widget_ProductLoopItem extends Widget_Base
{

	public function get_name()
	{
		return 'bt-product-loop-item';
	}

	public function get_title()
	{
		return __('Product Loop Item', 'freska');
	}

	public function get_icon()
	{
		return 'bt-bears-icon eicon-post';
	}

	public function get_categories()
	{
		return ['freska'];
	}

	private function get_supported_products() {
		$products = wc_get_products([
			'limit' => -1,
			'status' => 'publish',
		]);

		$options = [];
		foreach ( $products as $product ) {
			$options[ $product->get_id() ] = $product->get_name();
		}

		return $options;
	}

	protected function register_layout_section_controls()
	{
		$this->start_controls_section(
			'section_layout',
			[
				'label' => __('Layout', 'freska'),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
            'enable_manual_prd',
            [
                'label'        => esc_html__( 'Manual Product', 'freska' ),
                'type'         => Controls_Manager::SWITCHER,
                'label_on'     => esc_html__( 'Enable', 'freska' ),
                'label_off'    => esc_html__( 'Auto', 'freska' ),
                'return_value' => 'yes',
                'default'      => '',
            ]
        );

        $this->add_control(
            'layout',
            [
                'label'   => esc_html__( 'Layout Style', 'freska' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => esc_html__( 'Default', 'freska' ),
                    'layout-1' => esc_html__( 'Style 1', 'freska' )
                ],
            ]
        );

		$options = $this->get_supported_products();
		$this->add_control(
			'product_id',
			[
				'label'       => esc_html__( 'Select Products', 'freska' ),
				'type'        => Controls_Manager::SELECT2,
				'options'     => $options, // id => title
				'multiple'    => false,
				'label_block' => true,
				'condition'   => [
                    'enable_manual_prd' => 'yes',
                ],
				'default'     => ! empty( $options ) ? array_key_first( $options ) : '',
			]
		);

		$this->add_responsive_control(
			'image_ratio',
			[
				'label' => __('Image Ratio', 'freska'),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0.3,
						'max' => 2,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .woocommerce-product-gallery__image' => 'padding-bottom: calc( {{SIZE}} * 100% ) !important;',
				],
			]
		);

		$this->add_responsive_control(
			'content_text_align',
			[
				'label' => esc_html__('Alignment', 'freska'),
				'type'  => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__('Left', 'freska'),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__('Center', 'freska'),
						'icon'  => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__('Right', 'freska'),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default' => 'left',
				'toggle' => true,
			]
		);

		$this->add_responsive_control(
			'item_gap',
			[
				'label' => esc_html__( 'Items Gap', 'freska' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bt-elwg-product-loop-item--layout-1 .woocommerce-loop-product' => 'gap: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'layout' => 'layout-1',
				],
			]
		);

		$this->add_responsive_control(
			'cta_right_offset',
			[
				'label' => esc_html__( 'Icon Right Offset', 'freska' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range' => [
					'px' => [
						'min' => -50,
						'max' => 100,
						'step' => 1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bt-elwg-product-loop-item--layout-1 .bt-add-to-cart' => 'right: {{SIZE}}{{UNIT}} !important;',
				],
				'condition' => [
					'layout' => 'layout-1',
				],
			]
		);

		$this->add_responsive_control(
			'cta_top_offset',
			[
				'label' => esc_html__( 'Icon Top Offset', 'freska' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bt-elwg-product-loop-item--layout-1 .bt-add-to-cart' => 'top: {{SIZE}}{{UNIT}} !important;',
				],
				'condition' => [
					'layout' => 'layout-1',
				],
			]
		);

		$this->add_control(
			'enable_process_stock',
			[
				'label'        => esc_html__('Process Stock', 'freska'),
				'type'         => Controls_Manager::SWITCHER,
				'label_on'     => esc_html__('Enable', 'freska'),
				'label_off'    => esc_html__('Disable', 'freska'),
				'return_value' => 'yes',
				'default'      => '',
				'separator'    => 'before',
				'description'  => esc_html__('Note: Enable "Manage stock?" in product settings to display stock bar', 'freska'),
				'condition'   => [
					'layout' => ['default'],
				],
			]
		);

		$this->end_controls_section();
	}

	protected function register_style_section_controls() 
	{
		// Title Style
		$this->start_controls_section(
			'section_title_style',
			[
				'label' => __('Title', 'freska'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __('Color', 'freska'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-loop-product__infor .woocommerce-loop-product__title' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'title_color_hover',
			[
				'label' => __('Hover Color', 'freska'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-loop-product__infor .woocommerce-loop-product__title:hover' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .woocommerce-loop-product__infor .woocommerce-loop-product__title',
			]
		);

		$this->end_controls_section();
		
		// Price Style
		$this->start_controls_section(
			'section_price_style',
			[
				'label' => __('Price', 'freska'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'price_color',
			[
				'label' => __('Color', 'freska'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bt-elwg-product-loop-item .woocommerce-loop-product__infor .price' => 'color: {{VALUE}};',
					'{{WRAPPER}} .bt-elwg-product-loop-item .woocommerce-loop-product__infor .price ins' => 'color: {{VALUE}};',
					'{{WRAPPER}} .bt-elwg-product-loop-item .woocommerce-loop-product__infor .price .woocommerce-Price-amount' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'price_typography',
				'selector' => '{{WRAPPER}} .bt-elwg-product-loop-item .woocommerce-loop-product__infor .price .woocommerce-Price-amount',
			]
		);
		$this->add_control(
			'price_sale_color',
			[
				'label' => __('Sale Price Color', 'freska'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bt-elwg-product-loop-item .woocommerce-loop-product__infor .price del' => 'color: {{VALUE}};',
					'{{WRAPPER}} .bt-elwg-product-loop-item .woocommerce-loop-product__infor .price del .woocommerce-Price-amount' => 'color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);
		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'price_sale_typography',
				'label' => __('Sale Price Typography', 'freska'),
				'selector' => '{{WRAPPER}} .bt-elwg-product-loop-item .woocommerce-loop-product__infor .price del .woocommerce-Price-amount',
			]
		);

		$this->end_controls_section();
	}
	protected function register_controls()
	{
		$this->register_layout_section_controls();
		$this->register_style_section_controls();
	}

	protected function render()
	{
		if (!class_exists('WooCommerce')) {
			return;
		}
		
		$settings = $this->get_settings_for_display();
		$layout = !empty($settings['layout']) ? $settings['layout'] : 'default';
		$enable_process_stock = ($layout === 'default') ? (isset($settings['enable_process_stock']) ? $settings['enable_process_stock'] : '') : '';

		?>
		<div class="bt-elwg-product-loop-item bt-elwg-product-loop-item--<?php echo esc_attr($layout); ?> <?php echo esc_attr($settings['content_text_align']); ?>">
			<?php
				$has_product = false; 
				if($settings['enable_manual_prd'] === 'yes' && !empty($settings['product_id'])) {
					$product_id = (int) $settings['product_id'];
					$query = new \WP_Query([
						'post_type'      => 'product',
						'p'              => $product_id,
						'posts_per_page' => 1,
					]);

					if ($query->have_posts())  {
						while ($query->have_posts()) { 
							$query->the_post();
							global $product;
							$product = wc_get_product(get_the_ID()); 

							if (empty($product) || ! $product->is_visible()) {
								get_template_part('woocommerce/content', 'product-placeholder');
							} else {
								$has_product = true;
								wc_get_template('content-product.php', array('enable_process_stock' => $enable_process_stock));
							}
						}
					} else {
						get_template_part('woocommerce/content', 'product-placeholder');
					}
					
					wp_reset_postdata();

				} else {
					global $product;
					if (empty($product) || ! $product->is_visible()) {
						get_template_part('woocommerce/content', 'product-placeholder');
					} else {
						$has_product = true;
						wc_get_template('content-product.php', array('enable_process_stock' => $enable_process_stock));
					}
				}
			?>
		</div>
		<?php
	}

	protected function content_template() {}
}
