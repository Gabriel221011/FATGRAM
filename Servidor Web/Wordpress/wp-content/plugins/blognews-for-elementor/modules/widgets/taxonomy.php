<?php
namespace BlogFoel;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Base;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow; 
use Elementor\Group_Control_Background;
use Elementor\Scheme_Color;
use Elementor\Utils;
use Elementor\Repeater;
use BlogFoel\Notices;

class BLOGFOELTaxonomy extends Widget_Base {

    public function get_name() {
        return 'taxonomy-widget';
    }
    public function get_title() {
        return 'BN Taxonomy';
    }
    public function get_icon() {
        return 'blogfoel-widget-icon bnicon-taxonomy';
    }
    public function get_categories() {
        return ['blogfoel-elementor'];
    }
    public function get_keywords() {
		return [ 'BLOGFOEL', 'themeansar', 'taxonomy-list', 'taxonomy', 'category', 'categories', 'tag', 'list'];
	}
    protected function register_controls() {

		$this->start_controls_section(
			'general_setting',
			[
				'label' => __( 'General Setting', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'layout_style',
			[
				'label'       => esc_html__( 'Layout Style', 'blognews-for-elementor' ),
				'placeholder' => esc_html__( 'Choose Layout Style from Here', 'blognews-for-elementor' ),
				'type'        => 'blogfoel-select',
				'label_block' => false,
				'default'     => 'layout-one',
				'options'     => [
					'layout-one'      => esc_html__( 'Style 1', 'blognews-for-elementor' ),
					'blogfoel-pro-layout-two' => esc_html__( 'Style 2(Pro)', 'blognews-for-elementor' ),
					'blogfoel-pro-layout-three' => esc_html__( 'Style 3(Pro)', 'blognews-for-elementor' ),
				],
			]
		);
		$this->add_control(
			'grid_view',
			[
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Grid View', 'blognews-for-elementor' ),
				'label_on' => esc_html__( 'Yes', 'blognews-for-elementor' ),
				'label_off' => esc_html__( 'No', 'blognews-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		$this->add_responsive_control(
			'columns',
			[
				'label' => esc_html__( 'Column', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 3,
				'step' => 1,
				'default' => '1',
				'condition' => [
					'grid_view' => 'yes'
				],
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);
		$this->add_responsive_control(
			'column_gap',
			[
				'label' => esc_html__( 'Column Space', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px'  => ['min' => 0, 'max' => 300],
					'%'  => ['min' => 0, 'max' => 100],
					'em'  => ['min' => 0, 'max' => 100],
					'rem' => ['min' => 0, 'max' => 100],
				],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'default' => [
					'size' => 1.5,
					'unit' => 'rem',
				],
				'tablet_default' => [
					'size' => 1.5,
					'unit' => 'rem',
				],
				'mobile_default' => [
					'size' => 1.5,
					'unit' => 'rem',
				],
				'selectors'       => [
					'{{WRAPPER}} .blogfoel-taxonomy-items-wrap .blogfoel-category-items' => 'grid-column-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			'row_gap',
			[
				'label' => esc_html__( 'Row Space', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px'  => ['min' => 0, 'max' => 300],
					'%'  => ['min' => 0, 'max' => 100],
					'em'  => ['min' => 0, 'max' => 100],
					'rem' => ['min' => 0, 'max' => 100],
				],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'default' => [
					'size' => 1.5,
					'unit' => 'rem',
				],
				'tablet_default' => [
					'size' => 1.5,
					'unit' => 'rem',
				],
				'mobile_default' => [
					'size' => 1.5,
					'unit' => 'rem',
				],
				'selectors'       => [
					'{{WRAPPER}} .blogfoel-taxonomy-items-wrap .blogfoel-category-items' => 'grid-row-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
            'no_result_text', 
            [
                'label' => esc_html__( 'No Result Found Text', 'blognews-for-elementor' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,  
                'default' => __('No Taxonomy Found.', 'blognews-for-elementor'),
            ]
        );
		$this->end_controls_section();

		$this->start_controls_section(
			'taxonomy_setting',
			[
				'label' => __( 'Taxonomy Query', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);

        $this->add_control(
            'query_type',
            [
                'label'       => esc_html__( 'Query Type', 'blognews-for-elementor' ),
				'type'        => 'blogfoel-select',
				'label_block' => false,
                'default'     => 'all-taxonomy',
                'options'     => [
                    'all-taxonomy' => esc_html__( 'All Taxonomy', 'blognews-for-elementor' ),
                    'blogfoel-pro-custom' => esc_html__( 'Custom (Pro)', 'blognews-for-elementor' ),
                ],
            ]
        );
		$this->add_control(
            'taxonomy_type',
            [
                'label'       => esc_html__( 'Taxonomy Type', 'blognews-for-elementor' ),
                'type'        => \Elementor\Controls_Manager::SELECT,
                'default'     => 'category-list',
                'options'     => [
                    'category-list' => esc_html__( 'Category', 'blognews-for-elementor' ),
                    'tags-list' => esc_html__( 'Tags', 'blognews-for-elementor' ),
                ],
            ]
        );
		$this->add_control(
			'number_of_terms',
			[
				'label' => esc_html__( 'Number of Item', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min'         => 0,
				'max'		  => 999,
				'condition' => [
					'query_type' => 'all-taxonomy'
				],
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);
		
        $this->add_control(
			'show_title',
			[
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Show Title', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
				'label_on' => esc_html__( 'Show', 'blognews-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'blognews-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);
		$this->add_control(
			'show_count',
			[
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Show Count', 'blognews-for-elementor' ),
				'label_on' => esc_html__( 'Show', 'blognews-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'blognews-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
        $this->add_control(
			'hide_link',
			[
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Enable Link', 'blognews-for-elementor' ),
				'label_on' => esc_html__( 'Yes', 'blognews-for-elementor' ),
				'label_off' => esc_html__( 'No', 'blognews-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
        $this->add_responsive_control(
            'taxonomy_align', 
            [
                'label' => __('Alignment', 'blognews-for-elementor') , 
                'type' => \Elementor\Controls_Manager::CHOOSE, 
                'options' => 
                [
                    'start' => [
                        'title' => __('Start', 'blognews-for-elementor') , 
                        'icon' => 'eicon-justify-start-h', 
                    ], 
                    'center' => [
                        'title' => __('Center', 'blognews-for-elementor') ,
                        'icon' => 'eicon-justify-center-h', 
                    ],
                    'end' => [
                        'title' => __('End', 'blognews-for-elementor') , 
                        'icon' => 'eicon-justify-end-h', 
                    ], 
                    'space-between' => [
                        'title' => __('Space Between', 'blognews-for-elementor') , 
                        'icon' => 'eicon-justify-space-between-h', 
                    ], 
                ],
                'default' =>'space-between',
                'selectors' => [
                    '{{WRAPPER}} .blogfoel-taxonomy-items-wrap .blogfoel-category-item a .cate-name' => 'justify-content: {{VALUE}};',
                ], 
            ]
        );
		$this->add_responsive_control(
			'taxonomy_gap',
			[
				'label' => esc_html__( 'Space Between Title & Count', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px'  => ['min' => 0, 'max' => 300],
					'%'  => ['min' => 0, 'max' => 100],
					'em'  => ['min' => 0, 'max' => 100],
					'rem' => ['min' => 0, 'max' => 100],
				],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'condition' => [
					'taxonomy_align!' => 'space-between'
				],
				'selectors'       => [
					'{{WRAPPER}} .blogfoel-taxonomy-items-wrap .blogfoel-category-item a .cate-name' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();

		Notices::go_premium_notice_content($this, 'notice_one');

        // STYLE
        $this->start_controls_section(
            'contant_style',
            [
                'label' => __( 'Content Style', 'blognews-for-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $slug = 'taxonomy';
		$this->start_controls_tabs( $slug.'_style_tabs' );
		$this->start_controls_tab(
			$slug.'_normal_style',
			[
				'label' => __( 'Normal', 'blognews-for-elementor' ),
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
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
				'selector'  => '{{WRAPPER}} .blogfoel-taxonomy-items-wrap .blogfoel-category-item a',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => $slug.'_border_type',
				'label'    => 'Border Type',
				'selector' => '{{WRAPPER}} .blogfoel-taxonomy-items-wrap .blogfoel-category-item a',
			]
		);
		$this->add_responsive_control(
			$slug.'_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .blogfoel-taxonomy-items-wrap .blogfoel-category-item a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .blogfoel-taxonomy-items-wrap .blogfoel-category-item a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
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
			$slug.'_hover_style',
			[
				'label' => __( 'Hover', 'blognews-for-elementor' ),

			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
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
				'selector'  => '{{WRAPPER}} .blogfoel-taxonomy-items-wrap .blogfoel-category-item a:hover',
			]
		);
		
		$this->add_control(
			$slug . '_border_type_hover',
			[
				'label' => esc_html__( 'Border Type', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
                'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'default',
				'options'     => [
					'default' => 'Default',
				  ],
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
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->add_control(
			$slug.'_seperator',
			[
				'label' => esc_html__( 'Seperator', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
		$this->add_control(
			$slug.'_style',
			[
				'label'       => esc_html__( 'Style', 'blognews-for-elementor' ),
				'placeholder' => esc_html__( 'Choose Style from Here', 'blognews-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'blogfoel-sp-one',
				'options'     => [
					'blogfoel-sp-one' => esc_html__( 'Style 1', 'blognews-for-elementor' ),
					'blogfoel-sp-two' => esc_html__( 'Style 2', 'blognews-for-elementor' ),
				],
			]
		);
		$this->add_control(
			$slug.'_color',
			[
				'label' => esc_html__( 'Color', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .blogfoel-category-items.blogfoel-d-flex li.blogfoel-category-item, {{WRAPPER}} .blogfoel-category-items li.blogfoel-category-item:not(:last-child)' => 'border-bottom-color: {{VALUE}};',
                ],
            ]
        );
		$this->add_responsive_control(
			$slug.'_tickness',
			[
				'label' => esc_html__( 'Tickness', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px'  => ['min' => 0, 'max' => 50],
					'em'  => ['min' => 0, 'max' => 10],
					'rem' => ['min' => 0, 'max' => 10],
				],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'default' => [
					'size' => 1,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 1,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 1,
					'unit' => 'px',
				],
				'selectors'       => [
					'{{WRAPPER}} .blogfoel-category-items.blogfoel-d-flex li.blogfoel-category-item, {{WRAPPER}} .blogfoel-category-items li.blogfoel-category-item:not(:last-child)' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
        $this->end_controls_section();
		
        // Title
        $this->start_controls_section(
            'taxonomy_title_style',
            [
                'label' => __( 'Title Style', 'blognews-for-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_title' => 'yes'
				],
            ]
        );

        $slug = 'taxonomy_title';
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
                'name'     => $slug.'_typography',
                'label'     => __( 'Typography', 'blognews-for-elementor' ),
                'selector' => '{{WRAPPER}} .blogfoel-taxonomy-items-wrap .blogfoel-category-item a .name',
            ]
        );
		$this->start_controls_tabs( $slug.'_style_tabs' );
		$this->start_controls_tab(
			$slug.'_normal_style',
			[
				'label' => __( 'Normal', 'blognews-for-elementor' ),
			]
		);
	
		$this->add_control(
			$slug.'_color',
			[
				'label' => esc_html__( 'Color', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .blogfoel-taxonomy-items-wrap .blogfoel-category-item a .name' => 'color: {{VALUE}};',
                ],
            ]
        );
		$this->add_control(
			$slug . '_border_type',
			[
				'label' => esc_html__( 'Border Type', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
                'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'default',
				'options'     => [
					'default' => 'Default',
				  ],
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
			$slug.'_hover_style',
			[
				'label' => __( 'Hover', 'blognews-for-elementor' ),
			]
		);
		
		$this->add_control(
			$slug.'_color_hover',
			[
				'label' => esc_html__( 'Color', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .blogfoel-taxonomy-items-wrap .blogfoel-category-item a:hover .name' => 'color: {{VALUE}};',
                ],
            ]
        );
	
		$this->add_control(
			$slug . '_border_type_hover',
			[
				'label' => esc_html__( 'Border Type', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
                'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'default',
				'options'     => [
					'default' => 'Default',
				  ],
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
        // Count
        $this->start_controls_section(
            'taxonomy_count_style',
            [
                'label' => __( 'Count Style', 'blognews-for-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_count' => 'yes'
				],
            ]
        );

        $slug = 'taxonomy_count';

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
                'name'     => $slug.'_typography',
                'label'     => __( 'Typography', 'blognews-for-elementor' ),
                'selector' => '{{WRAPPER}} .blogfoel-taxonomy-items-wrap .blogfoel-category-item a .count',
            ]
        );

		$this->start_controls_tabs( $slug.'_style_tabs' );
		$this->start_controls_tab(
			$slug.'_normal_style',
			[
				'label' => __( 'Normal', 'blognews-for-elementor' ),
			]
		);
		$this->add_control(
			$slug.'_color',
			[
				'label' => esc_html__( 'Color', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .blogfoel-taxonomy-items-wrap .blogfoel-category-item a .count' => 'color: {{VALUE}};',
                ],
            ]
        );
		$this->add_responsive_control(
			$slug.'_size',
			[
				'label' => esc_html__( 'Width', 'blognews-for-elementor' ). BLOGFOEL_PRO_ICON,
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px'  => ['min' => 0, 'max' => 300],
					'%'  => ['min' => 0, 'max' => 100],
					'em'  => ['min' => 0, 'max' => 100],
					'rem' => ['min' => 0, 'max' => 100],
				],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'default' => [
					'size' => 26,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 26,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 26,
					'unit' => 'px',
				],
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);
		
		$this->add_control(
			$slug . '_border_type',
			[
				'label' => esc_html__( 'Border Type', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
                'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'default',
				'options'     => [
					'default' => 'Default',
				  ],
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);
		$this->start_popover();
		$this->end_popover();

		$this->add_responsive_control(
			$slug.'_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'blognews-for-elementor' ). BLOGFOEL_PRO_ICON,
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
			$slug.'_hover_style',
			[
				'label' => __( 'Hover', 'blognews-for-elementor' ),
			]
		);
		
		$this->add_control(
			$slug.'_color_hover',
			[
				'label' => esc_html__( 'Color', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .blogfoel-taxonomy-items-wrap .blogfoel-category-item a:hover .count' => 'color: {{VALUE}};',
                ],
            ]
        );
		
		$this->add_control(
			$slug . '_border_type_hover',
			[
				'label' => esc_html__( 'Border Type', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
                'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'default',
				'options'     => [
					'default' => 'Default',
				  ],
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

		Notices::go_premium_notice_style($this, 'notice_two');
    }
    protected function render() {
        $settings = $this->get_settings_for_display();
        $taxonomy_type = $settings['taxonomy_type'];
        $layout_style = $settings['layout_style'];
        $taxonomy_style = $settings['taxonomy_style'];
        $grid_view = $settings['grid_view'];
        $no_result_text = $settings['no_result_text'];
		$query_type = $settings['query_type'];
		$number_of_terms = $settings['number_of_terms'];
		$allTax = [];
        ?>
        <div class="blogfoel-widget-wrapper">
            <div class="blogfoel-taxonomy-items-wrap <?php echo esc_attr($layout_style) ?>">
				<?php	
				
				$i = 0;
				$taxonomy = ($taxonomy_type == 'category-list') ? 'category' : 'post_tag';
				$terms = get_terms( array(
					'taxonomy'   => $taxonomy,
					'orderby'    => 'name',
					'order'      => 'ASC',
					'hide_empty' => false,
				) );
				foreach ($terms as $value) {
					if($i !== $number_of_terms || $i === 0){
						$allTax[] = $value->slug;
					}else{
						break;
					}
					$i++;
				}
				$includeTax = $allTax;
				
				if(!empty($includeTax)) { ?>
					<ul class="blogfoel-category-items <?php echo ('yes' === $grid_view) ? '' : 'blogfoel-d-flex'; ?>">                        
					<?php foreach($includeTax as $taxVal) { 	
						$catID = get_term_by('slug', $taxVal, $taxonomy);
						$catID = $catID->term_id;
						$term = get_term( $catID, $taxonomy ); 
						$image_id = get_term_meta ( $catID, 'category-image-id', true );
						$image_url = wp_get_attachment_image_src ( $image_id, 'full' ) == '' ? blogfoel_placeholder_image_src() : wp_get_attachment_image_src ( $image_id, 'full' )[0];
						?>
						<li class="blogfoel-category-item <?php echo esc_attr($taxonomy_style) ?>">
							<a <?php if ( $settings['hide_link'] == 'yes' ) echo 'href="' . esc_url( get_term_link( $catID ) ) . '"'; ?>>									
								<span class="cate-name">
									<span class="name"><?php echo esc_html($term->name); ?></span>
									<?php if ( $settings['show_count'] === 'yes' ) { ?> 
										<span class="count"><?php echo esc_html($term->count); ?></span>
									<?php } ?>
								</span>
							</a>
						</li>
					<?php } ?>
					</ul>
				<?php } else { ?>
					<div class="blogfoel-alert-notice"><?php echo esc_html($no_result_text) ?></div>
				<?php } ?> 
            </div>
        </div>
        <?php
    }
}
