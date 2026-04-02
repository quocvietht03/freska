<?php

namespace FreskaElementorWidgets\Widgets\MegaMenu;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Icons_Manager;

class Widget_MegaMenu extends Widget_Base
{
	private function get_available_menus()
	{
		$menus = wp_get_nav_menus();

		$options = [];

		foreach ($menus as $menu) {
			$options[$menu->slug] = $menu->name;
		}

		return $options;
	}

	public function get_name()
	{
		return 'bt-megamenu';
	}

	public function get_title()
	{
		return __('BT MegaMenu', 'freska');
	}

	public function get_icon()
	{
		return 'bt-bears-icon eicon-nav-menu';
	}

	public function get_categories()
	{
		return ['freska'];
	}

	public function get_script_depends()
	{
		return ['freska-widgets'];
	}

	protected function register_content_section_controls()
	{
		$this->start_controls_section(
			'section_layout',
			[
				'label' => __('Layout', 'freska'),
			]
		);

		$menus = $this->get_available_menus();

		if (! empty($menus)) {
			$this->add_control(
				'menu',
				[
					'label' => esc_html__('Menu', 'freska'),
					'type' => Controls_Manager::SELECT,
					'options' => $menus,
					'default' => array_keys($menus)[0],
					'save_default' => true,
					'description' => sprintf(
						/* translators: 1: Link opening tag, 2: Link closing tag. */
						esc_html__('Go to the %1$sMenus screen%2$s to manage your menus.', 'freska'),
						sprintf('<a href="%s" target="_blank">', admin_url('nav-menus.php')),
						'</a>'
					),
				]
			);
		} else {
			$this->add_control(
				'menu',
				[
					'type' => Controls_Manager::ALERT,
					'alert_type' => 'info',
					'heading' => esc_html__('There are no menus in your site.', 'freska'),
					'content' => sprintf(
						/* translators: 1: Link opening tag, 2: Link closing tag. */
						esc_html__('Go to the %1$sMenus screen%2$s to create one.', 'freska'),
						sprintf('<a href="%s" target="_blank">', admin_url('nav-menus.php?action=edit&menu=0')),
						'</a>'
					),
				]
			);
		}
		$this->add_control(
			'menu_layout',
			[
				'label' => esc_html__('Layout', 'freska'),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'horizontal' => esc_html__('Horizontal', 'freska'),
					'vertical' => esc_html__('Vertical', 'freska'),
				],
				'default' => 'horizontal',
			]
		);

		$this->add_control(
			'vertical_layout_notice',
			[
				'type' => Controls_Manager::ALERT,
				'alert_type' => 'warning',
				'heading' => esc_html__('Vertical layout notice', 'freska'),
				'content' => esc_html__('The content widget (megamenu block) in each menu item will not work in vertical layout. You need to set up the content according to the options below.', 'freska'),
				'condition' => [
					'menu_layout' => 'vertical',
				],
			]
		);

		$this->add_control(
			'vertical_menu_width',
			[
				'label' => esc_html__('Vertical Menu Width', 'freska'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => ['min' => 100, 'max' => 1920],
				],
				'default' => [
					'size' => 1290,
					'unit' => 'px',
				],
				'condition' => [
					'menu_layout' => 'vertical',
				],
			]
		);

		$this->add_control(
			'vertical_menu_edge_padding',
			[
				'label' => esc_html__('Edge Padding (per side)', 'freska'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => ['min' => 0, 'max' => 100],
				],
				'default' => [
					'size' => 15,
					'unit' => 'px',
				],
				'description' => esc_html__('Margin per side (left + right) when viewport is narrower than menu width.', 'freska'),
				'condition' => [
					'menu_layout' => 'vertical',
				],
			]
		);

