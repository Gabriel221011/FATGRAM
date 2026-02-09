<?php

namespace BlogFoel;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use ElementorPro\Modules\Posts\Widgets\Posts_Base;
use Elementor\Repeater;
use Elementor\Group_Control_Background;
use BlogFoel\Notices;
use WP_Query;

class BLOGFOELFilterTabPost extends \Elementor\Widget_Base {

	private $blog_card_class = 'blogfoel-small-post';
	private $blog_inner_class = 'blogfoel-inner';
	private $blog_category = 'blogfoel-category';
	private $blog_image = 'blogfoel-back-img';
	private $blog_title = 'blogfoel-title';
	private $blog_meta = 'blogfoel-meta';
	private $blog_tab = 'blogfoel-filter-tab';

	public function get_name() {
		return 'blognews-filter-tabs-1';
	}

	public function get_title() {
		return __( 'BN Filter Tab Post 1', 'blognews-for-elementor' );
	}

	public function get_categories() {
		return [ 'blogfoel-elementor' ];
	}

	public function get_icon() {
		return 'blogfoel-widget-icon bnicon-filter-tab-1';
	}

	public function get_style_depends() {
		return [
			'blognews-post-blog',
		];
	}

	public function get_script_depends() {
		return [
			'blognews-post-blog',
		];
	}

