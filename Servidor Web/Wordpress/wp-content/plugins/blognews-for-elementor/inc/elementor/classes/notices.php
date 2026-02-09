<?php

namespace BlogFoel;

if (!defined('ABSPATH')) {
    exit;
} // Exit if accessed directly

use \Elementor\Controls_Manager;

class Notices
{
    public static function go_premium_notice_content($obj, $id)
    {   
        $obj->start_controls_section(
			$id.'_blogfoel_section_pro',
			[
				'label' => esc_html__('Go Premium for More Features', 'blognews-for-elementor'),
                'tab'   => Controls_Manager::TAB_CONTENT,
                'classes' => 'blogfoel-section-pro',
			]
		);

		$obj->add_control(
			$id.'_blogfoel_control_get_pro',
			[
				'label'       => esc_html__('Unlock more Features and Controls', 'blognews-for-elementor'),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => [
					'1' => [
						'title' => '',
						'icon' => 'eicon-lock',
					],
				],
				'default'     => '1',
				'description' => BLOGFOEL_GO_PRO_HTML,
			]
		);

		$obj->end_controls_section();
    }
        public static function go_premium_notice_style($obj, $id)
    {   
        $obj->start_controls_section(
			$id.'_blogfoel_section_pro',
			[
				'label' => esc_html__('Go Premium for More Features', 'blognews-for-elementor'),
                'tab'   => Controls_Manager::TAB_STYLE,
                'classes' => 'blogfoel-section-pro',
			]
		);

		$obj->add_control(
			$id.'_blogfoel_control_get_pro',
			[
				'label'       => esc_html__('Unlock more Features and Controls', 'blognews-for-elementor'),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => [
					'1' => [
						'title' => '',
						'icon' => 'eicon-lock',
					],
				],
				'default'     => '1',
				'description' => BLOGFOEL_GO_PRO_HTML,
			]
		);

		$obj->end_controls_section();
    }
}