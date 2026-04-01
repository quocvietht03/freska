<?php

namespace FreskaElementorWidgets\Widgets\LocaleSwitcher;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;

class Widget_LocaleSwitcher extends Widget_Base
{
    public function get_name()
    {
        return 'bt-locale-switcher';
    }

    public function get_title()
    {
        return __('Locale Switcher (Language + Currency)', 'freska');
    }

    public function get_icon()
    {
        return 'bt-bears-icon eicon-select';
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
            'section_display',
            [
                'label' => __('Display', 'freska'),
            ]
        );

        $this->add_control(
            'show_language',
            [
                'label' => __('Show Language Switcher', 'freska'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'freska'),
                'label_off' => __('No', 'freska'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'show_currency',
            [
                'label' => __('Show Currency Switcher', 'freska'),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => __('Yes', 'freska'),
                'label_off' => __('No', 'freska'),
                'return_value' => 'yes',
                'default' => 'yes',
            ]
        );

        $this->add_control(
            'dropdown_position',
            [
                'label' => __('Dropdown Position', 'freska'),
                'type' => Controls_Manager::SELECT,
                'default' => 'bottom',
                'options' => [
                    'top' => __('Top', 'freska'),
                    'bottom' => __('Bottom', 'freska'),
                ],
            ]
        );

        $this->add_control(
            'layout_direction',
            [
                'label' => __('Layout', 'freska'),
                'type' => Controls_Manager::SELECT,
                'default' => 'row',
                'options' => [
                    'row' => __('Language first, then Currency', 'freska'),
                    'row-reverse' => __('Currency first, then Language', 'freska'),
                ],
                'selectors' => [
                    '{{WRAPPER}} .bt-elwg-locale-switcher__inner' => 'flex-direction: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'switcher_alignment',
            [
                'label' => __('Alignment', 'freska'),
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
                'default' => 'left',
                'selectors' => [
                    '{{WRAPPER}} .bt-elwg-locale-switcher__inner' => 'justify-content: {{VALUE}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_languages',
            [
                'label' => __('Languages', 'freska'),
                'condition' => [
                    'show_language' => 'yes',
                ],
            ]
        );

        $lang_repeater = new Repeater();
        $lang_repeater->add_control(
            'language_name',
            [
                'label' => __('Language Name', 'freska'),
                'type' => Controls_Manager::TEXT,
                'default' => __('English', 'freska'),
                'label_block' => true,
            ]
        );
        $lang_repeater->add_control(
            'language_link',
            [
                'label' => __('Language Link', 'freska'),
                'type' => Controls_Manager::URL,
                'placeholder' => __('https://your-site.com/en/', 'freska'),
                'show_external' => false,
            ]
        );
        $lang_repeater->add_control(
            'language_flag',
            [
                'label' => __('Flag Image', 'freska'),
                'type' => Controls_Manager::MEDIA,
                'default' => [],
            ]
        );

        $this->add_control(
            'languages',
            [
                'label' => __('Language Items', 'freska'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $lang_repeater->get_controls(),
                'default' => [
                    ['language_name' => __('English', 'freska'), 'language_link' => ['url' => '#'], 'language_flag' => []],
                    ['language_name' => __('Español', 'freska'), 'language_link' => ['url' => '#'], 'language_flag' => []],
                    ['language_name' => __('Français', 'freska'), 'language_link' => ['url' => '#'], 'language_flag' => []],
                ],
                'title_field' => '{{{ language_name }}}',
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_currencies',
            [
                'label' => __('Currencies', 'freska'),
                'condition' => [
                    'show_currency' => 'yes',
                ],
            ]
        );

        $curr_repeater = new Repeater();
        $curr_repeater->add_control(
            'currency_symbol',
            [
                'label' => __('Currency Symbol', 'freska'),
                'type' => Controls_Manager::TEXT,
                'default' => '$',
                'label_block' => true,
            ]
        );
        $curr_repeater->add_control(
            'currency_name',
            [
                'label' => __('Currency Name', 'freska'),
                'type' => Controls_Manager::TEXT,
                'default' => __('Dollar (US)', 'freska'),
                'label_block' => true,
            ]
        );
        $curr_repeater->add_control(
            'currency_link',
            [
                'label' => __('Currency Link', 'freska'),
                'type' => Controls_Manager::URL,
                'placeholder' => __('https://your-site.com?currency=USD', 'freska'),
                'show_external' => false,
            ]
        );

        $this->add_control(
            'currencies',
            [
                'label' => __('Currency Items', 'freska'),
                'type' => Controls_Manager::REPEATER,
                'fields' => $curr_repeater->get_controls(),
                'default' => [
                    ['currency_symbol' => '$', 'currency_name' => __('Dollar (US)', 'freska'), 'currency_link' => ['url' => '#']],
                    ['currency_symbol' => '£', 'currency_name' => __('Pound (UK)', 'freska'), 'currency_link' => ['url' => '#']],
                    ['currency_symbol' => '€', 'currency_name' => __('Euro (EUR)', 'freska'), 'currency_link' => ['url' => '#']],
                ],
                'title_field' => '{{{ currency_symbol }}} {{{ currency_name }}}',
            ]
        );

        $this->end_controls_section();
    }

    protected function register_style_section_controls()
    {
        $this->start_controls_section(
            'section_style_current_item',
            [
                'label' => __('Current Item', 'freska'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_control(
            'current_item_text_color',
            [
                'label' => __('Text Color', 'freska'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-current-item' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            'current_item_background_color',
            [
                'label' => __('Background Color', 'freska'),
                'type' => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .bt-current-item' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_responsive_control(
            'current_item_padding',
            [
                'label' => __('Padding', 'freska'),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => ['px'],
                'selectors' => [
                    '{{WRAPPER}} .bt-current-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_gap',
            [
                'label' => __('Gap Between Switchers', 'freska'),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'switcher_gap',
            [
                'label' => __('Gap', 'freska'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['px', 'em'],
                'range' => [
                    'px' => ['min' => 0, 'max' => 60],
                ],
                'selectors' => [
                    '{{WRAPPER}} .bt-elwg-locale-switcher__inner' => 'gap: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
    }

    protected function register_controls()
    {
        $this->register_layout_section_controls();
        $this->register_style_section_controls();
    }

    private function render_language_item_content($item)
    {
        $name = isset($item['language_name']) ? $item['language_name'] : '';
        $flag = isset($item['language_flag']['url']) ? $item['language_flag']['url'] : '';
        if ($flag) {
            echo '<span class="language-flag"><img src="' . esc_url($flag) . '" alt=""></span>';
        }
        echo '<span>' . esc_html($name) . '</span>';
    }

    private function render_language_switcher($settings)
    {
        $languages = !empty($settings['languages']) && is_array($settings['languages']) ? $settings['languages'] : [];
        $current = !empty($languages) ? $languages[0] : null;
        $dropdown_position = isset($settings['dropdown_position']) ? $settings['dropdown_position'] : 'bottom';
        ?>
        <div class="language-switcher bt-elwg-switcher js-switcher-dropdown">
            <ul class="bt-dropdown">
                <li class="bt-has-dropdown">
                    <a href="#" class="bt-current-item">
                        <span class="bt-current-item-text">
                            <?php
                            if ($current) {
                                $this->render_language_item_content($current);
                            } else {
                                echo '<span>' . esc_html__('English', 'freska') . '</span>';
                            }
                            ?>
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M13.5306 6.52927L8.5306 11.5293C8.46092 11.5992 8.37813 11.6547 8.28696 11.6925C8.1958 11.7304 8.09806 11.7499 7.99935 11.7499C7.90064 11.7499 7.8029 11.7304 7.71173 11.6925C7.62057 11.6547 7.53778 11.5992 7.4681 11.5293L2.4681 6.52927C2.3272 6.38837 2.24805 6.19728 2.24805 5.99802C2.24805 5.79876 2.3272 5.60767 2.4681 5.46677C2.60899 5.32587 2.80009 5.24672 2.99935 5.24672C3.19861 5.24672 3.3897 5.32587 3.5306 5.46677L7.99997 9.93614L12.4693 5.46615C12.6102 5.32525 12.8013 5.24609 13.0006 5.24609C13.1999 5.24609 13.391 5.32525 13.5318 5.46615C13.6727 5.60704 13.7519 5.79814 13.7519 5.9974C13.7519 6.19665 13.6727 6.38775 13.5318 6.52865L13.5306 6.52927Z" fill="currentColor" />
                        </svg>
                    </a>
                    <ul class="sub-dropdown bt-dropdown-position-<?php echo esc_attr($dropdown_position); ?>">
                        <?php
                        if (!empty($languages)) {
                            foreach ($languages as $index => $item) {
                                $url = !empty($item['language_link']['url']) ? $item['language_link']['url'] : '#';
                                $is_active = ($index === 0);
                                echo '<li><a href="' . esc_url($url) . '" class="bt-item' . ($is_active ? ' active' : '') . '">';
                                $this->render_language_item_content($item);
                                echo '</a></li>';
                            }
                        }
                        ?>
                    </ul>
                </li>
            </ul>
        </div>
        <?php
    }

    private function render_currency_switcher($settings)
    {
        $currencies = !empty($settings['currencies']) && is_array($settings['currencies']) ? $settings['currencies'] : [];
        $current = !empty($currencies) ? $currencies[0] : null;
        $current_text = $current
            ? trim(($current['currency_symbol'] ?? '') . ' ' . ($current['currency_name'] ?? ''))
            : '$ ' . __('Dollar (US)', 'freska');
        $dropdown_position = isset($settings['dropdown_position']) ? $settings['dropdown_position'] : 'bottom';
        ?>
        <div class="currency-switcher bt-elwg-switcher js-switcher-dropdown">
            <ul class="bt-dropdown">
                <li class="bt-has-dropdown">
                    <a href="#" class="bt-current-item">
                        <span class="bt-current-item-text"><?php echo esc_html($current_text); ?></span>
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M13.5306 6.52927L8.5306 11.5293C8.46092 11.5992 8.37813 11.6547 8.28696 11.6925C8.1958 11.7304 8.09806 11.7499 7.99935 11.7499C7.90064 11.7499 7.8029 11.7304 7.71173 11.6925C7.62057 11.6547 7.53778 11.5992 7.4681 11.5293L2.4681 6.52927C2.3272 6.38837 2.24805 6.19728 2.24805 5.99802C2.24805 5.79876 2.3272 5.60767 2.4681 5.46677C2.60899 5.32587 2.80009 5.24672 2.99935 5.24672C3.19861 5.24672 3.3897 5.32587 3.5306 5.46677L7.99997 9.93614L12.4693 5.46615C12.6102 5.32525 12.8013 5.24609 13.0006 5.24609C13.1999 5.24609 13.391 5.32525 13.5318 5.46615C13.6727 5.60704 13.7519 5.79814 13.7519 5.9974C13.7519 6.19665 13.6727 6.38775 13.5318 6.52865L13.5306 6.52927Z" fill="currentColor" />
                        </svg>
                    </a>
                    <ul class="sub-dropdown bt-dropdown-position-<?php echo esc_attr($dropdown_position); ?>">
                        <?php
                        if (!empty($currencies)) {
                            foreach ($currencies as $index => $item) {
                                $symbol = $item['currency_symbol'] ?? '';
                                $name = $item['currency_name'] ?? '';
                                $url = !empty($item['currency_link']['url']) ? $item['currency_link']['url'] : '#';
                                $text = trim($symbol . ' ' . $name);
                                if ($text === '') {
                                    continue;
                                }
                                $is_active = ($index === 0);
                                echo '<li><a href="' . esc_url($url) . '" class="bt-item' . ($is_active ? ' active' : '') . '">' . esc_html($text) . '</a></li>';
                            }
                        }
                        ?>
                    </ul>
                </li>
            </ul>
        </div>
        <?php
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $show_language = $settings['show_language'] === 'yes';
        $show_currency = $settings['show_currency'] === 'yes';

        if (!$show_language && !$show_currency) {
            return;
        }
        ?>
        <div class="bt-elwg-locale-switcher bt-elwg-locale-switcher--default">
            <div class="bt-elwg-locale-switcher__inner">
                <?php if ($show_language) : ?>
                    <?php $this->render_language_switcher($settings); ?>
                <?php endif; ?>
                <?php if ($show_currency) : ?>
                    <?php $this->render_currency_switcher($settings); ?>
                <?php endif; ?>
            </div>
        </div>
        <?php
    }

    protected function content_template()
    {
    }
}
