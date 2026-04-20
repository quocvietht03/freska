<?php

namespace FreskaElementorWidgets\Widgets\BundleCart;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Utils;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;

class Widget_BundleCart extends Widget_Base
{

    public function get_name()
    {
        return 'bt-bundle-cart';
    }

    public function get_title()
    {
        return __('Bundle Cart', 'freska');
    }

    public function get_icon()
    {
        return 'bt-bears-icon eicon-cart-medium';
    }

    public function get_categories()
    {
        return ['freska'];
    }

    public function get_script_depends()
    {
        return ['elementor-widgets'];
    }

    protected function get_supported_ids()
    {
        $supported_ids = [];

        $wp_query = new \WP_Query(array(
            'post_type' => 'product',
            'post_status' => 'publish',
            'posts_per_page' => -1,
        ));

        if ($wp_query->have_posts()) {
            while ($wp_query->have_posts()) {
                $wp_query->the_post();
                $supported_ids[get_the_ID()] = get_the_title();
            }
        }

        wp_reset_postdata();

        return $supported_ids;
    }

    protected function register_layout_section_controls()
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Bundle Cart', 'freska'),
            ]
        );
        $this->add_control(
            'show_numbers',
            [
                'label' => __('Show Numbers In List', 'freska'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'freska'),
                'label_off' => __('Hide', 'freska'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'show_category',
            [
                'label' => __('Show Category', 'freska'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Show', 'freska'),
                'label_off' => __('Hide', 'freska'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );
        $this->add_control(
            'sub_heading',
            [
                'label' => __('Sub Heading', 'freska'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => __('Enter sub heading', 'freska'),
                'label_block' => true,
            ]
        );
        $this->add_control(
            'heading',
            [
                'label' => __('Heading', 'freska'),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'placeholder' => __('Enter heading', 'freska'),
                'label_block' => true,
            ]
        );

        $repeater = new Repeater();
        $repeater->add_control(
            'id_product',
            [
                'label' => __('Select Product', 'freska'),
                'type' => Controls_Manager::SELECT2,
                'options' => $this->get_supported_ids(),
                'label_block' => true,
                'multiple' => false,
            ]
        );
        $this->add_control(
            'bundle_items',
            [
                'label' => __('Bundle Items', 'freska'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'id_product' => '',
                    ],
                    [
                        'id_product' => '',
                    ],
                    [
                        'id_product' => '',
                    ],

                ],
            ]
        );
        $this->end_controls_section();
    }
    protected function register_style_content_section_controls()
    {
        $this->start_controls_section(
            'section_style',
            [
                'label' => __('Style', 'freska'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'content_position',
            [
                'label' => __('Content Position', 'freska'),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => __('Left', 'freska'),
                        'icon' => 'eicon-h-align-left',
                    ],
                    'center' => [
                        'title' => __('Center', 'freska'),
                        'icon' => 'eicon-h-align-center',
                    ],
                    'right' => [
                        'title' => __('Right', 'freska'),
                        'icon' => 'eicon-h-align-right',
                    ],
                ],
                'default' => 'center',
                'toggle' => false,
                'selectors' => [
                    '{{WRAPPER}} .bt-bundle-cart__list-products' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'sub_heading_color',
            [
                'label' => __('Sub Heading Color', 'freska'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-bundle-cart__list-products .bt-list-header .bt-sub-heading' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => 'sub_heading_typography',
                'label'    => __('Typography', 'freska'),
                'default'  => '',
                'selector' => '{{WRAPPER}} .bt-bundle-cart__list-products .bt-list-header .bt-sub-heading',
            ]
        );
        $this->add_control(
            'heading_color',
            [
                'label' => __('Heading Color', 'freska'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-bundle-cart__list-products .bt-list-header .bt-heading' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'heading_typography',
                'label' => __('Typography', 'freska'),
                'default'  => '',
                'selector' => '{{WRAPPER}} .bt-bundle-cart__list-products .bt-list-header .bt-heading',
            ]
        );
        $this->add_control(
            'category_style_heading',
            [
                'label' => __('Category', 'freska'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'category_color',
            [
                'label' => __('Color', 'freska'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-product-category' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'category_typography',
                'label' => __('Typography', 'freska'),
                'selector' => '{{WRAPPER}} .bt-product-category',
            ]
        );

        $this->add_control(
            'title_style_heading',
            [
                'label' => __('Title', 'freska'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'title_color',
            [
                'label' => __('Color', 'freska'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-product-name a' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typography',
                'label' => __('Typography', 'freska'),
                'selector' => '{{WRAPPER}} .bt-product-name a',
            ]
        );

        $this->add_control(
            'price_style_heading',
            [
                'label' => __('Price', 'freska'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control(
            'price_color',
            [
                'label' => __('Color', 'freska'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-price .woocommerce-Price-amount' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'price_typography',
                'label' => __('Typography', 'freska'),
                'selector' => '{{WRAPPER}} .bt-price .woocommerce-Price-amount',
            ]
        );

        $this->add_control(
            'old_price_color',
            [
                'label' => __('Old Price Color', 'freska'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-price del .woocommerce-Price-amount' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'old_price_typography',
                'label' => __('Old Price Typography', 'freska'),
                'selector' => '{{WRAPPER}} .bt-price del .woocommerce-Price-amount',
            ]
        );

        $this->end_controls_section();
    }

    protected function register_controls()
    {
        $this->register_layout_section_controls();
        $this->register_style_content_section_controls();
    }

    protected function render()
    {
        if (!class_exists('WooCommerce')) {
            return;
        }
        
        $settings = $this->get_settings_for_display();


        // Check if any products are selected
        $has_products = false;
        if (!empty($settings['bundle_items'])) {
            foreach ($settings['bundle_items'] as $item) {
                if (!empty($item['id_product'])) {
                    $has_products = true;
                    break;
                }
            }
        }

        // Show message if no products selected
        if (!$has_products) {
            echo '<div class="bt-elwg-bundle-cart--default">';
            echo '<div class="bt-bundle-cart-empty-message">';
            echo '<p>' . esc_html__('Please select products in the bundle items.', 'freska') . '</p>';
            echo '</div>';
            echo '</div>';
            return;
        }
        
?>
        <div class="bt-elwg-bundle-cart--default">
            <div class="bt-bundle-cart">
                <div class="bt-bundle-cart__list-products">
                    <div class="bt-list-header">
                        <?php if (!empty($settings['sub_heading'])) : ?>
                            <p class="bt-sub-heading"><?php echo esc_html($settings['sub_heading']); ?></p>
                        <?php endif; ?>
                        <?php if (!empty($settings['heading'])) : ?>
                            <h2 class="bt-heading"><?php echo esc_html($settings['heading']); ?></h2>
                        <?php endif; ?>
                    </div>
                    <?php if (!empty($settings['bundle_items'])) : ?>
                        <ul class="bt-bundle-cart-product-list">
                            <?php
                            // Collect all product IDs from bundle_items
                            $bundle_product_ids = [];
                            foreach ($settings['bundle_items'] as $item) {
                                if (!empty($item['id_product'])) {
                                    $bundle_product_ids[] = $item['id_product'];
                                }
                            }

                            // Prepare WP_Query to get products in the same order as bundle_items
                            if (!empty($bundle_product_ids)) {
                                $args = [
                                    'post_type'      => 'product',
                                    'post__in'       => $bundle_product_ids,
                                    'posts_per_page' => -1,
                                    'orderby'        => 'post__in',
                                ];
                                $bundle_query = new \WP_Query($args);
                                $index = 1;
                                if ($bundle_query->have_posts()) :
                                    while ($bundle_query->have_posts()) : $bundle_query->the_post();
                                        global $product;
                                        $product_id = get_the_ID();
                                        if (!$product) {
                                            $product = wc_get_product($product_id);
                                        }
                                        if (!$product) {
                                            continue;
                                        }
                                        $order_currency = get_woocommerce_currency();
                                        $product_currencySymbol = get_woocommerce_currency_symbol($order_currency);
                                        $is_in_stock = $product->is_in_stock();
                                        $out_of_stock_class = !$is_in_stock ? ' out-of-stock' : '';
                            ?>
                                        <li class="bt-bundle-cart-product-list__item<?php echo esc_attr($out_of_stock_class); ?>"
                                            data-product-currency="<?php echo esc_attr($product_currencySymbol); ?>"
                                            data-product-default-price="<?php echo esc_attr($product->get_sale_price() ? $product->get_sale_price() : $product->get_regular_price()); ?>"
                                            data-product-id="<?php echo esc_attr($product_id); ?>"
                                            data-in-stock="<?php echo esc_attr($is_in_stock ? '1' : '0'); ?>">
                                            <?php if ($settings['show_numbers'] === 'yes') : ?>
                                                <div class="bt-number-product">
                                                    <?php echo esc_html($index); ?>
                                                </div>
                                            <?php endif; ?>
                                            <a class="bt-bundle-cart-product-thumbnail" href="<?php echo esc_url($product->get_permalink()); ?>">
                                                <?php
                                                if (has_post_thumbnail($product_id)) {
                                                    echo get_the_post_thumbnail($product_id, 'thumbnail');
                                                } else {
                                                    echo '<img src="' . esc_url(wc_placeholder_img_src('woocommerce_thumbnail')) . '" alt="' . esc_html__('Awaiting product image', 'freska') . '" class="wp-post-image" />';
                                                }
                                                ?>
                                            </a>
                                            <div class="bt-product-content">
                                                <div class="bt-product-content__inner">
                                                    <?php if ($settings['show_category'] === 'yes') : ?>
                                                        <div class="bt-product-category">
                                                            <?php echo wc_get_product_category_list($product_id, ', ', ''); ?>
                                                        </div>
                                                    <?php endif; ?>

                                                    <h4 class="bt-product-name">
                                                        <a href="<?php echo esc_url($product->get_permalink()); ?>">
                                                            <?php echo esc_html($product->get_name()); ?>
                                                        </a>
                                                    </h4>
                                                    <?php if (!$product->is_type('variable')) : ?>
                                                        <?php echo wc_get_stock_html($product); // WPCS: XSS ok. ?>
                                                    <?php endif; ?>
                                                    <?php
                                                    if ($product->is_type('variable')) {
                                                        /**
                                                         * BundleCart Variable Support
                                                         */
                                                         if (function_exists('freska_woocommerce_template_loop_add_to_cart')) {
                                                            freska_woocommerce_template_loop_add_to_cart();
                                                         } else {
                                                            do_action('freska_woocommerce_template_single_add_to_cart');
                                                         }
                                                    }
                                                    ?>
                                                </div>

                                                <?php
                                                $price_class = $product->is_type( 'variable' ) ? 'bt-product-variable' : '';
                                                $price_html  = $product->get_price_html();
                                                ?>
                                                <p class="bt-price <?php echo esc_attr( $price_class ); ?>">
                                                    <?php echo wp_kses_post( $price_html ); ?>
                                                </p>
                                            </div>
                                        </li>
                            <?php
                                        $index++;
                                    endwhile;
                                    wp_reset_postdata();
                                endif;
                            }
                            ?>
                        </ul>
                    <?php endif; ?>
                    <div class="bt-button-wrapper">
                        <?php
                        // Build $product_ids array with proper structure
                        $product_ids = [];
                        if (!empty($settings['bundle_items'])) {
                            foreach ($settings['bundle_items'] as $item) {
                                // Check if product ID exists and is not empty
                                if (!isset($item['id_product']) || empty($item['id_product'])) {
                                    continue;
                                }
                                $product = wc_get_product($item['id_product']);
                                if (!$product) {
                                    continue;
                                }
                                $variation_id = 0; // Will be updated by JS
                                $product_ids[] = [
                                    'product_id'   => $item['id_product'],
                                    'variation_id' => $variation_id,
                                ];
                            }
                        }                   
                        if (!empty($product_ids)) :
                        ?>
                            <a class="bt-button bt-button-add-set-to-cart" data-ids="<?php echo esc_attr(json_encode($product_ids)); ?>" href="#">
                                <?php esc_html_e('Add set to cart', 'freska'); ?>
                                <span class="bt-btn-price"></span>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>

            </div>
        </div>
<?php
    }

    protected function content_template() {}
}




