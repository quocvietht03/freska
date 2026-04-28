<?php

namespace FreskaElementorWidgets\Widgets\ListFaq;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Typography;


class Widget_ListFaq extends Widget_Base
{

    public function get_name()
    {
        return 'bt-list-faq';
    }

    public function get_title()
    {
        return __('List FAQ', 'freska');
    }

    public function get_icon()
    {
        return 'bt-bears-icon eicon-accordion';
    }

    public function get_categories()
    {
        return ['freska'];
    }

    public function get_script_depends()
    {
        return ['elementor-widgets'];
    }

    protected function register_layout_section_controls()
    {
        $this->start_controls_section(
            'section_content',
            [
                'label' => __('Content', 'freska'),
            ]
        );

        $repeater = new Repeater();


        $repeater->add_control(
            'faq_title',
            [
                'label' => __('Text', 'freska'),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => __('FAQ title', 'freska'),
            ]
        );

        $repeater->add_control(
            'faq_content',
            [
                'label' => __('Content', 'freska'),
                'type' => Controls_Manager::WYSIWYG,
                'label_block' => true,
                'default' => __('FAQ content', 'freska'),
            ]
        );

        $this->add_control(
            'list',
            [
                'label' => __('List Faq', 'freska'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'faq_title' => __('What is Freska?', 'freska'),
                        'faq_content' => __('Freska is a powerful WordPress theme that helps you build beautiful websites quickly and easily.', 'freska')
                    ],
                    [
                        'faq_title' => __('How do I get started with Freska?', 'freska'),
                        'faq_content' => __('Simply install the theme, import a demo, and customize it using our intuitive page builder.', 'freska')
                    ],
                    [
                        'faq_title' => __('Do you offer support?', 'freska'),
                        'faq_content' => __('Yes, we provide dedicated support through our help center and ticket system.', 'freska')
                    ],
                ],
                'title_field' => '{{{ faq_title }}}',
            ]
        );


        $this->end_controls_section();
    }

    protected function register_style_section_controls()
    {
        $this->start_controls_section(
            'section_style_item',
            [
                'label' => esc_html__('General', 'freska'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'list_border',
            [
                'label' => __('Border Width', 'freska'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px'],
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
                    '{{WRAPPER}} .bt-elwg-list-faq--default .item-faq-inner' => 'border-bottom: {{SIZE}}{{UNIT}} solid #E9E9E9;',
                ],
            ]
        );
        $this->add_control(
            'list_border_color',
            [
                'label' => __('Border Color', 'freska'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-elwg-list-faq--default .item-faq-inner' => 'border-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'list_gap',
            [
                'label' => __('Space Between', 'freska'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
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
                    '{{WRAPPER}} .bt-elwg-list-faq--default .item-faq-inner' => 'padding-top: {{SIZE}}{{UNIT}};padding-bottom: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'list_gap_horizontal',
            [
                'label' => __('Horizontal Padding', 'freska'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', '%'],
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
                    '{{WRAPPER}} .bt-elwg-list-faq--default .item-faq-inner' => 'padding-left: {{SIZE}}{{UNIT}};padding-right: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->end_controls_section();


        $this->start_controls_section(
            'section_style_content',
            [
                'label' => esc_html__('Content', 'freska'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'title_style',
            [
                'label' => esc_html__('Title', 'freska'),
                'type' => Controls_Manager::HEADING,
            ]
        );
        $this->add_control(
            'list_title_color',
            [
                'label' => __('Color', 'freska'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .bt-item-title h3' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'list_title_hover_color',
            [
                'label' => __('Color Hover/Active', 'freska'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .bt-item-title:hover h3' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .bt-item-title.active h3' => 'color: {{VALUE}};',
                ],
            ]
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'list_title_typography',
                'label' => __('Typography', 'freska'),
                'default' => '',
                'selector' => '{{WRAPPER}} .bt-item-title h3 ',
            ]
        );

        $this->add_control(
            'icon_style',
            [
                'label' => esc_html__('Icon', 'freska'),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );

        $this->add_control(
            'icon_color',
            [
                'label' => __('Icon Color', 'freska'),
                'type' => Controls_Manager::COLOR,
                'default' => '#183F91',
                'selectors' => [
                    '{{WRAPPER}} .bt-item-title svg path' => 'fill: {{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'icon_hover_color',
            [
                'label' => __('Icon Hover/Active Color', 'freska'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .bt-item-title:hover svg path' => 'fill: {{VALUE}};',
                    '{{WRAPPER}} .bt-item-title.active svg path' => 'fill: {{VALUE}};',
                ],
            ]
        );


        $this->add_control(
            'content_style',
            [
                'label' => esc_html__('content', 'freska'),
                'type' => Controls_Manager::HEADING,
            ]
        );
        $this->add_control(
            'list_content_color',
            [
                'label' => __('Color', 'freska'),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                '{{WRAPPER}} .bt-item-content, {{WRAPPER}} .bt-item-content p' => 'color: {{VALUE}};'

                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'list_content_typography',
                'label' => __('Typography', 'freska'),
                'default' => '',
                'selector' => '{{WRAPPER}} .bt-item-content, {{WRAPPER}} .bt-item-content p'
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
        $settings = $this->get_settings_for_display();

        if (empty($settings['list'])) {
            return;
        }

?>
        <div class="bt-elwg-list-faq--default">
            <div class="bt-elwg-list-faq-inner">
                <?php foreach ($settings['list'] as $index => $item): ?>
                    <div class="item-faq">
                        <div class="item-faq-inner">
                            <div class="bt-item-title">
                                <?php if (!empty($item['faq_title'])): ?>
                                    <h3> <?php echo esc_html($item['faq_title']) ?> </h3>
                                <?php endif; ?>
                             <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                               <rect class="horizontal-line" x="5" y="11" width="14" height="2" fill="currentColor"/>
                               <rect class="vertical-line" x="11" y="5" width="2" height="14" fill="currentColor"/>
                            </svg>
                            </div>

                            <?php if (!empty($item['faq_content'])): ?>
                                <div class="bt-item-content">
                                    <?php echo wp_kses_post($item['faq_content']) ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
<?php }

    protected function content_template() {}
}