		$this->add_responsive_control(
			'main_menu_item_max_width',
			[
				'label' => esc_html__('Item Max Width', 'freska'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px'],
				'range' => [
					'px' => ['min' => 50, 'max' => 1290],
				],
				'default' => [
					'size' => 300,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .bt-megamenu--layout-vertical' => '--max-width-item-vertical: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'menu_layout' => 'vertical',
				],
			]
		);

		$this->add_control(
			'menu_alignment',
			[
				'label' => esc_html__('Alignment', 'freska'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__('Left', 'freska'),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__('Center', 'freska'),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__('Right', 'freska'),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'center',
				'selectors' => [
					'{{WRAPPER}} .bt-megamenu--layout-horizontal .bt-megamenu' => 'justify-content: {{VALUE}};',
				],
				'selectors_dictionary' => [
					'left' => 'flex-start',
					'center' => 'center',
					'right' => 'flex-end',
				],
				'condition' => [
					'menu_layout' => 'horizontal',
				],
			]
		);

		$this->add_control(
			'submenu_indicator_separator',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);
		$this->add_control(
			'submenu_indicator',
			[
				'label'   => esc_html__('Submenu Indicator', 'freska'),
				'type'    => Controls_Manager::ICONS,
				'default' => [
					'value'   => 'fas fa-chevron-down',
					'library' => 'fa-solid',
				],
			]
		);
		$this->start_controls_tabs('tabs_submenu_indicator_color');

		$this->start_controls_tab(
			'tab_submenu_indicator_color_normal',
			[
				'label' => esc_html__('Normal', 'freska'),
			]
		);
		$this->add_control(
			'submenu_indicator_color',
			[
				'label'     => esc_html__('Color', 'freska'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .bt-megamenu .bt-submenu-indicator' => 'color: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_submenu_indicator_color_hover',
			[
				'label' => esc_html__('Hover', 'freska'),
			]
		);
		$this->add_control(
			'submenu_indicator_color_hover',
			[
				'label'     => esc_html__('Color', 'freska'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .bt-megamenu > li:hover > a .bt-submenu-indicator' => 'color: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_submenu_indicator_color_active',
			[
				'label' => esc_html__('Active', 'freska'),
			]
		);
		$this->add_control(
			'submenu_indicator_color_active',
			[
				'label'     => esc_html__('Color', 'freska'),
				'type'      => Controls_Manager::COLOR,
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .bt-megamenu > li.current-menu-item > a .bt-submenu-indicator, {{WRAPPER}} .bt-megamenu > li.current-menu-ancestor > a .bt-submenu-indicator' => 'color: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_responsive_control(
			'submenu_indicator_size',
			[
				'label'      => esc_html__('Submenu Indicator Size', 'freska'),
				'type'       => Controls_Manager::SLIDER,
				'size_units' => ['px', 'em', 'rem'],
				'range'      => [
					'px'  => ['min' => 8, 'max' => 48],
					'em'  => ['min' => 0.5, 'max' => 3],
					'rem' => ['min' => 0.5, 'max' => 3],
				],
				'selectors'  => [
					'{{WRAPPER}} .bt-megamenu .bt-submenu-indicator svg' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'toggle_menu_alignment',
			[
				'label' => esc_html__('Toggle Menu Alignment', 'freska'),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__('Left', 'freska'),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__('Center', 'freska'),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__('Right', 'freska'),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => 'right',
				'selectors' => [
					'{{WRAPPER}} .bt-megamenu-toggle' => 'margin-left: {{VALUE}}; margin-right: {{VALUE}};',
				],
				'selectors_dictionary' => [
					'left' => '0; margin-right: auto;',
					'center' => 'auto;',
					'right' => '0; margin-left: auto;',
				],
			]
		);
		$this->end_controls_section();
	}

	protected function register_style_content_section_controls()
	{
		$this->start_controls_section(
			'section_style_main_menu',
			[
				'label' => esc_html__('Main Menu', 'freska'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'main_menu_background',
			[
				'label' => esc_html__('Background', 'freska'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-megamenu > li > a' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'main_menu_border',
				'label' => esc_html__('Border', 'freska'),
				'selector' => '{{WRAPPER}} .bt-megamenu',
			]
		);
		$this->add_control(
			'main_menu_border_radius',
			[
				'label' => esc_html__('Border Radius', 'freska'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%'],
				'selectors' => [
					'{{WRAPPER}} .bt-elwg-megamenu--default.bt-megamenu--layout-vertical .bt-megamenu > li:last-child > a' => 'border-radius: 0 0 {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .bt-elwg-megamenu--default.bt-megamenu--layout-vertical .bt-megamenu > li:first-child > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} 0 0;',
					'{{WRAPPER}} .bt-megamenu' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'main_menu_padding',
			[
				'label' => esc_html__('Padding', 'freska'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', '%', 'em', 'rem'],
				'default' => [
					'top' => '',
					'right' => '',
					'bottom' => '',
					'left' => '',
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .bt-megamenu' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_main_menu_items',
			[
				'label' => esc_html__('Menu Items', 'freska'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs('tabs_main_menu_item_style');

		$this->start_controls_tab(
			'tab_main_menu_item_normal',
			[
				'label' => esc_html__('Normal', 'freska'),
			]
		);

		$this->add_control(
			'color_main_menu_item',
			[
				'label' => esc_html__('Text Color', 'freska'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-megamenu > li > a' => 'color: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'color_main_menu_item_icon',
			[
				'label' => esc_html__('Icon Color', 'freska'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-megamenu > li > a .bt-menu-icon svg path' => 'color: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'background_main_menu_item',
			[
				'label' => esc_html__('Background', 'freska'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-megamenu > li > a' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_main_menu_item_hover',
			[
				'label' => esc_html__('Hover', 'freska'),
			]
		);

		$this->add_control(
			'color_main_menu_item_hover',
			[
				'label' => esc_html__('Text Color', 'freska'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bt-megamenu > li:hover > a' => 'color: {{VALUE}}; fill: {{VALUE}};',
					'{{WRAPPER}} .bt-megamenu > li:focus > a' => 'color: {{VALUE}}; fill: {{VALUE}};',

				],
			]
		);
		$this->add_control(
			'color_main_menu_item_icon_hover',
			[
				'label' => esc_html__('Icon Color', 'freska'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-megamenu > li:hover > a .bt-menu-icon svg path' => 'color: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'background_main_menu_item_hover',
			[
				'label' => esc_html__('Background', 'freska'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-megamenu > li:hover > a' => 'background-color: {{VALUE}};border-color: {{VALUE}};',
					'{{WRAPPER}} .bt-megamenu > li:focus > a' => 'background-color: {{VALUE}};border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_main_menu_item_active',
			[
				'label' => esc_html__('Active', 'freska'),
			]
		);

		$this->add_control(
			'color_main_menu_item_active',
			[
				'label' => esc_html__('Text Color', 'freska'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-megamenu > li.current-menu-item > a' => 'color: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			'color_main_menu_item_icon_active',
			[
				'label' => esc_html__('Icon Color', 'freska'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-megamenu > li.current-menu-item > a .bt-menu-icon svg path' => 'color: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'background_main_menu_item_active',
			[
				'label' => esc_html__('Background', 'freska'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-megamenu > li.current-menu-item > a' => 'background-color: {{VALUE}};border-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'main_menu_separator',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'main_menu_typography',
				'selector' => '{{WRAPPER}} .bt-megamenu > li > a',
			]
		);

		$this->add_responsive_control(
			'padding_vertical_main_menu_item',
			[
				'label' => esc_html__('Vertical Padding', 'freska'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', 'em', 'rem', 'custom'],
				'range' => [
					'px' => [
						'max' => 50,
					],
					'em' => [
						'max' => 5,
					],
					'rem' => [
						'max' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bt-megamenu > li > a' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}} .bt-megamenu > li > .bt-toggle-icon' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'padding_horizontal_main_menu_item',
			[
				'label' => esc_html__('Horizontal Padding', 'freska'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', 'em', 'rem', 'custom'],
				'range' => [
					'px' => [
						'max' => 50,
					],
					'em' => [
						'max' => 5,
					],
					'rem' => [
						'max' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .bt-megamenu > li > a' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}}',
				],
			]
		);
		$this->add_responsive_control(
			'main_menu_dropdown_distance',
			[
				'label' => esc_html__('Distance from content', 'freska'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', 'em', 'rem', 'custom'],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 80,
					],
					'em' => [
						'min' => 0,
						'max' => 5,
					],
					'rem' => [
						'min' => 0,
						'max' => 5,
					],
				],
				'default' => [
					'size' => 0,
					'unit' => 'px',
				],
				'selectors' => [
					'{{WRAPPER}} .bt-megamenu > li.menu-item-has-megamenu .bt-megamenu-dropdown' => 'padding-top: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .bt-megamenu-wrapper' => '--distance: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'menu_layout' => 'horizontal',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'main_menu_item_border',
				'label' => esc_html__('Border', 'freska'),
				'selector' => '{{WRAPPER}} .bt-megamenu > li > a',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_sub_menu',
			[
				'label' => esc_html__('Sub Menu', 'freska'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->start_controls_tabs('tabs_sub_menu_item_style');

		$this->start_controls_tab(
			'tab_sub_menu_item_normal',
			[
				'label' => esc_html__('Normal', 'freska'),
			]
		);

		$this->add_control(
			'color_sub_menu_item',
			[
				'label' => esc_html__('Text Color', 'freska'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .sub-menu > li > a' => 'color: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'background_sub_menu_item',
			[
				'label' => esc_html__('Background', 'freska'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .sub-menu > li > a' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_sub_menu_item_hover',
			[
				'label' => esc_html__('Hover', 'freska'),
			]
		);

		$this->add_control(
			'color_sub_menu_item_hover',
			[
				'label' => esc_html__('Text Color', 'freska'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .sub-menu > li > a:hover,
					{{WRAPPER}} .sub-menu > li > a:focus' => 'color: {{VALUE}}; fill: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'background_sub_menu_item_hover',
			[
				'label' => esc_html__('Background', 'freska'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .sub-menu > li > a:hover,
					{{WRAPPER}} .sub-menu > li > a:focus' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_sub_menu_item_active',
			[
				'label' => esc_html__('Active', 'freska'),
			]
		);

		$this->add_control(
			'color_sub_menu_item_active',
			[
				'label' => esc_html__('Text Color', 'freska'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .sub-menu > li.current-menu-item > a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'background_sub_menu_item_active',
			[
				'label' => esc_html__('Background', 'freska'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .sub-menu > li.current-menu-item > a' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_control(
			'sub_menu_separator',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'sub_menu_typography',
				'selector' => '{{WRAPPER}} .sub-menu > li > a',
			]
		);

		$this->add_responsive_control(
			'padding_vertical_sub_menu_item',
			[
				'label' => esc_html__('Vertical Padding', 'freska'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', 'em', 'rem', 'custom'],
				'range' => [
					'px' => [
						'max' => 50,
					],
					'em' => [
						'max' => 5,
					],
					'rem' => [
						'max' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .sub-menu > li > a' => 'padding-top: {{SIZE}}{{UNIT}}; padding-bottom: {{SIZE}}{{UNIT}}',
				],
			]
		);
		$this->add_responsive_control(
			'padding_horizontal_sub_menu_item',
			[
				'label' => esc_html__('Horizontal Padding', 'freska'),
				'type' => Controls_Manager::SLIDER,
				'size_units' => ['px', 'em', 'rem', 'custom'],
				'range' => [
					'px' => [
						'max' => 50,
					],
					'em' => [
						'max' => 5,
					],
					'rem' => [
						'max' => 5,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .sub-menu > li > a' => 'padding-left: {{SIZE}}{{UNIT}}; padding-right: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'sub_menu_item_border',
				'label' => esc_html__('Border', 'freska'),
				'selector' => '{{WRAPPER}} .sub-menu > li > a',
			]
		);

		$this->add_responsive_control(
			'sub_menu_item_border_radius',
			[
				'label' => esc_html__('Border Radius', 'freska'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', 'rem', '%', 'custom'],
				'selectors' => [
					'{{WRAPPER}} .sub-menu > li > a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			'sub_menu_box_separator',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

		$this->add_control(
			'sub_menu_box_heading',
			[
				'label' => esc_html__('Sub Menu Box', 'freska'),
				'type' => Controls_Manager::HEADING,
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'sub_menu_box_shadow',
				'selector' => '{{WRAPPER}} .bt-megamenu .sub-menu',
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'sub_menu_box_border',
				'label' => esc_html__('Border', 'freska'),
				'selector' => '{{WRAPPER}} .bt-megamenu .sub-menu',
			]
		);

		$this->add_responsive_control(
			'sub_menu_box_border_radius',
			[
				'label' => esc_html__('Border Radius', 'freska'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', 'rem', '%', 'custom'],
				'selectors' => [
					'{{WRAPPER}} .bt-megamenu .sub-menu' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'sub_menu_box_padding',
			[
				'label' => esc_html__('Padding', 'freska'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', 'rem', '%', 'custom'],
				'selectors' => [
					'{{WRAPPER}} .bt-megamenu .sub-menu' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_style_megamenu',
			[
				'label' => esc_html__('Mega Menu (Content)', 'freska'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'megamenu_background',
			[
				'label' => esc_html__('Background Color', 'freska'),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .bt-megamenu-dropdown .elementor' => 'background-color: {{VALUE}} !important',
				],
			]
		);

		$this->add_responsive_control(
			'megamenu_padding',
			[
				'label' => esc_html__('Padding', 'freska'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => ['px', 'em', 'rem', '%', 'custom'],
				'selectors' => [
					'{{WRAPPER}} .bt-megamenu-dropdown .elementor' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'megamenu_box_shadow',
				'selector' => '{{WRAPPER}} .bt-megamenu-dropdown .elementor',
			]
		);

		$this->end_controls_section();
	}

	protected function register_controls()
	{
		$this->register_content_section_controls();
		$this->register_style_content_section_controls();
	}

	/**
	 * Create custom Walker class for mega menu
	 *
	 * @param array $settings Widget settings.
	 * @return \Walker_Nav_Menu Custom walker instance
	 */
	private function create_megamenu_walker($settings = array())
	{
		$submenu_indicator = isset($settings['submenu_indicator']) && ! empty($settings['submenu_indicator']['value']) ? $settings['submenu_indicator'] : null;

		return new class($submenu_indicator) extends \Walker_Nav_Menu {
			private $parent_items = array();
			private $submenu_indicator = null;

			public function __construct($submenu_indicator = null)
			{
				$this->submenu_indicator = $submenu_indicator;
			}

			public function start_lvl(&$output, $depth = 0, $args = null)
			{
				// Check if parent item (at depth - 1) has mega menu enabled
				$parent_depth = $depth - 1;
				if ($parent_depth >= 0 && isset($this->parent_items[$parent_depth])) {
					$parent_item_id = $this->parent_items[$parent_depth];
					$megamenu_enabled = get_post_meta($parent_item_id, '_freska_megamenu_enabled', true);
					$megamenu_id = get_post_meta($parent_item_id, '_freska_megamenu_id', true);

					// Validate block exists and is published
					if ($megamenu_id) {
						$block_status = get_post_status($megamenu_id);
						$block_type = get_post_type($megamenu_id);
						if ($block_status !== 'publish' || $block_type !== 'megamenu') {
							$megamenu_id = false;
						}
					}

					// If parent has mega menu enabled with valid block, don't render sub-menu
					if ($megamenu_enabled === '1' && $megamenu_id) {
						return;
					}
				}

				$indent = str_repeat("\t", $depth);
				$output .= "\n$indent<ul class=\"sub-menu\">\n";
			}

			public function end_lvl(&$output, $depth = 0, $args = null)
			{
				// Check if parent item (at depth - 1) has mega menu enabled
				$parent_depth = $depth - 1;
				if ($parent_depth >= 0 && isset($this->parent_items[$parent_depth])) {
					$parent_item_id = $this->parent_items[$parent_depth];
					$megamenu_enabled = get_post_meta($parent_item_id, '_freska_megamenu_enabled', true);
					$megamenu_id = get_post_meta($parent_item_id, '_freska_megamenu_id', true);

					// Validate block exists and is published
					if ($megamenu_id) {
						$block_status = get_post_status($megamenu_id);
						$block_type = get_post_type($megamenu_id);
						if ($block_status !== 'publish' || $block_type !== 'megamenu') {
							$megamenu_id = false;
						}
					}

					// If parent has mega menu enabled with valid block, don't render sub-menu
					if ($megamenu_enabled === '1' && $megamenu_id) {
						return;
					}
				}

				$indent = str_repeat("\t", $depth);
				$output .= "$indent</ul>\n";
			}

			public function start_el(&$output, $item, $depth = 0, $args = null, $id = 0)
			{
				if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
					$t = '';
					$n = '';
				} else {
					$t = "\t";
					$n = "\n";
				}
				$indent = ($depth) ? str_repeat($t, $depth) : '';

				$classes = empty($item->classes) ? array() : (array) $item->classes;
				$classes[] = 'menu-item-' . $item->ID;

				// Check if mega menu is enabled for this item (only for depth 0)
				$megamenu_enabled = false;
				$megamenu_id = false;
				$megamenu_content_width = 'full-width';
				$megamenu_horizontal_position = 'default';

				// Get icon menu data for all depths
				$icon_enabled = get_post_meta($item->ID, '_freska_icon_enabled', true);
				$icon_svg_url = get_post_meta($item->ID, '_freska_icon_svg_url', true);

				// Add class if custom icon is enabled
				if ($icon_enabled === '1' && !empty($icon_svg_url)) {
					$classes[] = 'has-custom-icon';
				}

				// Get label menu data for all depths
				$label_enabled = get_post_meta($item->ID, '_freska_label_enabled', true);
				$label_text = get_post_meta($item->ID, '_freska_label_text', true);
				$label_color = get_post_meta($item->ID, '_freska_label_color', true);
				if (empty($label_color)) {
					$label_color = '#00706e';
				}

				if ($depth === 0) {
					$megamenu_enabled = get_post_meta($item->ID, '_freska_megamenu_enabled', true);
					$megamenu_id = get_post_meta($item->ID, '_freska_megamenu_id', true);
					$megamenu_content_width = get_post_meta($item->ID, '_freska_megamenu_content_width', true);
					if (empty($megamenu_content_width)) {
						$megamenu_content_width = 'full-width';
					}
					$megamenu_horizontal_position = get_post_meta($item->ID, '_freska_megamenu_horizontal_position', true);
					if (empty($megamenu_horizontal_position)) {
						$megamenu_horizontal_position = 'default';
					}

					// Validate block exists and is published
					if ($megamenu_id) {
						$block_status = get_post_status($megamenu_id);
						$block_type = get_post_type($megamenu_id);
						if ($block_status !== 'publish' || $block_type !== 'megamenu') {
							$megamenu_id = false;
						}
					}

					// Only add class if mega menu is enabled AND has valid block
					if ($megamenu_enabled === '1' && $megamenu_id) {
						$classes[] = 'menu-item-has-megamenu';
					}

					// Store parent item ID for children to check
					$this->parent_items[$depth] = $item->ID;
				} else {
					// Store parent item ID for this depth
					$this->parent_items[$depth] = $item->ID;
				}

				$args = apply_filters('nav_menu_item_args', $args, $item, $depth);

				$class_names = join(' ', apply_filters('nav_menu_css_class', array_filter($classes), $item, $args, $depth));
				$class_names = $class_names ? ' class="' . esc_attr($class_names) . '"' : '';

				$id = apply_filters('nav_menu_item_id', 'menu-item-' . $item->ID, $item, $args, $depth);
				$id = $id ? ' id="' . esc_attr($id) . '"' : '';

				$output .= $indent . '<li' . $id . $class_names . '>';

				$atts = array();
				$atts['title']  = ! empty($item->attr_title) ? $item->attr_title : '';
				$atts['target'] = ! empty($item->target)     ? $item->target     : '';
				$atts['rel']    = ! empty($item->xfn)        ? $item->xfn        : '';
				$atts['href']   = ! empty($item->url)        ? $item->url        : '';

				$atts = apply_filters('nav_menu_link_attributes', $atts, $item, $args, $depth);

				$attributes = '';
				foreach ($atts as $attr => $value) {
					if (! empty($value)) {
						$value = ('href' === $attr) ? esc_url($value) : esc_attr($value);
						$attributes .= ' ' . $attr . '="' . $value . '"';
					}
				}

				$title = apply_filters('the_title', $item->title, $item->ID);
				$title = apply_filters('nav_menu_item_title', $title, $item, $args, $depth);

				// Add custom icon to title if enabled
				$custom_icon_html = '';
				if ($icon_enabled === '1' && !empty($icon_svg_url)) {
					$is_svg = false;

					// Check if the image is SVG
					if (pathinfo($icon_svg_url, PATHINFO_EXTENSION) === 'svg') {
						$is_svg = true;
					}

					if ($is_svg) {
						// Render SVG content directly
						$response = wp_safe_remote_get($icon_svg_url, array(
							'timeout' => 20,
							'headers' => array(
								'User-Agent' => 'Mozilla/5.0 (compatible; WordPress)',
							),
						));
						if (!is_wp_error($response)) {
							$custom_icon_html = '<span class="bt-menu-icon">' . wp_remote_retrieve_body($response) . '</span>';
						}
					}
				}

				// Add label to title if enabled
				$label_html = '';
				if ($label_enabled === '1' && !empty($label_text)) {
					$label_html = '<span class="bt-menu-label" style="--background-color: ' . esc_attr($label_color) . ';">' . esc_html($label_text) . '</span>';
				}

				$show_indicator = $this->submenu_indicator && (
					($depth === 0 && $megamenu_enabled === '1' && $megamenu_id) ||
					in_array('menu-item-has-children', $classes, true)
				);
				$submenu_indicator_html = '';
				if ($show_indicator) {
					$indicator_icon_html = Icons_Manager::try_get_icon_html($this->submenu_indicator, ['aria-hidden' => 'true']);
					if ($indicator_icon_html) {
						$submenu_indicator_html = '<span class="bt-submenu-indicator">' . $indicator_icon_html . '</span>';
					}
				}

				$item_output = isset($args->before) ? $args->before : '';
				$item_output .= '<a' . $attributes . '>';
				$item_output .= (isset($args->link_before) ? $args->link_before : '') . $custom_icon_html . $title . $label_html . (isset($args->link_after) ? $args->link_after : '');
				$item_output .= $submenu_indicator_html;
				$item_output .= '</a>';

				// Add mega menu dropdown ONLY if enabled AND has valid block (depth 0 only)
				if ($depth === 0 && $megamenu_enabled === '1' && $megamenu_id) {
					$content_width_class = 'bt-megamenu-' . esc_attr($megamenu_content_width);
					$horizontal_position_class = '';
					if ($megamenu_content_width === 'fit-to-content' && in_array($megamenu_horizontal_position, array('left', 'center', 'center-to-item', 'right'), true)) {
						$horizontal_position_class = 'bt-megamenu-horizontal-' . esc_attr($megamenu_horizontal_position);
					}
					$dropdown_classes = trim('bt-megamenu-dropdown ' . $content_width_class . ' ' . $horizontal_position_class);
					$item_output .= '<div class="' . esc_attr($dropdown_classes) . '">';
					if (function_exists('freska_display_megamenu')) {
						ob_start();
						freska_display_megamenu($megamenu_id);
						$item_output .= ob_get_clean();
					}
					$item_output .= '</div>';
				}

				$item_output .= isset($args->after) ? $args->after : '';

				$output .= apply_filters('walker_nav_menu_start_el', $item_output, $item, $depth, $args);
			}

			public function end_el(&$output, $item, $depth = 0, $args = null)
			{
				// Remove parent item from tracking when ending
				if (isset($this->parent_items[$depth])) {
					unset($this->parent_items[$depth]);
				}

				if (isset($args->item_spacing) && 'discard' === $args->item_spacing) {
					$t = '';
					$n = '';
				} else {
					$t = "\t";
					$n = "\n";
				}
				$output .= "</li>{$n}";
			}
		};
	}

	protected function render()
	{
		$available_menus = $this->get_available_menus();

		if (! $available_menus) {
			return;
		}

		$settings = $this->get_active_settings();
		$walker = $this->create_megamenu_walker($settings);

		$toggle_alignment = isset($settings['toggle_menu_alignment']) ? $settings['toggle_menu_alignment'] : 'right';
		$menu_layout = isset($settings['menu_layout']) ? $settings['menu_layout'] : 'horizontal';
		$vertical_menu_width = isset($settings['vertical_menu_width']['size']) ? $settings['vertical_menu_width']['size'] : 0;
		$vertical_menu_edge_padding = isset($settings['vertical_menu_edge_padding']['size']) ? $settings['vertical_menu_edge_padding']['size'] : 15;
?>
		<div class="bt-elwg-megamenu--default bt-megamenu--layout-<?php echo esc_attr($menu_layout); ?> bt-megamenu-js-pending" data-menu-layout="<?php echo esc_attr($menu_layout); ?>" data-vertical-menu-width="<?php echo esc_attr($vertical_menu_width); ?>" data-vertical-menu-edge-padding="<?php echo esc_attr($vertical_menu_edge_padding); ?>">
			<button class="bt-megamenu-toggle bt-toggle-align-<?php echo esc_attr($toggle_alignment); ?>" aria-label="<?php esc_attr_e('Toggle Menu', 'freska'); ?>" aria-expanded="false">
				<span class="bt-toggle-bar"></span>
				<span class="bt-toggle-bar"></span>
				<span class="bt-toggle-bar"></span>
			</button>
			<div class="bt-megamenu-wrapper">
				<?php
				wp_nav_menu(
					array(
						'menu' 				=> $settings['menu'],
						'container_class' 	=> 'bt-megamenu-container',
						'menu_class' 		=> 'bt-megamenu',
						'items_wrap'      	=> '<ul id="%1$s" class="%2$s">%3$s</ul>',
						'fallback_cb'     	=> false,
						'theme_location' 	=> '',
						'walker'			=> $walker,
						'link_before'		=> '<span>',
						'link_after'		=> '</span>',
					)
				);
				?>
			</div>
		</div>
<?php
	}

	protected function content_template() {}
}
