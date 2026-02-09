<?php
namespace BlogFoel;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use ElementorPro\Modules\Posts\Widgets\Posts_Base;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use WP_Query;
use BlogFoel\Notices;

class BLOGFOELPostTicket extends \Elementor\Widget_Base {

	private $blog_card_class = 'blognews-ticker';
	private $blog_inner = 'blognews-ticker-inner';
	private $blog_category = 'blognews-ticker-category';
	private $ticker_media = 'blognews-ticker-media';
	private $blog_title = 'blognews-ticker-title'; 
	private $blog_head = 'blognews-ticker-heading-title'; 
	private $blog_meta = 'blognews-ticker-meta';
	private $blog_btn = 'blognews-ticker-button';

	public function get_name() {
		return 'blognews-post-ticker';
	}

	public function get_title() {
		return __( 'BN Post Ticker', 'blognews-for-elementor' );
	}

	public function get_categories() {
		return [ 'blogfoel-elementor' ];
	}

	public function get_icon() {
		return 'blogfoel-widget-icon bnicon-ticker';
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
			'post ticker',
			'news ticker',
			'breaking news',
			'ticker',
            'BLOGFOEL',
            'themeansar',
			'marquee',
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
			'post_category',
			[
				'label' => esc_html__( 'Filter By Categories', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
				'placeholder' => 'Choose Category to Include',
				'options'     => blognews_get_categories(),
			]
		);
		$this->add_control(
			'post_tags',
			[
				'label' => esc_html__( 'Filter By Tags', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
				'multiple' => true,
				'placeholder' => 'Choose Tag to Include',
				'options'     => blognews_get_tags(),
			]
		);
		$this->add_control(
			'number_of_posts',
			[
				'label' => esc_html__( 'Number of Posts', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::HIDDEN,
				'placeholder' => '6',
				'min'         => 1,
				'max'		  => 10,
				'default'     => 6,
			]
		);

		$this->add_control(
            'order_by',
            [
                'label'       => esc_html__( 'Order By', 'blognews-for-elementor' ),
                'placeholder' => esc_html__( 'Order By', 'blognews-for-elementor' ),
                'type'        => \Elementor\Controls_Manager::SELECT,
                'default'     => 'ID',
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
                'placeholder' => esc_html__( 'Order', 'blognews-for-elementor' ),
                'type'        => \Elementor\Controls_Manager::SELECT,
                'default'     => 'DESC',
                'options'     => [
					'ASC' => 'Ascending',
					'DESC' => 'Descending'
                ],
            ]
        );
		$this->add_responsive_control(
			'title_length',
			[
				'label' => esc_html__( 'Title Length (In Words)', 'blognews-for-elementor' ). BLOGFOEL_PRO_ICON,
				'type' => \Elementor\Controls_Manager::NUMBER,
				'placeholder' => '10',
				'min'         => 1,
				'default'     => '',
				'max'		  => 10,
				'classes' => 'blogfoel-pro-popup-notice',
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
			'media_type',
			[
				'label'       => esc_html__( 'Media Type', 'blognews-for-elementor' ),
				'placeholder' => esc_html__( 'Choose', 'blognews-for-elementor' ),
				'type'        => 'blogfoel-select',
				'label_block' => false,
				'default' => 'icon',
				'options'     => [
					'none'   => esc_html__( 'None', 'blognews-for-elementor' ),
					'icon'   => esc_html__( 'Icon', 'blognews-for-elementor' ),
					'blogfoel-pro-image' => esc_html__( 'Image (Pro)', 'blognews-for-elementor' ),
				],
			]
		);

		$this->add_control(
			'ticker_post_icon',
			[
				'label' => __( 'Icon', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-arrow-left',
					'library' => 'solid',
				],
				'condition' => [
					'media_type'=> 'icon',
				]
			]
		);

		$this->add_control(
			'directon_type',
			[
				'label'       => esc_html__( 'Slide Direction', 'blognews-for-elementor' ),
				'placeholder' => esc_html__( 'Choose Direction', 'blognews-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'options'     => [ 
					'right' => 'Right', 
					'left' => 'Left',
				],
				'default' => 'left',
			]
		);
		
		$this->add_control(
			'slide_speed',
			[
				'label'       => esc_html__( 'Slide Speed', 'blognews-for-elementor' ),
				'placeholder' => esc_html__( 'Choose Speed', 'blognews-for-elementor' ),
				'type'        => 'blogfoel-select',
				'label_block' => false,
				'default' => '80',
				'options'     => [ 
					'100' => '1s',
					'90' => '2s',
					'80' => '3s',
					'blogfoel-pro-70' => '4s (Pro)',
					'blogfoel-pro-60' => '5s (Pro)',
					'blogfoel-pro-50' => '6s (Pro)',
					'blogfoel-pro-40' => '7s (Pro)',
					'blogfoel-pro-30' => '8s (Pro)',
					'blogfoel-pro-20' => '9s (Pro)',
					'blogfoel-pro-10' => '10s (Pro)',
				],
				'default' => '80',
			]
		);
		$this->add_control(
			'show_play',
			[
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Show Pause/Play', 'blognews-for-elementor' ),
				'label_on' => esc_html__( 'Show', 'blognews-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'blognews-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'ticker_title_section',
			[
				'label' => __( 'Ticker Title', 'blognews-for-elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'ticker_title', [
				'label' => __( 'Title', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Latest News' , 'blognews-for-elementor' ),
				'label_block' => true,
			]
		);
		$this->add_control(
            'title_tag',
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

		$this->add_control(
			'ticker_icon',
			[
				'label' => __( 'Icon', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-bolt',
					'library' => 'solid',
				],
			]
		);

		$this->end_controls_section();
		
		Notices::go_premium_notice_content($this, 'notice_one');
		
		// STYLE
		$this->start_controls_section(
			'blog_style',
			[
				'label' => __( 'Blog Style', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		); 

		$slug = 'blog_box';

		$this->add_responsive_control(
			$slug.'_height',
			[
				'label'           => __( 'Min Height', 'blognews-for-elementor' ),
				'type'            => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'range' => [
					'px'  => ['min' => 0, 'max' => 80],
				],
				'default_desktop' => ['size' => '', 'unit' => 'px'],
				'tablet_default'  => ['size' => '', 'unit' => 'px'],
				'mobile_default'  => ['size' => '', 'unit' => 'px'],
				'selectors'       => [
					'{{WRAPPER}} .'.$this->blog_card_class => 'min-height: {{SIZE}}{{UNIT}};', 
				],
			]
		);

		$this->add_control(
			$slug.'_bg_color',
			[
				'label'     => __( 'Background Color', 'blognews-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}  .'.$this->blog_card_class => 'background-color: {{VALUE}}',
					'{{WRAPPER}}  .'.$this->blog_card_class.' .blogfoel-latest-title' => 'background-color: {{VALUE}}',
				], 
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
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

		$this->end_controls_section();
		
		// Heading 
		$this->start_controls_section(
			'ticker_heading_style',
			[
				'label' => __( 'Title Settings', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$slug = 'ticker_heading';		
		$this->add_control(
			$slug.'_color',
			[
				'label'     => __( 'Color', 'blognews-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}  .'.$this->blog_head => 'color: {{VALUE}}',
					'{{WRAPPER}}  .'.$this->blog_head.' svg' => 'fill: {{VALUE}}',
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
				'selector'  => '{{WRAPPER}} .'.$this->blog_head,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => $slug.'_typography',
				'selector' => '{{WRAPPER}} .'.$this->blog_head,
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
					'{{WRAPPER}} .'.$this->blog_head.' i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .'.$this->blog_head.' svg' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => $slug.'_border_type',
				'label'    => 'Border Type',
				'selector' => '{{WRAPPER}}  .'.$this->blog_head,
			]
		);
		$this->add_responsive_control(
			$slug.'_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .'.$this->blog_head => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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

		$this->end_controls_section();
		
		//Blog Image
		$this->start_controls_section(
			'image_style',
			[
				'label' => __( 'Media Settings', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$slug = 'ticker_media';
		$this->add_control(
			$slug.'_color',
			[
				'label'     => __( 'Color', 'blognews-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}  .'.$this->ticker_media => 'color: {{VALUE}}',
					'{{WRAPPER}}  .'.$this->ticker_media.' svg' => 'fill: {{VALUE}}',
				],
				'condition' => [
					'media_type'=> 'icon',
				]
			]
		);

		$this->add_control(
			$slug.'_bg_color',
			[
				'label'     => __( 'Background Color', 'blognews-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'.$this->ticker_media => 'background-color: {{VALUE}}',
				],
				'condition' => [
					'media_type'=> 'icon',
				]
			]
		);
		$this->add_responsive_control(
			$slug.'_size',
			[
				'label' => esc_html__( 'Size', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'range' => [
					'px'  => ['min' => 0, 'max' => 50],
				],
				'default_desktop' => ['size' => '', 'unit' => 'px'],
				'tablet_default'  => ['size' => '', 'unit' => 'px'],
				'mobile_default'  => ['size' => '', 'unit' => 'px'],
				'selectors'       => [
					'{{WRAPPER}} .'.$this->ticker_media => 'font-size: {{SIZE}}{{UNIT}};', 
					'{{WRAPPER}} .'.$this->ticker_media.' svg' => 'width: {{SIZE}}{{UNIT}};', 
				],
				'separator'     => 'after',
				'condition' => [
					'media_type'=> 'icon',
				]
			],
		);
		$this->add_responsive_control(
			$slug.'_width',
			[
				'label' => esc_html__( 'Width', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px','vw', 'custom' ],
				'range' => [
					'px'  => ['min' => 0, 'max' => 60],
					'vw'  => ['min' => 0, 'max' => 100],
				],
				'default_desktop' => ['size' => '', 'unit' => 'px'],
				'tablet_default'  => ['size' => '', 'unit' => 'px'],
				'mobile_default'  => ['size' => '', 'unit' => 'px'],
				'selectors'       => [
					'{{WRAPPER}} .'.$this->ticker_media => 'width: {{SIZE}}{{UNIT}};', 
				],
			],
		);

		$this->add_responsive_control(
			$slug.'_height',
			[
				'label'           => __( 'Height', 'blognews-for-elementor' ),
				'type'            => Controls_Manager::SLIDER,
				'size_units' => [ 'px','vh', 'custom' ],
				'range' => [
					'px'  => ['min' => 0, 'max' => 60],
					'vh'  => ['min' => 0, 'max' => 100],
				],
				'default_desktop' => ['size' => '', 'unit' => 'px'],
				'tablet_default'  => ['size' => '', 'unit' => 'px'],
				'mobile_default'  => ['size' => '', 'unit' => 'px'],
				'selectors'       => [
					'{{WRAPPER}} .'.$this->ticker_media => 'height: {{SIZE}}{{UNIT}};', 
				],
			]
		);

			
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => $slug.'_border_type',
				'label'    => 'Border Type',
				'selector' => '{{WRAPPER}} .'.$this->ticker_media,
			]
		);

		$this->add_responsive_control(
			$slug.'_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .'.$this->ticker_media => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .'.$this->ticker_media => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		// Title
		$this->start_controls_section(
			'title_style',
			[
				'label' => __( 'Post Title Settings', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$slug = 'ticker_title';

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
					'{{WRAPPER}}  .'.$this->blog_title => 'color: {{VALUE}}',
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
					'{{WRAPPER}} .'.$this->blog_title.':hover' => 'color: {{VALUE}}',
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
				'name'     => $slug.'_typography',                
				'label'     => __( 'Typography', 'blognews-for-elementor' ),
				'selector' => '{{WRAPPER}} .'.$this->blog_title.' .title',
			]
		);

		$this->end_controls_section();

		// Pause Play Icon 
		$this->start_controls_section(
			'play_icon_style',
			[
				'label' => __( 'Pause/Play Settings', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$slug = 'play_icon';		
		$this->add_control(
			$slug.'_color',
			[
				'label'     => __( 'Color', 'blognews-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}  .'.$this->blog_btn.' span' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			$slug.'_bg_color',
			[
				'label'     => __( 'Background Color', 'blognews-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'.$this->blog_btn.'' => 'background-color: {{VALUE}}',
				],
			]
		);
	
		$this->add_responsive_control(
			$slug.'_width',
			[
				'label'           => __( 'Width', 'blognews-for-elementor' ),
				'type'            => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'range' => [
					'px'  => ['min' => 0, 'max' => 60],
				],
				'default_desktop' => ['size' => '', 'unit' => 'px'],
				'tablet_default'  => ['size' => '', 'unit' => 'px'],
				'mobile_default'  => ['size' => '', 'unit' => 'px'],
				'selectors'       => [
					'{{WRAPPER}} .'.$this->blog_btn.'' => 'width: {{SIZE}}{{UNIT}};',
				],
			],
		);

		$this->add_responsive_control(
			$slug.'_size',
			[
				'label'           => __( 'Size', 'blognews-for-elementor' ),
				'type'            => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'range' => [
					'px'  => ['min' => 0, 'max' => 50],
				],
				'default_desktop' => ['size' => '', 'unit' => 'px'],
				'tablet_default'  => ['size' => '', 'unit' => 'px'],
				'mobile_default'  => ['size' => '', 'unit' => 'px'],
				'selectors'       => [
					'{{WRAPPER}} .'.$this->blog_btn.' span' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => $slug.'_border_type',
				'label'    => 'Border Type',
				'selector' => '{{WRAPPER}}  .'.$this->blog_btn,
			]
		);
		$this->add_responsive_control(
			$slug.'_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .'.$this->blog_btn => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
		
		Notices::go_premium_notice_style($this, 'notice_two');
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$show_play = $settings['show_play']; 
	
		$media_type = $settings['media_type']; 
		$ticker_post_icon = $settings['ticker_post_icon'];
		$wtitle = $settings['ticker_title'];
		$wtitle_icon = $settings['ticker_icon'];
		$directon_type = $settings['directon_type']; 
		$slide_speed = $settings['slide_speed']; 
		
		
		// Corrected paged value
		$paged = max(1, get_query_var('paged', get_query_var('page', 1)));
	
		// Updated query arguments
		$args = [
			'posts_per_page' => $settings['number_of_posts'] ?? 5,
			'orderby'        => $settings['order_by'] ?? 'ID',
			'order'          => $settings['order'] ?? 'DESC',
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'paged'          => $paged,
			'ignore_sticky_posts' => 1,
		];
	
		if (!empty($settings['post_category'])) {
			$args['category__in'] = array_map('intval', (array) $settings['post_category']);
		}
	
		if (!empty($settings['post_tags'])) {
			$args['tag__in'] = array_map('intval', (array) $settings['post_tags']);
		}
	
		$wp_query = new WP_Query($args);
		?>
	
		<div class="blogfoel-latest-news <?php echo esc_attr($this->blog_card_class); ?>"> 
			<div class="blogfoel-latest-title">
				<<?php echo esc_attr($settings['title_tag']); ?> class="blogfoel-latest-heading <?php echo esc_attr($this->blog_head); ?>">
				<?php  
					\Elementor\Icons_Manager::render_icon($wtitle_icon, ['aria-hidden' => 'true']);
					echo esc_html($wtitle); ?>
				</<?php echo esc_attr($settings['title_tag']); ?>>
			</div>
	
			<div class="blogfoel-latest-news-inner <?php echo esc_attr($this->blog_inner); ?>">
				<div class="blogfoel-latest-news-slider" tickerDirection = "<?php echo esc_attr($directon_type) ?>" tickerSpeed = "<?php echo esc_attr($slide_speed) ?>" tickerHover="no">					 
				<?php if ($wp_query->have_posts()) {
					while ($wp_query->have_posts()) { $wp_query->the_post(); 
						$thumbnail_id = get_post_thumbnail_id(); ?>
						<a class="blogfoel-latest-list <?php echo esc_attr($this->blog_title); ?>" href="<?php echo esc_url(get_permalink()); ?>" title="<?php the_title_attribute(); ?>">
						   <?php if ($media_type == 'icon') {
								echo '<span class="blogfoel-ticker-media icon '.esc_attr($this->ticker_media).'">';
									\Elementor\Icons_Manager::render_icon($ticker_post_icon, ['aria-hidden' => 'true']);
								echo '</span>';
							} ?>
							</span>
							<span class="title"><?php echo esc_html(wp_trim_words(get_the_title(), 10)); ?></span>
						</a>
					<?php }
				} ?>
				</div>
			</div>
			<?php if ($show_play === 'yes') : ?>
				<div class="blogfoel-latest-play <?php echo esc_attr($this->blog_btn); ?>">
					<span><i class="fas fa-pause"></i></span>
				</div> 
			<?php endif; ?>	
		</div>
		<?php
		wp_reset_postdata();
	}	
}