    public function get_keywords() {
		return [
            'BLOGFOEL',
            'post blog',
            'news',
            'tabs',
            'list',
            'themeansar',
		];
	}
	protected function register_controls() {
		$this->start_controls_section(
			'item_configuration',
			[
				'label' => __( 'Post Query', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'template_style',
			[
				'label'       => esc_html__( 'Template Style', 'blognews-for-elementor' ),
				'placeholder' => esc_html__( 'Choose Template from Here', 'blognews-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::HIDDEN,
				'default'     => 'default',
				'options'     => [
					'default'      => esc_html__( 'Default', 'blognews-for-elementor' ),
					'two' => esc_html__( 'Two', 'blognews-for-elementor' ),
				],
			]
		);

		$this->add_control(
            'order_by',
            [
                'label'       => esc_html__( 'Order By', 'blognews-for-elementor' ),
                'placeholder' => esc_html__( 'ID', 'blognews-for-elementor' ),
                'type'        => \Elementor\Controls_Manager::SELECT,
                'default'     => '',
                'options'     => [
					'none' => 'None',
					'ID' => 'ID',
					'author' => 'Author',
					'title' => 'Title',
					'name' => 'Name',
					'type' => 'Type',
					'date' => 'Date',
					'modified' => 'Modified',
					'parent' => 'Parent',
					'rand' => 'Random',
					'comment_count' => 'Comment_count',
                ],
            ]
        );
		$this->add_control(
            'order',
            [
                'label'       => esc_html__( 'Order', 'blognews-for-elementor' ),
                'placeholder' => esc_html__( 'DESC', 'blognews-for-elementor' ),
                'type'        => \Elementor\Controls_Manager::SELECT,
                'default'     => '',
                'options'     => [
					'ASC' => 'Ascending',
					'DESC' => 'Descending'
                ],
            ]
        );
		$this->add_control(
			'number_of_posts',
			[
				'label' => esc_html__( 'Number of Posts', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'placeholder' => '4',
				'min'         => 1,
				'max'		  => 5,
				'default'     => '4',
			]
		);
		
		$this->add_control(
			'category_style',
			[
				'label'       => esc_html__( 'Category Style', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
				'placeholder' => esc_html__( 'Choose layout Style from Here', 'blognews-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'one',
				'options'     => [
					'one'   => esc_html__( 'Style 1', 'blognews-for-elementor' ),
				],
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);
		$this->add_responsive_control(
			'title_length',
			[
				'label' => esc_html__( 'Title Length (In Lines)', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'placeholder' => '2',
				'min'         => 1,
				'default'     => '',
				'max'		  => 5,
				'selectors'       => [
					'{{WRAPPER}} .'.$this->blog_title => '-webkit-line-clamp: {{VALUE}};',
				],
			]
		);

		$repeater = new Repeater();
		$repeater->add_control(
			'tabs_title', 
			[
				'label' => __( 'Title', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Trending' , 'blognews-for-elementor' ),
				'label_block' => true,
			]
		);
		
		$repeater->add_control(
			'tabs_category',
			[
				'label' => esc_html__( 'Filter By Categories', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'multiple' => true,
				'placeholder' => 'Choose Category to Include',
				'options'     => blognews_get_categories(),
			]
		);
		$repeater->add_control(
			'tabs_icon',
			[
				'label' => esc_html__( 'Select Icon', 'blognews-for-elementor' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
				'label_block' => false,
                'default' => [
					'value' => 'fas fa-circle',
					'library' => 'fa-solid',
				],
			]
		);
	
		$this->add_control(
			'tabs_post_repeater',
			[
				'label'       => esc_html__('Filter Tabs', 'blognews-for-elementor'),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),'default' => [
					[
						'tabs_title' => __( 'Trending' , 'blognews-for-elementor' ),
						'tabs_category' => 0,
						'tabs_icon' =>['value' => 'fas fa-newspaper', 'library' => 'solid',],
					],
					[
						'tabs_title' => __( ' Popular' , 'blognews-for-elementor' ),
						'tabs_category' => 0,
						'tabs_icon' =>['value' => 'fas fa-fire', 'library' => 'solid',],
					],
					[
						'tabs_title' => __( 'Latest' , 'blognews-for-elementor' ),
						'tabs_category' => 0,
						'tabs_icon' =>['value' => 'fas fa-bolt', 'library' => 'solid',],
					],
				],
				'title_field' => '{{{ tabs_title }}}',
			]
		);

		$this->add_control(
			'repeater_pro_notice',
			[
				'raw' => 'More than 3 are available in <a href="'.BLOGFOEL_PRO_LINK.'" target="_blank">Pro Version!</a>',
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'content_classes' => 'blogfoel-pro-notice',
			]
		);
		
		$this->end_controls_section();

		$this->start_controls_section(
			'settings',
			[
				'label' => __( 'Post Settings', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'show_image',
			[
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Show Image', 'blognews-for-elementor' ). BLOGFOEL_PRO_ICON,
				'label_on' => esc_html__( 'Show', 'blognews-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'blognews-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Image_Size::get_type(),
			[
				'name' => 'thumbnail_size', 
				'default' => 'large',
				'condition' => [
					'show_image' => 'yes'
				],
			]
		);
		$this->add_control(
			'cat_icon',
			[
				'label' => esc_html__( 'Category Icon', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
                'default' => [
					'value' => '',
					'library' => '',
				],
				'label_block' => false,
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);
		$repeater = new Repeater();
		$repeater->add_control(
			'post_content', 
			[
				'label' => __( 'Select Content', 'blognews-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::HIDDEN,
				'default'     => 'title',
				'options'     => [
					'category'	=> esc_html__( 'Category', 'blognews-for-elementor' ),
					'title'	=> esc_html__( 'Title', 'blognews-for-elementor' ),
					'meta'	=> esc_html__( 'Metas', 'blognews-for-elementor' ),
				],
			]
		);
		$repeater->add_control(
			'show_content',
			[
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Hide/show', 'blognews-for-elementor' ),
				'label_on' => esc_html__( 'Show', 'blognews-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'blognews-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->add_control(
			'post_content_repeater',
			[
				'label'       => esc_html__('Post Content', 'blognews-for-elementor'),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default' => [
					[
						'post_content' => 'category',
					],
					[
						'post_content' => 'title',
					],
					[
						'post_content' => 'meta',
					],
				],
				'title_field' => '{{{ post_content.charAt(0).toUpperCase() + post_content.slice(1) }}}',
			]
		);

		$repeater = new Repeater();
		$repeater->add_control(
			'post_meta', 
			[
				'label' => __( 'Select Post Meta', 'blognews-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'author',
				'options'     => [
					'author'	=> esc_html__( 'Author', 'blognews-for-elementor' ),
					'date'	=> esc_html__( 'Date', 'blognews-for-elementor' ),
				],
			]
		);
		$repeater->add_control(
			'show_avatar',
			[
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Show Avatar', 'blognews-for-elementor' ),
				'label_on' => esc_html__( 'Show', 'blognews-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'blognews-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'no',
				'condition' => [
					'post_meta' => 'author'
				],
			]
		);
		$repeater->add_responsive_control(
			'avatar_size',
			[
				'label' => esc_html__( 'Size', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'placeholder' => '25',
				'min'         => 1,
				'max'		  => 100,
				'default'     => 25,
				'selectors'       => [
					'{{WRAPPER}} .blogfoel-author .blogfoel-avatar' => 'width: {{SIZE}}px;',
				],
				'condition' => [
					'show_avatar' => 'yes',
					'post_meta' => 'author',
				],
			]
		);
		$repeater->add_control(
			'date_format',
			[
				'label'       => esc_html__( 'Date Format', 'blognews-for-elementor' ). BLOGFOEL_PRO_ICON ,
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'default',
				'options'     => [
					'default'       => esc_html__( 'Default', 'blognews-for-elementor' ),
					'wordpress'       => esc_html__( 'Wordpress', 'blognews-for-elementor' ),
				],
				'condition' => [
					'post_meta' => 'date'
				],
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);
		
		$repeater->add_control(
			'meta_icon',
			[
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Show Icon', 'blognews-for-elementor' ),
				'label_on' => esc_html__( 'Show', 'blognews-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'blognews-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		
		$this->add_control(
			'post_meta_repeater',
			[
				'label'       => esc_html__('Post Meta', 'blognews-for-elementor'),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default' => [
					[
						'post_meta' => 'author',
						'meta_icon' => 'yes',
					],
					[
						'post_meta' => 'date',
						'meta_icon' => 'yes',
					],
				],
				'title_field' => '{{{ post_meta.charAt(0).toUpperCase() + post_meta.slice(1) }}}',
			]
		);

		$this->end_controls_section();
		Notices::go_premium_notice_content($this, 'notice_one');
		// Style
		$this->start_controls_section(
			'filter_tabs_style',
			[
				'label' => __( 'Tabs', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'template_style' => 'default'
				],
			]
		);

		$slug = 'filter_tabs';
		$this->add_responsive_control(
			$slug.'_width',
			[
				'label' => esc_html__( 'Width', 'blognews-for-elementor' ),
				'type'        => 'blogfoel-select',
				'label_block' => false,
				'default' => '1',
				'options' => [
					'1' => esc_html__( 'Full Width', 'blognews-for-elementor' ),
					'blogfoel-pro-2' => esc_html__( 'Auto (Pro)', 'blognews-for-elementor' ),
				],
			]
		);
		$this->add_responsive_control(
            $slug.'_align',
            [
                'label'     => __( 'Alignment', 'blognews-for-elementor' ). BLOGFOEL_PRO_ICON,
                'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'flex-start'   => [
						'title' => __( 'Left', 'blognews-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'blognews-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					],
					'flex-end'  => [
						'title' => __( 'Right', 'blognews-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => '',
				'condition' => [
					$slug.'_width' => '1'
				],
				'classes' => 'blogfoel-pro-popup-notice',
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
					'{{WRAPPER}} .'.$this->blog_tab => 'color: {{VALUE}};',
				],
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
				'selector'  => '{{WRAPPER}} .'.$this->blog_tab,
			]
		);
		$this->add_control(
			$slug.'_border_color',
			[
				'label' => esc_html__( 'Border Color', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'.$this->blog_tab => 'border-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			$slug.'_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'.$this->blog_tab.' i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .'.$this->blog_tab.' svg' => 'fill: {{VALUE}};',
				],
			]
		);
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
					'{{WRAPPER}} .'.$this->blog_tab.':hover' => 'color: {{VALUE}};',
				],
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
				'selector'  => '{{WRAPPER}} .'.$this->blog_tab.':hover',
			]
		);
		$this->add_control(
			$slug.'_border_hover_color',
			[
				'label' => esc_html__( 'Border Color', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'.$this->blog_tab.':hover' => 'border-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			$slug.'_icon_hover_color',
			[
				'label' => esc_html__( 'Icon Color', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'.$this->blog_tab.':hover i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .'.$this->blog_tab.':hover svg' => 'fill: {{VALUE}};',
				],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			$slug.'_active_style',
			[
				'label' => __( 'Active', 'blognews-for-elementor' ),

			]
		);
		$this->add_control(
			$slug.'_color_active',
			[
				'label' => esc_html__( 'Color', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'.$this->blog_tab.'.active' => 'color: {{VALUE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => $slug.'_bg_color_active',
				'types'          => [ 'classic', 'gradient' ],
				'exclude'        => [ 'image' ],
				'fields_options' => [
					'background' => [
						'label' => esc_html__( 'Background Color', 'blognews-for-elementor' ),
						'default' => 'classic',
					],
				],
				'selector'  => '{{WRAPPER}} .'.$this->blog_tab.'.active',
			]
		);
		$this->add_control(
			$slug.'_border_active_color',
			[
				'label' => esc_html__( 'Border Color', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'.$this->blog_tab.'.active' => 'border-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			$slug.'_icon_active_color',
			[
				'label' => esc_html__( 'Icon Color', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'.$this->blog_tab.'.active i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .'.$this->blog_tab.'.active svg' => 'fill: {{VALUE}};',
				],
			]
		);
		
		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			$slug.'_hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     =>  $slug.'_typography',
                'label'     => __( 'Typography', 'blognews-for-elementor' ),
				'selector' => '{{WRAPPER}} .'.$this->blog_tab,
			]
		);
		$this->add_responsive_control(
			$slug.'_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'blognews-for-elementor' ),
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
				'selectors'       => [
					'{{WRAPPER}} .'.$this->blog_tab.' i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .'.$this->blog_tab.' svg' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => $slug.'_border_type',
				'label'    => 'Border Type',
				'selector' => '{{WRAPPER}} .'.$this->blog_tab,
				'exclude'        => [ 'color' ],
			]
		);
		$this->add_responsive_control(
			$slug.'_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .'.$this->blog_tab => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
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
			$slug.'columns_gap',
			[
				'label' => esc_html__( 'Columns Gap', 'blognews-for-elementor' ),
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
				'selectors'       => [
					'{{WRAPPER}} .blogfoel-filter-tab-wrapper' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			$slug.'inner_gap',
			[
				'label' => esc_html__( 'Inner Icon Gap', 'blognews-for-elementor' ). BLOGFOEL_PRO_ICON,
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
		$this->add_responsive_control(
			$slug.'_gap',
			[
				'label' => esc_html__( 'Bottom Gap', 'blognews-for-elementor' ). BLOGFOEL_PRO_ICON,
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

		$this->end_controls_section();

		$this->start_controls_section(
			'blog_style',
			[
				'label' => __( 'Blog Style', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$slug = 'blog';

		$this->add_responsive_control(
			$slug.'_vertical_align',
			[
				'label' => __('Vertical Alignment', 'blognews-for-elementor') , 
				'type' => Controls_Manager::CHOOSE, 
                'default' => '',
				'options' => [
					'flex-start' => [
						'title' => __('Start', 'blognews-for-elementor') , 
						'icon' => 'eicon-align-start-v', 
					], 
					'center' => [
						'title' => __('Center', 'blognews-for-elementor') , 
						'icon' => 'eicon-align-center-v',
					], 
					'flex-end' => [
						'title' => __('End', 'blognews-for-elementor') , 
						'icon' => 'eicon-align-end-v',
					], 
				], 
				'selectors' => [
					'{{WRAPPER}} .'. $this->blog_card_class => 'align-items: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => $slug.'_background_color',
				'types'          => [ 'classic', 'gradient' ],
				'exclude'        => [ 'image' ],
				'fields_options' => [
					'background' => [
						'label' => esc_html__( 'Background Color', 'blognews-for-elementor' ),
						'default' => 'classic',
					],
				],
				'selector'  => '{{WRAPPER}} .'.$this->blog_card_class,
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => $slug.'_border_type',
				'label'    => 'Border Type',
				'selector' => '{{WRAPPER}}  .'.$this->blog_card_class,
			]
		);
		$this->add_responsive_control(
			$slug.'_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .'.$this->blog_card_class => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .'.$this->blog_card_class => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .'.$this->blog_card_class => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'      => $slug.'_box_shadow',
				'label'    => 'Box Shadow',
				'selector' => '{{WRAPPER}} .'.$this->blog_card_class.'',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'image_style',
			[
				'label' => __( 'Image', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_image' => 'yes'
				],
			]
		);

		$slug = 'image';
		$this->add_responsive_control(
			$slug.'_img_position',
			[
				'label'     => __( 'Image Position', 'blognews-for-elementor' ),
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
				'selectors' => [
					'{{WRAPPER}} .'.$this->blog_card_class => 'flex-direction: {{VALUE}}',
				],
			]
		);
	
		$this->add_responsive_control(
			$slug.'_gap',
			[
				'label' => esc_html__( 'Gap', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px'  => ['min' => 0, 'max' => 100],
					'em'  => ['min' => 0, 'max' => 100],
					'rem' => ['min' => 0, 'max' => 100],
				],
				'default_desktop' => ['size' => '', 'unit' => 'px'],
				'tablet_default'  => ['size' => '', 'unit' => 'px'],
				'mobile_default'  => ['size' => '', 'unit' => 'px'],
				'selectors'       => [
					'{{WRAPPER}} .'.$this->blog_card_class => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			$slug.'_width',
			[
				'label' => esc_html__( 'Width', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem','vw', 'custom' ],
				'range' => [
					'px'  => ['min' => 0, 'max' => 200],
					'em'  => ['min' => 0, 'max' => 100],
					'rem' => ['min' => 0, 'max' => 100],
					'vw'  => ['min' => 0, 'max' => 200],
				],
				'default_desktop' => ['size' => '', 'unit' => 'px'],
				'tablet_default'  => ['size' => '', 'unit' => 'px'],
				'mobile_default'  => ['size' => '', 'unit' => 'px'],
				'selectors'       => [
					'{{WRAPPER}} .blogfoel-back-img' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'template_style' => 'default'
				],
			]
		);

		$this->add_responsive_control(
			$slug.'_height',
			[
				'label' => esc_html__( 'Height', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem','vh', 'custom' ],
				'range' => [
					'px'  => ['min' => 0, 'max' => 200],
					'em'  => ['min' => 0, 'max' => 100],
					'rem' => ['min' => 0, 'max' => 100],
					'vh'  => ['min' => 0, 'max' => 200],
				],
				'default_desktop' => ['size' => '', 'unit' => 'px'],
				'tablet_default'  => ['size' => '', 'unit' => 'px'],
				'mobile_default'  => ['size' => '', 'unit' => 'px'],
				'selectors'       => [
					'{{WRAPPER}} .'.$this->blog_image => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => $slug.'_border_type',
				'label'    => 'Border Type',
				'selector' => '{{WRAPPER}} .'.$this->blog_image,
			]
		);
		$this->add_responsive_control(
			$slug.'_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .'.$this->blog_image => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'category_tab_style',
			[
				'label' => __( 'Category', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$slug = 'blog_category';
		
		$this->add_responsive_control(
			$slug.'_align',
			[
				'label'     => __( 'Alignment', 'blognews-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'flex-start'   => [
						'title' => __( 'Left', 'blognews-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'blognews-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					],
					'flex-end'  => [
						'title' => __( 'Right', 'blognews-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .'.$this->blog_category => 'justify-content: {{VALUE}}',
				],
			]
		);
		
		blogfoel_category_style_control( $this , $slug , $this->blog_category );

		$this->end_controls_section();

		$this->start_controls_section(
			'title_style',
			[
				'label' => __( 'Title', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$slug = 'title';
		
		blogfoel_title_style_control( $this , $slug , $this->blog_title );

		$this->end_controls_section();

		$this->start_controls_section(
			'metas_style',
			[
				'label' => __( 'Metas', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$slug = 'metas';

		$this->add_responsive_control(
			$slug.'_align',
			[
				'label'     => __( 'Alignment', 'blognews-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'flex-start'   => [
						'title' => __( 'Left', 'blognews-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'blognews-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					],
					'flex-end'  => [
						'title' => __( 'Right', 'blognews-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .'.$this->blog_meta => 'justify-content: {{VALUE}}',
				],
			]
		);
	
		blogfoel_meta_style_control( $this , $slug , $this->blog_meta );
		
		$this->end_controls_section();

		Notices::go_premium_notice_style($this, 'notice_two');
	}

	protected function render() {
		$settings = $this->get_settings_for_display();

		$title_html_tag = $settings['title_html_tag'];
		$show_image        = $settings['show_image'];
		$template_style = $settings['template_style'];
		$category_style     = $settings['category_style'];

		$title_animation_type = $settings['title_animation_type'];
		$custom_animation     = $settings['title_custom_animation'];
		$title_animation      = $custom_animation;

		$tabs_post_repeater = $settings['tabs_post_repeater'];
	
		$post_content_repeater       = $settings['post_content_repeater'];
		$post_meta_repeater          = $settings['post_meta_repeater'];

		if (!is_array($tabs_post_repeater) || empty($tabs_post_repeater)) {
			return;
		}
	
		// Define paged only once
		$paged = (get_query_var('paged')) ? absint(get_query_var('paged')) :
				 (get_query_var('page') ? absint(get_query_var('page')) : 1);
		?>
		<div class="blogfoel-widget-wrapper">
			<div class="blogfoel-post-filter-tabs">
				<div class="blogfoel-filter-tab-wrapper">
					<?php 
					foreach ($tabs_post_repeater as $key => $item): 
						$title = $item['tabs_title'];
						$icon = $item['tabs_icon'];
						$filter_slug = sanitize_title($title); // for consistent filtering
						if ($key === 3 ) { break; } ?>
						<span class="blogfoel-filter-tab<?php echo ($key === 0) ? ' active' : ''; ?>" data-filter="<?php echo esc_attr($filter_slug); ?>">
							<?php \Elementor\Icons_Manager::render_icon( $icon, [ 'aria-hidden' => 'true' ] ); echo esc_html($title); ?>
						</span>
					<?php endforeach; ?>
				</div> 
		
				<div class="blogfoel-filter-items">
					<?php foreach ($tabs_post_repeater as $item):
						$category = $item['tabs_category'];
						$title = $item['tabs_title'];
						$filter_slug = sanitize_title($title); // used for filtering
		
						$args = [
							'posts_per_page'      => $settings['number_of_posts'] ?? 4,
							'orderby'             => $settings['order_by'] ?? 'ID',
							'order'               => $settings['order'] ?? 'DESC',
							'post_type'           => 'post',
							'post_status'         => 'publish',
							'paged'               => $paged,
							'ignore_sticky_posts' => 1,
						];
		
						if (!empty($category)) {
							$args['category__in'] = (array) $category;
						}
		
						$wp_query = new WP_Query($args);
						$post_count = 0;
		
						if ($wp_query->have_posts()):
							while ($wp_query->have_posts()): $wp_query->the_post();
								$excerpt_length = 0;
								$thumbnail_id = get_post_thumbnail_id();
								$image_class = empty($thumbnail_id) ? ' no-image' : '';
								$categories = get_the_category();
								$comments_count = get_comments_number();
								$file_path = BLOGFOEL_DIR_PATH . 'modules/layouts/filter-tabs-1/layout-1.php';
								?>
								<div class="blogfoel-filter-item" data-filter="<?php echo esc_attr($filter_slug); ?>">
								<?php if (file_exists($file_path)) {
								require $file_path;
							} ?>
								</div>
								<?php
								$post_count++;
							endwhile;
						else: ?>
							<p><?php esc_html_e('No posts found.', 'blognews-for-elementor'); ?></p>
						<?php 
						endif;
						wp_reset_postdata();
					endforeach; ?>
				</div>
			</div>
		</div>
		<?php
	}
}