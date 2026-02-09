<?php
namespace BlogFoel;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use BlogFoel\Notices;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class BLOGFOELPostPagination extends \Elementor\Widget_Base {
	
	private $single_pagination_wrapper = 'blogfoel-single-post-pagination-wrapper';
	private $single_pagi = 'blogfoel-single-post-pagination';
	private $single_pagi_icon = 'blogfoel-single-post-pagination-icon';

	public function get_name() {
		return 'blognews-post-pagination';
	}

	public function get_title() {
		return esc_html__( 'BN Post Pagination', 'blognews-for-elementor' );
	}

	public function get_icon() {
		return 'blogfoel-widget-icon bnicon-post-pagination';
	}

	public function get_categories() {
		return [ 'blogfoel-sng-elementor' ];
	}

	public function get_keywords() {
		return [ 'post-pagination', 'post', 'pagination' , 'post pagination'];
	}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_post_categories',
			[
				'label' => esc_html__( 'General', 'blognews-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'pagination_icons',
			[
				'label'       => esc_html__( 'Pagination Icon', 'blognews-for-elementor' ),
				'placeholder' => esc_html__( 'Choose Icon from Here', 'blognews-for-elementor' ),
				'type'        => 'blogfoel-select',
				'label_block' => false,
				'default'     => 'fas fa-angle-double-',
				'options'     => [
					'fas fa-angle-' 			=> esc_html__( 'Angle', 'blognews-for-elementor' ),
					'fas fa-angle-double-' 		=> esc_html__( 'Double Angle', 'blognews-for-elementor' ),
					'fas fa-arrow-' 			=> esc_html__( 'Arrow', 'blognews-for-elementor' ),
					'blogfoel-pro-icon fas fa-long-arrow-alt-' 	=> esc_html__( 'Long Arrow (Pro)', 'blognews-for-elementor' ),
					'blogfoel-pro-icon fas fa-arrow-circle-'		=> esc_html__( 'Circle Arrow (Pro)', 'blognews-for-elementor' ),
					'blogfoel-pro-icon fas fa-arrow-alt-circle-' 	=> esc_html__( 'Circle Arrow 2 (Pro)', 'blognews-for-elementor' ),
					'blogfoel-pro-icon far fa-arrow-alt-circle-' 	=> esc_html__( 'Circle Arrow 3 (Pro)', 'blognews-for-elementor' ),
					'blogfoel-pro-icon fas fa-caret-' 			=> esc_html__( 'Caret (Pro)', 'blognews-for-elementor' ),
					'blogfoel-pro-icon fas fa-caret-square-' 		=> esc_html__( 'Square Caret (Pro)', 'blognews-for-elementor' ),
					'blogfoel-pro-icon far fa-caret-square-' 		=> esc_html__( 'Square Caret 2 (Pro)', 'blognews-for-elementor' ),
					'blogfoel-pro-icon fas fa-chevron-' 			=> esc_html__( 'Chevron (Pro)', 'blognews-for-elementor' ),
					'blogfoel-pro-icon fas fa-chevron-circle-' 	=> esc_html__( 'Circle Chevron (Pro)', 'blognews-for-elementor' ),
					'blogfoel-pro-icon fas fa-hand-point-' 		=> esc_html__( 'Hand Point (Pro)', 'blognews-for-elementor' ),
					'blogfoel-pro-icon far fa-hand-point-' 		=> esc_html__( 'Hand Point 2 (Pro)', 'blognews-for-elementor' ),
				],
			]
		);

		$this->add_control(
			'pagination_same_term',
			[
				'label' => esc_html__( 'Same Term', 'blognews-for-elementor' ),
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'blognews-for-elementor' ),
				'label_off' => esc_html__( 'No', 'blognews-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'no',
				'escape' => false,
			]
		);

		$this->add_responsive_control(
            'post_categories_align',
            [
                'label' => esc_html__( 'Alignment', 'blognews-for-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'left',
                'label_block' => false,
                'options' => [
					'left'    => [
						'title' => __( 'Left', 'blognews-for-elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'blognews-for-elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'blognews-for-elementor' ),
						'icon' => 'eicon-text-align-right',
					],
                ],
				'selectors' => [
					'{{WRAPPER}} .'. $this->single_pagi_icon => 'text-align: {{VALUE}}',
				],
            ]
        );

		$this->end_controls_section(); // End Controls Section
		Notices::go_premium_notice_content($this, 'notice_one');

		// Blog Category
		$this->start_controls_section(
			'single_blog_pagination_area_settings',
			[
				'label' => __( 'Pagination Area Settings ', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,   
				
			]
		);
		
		$slug = 'single_blog_pagination_area';

		$this->start_controls_tabs( $slug.'_tabs' );

		$this->start_controls_tab(
			$slug.'_normal_style',
			[
				'label' => __( 'Normal', 'blognews-for-elementor' ),
			]
		);
		
		$this->add_control(
			$slug.'_bg_color',
			[
				'label'     => __( 'Background Color', 'blognews-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .blogfoel-single-post-pagination .post-navigation' => 'background-color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => $slug.'_border_type',
				'label'    => 'Border Type',
				'selector' => '{{WRAPPER}}  .blogfoel-single-post-pagination .post-navigation',
			]
		);
		$this->add_responsive_control(
			$slug.'_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .blogfoel-single-post-pagination .post-navigation' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
	
		$this->add_responsive_control(
			$slug.'_padding',
			[
				'label' => esc_html__( 'Padding', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}}  .blogfoel-single-post-pagination .post-navigation' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			$slug.'_margin',
			[
				'label' => esc_html__( 'Margin', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}}  .blogfoel-single-post-pagination .post-navigation' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'      => $slug.'_box_shadow',
				'label'    => 'Box Shadow',
				'selector' => '{{WRAPPER}}  .blogfoel-single-post-pagination .post-navigation',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			$slug.'_style_hover',
			[
				'label' => __( 'Hover', 'blognews-for-elementor' ),

			]
		);
		
		$this->add_control(
			$slug.'_color_bg_hover',
			[
				'label'     => __( 'Background Color', 'blognews-for-elementor' ). BLOGFOEL_PRO_ICON,
				'type'      => Controls_Manager::COLOR,
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);

		$this->add_control(
			$slug . '_border_type_hover',
			[
				'label' => esc_html__( 'Border Type', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
				'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
				'label_off' => esc_html__( 'Default', 'blognews-for-elementor' ),
				'label_on' => esc_html__( 'Custom', 'blognews-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'no',
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);
		$this->start_popover();
		$this->end_popover();

		$this->add_responsive_control(
			$slug.'_border_radius_hover',
			[
				'label' => esc_html__( 'Border Radius', 'blognews-for-elementor' ). BLOGFOEL_PRO_ICON,
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);
	
		$this->add_responsive_control(
			$slug.'_padding_hover',
			[
				'label' => esc_html__( 'Padding', 'blognews-for-elementor' ). BLOGFOEL_PRO_ICON,
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);

		$this->add_responsive_control(
			$slug.'_margin_hover',
			[
				'label' => esc_html__( 'Margin', 'blognews-for-elementor' ). BLOGFOEL_PRO_ICON,
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);

		$this->add_control(
			$slug . '_box_shadow_hover',
			[
				'label' => esc_html__( 'Box Shadow', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
				'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
				'label_off' => esc_html__( 'Default', 'blognews-for-elementor' ),
				'label_on' => esc_html__( 'Custom', 'blognews-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'no',
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);
		$this->start_popover();
		$this->end_popover();

		$this->end_controls_tab();
		$this->end_controls_tabs(); 
		$this->end_controls_section();

		// Blog Category
		$this->start_controls_section(
			'single_blog_pagination_icon_settings',
			[
				'label' => __( 'Icon Settings ', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,   
				
			]
		);
		
		$slug = 'single_blog_pagination_icon';

		$this->start_controls_tabs( $slug.'_tabs' );

		$this->start_controls_tab(
			$slug.'_normal_style',
			[
				'label' => __( 'Normal', 'blognews-for-elementor' ),
			]
		);
		
		$this->add_control(
			$slug.'_color',
			[
				'label'     => __( 'Color', 'blognews-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}  .blogfoel-single-post-pagination .post-navigation .nav-previous a i:before' => 'color: {{VALUE}}',
					'{{WRAPPER}}  .blogfoel-single-post-pagination .post-navigation .nav-next a i:before' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			$slug.'_bg_color',
			[
				'label'     => __( 'Background Color', 'blognews-for-elementor' ). BLOGFOEL_PRO_ICON,
				'type'      => Controls_Manager::COLOR,
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);
		
		$this->add_responsive_control(
			$slug.'_icon_size',
			[
				'label'           => __( 'Icon Size', 'blognews-for-elementor' ),
				'type'            => Controls_Manager::SLIDER,
				'size_units'      => [ 'px', '%' ],
				'range'           => [
					'px' => [
						'min' => 0,
						'max' => 120,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'devices'         => [ 'desktop', 'tablet', 'mobile' ],
				'desktop_default' => [
					'size' => '',
					'unit' => 'px',
				],
				'tablet_default'  => [
					'size' => '',
					'unit' => 'px',
				],
				'mobile_default'  => [
					'size' => '',
					'unit' => 'px',
				],
				'selectors'       => [
					'{{WRAPPER}}  .blogfoel-single-post-pagination .post-navigation .nav-previous a i:before' => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}  .blogfoel-single-post-pagination .post-navigation .nav-next a i:before' => 'font-size: {{SIZE}}{{UNIT}}',
				
				],
			]
		);

		$this->add_control(
			$slug . '_border_type',
			[
				'label' => esc_html__( 'Border Type', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
				'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
				'label_off' => esc_html__( 'Default', 'blognews-for-elementor' ),
				'label_on' => esc_html__( 'Custom', 'blognews-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'no',
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);
		$this->start_popover();
		$this->end_popover();

		$this->add_responsive_control(
			$slug.'_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);
	
		$this->add_responsive_control(
			$slug.'_padding',
			[
				'label' => esc_html__( 'Padding', 'blognews-for-elementor' ). BLOGFOEL_PRO_ICON,
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);

		$this->add_responsive_control(
			$slug.'_margin',
			[
				'label' => esc_html__( 'Margin', 'blognews-for-elementor' ). BLOGFOEL_PRO_ICON,
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);

		$this->add_control(
			$slug . '_box_shadow',
			[
				'label' => esc_html__( 'Box Shadow', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
				'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
				'label_off' => esc_html__( 'Default', 'blognews-for-elementor' ),
				'label_on' => esc_html__( 'Custom', 'blognews-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'no',
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);
		$this->start_popover();
		$this->end_popover();

		$this->end_controls_tab();

		$this->start_controls_tab(
			$slug.'_style_hover',
			[
				'label' => __( 'Hover', 'blognews-for-elementor' ),

			]
		);
		
		$this->add_control(
			$slug.'_color_hover',
			[
				'label'     => __( 'Color', 'blognews-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}  .blogfoel-single-post-pagination .post-navigation .nav-previous a i:hover:before' => 'color: {{VALUE}}',
					'{{WRAPPER}}  .blogfoel-single-post-pagination .post-navigation .nav-next a i:hover:before' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			$slug.'_color_bg_hover',
			[
				'label'     => __( 'Background Color', 'blognews-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}  .blogfoel-single-post-pagination .post-navigation .nav-previous a i:hover:before' => 'background-color: {{VALUE}}',
					'{{WRAPPER}}  .blogfoel-single-post-pagination .post-navigation .nav-next a i:hover:before' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_responsive_control(
			$slug.'_hover_icon_size',
			[
				'label'           => __( 'Icon Size', 'blognews-for-elementor' ),
				'type'            => Controls_Manager::SLIDER,
				'size_units'      => [ 'px', '%' ],
				'range'           => [
					'px' => [
						'min' => 0,
						'max' => 120,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'devices'         => [ 'desktop', 'tablet', 'mobile' ],
				'desktop_default' => [
					'size' => '',
					'unit' => 'px',
				],
				'tablet_default'  => [
					'size' => '',
					'unit' => 'px',
				],
				'mobile_default'  => [
					'size' => '',
					'unit' => 'px',
				],
				'selectors'       => [
					'{{WRAPPER}}  .blogfoel-single-post-pagination .post-navigation .nav-previous a i:hover:before' => 'font-size: {{SIZE}}{{UNIT}}',
					'{{WRAPPER}}  .blogfoel-single-post-pagination .post-navigation .nav-next a i:hover:before' => 'font-size: {{SIZE}}{{UNIT}}',
				
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => $slug.'_border_type_hover',
				'label'    => 'Border Type',
				'selector' => '{{WRAPPER}} .blogfoel-single-post-pagination .post-navigation .nav-previous a i:hover:before, {{WRAPPER}}  .blogfoel-single-post-pagination .post-navigation .nav-next a i:hover:before',
			]
		);
		$this->add_responsive_control(
			$slug.'_border_radius_hover',
			[
				'label' => esc_html__( 'Border Radius', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}}  .blogfoel-single-post-pagination .post-navigation .nav-previous a i:hover:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}  .blogfoel-single-post-pagination .post-navigation .nav-next a i:hover:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
	
		$this->add_responsive_control(
			$slug.'_padding_hover',
			[
				'label' => esc_html__( 'Padding', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}}  .blogfoel-single-post-pagination .post-navigation .nav-previous a i:hover:before' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}  .blogfoel-single-post-pagination .post-navigation .nav-next a i:hover:before' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			$slug.'_margin_hover',
			[
				'label' => esc_html__( 'Margin', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}}  .blogfoel-single-post-pagination .post-navigation .nav-previous a i:hover:before' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}  .blogfoel-single-post-pagination .post-navigation .nav-next a i:hover:before' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'      => $slug.'_box_shadow_hover',
				'label'    => 'Box Shadow',
				'selector' => '{{WRAPPER}} .blogfoel-single-post-pagination .post-navigation .nav-previous a i:hover:before, {{WRAPPER}}  .blogfoel-single-post-pagination .post-navigation .nav-next a i:hover:before',
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs(); 
		$this->end_controls_section();

		// Blog Pagination Links
		$this->start_controls_section(
			'single_blog_pagination_links_settings',
			[
				'label' => __( 'Links Settings ', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,   
				
			]
		);
		
		$slug = 'single_blog_pagination_links';

		$this->start_controls_tabs( $slug.'_tabs' );

		$this->start_controls_tab(
			$slug.'_normal_style',
			[
				'label' => __( 'Normal', 'blognews-for-elementor' ),
			]
		);
		
		$this->add_control(
			$slug.'_color',
			[
				'label'     => __( 'Color', 'blognews-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .blogfoel-single-post-pagination .post-navigation .nav-previous a' => 'color: {{VALUE}}',
					'{{WRAPPER}} .blogfoel-single-post-pagination .post-navigation .nav-next a' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => $slug.'_typography',
                'label'     => __( 'Typography', 'blognews-for-elementor' ),
				'selector' => '{{WRAPPER}} .blogfoel-single-post-pagination .post-navigation .nav-previous a span, {{WRAPPER}} .blogfoel-single-post-pagination .post-navigation .nav-next a span',
			]
		);

		$this->add_responsive_control(
			$slug.'_margin',
			[
				'label' => esc_html__( 'Margin', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .blogfoel-single-post-pagination .post-navigation .nav-previous a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .blogfoel-single-post-pagination .post-navigation .nav-next a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			$slug.'_style_hover',
			[
				'label' => __( 'Hover', 'blognews-for-elementor' ),

			]
		);

		$this->add_control(
			$slug.'_color_hover',
			[
				'label'     => __( 'Color', 'blognews-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .blogfoel-single-post-pagination .post-navigation .nav-previous a:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .blogfoel-single-post-pagination .post-navigation .nav-next a:hover' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => $slug.'_typography_hover',
                'label'     => __( 'Typography', 'blognews-for-elementor' ),
				'selector' => '{{WRAPPER}} .blogfoel-single-post-pagination .post-navigation .nav-previous a span:hover, {{WRAPPER}} .blogfoel-single-post-pagination .post-navigation .nav-next a span:hover',
			]
		);

		$this->add_responsive_control(
			$slug.'_margin_hover',
			[
				'label' => esc_html__( 'Margin', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}}  .blogfoel-single-post-pagination .post-navigation .nav-previous a:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}}  .blogfoel-single-post-pagination .post-navigation .nav-next a:hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs(); 
		$this->end_controls_section();
		
		Notices::go_premium_notice_style($this, 'notice_two');
	}

	protected function render() {
		$settings = $this->get_settings();
		$current_url = $_SERVER['REQUEST_URI'];
		if ( ( class_exists( "\Elementor\Plugin" ) && \Elementor\Plugin::$instance->editor->is_edit_mode() ) ||  ( class_exists( "\Elementor\Plugin" ) && isset( $_GET['preview'] ) && isset( $_GET['preview_id'] ) && $_GET['preview'] == true ) || ( strpos($current_url, 'blogfoel-hf-builder') !== false && get_post_type() == 'blogfoel-hf-builder' ) ) {
			$post_id = get_the_ID();
        	$post_id = \Elementor\Plugin::$instance->documents->get($post_id, false)->get_settings('blogfoel_demo_post_id');
            $post = get_post( $post_id );
            if ( ! $post ) {
                return;
            }
        }else{
            $post_id = get_the_ID();
            $post = get_post($post_id);
            if ( ! $post ) {
                return;
            }
		} 
		
		$left_icon = $settings['pagination_icons'].'left';
		$right_icon = $settings['pagination_icons'].'right';

		$wp_query = new \WP_Query(array('p' => $post_id)); // Create a new query for the single post
		
		$args = array(
			'prev_text' => '<i class="'.esc_attr($left_icon).'"></i><span>%title</span>',
			'next_text' => '<span>%title</span><i class="'.esc_attr($right_icon).'"></i>',
		);
		
		if($settings['pagination_same_term'] === 'yes'){
			$args['in_same_term'] = true;
		}else{ 
			$args['in_same_term'] = false;
		}

		if($post_id !== "") { ?>
		<div class="blogfoel-single-post-pagination <?php echo esc_attr($this->single_pagination_wrapper)?>"><?php 
			// Display the post navigation for the single post
			if ($wp_query->have_posts()) {
				while ($wp_query->have_posts()) {
					$wp_query->the_post();
					the_post_navigation( $args );
					wp_link_pages(array(
							'before' => '<div class="single-nav-links">',
							'after' => '</div>',
					));
				}
			}
			// Reset the custom query
			wp_reset_postdata();
		?></div><?php
		}
	}
}