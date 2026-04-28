<?php

namespace FreskaElementorWidgets\Widgets\ProductMinimumItem;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

class Widget_ProductMinimumItem extends Widget_Base
{

    public function get_name()
    {
        return 'bt-product-minimum-item';
    }

    public function get_title()
    {
        return __('Product Minimum Item', 'freska');
    }

    public function get_icon()
    {
        return 'bt-bears-icon eicon-product-price';
    }

    public function get_categories()
    {
        return ['freska'];
    }

    private function get_supported_products()
    {
        $products = wc_get_products([
            'limit'  => -1,
            'status' => 'publish',
        ]);

        $options = [];
        foreach ($products as $product) {
            $options[$product->get_id()] = $product->get_name();
        }

        return $options;
    }

    protected function register_controls()
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Product', 'freska'),
                'tab'   => Controls_Manager::TAB_CONTENT,
            ]
        );

        $options = $this->get_supported_products();
        $this->add_control(
            'product_id',
            [
                'label'       => esc_html__('Select Product', 'freska'),
                'type'        => Controls_Manager::SELECT2,
                'options'     => $options,
                'multiple'    => false,
                'label_block' => true,
                'default'     => !empty($options) ? array_key_first($options) : '',
            ]
        );

        $this->add_control(
            'image_size',
            [
                'label'   => __('Image Size', 'freska'),
                'type'    => Controls_Manager::SELECT,
                'default' => 'thumbnail',
                'options' => [
                    'thumbnail'    => __('Thumbnail', 'freska'),
                    'medium'       => __('Medium', 'freska'),
                    'medium_large' => __('Medium Large', 'freska'),
                    'large'        => __('Large', 'freska'),
                    'full'         => __('Full', 'freska'),
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_layout',
            [
                'label' => __('Layout', 'freska'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'card_padding',
            [
                'label'      => __('Card Padding', 'freska'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em', '%'],
                'default'    => [
                    'top'    => '12',
                    'right'  => '12',
                    'bottom' => '12',
                    'left'   => '12',
                    'unit'   => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bt-product-minimum-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'gap',
            [
                'label'      => __('Gap', 'freska'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => ['min' => 0, 'max' => 40],
                ],
                'default' => ['size' => 12, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .bt-product-minimum-item--link' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_control(
            'background_color',
            [
                'label'     => __('Background', 'freska'),
                'type'      => Controls_Manager::COLOR,
                'default'   => '#FFFFFF',
                'selectors' => [
                    '{{WRAPPER}} .bt-product-minimum-item' => 'background-color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name'     => 'card_border',
                'selector' => '{{WRAPPER}} .bt-product-minimum-item',
            ]
        );

        $this->add_responsive_control(
            'card_border_radius',
            [
                'label'      => __('Border Radius', 'freska'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default'    => [
                    'top'    => '8',
                    'right'  => '8',
                    'bottom' => '8',
                    'left'   => '8',
                    'unit'   => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bt-product-minimum-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name'     => 'card_box_shadow',
                'selector' => '{{WRAPPER}} .bt-product-minimum-item',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_image',
            [
                'label' => __('Image', 'freska'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'image_width',
            [
                'label'      => __('Width', 'freska'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => ['min' => 20, 'max' => 200],
                ],
                'default'   => ['size' => 60, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .bt-product-minimum-item--image' => 'width: {{SIZE}}{{UNIT}}; min-width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_height',
            [
                'label'      => __('Height', 'freska'),
                'type'       => Controls_Manager::SLIDER,
                'size_units' => ['px'],
                'range'      => [
                    'px' => ['min' => 20, 'max' => 200],
                ],
                'default'   => ['size' => 60, 'unit' => 'px'],
                'selectors' => [
                    '{{WRAPPER}} .bt-product-minimum-item--image' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->add_responsive_control(
            'image_border_radius',
            [
                'label'      => __('Border Radius', 'freska'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', '%'],
                'default'    => [
                    'top' => '4', 'right' => '4', 'bottom' => '4', 'left' => '4', 'unit' => 'px',
                ],
                'selectors' => [
                    '{{WRAPPER}} .bt-product-minimum-item--image img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_title',
            [
                'label' => __('Title', 'freska'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'title_color',
            [
                'label'     => __('Color', 'freska'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-product-minimum-item--title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'title_hover_color',
            [
                'label'     => __('Hover Color', 'freska'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-product-minimum-item--link:hover .bt-product-minimum-item--title' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'title_typography',
                'selector' => '{{WRAPPER}} .bt-product-minimum-item--title',
            ]
        );

        $this->add_responsive_control(
            'title_margin',
            [
                'label'      => __('Margin', 'freska'),
                'type'       => Controls_Manager::DIMENSIONS,
                'size_units' => ['px', 'em'],
                'default'    => [
                    'bottom' => '4', 'unit' => 'px', 'isLinked' => false,
                ],
                'selectors' => [
                    '{{WRAPPER}} .bt-product-minimum-item--title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_price',
            [
                'label' => __('Price', 'freska'),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'price_color',
            [
                'label'     => __('Color', 'freska'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-product-minimum-item--price' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'price_typography',
                'selector' => '{{WRAPPER}} .bt-product-minimum-item--price',
            ]
        );

        $this->add_control(
            'price_regular_color',
            [
                'label'     => __('Regular Price Color (del)', 'freska'),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-product-minimum-item--price del' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function render()
    {
        if (!class_exists('WooCommerce')) {
            return;
        }

        $settings   = $this->get_settings_for_display();
        $product_id = !empty($settings['product_id']) ? absint($settings['product_id']) : 0;

        if (!$product_id) {
            return;
        }

        $product = wc_get_product($product_id);

        if (!$product || !$product->is_visible()) {
            return;
        }

        $image_size = !empty($settings['image_size']) ? sanitize_key($settings['image_size']) : 'thumbnail';
        ?>
        <div class="bt-elwg-product-minimum-item">
            <div class="bt-product-minimum-item">
                <a class="bt-product-minimum-item--link" href="<?php echo esc_url($product->get_permalink()); ?>">
                    <div class="bt-product-minimum-item--image">
                        <?php
                        if (has_post_thumbnail($product_id)) {
                            echo get_the_post_thumbnail($product_id, $image_size);      
                        } else {
                            echo '<img src="' . esc_url(wc_placeholder_img_src('woocommerce_thumbnail')) . '" alt="' . esc_attr($product->get_name()) . '" class="wp-post-image" />';
                        }
                        ?>
                    </div>
                    <div class="bt-product-minimum-item--info">
                        <div class="bt-product-minimum-item--title">
                            <?php echo esc_html($product->get_name()); ?>
                        </div>
                        <div class="bt-product-minimum-item--price">
                            <?php echo wp_kses_post($product->get_price_html()); ?>
                        </div>
                    </div>
                </a>
            </div>
        </div>
        <?php
    }

    protected function content_template() {}
}