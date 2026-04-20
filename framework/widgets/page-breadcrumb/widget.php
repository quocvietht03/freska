<?php

namespace FreskaElementorWidgets\Widgets\PageBreadcrumb;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

class Widget_PageBreadcrumb extends Widget_Base
{

	public function get_name()
	{
		return 'bt-page-breadcrumb';
	}

	public function get_title()
	{
		return __('Page Breadcrumb', 'freska');
	}

	public function get_icon()
	{
		return 'bt-bears-icon eicon-integration';
	}

	public function get_categories()
	{
		return ['freska'];
	}

	protected function register_content_section_controls() {}

	protected function register_style_content_section_controls()
	{

		$this->start_controls_section(
			'section_style_content',
			[
				'label' => esc_html__('Content', 'freska'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => __('Icon Color', 'freska'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-page-breadcrumb .bt-deli svg path' => 'stroke: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'home_text_color',
			[
				'label' => __('Home Text Color', 'freska'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-page-breadcrumb a.bt-home' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'home_text_color_hover',
			[
				'label' => __('Home Text Color Hover', 'freska'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-page-breadcrumb a.bt-home:hover' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_control(
			'current_text_color',
			[
				'label' => __('Current Page Text Color', 'freska'),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .bt-page-breadcrumb .current' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_typography',
				'label' => __('Text Typography', 'freska'),
				'default' => '',
				'selector' => '{{WRAPPER}} .bt-page-breadcrumb, {{WRAPPER}} .bt-page-breadcrumb a.bt-home',
			]
		);

		$this->end_controls_section();
	}

	protected function register_controls()
	{
		$this->register_content_section_controls();
		$this->register_style_content_section_controls();
	}

	protected function render()
	{
		$settings = $this->get_settings_for_display();

?>
		<div class="bt-elwg-page-breadcrumb">
			<div class="bt-page-breadcrumb">
				<?php
				$home_text = esc_html__('Homepage', 'freska');
				$delimiter = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                <g clip-path="url(#clip0_4145_9660)">
                                    <path d="M3.125 10H16.875" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M11.25 4.375L16.875 10L11.25 15.625" stroke-linecap="round" stroke-linejoin="round"/>
                                </g>
                                <defs>
                                    <clipPath id="clip0_4145_9660">
                                    <rect width="20" height="20" fill="white"/>
                                    </clipPath>
                                </defs>
                                </svg>';
				echo freska_page_breadcrumb($home_text, $delimiter);
				?>
			</div>
		</div>
<?php
	}

	protected function content_template() {}
}
