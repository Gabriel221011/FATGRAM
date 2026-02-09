<?php

use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

//  Category Controls

function blogfoel_category_style_control( $obj, $slug, $blog_category ){

	$obj->add_responsive_control(
		$slug.'_icon_position',
		[
			'label'     => __( 'Icon Position', 'blognews-for-elementor' ). BLOGFOEL_PRO_ICON,
			'type'      => Controls_Manager::CHOOSE,
			'options'   => [
				'row'   => [
					'title' => __( 'Left', 'blognews-for-elementor' ),
					'icon'  => 'eicon-h-align-left',
				],
				'row-reverse'  => [
					'title' => __( 'Right', 'blognews-for-elementor' ),
					'icon'  => 'eicon-h-align-right',
				],
			],
			'default'   => '',
			'classes' => 'blogfoel-pro-popup-notice',
		]
	);
	$obj->add_responsive_control(
		$slug.'_icon_gap',
		[
			'label' => esc_html__( 'Gap', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
			'type' => \Elementor\Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
			'range' => [
				'px'  => ['min' => 0, 'max' => 100],
				'em'  => ['min' => 0, 'max' => 100],
				'rem' => ['min' => 0, 'max' => 100],
			],
			'devices' => [ 'desktop', 'tablet', 'mobile' ],
			'default' => ['size' => '', 'unit' => 'px'],
			'tablet_default'  => ['size' => '', 'unit' => 'px'],
			'mobile_default'  => ['size' => '', 'unit' => 'px'], 
			'classes' => 'blogfoel-pro-popup-notice',
		]
	);
	$obj->add_responsive_control(
		$slug.'_icon_size',
		[
			'label' => esc_html__( 'Font Size', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
			'type' => Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
			'range' => [
				'px'  => ['min' => 0, 'max' => 100],
				'em'  => ['min' => 0, 'max' => 100],
				'rem' => ['min' => 0, 'max' => 100],
			],
			'default_desktop' => ['size' => '', 'unit' => 'px'],
			'tablet_default'  => ['size' => '', 'unit' => 'px'],
			'mobile_default'  => ['size' => '', 'unit' => 'px'],
			'classes' => 'blogfoel-pro-popup-notice',
		]
	);
	
	$obj->start_controls_tabs( $slug.'_style_tabs' );

	$obj->start_controls_tab(
		$slug.'_normal_style',
		[
			'label' => __( 'Normal', 'blognews-for-elementor' ),
		]
	);
	$obj->add_control(
		$slug.'_color',
		[
			'label' => esc_html__( 'Color', 'blognews-for-elementor' ),
			'type' => \Elementor\Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .'.$blog_category.' a' => 'color: {{VALUE}};',
			],
		]
	);
	
	$obj->add_group_control(
		\Elementor\Group_Control_Background::get_type(),
		[
			'name'      => $slug.'_bg_color',
			'types'          => [ 'classic', 'gradient' ],
			'exclude'        => [ 'image' ],
			'fields_options' => [
				'background' => [
					'label' => esc_html__( 'Background Color', 'blognews-for-elementor' ),
					'default' => 'classic',
				],
			],
			'condition' => [
				'category_style!' => 'two',
			],
			'selector'  => '{{WRAPPER}} .'.$blog_category.' a',
		]
	);
	$obj->add_control(
		$slug.'_icon_color',
		[
			'label' => esc_html__( 'Icon Color', 'blognews-for-elementor' ). BLOGFOEL_PRO_ICON,
			'type' => \Elementor\Controls_Manager::COLOR,
			'classes' => 'blogfoel-pro-popup-notice',
		]
	);

	$obj->end_controls_tab();

	$obj->start_controls_tab(
		$slug.'_hover_style',
		[
			'label' => __( 'Hover', 'blognews-for-elementor' ),

		]
	);
	$obj->add_control(
		$slug.'_color_hover',
		[
			'label' => esc_html__( 'Color', 'blognews-for-elementor' ),
			'type' => \Elementor\Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .'.$blog_category.' a:hover' => 'color: {{VALUE}};',
			],
		]
	);
	$obj->add_group_control(
		\Elementor\Group_Control_Background::get_type(),
		[
			'name'      => $slug.'_bg_color_hover',
			'types'          => [ 'classic', 'gradient' ],
			'exclude'        => [ 'image' ],
			'fields_options' => [
				'background' => [
					'label' => esc_html__( 'Background Color', 'blognews-for-elementor' ),
					'default' => 'classic',
				],
			],
			'condition' => [
				'category_style!' => 'two',
			],
			'selector'  => '{{WRAPPER}} .'.$blog_category.' a:hover',
		]
	);
	$obj->add_control(
		$slug.'_icon_hover_color',
		[
			'label' => esc_html__( 'Icon Color', 'blognews-for-elementor' ). BLOGFOEL_PRO_ICON,
			'type' => \Elementor\Controls_Manager::COLOR,
			'classes' => 'blogfoel-pro-popup-notice',
		]
	);
	
	$obj->end_controls_tab();
	$obj->end_controls_tabs();

	$obj->add_control(
		$slug.'_hr',
		[
			'type' => \Elementor\Controls_Manager::DIVIDER,
		]
	);
	$obj->add_group_control(
		\Elementor\Group_Control_Typography::get_type(),
		[
			'name'     => $slug.'_typography',
			'label'     => __( 'Typography', 'blognews-for-elementor' ),
			'selector' => '{{WRAPPER}} .'.$blog_category.' a',
		]
	);
	$obj->add_group_control(
		Group_Control_Border::get_type(),
		[
			'name'     => $slug.'_border_type',
			'label'    => 'Border Type',
			'selector' => '{{WRAPPER}}  .'.$blog_category.' a',
		]
	);
	$obj->add_responsive_control(
		$slug.'_border_radius',
		[
			'label' => esc_html__( 'Border Radius', 'blognews-for-elementor' ),
			'type' => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
			'selectors' => [
				'{{WRAPPER}} .'.$blog_category.' a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);
	$obj->add_responsive_control(
		$slug.'_padding',
		[
			'label' => esc_html__( 'Padding', 'blognews-for-elementor' ). BLOGFOEL_PRO_ICON,
			'type' => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
			'classes' => 'blogfoel-pro-popup-notice',
		]
	);
	$obj->add_responsive_control(
		$slug.'_margin',
		[
			'label' => esc_html__( 'Margin', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
			'type' => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
			'classes' => 'blogfoel-pro-popup-notice',
		]
	);
	$obj->add_control(
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
	$obj->start_popover();
	
	$obj->end_popover();

}
//  End Category Controls

//  Title Controls
function blogfoel_title_style_control( $obj, $slug, $blog_title ){
	
	$obj->add_control(
		$slug.'_animation_type',
		[
			'label'     => __( 'Animation', 'blognews-for-elementor' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => [
				'custom'   => [
					'title' => __( 'Custom', 'blognews-for-elementor' ),
					'icon'  => 'eicon-click',
				],
				'elementor'  => [
					'title' => __( 'Elementor', 'blognews-for-elementor' ),
					'icon'  => 'eicon-elementor',
				],
			],
			'default'   => 'custom',
			'label_block'   =>  true,
		]
	);
	$obj->add_control(
		$slug.'_custom_animation',
		[
			'label'       => esc_html__( 'Animation', 'blognews-for-elementor' ),
			'type'        => 'blogfoel-select',
			'label_block' => false,
			'default'     => 'animate-one',
			'options'     => [
				'animate-none'  => esc_html__( 'None', 'blognews-for-elementor' ),
				'animate-one'   => esc_html__( 'Effect 1', 'blognews-for-elementor' ),
				'blogfoel-pro-animate-two'   => esc_html__( 'Effect 2 (Pro)', 'blognews-for-elementor' ),
				'blogfoel-pro-animate-three' => esc_html__( 'Effect 3 (Pro)', 'blognews-for-elementor' ),
				'blogfoel-pro-animate-four'  => esc_html__( 'Effect 4 (Pro)', 'blognews-for-elementor' ),
				'blogfoel-pro-animate-five'  => esc_html__( 'Effect 5 (Pro)', 'blognews-for-elementor' ),
				'blogfoel-pro-animate-six'   => esc_html__( 'Effect 6 (Pro)', 'blognews-for-elementor' ),
			],
			'condition' => [
				$slug.'_animation_type' => 'custom'
			],
		]
	);

	$obj->add_control(
		$slug.'_elementor_animation',
		[
			'label'       => esc_html__( 'Animation', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
			'type' => 'blogfoel-select',
			'label_block' => false,
			'default'     => 'none',
			'options'     => [
				'none'  => esc_html__( 'None', 'blognews-for-elementor' ),
			],
			'condition' => [
				$slug.'_animation_type!' => 'custom'
			],
			'classes' => 'blogfoel-pro-popup-notice',
		]
	);

	$obj->start_controls_tabs( $slug.'_style_tabs' );

	$obj->start_controls_tab(
		$slug.'_normal_style',
		[
			'label' => __( 'Normal', 'blognews-for-elementor' ),
		]
	);
	$obj->add_control(
		$slug.'_color',
		[
			'label' => esc_html__( 'Color', 'blognews-for-elementor' ),
			'type' => \Elementor\Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .'.$blog_title => 'color: {{VALUE}};',
			],
		]
	);
	$obj->end_controls_tab();

	$obj->start_controls_tab(
		$slug.'_hover_style',
		[
			'label' => __( 'Hover', 'blognews-for-elementor' ),
		]
	);
	$obj->add_control(
		$slug.'_color_hover',
		[
			'label' => esc_html__( 'Color', 'blognews-for-elementor' ),
			'type' => \Elementor\Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .'.$blog_title.' a:hover' => 'color: {{VALUE}};',
				'{{WRAPPER}}' => '--title-hover-color: {{VALUE}};',
			],
		]
	);
	
	$obj->end_controls_tab();
	$obj->end_controls_tabs();

	$obj->add_control(
		$slug.'_hr',
		[
			'type' => \Elementor\Controls_Manager::DIVIDER,
		]
	);
	$obj->add_control(
		$slug.'_html_tag',
		[
			'label'       => esc_html__( 'Title Html Tag', 'blognews-for-elementor' ),
			'placeholder' => esc_html__( 'Choose Tag to Include', 'blognews-for-elementor' ),
			'type'        => \Elementor\Controls_Manager::SELECT,
			'default'     => 'h4',
			'options'     => [
				'h1' => esc_html__( 'H1', 'blognews-for-elementor' ),
				'h2' => esc_html__( 'H2', 'blognews-for-elementor' ),
				'h3' => esc_html__( 'H3', 'blognews-for-elementor' ),
				'h4' => esc_html__( 'H4', 'blognews-for-elementor' ),
				'h5' => esc_html__( 'H5', 'blognews-for-elementor' ),
				'h6' => esc_html__( 'H6', 'blognews-for-elementor' ),
			],
		]
	);
	$obj->add_responsive_control(
		$slug.'_align',
		[
			'label'     => __( 'Alignment', 'blognews-for-elementor' ),
			'type'      => Controls_Manager::CHOOSE,
			'options'   => [
				'left'   => [
					'title' => __( 'Left', 'blognews-for-elementor' ),
					'icon'  => 'eicon-text-align-left',
				],
				'center' => [
					'title' => __( 'Center', 'blognews-for-elementor' ),
					'icon'  => 'eicon-text-align-center',
				],
				'right'  => [
					'title' => __( 'Right', 'blognews-for-elementor' ),
					'icon'  => 'eicon-text-align-right',
				],
			],
			'default'   => '',
			'selectors' => [
				'{{WRAPPER}} .'.$blog_title => 'text-align: {{VALUE}}',
			],
		]
	);
	
	$obj->add_group_control(
		\Elementor\Group_Control_Typography::get_type(),
		[
			'name'     => $slug.'_typography',
			'label'     => __( 'Typography', 'blognews-for-elementor' ),
			'selector' => '{{WRAPPER}} .'.$blog_title,
		]
	);

	$obj->add_responsive_control(
		$slug.'_margin',
		[
			'label' => esc_html__( 'Margin', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
			'type' => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
			'classes' => 'blogfoel-pro-popup-notice',
		]
	);
}

//  End Title Controls

//  Meta Controls
function blogfoel_meta_style_control( $obj, $slug, $blog_meta ){

	$obj->add_responsive_control(
		$slug.'_wrapper_col_gap',
		[
			'label' => esc_html__( 'Wrapper Colunm Gap', 'blognews-for-elementor' ),
			'type' => \Elementor\Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
			'range' => [
				'px'  => ['min' => 0, 'max' => 100],
				'em'  => ['min' => 0, 'max' => 100],
				'rem' => ['min' => 0, 'max' => 100],
			],
			'devices'         => [ 'desktop', 'tablet', 'mobile' ],
			'default'         => ['size' => '', 'unit' => 'px'],
			'tablet_default'  => ['size' => '', 'unit' => 'px'],
			'mobile_default'  => ['size' => '', 'unit' => 'px'],
			'selectors'       => [
				'{{WRAPPER}} .'.$blog_meta => 'column-gap : {{SIZE}}{{UNIT}};',
			],
		]
	);
	$obj->add_responsive_control(
		$slug.'_wrapper_row_gap',
		[
			'label' => esc_html__( 'Wrapper Row Gap', 'blognews-for-elementor' ),
			'type' => \Elementor\Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
			'range' => [
				'px'  => ['min' => 0, 'max' => 100],
				'em'  => ['min' => 0, 'max' => 100],
				'rem' => ['min' => 0, 'max' => 100],
			],
			'devices'         => [ 'desktop', 'tablet', 'mobile' ],
			'default'         => ['size' => '', 'unit' => 'px'],
			'tablet_default'  => ['size' => '', 'unit' => 'px'],
			'mobile_default'  => ['size' => '', 'unit' => 'px'],
			'selectors'       => [
				'{{WRAPPER}} .'.$blog_meta => 'row-gap: {{SIZE}}{{UNIT}};',
			],
		]
	);
	$obj->start_controls_tabs( $slug.'_style_tabs' );

	$obj->start_controls_tab(
		$slug.'_normal_style',
		[
			'label' => __( 'Normal', 'blognews-for-elementor' ),
		]
	);
	$obj->add_control(
		$slug.'_color',
		[
			'label' => esc_html__( 'Color', 'blognews-for-elementor' ),
			'type' => \Elementor\Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .'.$blog_meta.' a' => 'color: {{VALUE}} !important;',
			],
		]
	);

	$obj->add_control(
		$slug.'_icon_color',
		[
			'label' => esc_html__( 'Icon Color', 'blognews-for-elementor' ),
			'type' => \Elementor\Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .'.$blog_meta.' i' => 'color: {{VALUE}};',
				'{{WRAPPER}} .'.$blog_meta.' span svg' => 'fill: {{VALUE}};',
			],
		]
	);
	$obj->end_controls_tab();

	$obj->start_controls_tab(
		$slug.'_hover_style',
		[
			'label' => __( 'Hover', 'blognews-for-elementor' ),
		]
	);
	
	$obj->add_control(
		$slug.'_hover_color',
		[
			'label' => esc_html__( 'Color', 'blognews-for-elementor' ),
			'type' => \Elementor\Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .'.$blog_meta.' a:hover' => 'color: {{VALUE}} !important;',
			],
		]
	);
	$obj->add_control(
		$slug.'_icon_hover_color',
		[
			'label' => esc_html__( 'Icon Color', 'blognews-for-elementor' ),
			'type' => \Elementor\Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .'.$blog_meta.' span:hover i' => 'color: {{VALUE}};',
				'{{WRAPPER}} .'.$blog_meta.' span:hover svg' => 'fill: {{VALUE}};',
			],
		]
	);	
	$obj->end_controls_tab();
	$obj->end_controls_tabs();

	$obj->add_control(
		$slug.'_hr',
		[
			'type' => \Elementor\Controls_Manager::DIVIDER,
		]
	);
	$obj->add_group_control(
		\Elementor\Group_Control_Typography::get_type(),
		[
			'name'     =>  $slug.'_typography',
			'label'     => __( 'Typography', 'blognews-for-elementor' ),
			'selector' => '{{WRAPPER}} .'.$blog_meta.' span',
		]
	);
	$obj->add_responsive_control(
		$slug.'_size',
		[
			'label' => esc_html__( 'Icon Size', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
			'type' => \Elementor\Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
			'range' => [
				'px'  => ['min' => 0, 'max' => 100],
				'em'  => ['min' => 0, 'max' => 100],
				'rem' => ['min' => 0, 'max' => 100],
			],
			'devices'         => [ 'desktop', 'tablet', 'mobile' ],
			'default'         => ['size' => '', 'unit' => 'px'],
			'tablet_default'  => ['size' => '', 'unit' => 'px'],
			'mobile_default'  => ['size' => '', 'unit' => 'px'],
			'classes' => 'blogfoel-pro-popup-notice',
		]
	);
	$obj->add_responsive_control(
		$slug.'_gap',
		[
			'label' => esc_html__( 'Icon Gap', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
			'type' => \Elementor\Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
			'range' => [
				'px'  => ['min' => 0, 'max' => 100],
				'em'  => ['min' => 0, 'max' => 100],
				'rem' => ['min' => 0, 'max' => 100],
			],
			'devices'         => [ 'desktop', 'tablet', 'mobile' ],
			'default'         => ['size' => '', 'unit' => 'px'],
			'tablet_default'  => ['size' => '', 'unit' => 'px'],
			'mobile_default'  => ['size' => '', 'unit' => 'px'],
			'classes' => 'blogfoel-pro-popup-notice',
		]
	);

}
//  End Meta Controls


//  Excerpt Controls
function blogfoel_excerpt_style_control( $obj, $slug, $blog_desc ){

    $obj->add_control(
        $slug.'_color',
        [
            'label' => esc_html__( 'Color', 'blognews-for-elementor' ),
            'type' => \Elementor\Controls_Manager::COLOR,
            'selectors' => [
                '{{WRAPPER}} .'.$blog_desc => 'color: {{VALUE}};',
            ],
        ]
    );

    $obj->add_group_control(
        \Elementor\Group_Control_Typography::get_type(),
        [
            'name'     =>  $slug.'_typography',
            'label'     => __( 'Typography', 'blognews-for-elementor' ),
            'selector' => '{{WRAPPER}} .'.$blog_desc,
        ]
    );

    $obj->add_responsive_control(
        $slug.'_margin',
        [
            'label' => esc_html__( 'Margin', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
            'type' => \Elementor\Controls_Manager::DIMENSIONS,
            'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
            'classes' => 'blogfoel-pro-popup-notice',
        ]
    );
}
//  End Excerpt Controls


//  Excerpt Controls
//  End Excerpt Controls