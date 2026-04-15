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
                    'layout-1' => esc_html__( 'Style 1', 'freska' ),
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
		$enable_process_stock = isset($settings['enable_process_stock']) ? $settings['enable_process_stock'] : '';
		$layout = !empty($settings['layout']) ? $settings['layout'] : 'default';

		?>
		<div class="bt-elwg-product-loop-item bt-elwg-product-loop-item--<?php echo esc_attr($layout); ?> <?php echo esc_attr($settings['content_text_align']); ?>">
			<div class="bt-product-item-wrap">
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
					} else {
						global $product;
					
						if (empty($product) || ! $product->is_visible()) {
							get_template_part('woocommerce/content', 'product-placeholder');
						} else {
							$has_product = true;
							wc_get_template('content-product.php', array('enable_process_stock' => $enable_process_stock));
						}
					}

					
					if ($layout === 'layout-1') {
						global $product;
						echo '<div class="bt-add-to-cart-layout-1">';
						$cart_icon_svg = '<svg xmlns="http://www.w3.org/2000/svg" width="19" height="17" viewBox="0 0 19 17" fill="none"><path d="M7.5 15C7.5 15.2472 7.42669 15.4889 7.28934 15.6945C7.15199 15.9 6.95676 16.0602 6.72835 16.1549C6.49995 16.2495 6.24861 16.2742 6.00614 16.226C5.76366 16.1777 5.54093 16.0587 5.36612 15.8839C5.1913 15.7091 5.07225 15.4863 5.02402 15.2439C4.97579 15.0014 5.00054 14.7501 5.09515 14.5216C5.18976 14.2932 5.34998 14.098 5.55554 13.9607C5.7611 13.8233 6.00277 13.75 6.25 13.75C6.58152 13.75 6.89946 13.8817 7.13388 14.1161C7.3683 14.3505 7.5 14.6685 7.5 15ZM14.375 13.75C14.1278 13.75 13.8861 13.8233 13.6805 13.9607C13.475 14.098 13.3148 14.2932 13.2202 14.5216C13.1255 14.7501 13.1008 15.0014 13.149 15.2439C13.1973 15.4863 13.3163 15.7091 13.4911 15.8839C13.6659 16.0587 13.8887 16.1777 14.1311 16.226C14.3736 16.2742 14.6249 16.2495 14.8534 16.1549C15.0818 16.0602 15.277 15.9 15.4143 15.6945C15.5517 15.4889 15.625 15.2472 15.625 15C15.625 14.6685 15.4933 14.3505 15.2589 14.1161C15.0245 13.8817 14.7065 13.75 14.375 13.75ZM18.1023 3.91719L16.0992 11.1266C15.9891 11.5204 15.7535 11.8675 15.4283 12.1154C15.103 12.3633 14.7058 12.4983 14.2969 12.5H6.575C6.16487 12.4998 5.76605 12.3655 5.43937 12.1175C5.1127 11.8696 4.87608 11.5215 4.76563 11.1266L2.025 1.25H0.625C0.45924 1.25 0.300269 1.18415 0.183058 1.06694C0.065848 0.949731 0 0.79076 0 0.625C0 0.45924 0.065848 0.300268 0.183058 0.183058C0.300269 0.065848 0.45924 1.15441e-08 0.625 1.15441e-08H2.5C2.63664 -2.62421e-05 2.76953 0.0447278 2.87831 0.127411C2.9871 0.210094 3.06579 0.32615 3.10234 0.457813L3.84297 3.125H17.5C17.5964 3.12498 17.6914 3.14724 17.7777 3.19004C17.8641 3.23284 17.9393 3.29501 17.9976 3.37171C18.056 3.44841 18.0958 3.53755 18.1139 3.63218C18.1321 3.7268 18.1281 3.82435 18.1023 3.91719ZM16.6773 4.375H4.19063L5.97266 10.7922C6.00921 10.9239 6.0879 11.0399 6.19669 11.1226C6.30547 11.2053 6.43836 11.25 6.575 11.25H14.2969C14.4335 11.25 14.5664 11.2053 14.6752 11.1226C14.784 11.0399 14.8627 10.9239 14.8992 10.7922L16.6773 4.375Z" fill="currentColor"/></svg>';
						if ($product && $has_product) {
							echo sprintf( '<a href="%s" data-quantity="1" class="%s" data-product_id="%s" data-product_sku="%s" aria-label="%s" rel="nofollow">%s</a>',
								esc_url( $product->add_to_cart_url() ),
								esc_attr( implode( ' ', array_filter( [
									'button',
									'product_type_' . $product->get_type(),
									$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
									$product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? 'ajax_add_to_cart' : '',
								] ) ) ),
								esc_attr( $product->get_id() ),
								esc_attr( $product->get_sku() ),
								esc_attr( $product->add_to_cart_description() ),
								$cart_icon_svg
							);
						} else {
							echo '<a href="#" class="button add_to_cart_button">' . $cart_icon_svg . '</a>';
						}
						echo '</div>';
					}

					if($settings['enable_manual_prd'] === 'yes') {
						wp_reset_postdata();
					}
				?>
			</div>
		</div>
		<?php
	}

	protected function content_template() {}
}
