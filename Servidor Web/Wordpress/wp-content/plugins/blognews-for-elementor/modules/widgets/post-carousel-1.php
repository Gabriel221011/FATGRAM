<?php

namespace BlogFoel;

use Elementor\Controls_Manager;
use Elementor\Widget_Base;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use ElementorPro\Modules\Posts\Widgets\Posts_Base;
use Elementor\Group_Control_Background;
use WP_Query;
use BlogFoel\Notices;
use Elementor\Repeater;

class BLOGFOELPostCarousel1 extends \Elementor\Widget_Base {

	private $blog_card_class = 'blogfoel-post-item';
	private $blog_inner_class = 'blogfoel-inner';
	private $blog_category = 'blogfoel-category';
	private $blog_image = 'blogfoel-img-wraper';
	private $blog_title = 'blogfoel-title';
	private $blog_meta = 'blogfoel-meta';
	private $blog_button = 'blogfoel-button';

	public function get_name() {
		return 'blognews-post-carousel-1';
	}

	public function get_title() {
		return __( 'BN Post Carousel 1', 'blognews-for-elementor' );
	}

	public function get_categories() {
		return [ 'blogfoel-elementor' ];
	}

	public function get_icon() {
		return 'blogfoel-widget-icon bnicon-post-carousel-1';
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
            'post carousel 1',
            'news',
            'themeansar',
			'slider',
			'carousel',
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
			'layout_style',
			[
				'label'       => esc_html__( 'Layout Style', 'blognews-for-elementor' ),
				'placeholder' => esc_html__( 'Choose layout Style from Here', 'blognews-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'layout-one',
				'options'     => [
					'layout-one'   => esc_html__( 'Style 1', 'blognews-for-elementor' ),
					'layout-two'   => esc_html__( 'Style 2 (Pro)', 'blognews-for-elementor' ),
					'layout-three' => esc_html__( 'Style 3 (Pro)', 'blognews-for-elementor' ),
					'layout-four'  => esc_html__( 'Style 4 (Pro)', 'blognews-for-elementor' ),
				],
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
				'type' => \Elementor\Controls_Manager::NUMBER,
				'placeholder' => '5',
				'min'         => 1,
				'max'		  => 5,
				'default'     => 5,
			]
		);
		$this->add_control(
			'excerpt_length',
			[
				'label' => esc_html__( 'Excerpt Length', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min' => 0,
				'max' => 50,
				'step' => 1,
				'default' => 20,
				'placeholder' => '20',
				'description' => 'Enter 0 to hide Excerpt',
				'classes' => 'blogfoel-pro-popup-notice',
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
				'label' => esc_html__( 'Show Image', 'blognews-for-elementor' ),
				'label_on' => esc_html__( 'Show', 'blognews-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'blognews-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
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
			'show_category',
			[
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Show Category', 'blognews-for-elementor' ),
				'label_on' => esc_html__( 'Show', 'blognews-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'blognews-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
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
		$this->add_control(
			'show_title',
			[
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Show Title', 'blognews-for-elementor' ),
				'label_on' => esc_html__( 'Show', 'blognews-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'blognews-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		$this->add_control(
			'show_excerpt',
			[
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Show Excerpt', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON ,
				'label_on' => esc_html__( 'Show', 'blognews-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'blognews-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'no',
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'post_meta_section',
			[
				'label' => __( 'Post Meta', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
	
		$this->add_control(
			'show_author',
			[
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Show Author', 'blognews-for-elementor' ),
				'label_on' => esc_html__( 'Show', 'blognews-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'blognews-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		$this->add_control(
			'show_avatar',
			[
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Show Avatar', 'blognews-for-elementor' ),
				'label_on' => esc_html__( 'Show', 'blognews-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'blognews-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'no',
				'condition' => [
					'show_author' => 'yes',
				],
			]
		);
		$this->add_responsive_control(
			'avatar_size',
			[
				'label' => esc_html__( 'Size', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
				'type' => \Elementor\Controls_Manager::NUMBER,
				'placeholder' => '30',
				'min'         => 1,
				'max'		  => 100,
				'default'     => '',
				'condition' => [
					'show_avatar' => 'yes',
					'show_author' => 'yes',
				],
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);
		$this->add_control(
			'author_icon',
			[
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Show Icon', 'blognews-for-elementor' ),
				'label_on' => esc_html__( 'Show', 'blognews-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'blognews-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => [
					'show_author' => 'yes',
					'show_avatar!' => 'yes',
				],
			]
		);
		$this->add_control(
			'show_date',
			[
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Show Date', 'blognews-for-elementor' ),
				'label_on' => esc_html__( 'Show', 'blognews-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'blognews-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'separator' => 'before',
			]
		);
		$this->add_control(
			'date_icon',
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
			'date_format',
			[
				'label'       => esc_html__( 'Date Format', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON ,
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'default',
				'options'     => [
					'default'       => esc_html__( 'Default', 'blognews-for-elementor' ),
					'wordpress'       => esc_html__( 'Wordpress', 'blognews-for-elementor' ),
				],
				'condition' => [
					'show_date' => 'yes'
				],
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);
		$this->add_control(
			'show_comments',
			[
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Show Comments', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON ,
				'label_on' => esc_html__( 'Show', 'blognews-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'blognews-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'no',
				'separator' => 'before',
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);
		$this->add_control(
			'show_read_time',
			[
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Show Read Time', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON ,
				'label_on' => esc_html__( 'Show', 'blognews-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'blognews-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'no',
				'separator' => 'before',
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);
		
		$this->add_control(
			'show_edit',
			[
				'type' => \Elementor\Controls_Manager::HIDDEN,
				'label' => esc_html__( 'Show Edit', 'blognews-for-elementor' ),
				'label_on' => esc_html__( 'Show', 'blognews-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'blognews-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'no',
				'separator' => 'before',
			]
		);
		
		$this->end_controls_section();

		$this->start_controls_section(
			'slider',
			[
				'label' => __( 'Slider Settings', 'blognews-for-elementor'),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_responsive_control(
			'slide_to_show',
			[
				'label' => esc_html__( 'Slides to Show', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min'         => 1,
				'default'     => '3',
				'max'		  => 4,
			]
		);
		$this->add_responsive_control(
			'slides_to_scroll',
			[
				'label' => esc_html__( 'Slides to Scroll', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min'         => 1,
				'default'     => '1',
				'max'		  => 12,
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);
		$this->add_responsive_control(
			'slides_space_between',
			[
				'label' => esc_html__( 'Slides Space Between', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min'         => 0,
				'default'     => '16',
				'max'		  => 100,
			]
		);
		$this->add_control(
			'show_navigation_arrow',
			[
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Show Navigation Arrow', 'blognews-for-elementor' ) .BLOGFOEL_PRO_ICON,
				'label_on' => esc_html__( 'Show', 'blognews-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'blognews-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);
		$this->add_control(
			'dot_clickable',
			[
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Dot Clickable', 'blognews-for-elementor' ) .BLOGFOEL_PRO_ICON,
				'label_on' => esc_html__( 'Show', 'blognews-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'blognews-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);
		$this->add_control(
			'autoplay',
			[
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Autoplay', 'blognews-for-elementor' ),
				'label_on' => esc_html__( 'Show', 'blognews-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'blognews-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		$this->add_control(
			'autoplay_speed',
			[
				'label' => esc_html__( 'AutoPlay Speed', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'placeholder' => '3000',
				'min'         => 1,
				'max'		  => 10000,
				'default'     => 3000,
				'condition'   => [
					'autoplay' => 'yes',
				],
			]
		);
		$this->add_control(
			'transition_between_slides',
			[
				'label' => esc_html__( 'Slide Switch Speed', 'blognews-for-elementor' ) .BLOGFOEL_PRO_ICON,
				'type' => \Elementor\Controls_Manager::NUMBER,
				'placeholder' => '1000',
				'min'         => 999,
				'max'		  => 1000,
				'default'     => 1000,
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);
		$this->add_control(
			'loop',
			[
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Loop', 'blognews-for-elementor' ),
				'label_on' => esc_html__( 'Show', 'blognews-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'blognews-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		$this->add_control(
			'display_dots',
			[
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Show Pagination Dots', 'blognews-for-elementor' ),
				'label_on' => esc_html__( 'Show', 'blognews-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'blognews-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		$this->add_control(
			'show_scrollbar',
			[
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Show Scroll Bar', 'blognews-for-elementor' ) .BLOGFOEL_PRO_ICON,
				'label_on' => esc_html__( 'Show', 'blognews-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'blognews-for-elementor' ),
				'return_value' => 'no',
				'default' => 'no',
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);
		$this->end_controls_section();
		
		Notices::go_premium_notice_content($this, 'notice_one');

		$this->start_controls_section(
			'blog_style',
			[
				'label' => __( 'Blog Style', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$slug = 'blog';
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
			$slug.'_inner_padding',
			[
				'label' => esc_html__( 'Inner Padding', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .'.$this->blog_inner_class => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
			'category_tab_style',
			[
				'label' => __( 'Category', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_category' => 'yes'
				],
			]
		);

		$slug = 'blog_category';
		$this->add_control(
			$slug.'_position',
			[
				'label'       => esc_html__( 'Position', 'blognews-for-elementor' ),
				'placeholder' => esc_html__( 'Choose Category Position from Here', 'blognews-for-elementor' ),
				'type'        => 'blogfoel-select',
				'label_block' => false,
				'default'     => 'category-bottom',
				'options'     => [
					'category-bottom'      => esc_html__( 'Above Title', 'blognews-for-elementor' ),
					'blogfoel-pro-category-top-left'    => esc_html__( 'Top Left (Pro)', 'blognews-for-elementor' ),
					'blogfoel-pro-category-top-right'   => esc_html__( 'Top Right (Pro)', 'blognews-for-elementor' ),
				],
			]
		);
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
				'condition' => [
					$slug.'_position' => 'category-bottom'
				],
			]
		);
		
		blogfoel_category_style_control( $this , $slug , $this->blog_category );

		$this->end_controls_section();

		$this->start_controls_section(
			'image_style',
			[
				'label' => __( 'Image', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$slug = 'image';
		
		$this->add_responsive_control(
			$slug.'_height',
			[
				'label' => esc_html__( 'Height', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem','vh', 'custom' ],
				'range' => [
					'px'  => ['min' => 0, 'max' => 1000],
					'em'  => ['min' => 0, 'max' => 100],
					'rem' => ['min' => 0, 'max' => 100],
					'vh'  => ['min' => 0, 'max' => 200],
				],
				'devices'         => [ 'desktop', 'tablet', 'mobile' ],
				'default'         => ['size' => '350', 'unit' => 'px'],
				'tablet_default'  => ['size' => '', 'unit' => 'px'],
				'mobile_default'  => ['size' => '', 'unit' => 'px'],
				'selectors'       => [
					'{{WRAPPER}} .'.$this->blog_card_class => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => $slug.'_overlay_color',
				'types'          => [ 'classic', 'gradient' ],
				'exclude'        => [ 'image' ],
				'fields_options' => [
					'background' => [
						'label' => esc_html__( 'Overlay Color', 'blognews-for-elementor' ),
						'default' => 'gradient',
					],
					'color' => [
						'default' => '#fff0',
					],
					'color_stop' => [
						'default' => [
							'unit' => '%',
							'size' => 50,
						],
					],
					'color_b' => [
						'default' => '#000',
					],
					'color_b_stop' => [
						'default' => [
							'unit' => '%',
							'size' => 90,
						],
					],
				],
				'selector'  => '{{WRAPPER}} .'.$this->blog_card_class.' .blogfoel-img-wraper:before',
			]
		);
		$this->add_responsive_control(
			$slug.'_opacity',
			[
				'label' => esc_html__( 'Opacity', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px'  => ['min' => 0, 'step' => 0.01, 'max' => 1],
				],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'default' => ['size' => '', 'unit' => 'px'],
				'tablet_default'  => ['size' => '', 'unit' => 'px'],
				'mobile_default'  => ['size' => '', 'unit' => 'px'],
				'selectors'       => [
					'{{WRAPPER}} .'.$this->blog_card_class.' .blogfoel-img-wraper:before' => 'opacity: {{SIZE}};',
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
		$this->end_controls_section();


		$this->start_controls_section(
			'title_style',
			[
				'label' => __( 'Title', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_title' => 'yes',
				],
			]
		);
		$slug = 'title';
		$this->add_control(
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
						'title' => __( 'Elementor (Pro)', 'blognews-for-elementor' ),
						'icon'  => 'eicon-elementor',
					],
				],
				'default'   => 'custom',
                'label_block'   =>  true,
			]
		);
		$this->add_control(
            $slug.'_custom_animation',
            [
                'label'       => esc_html__( 'Animation', 'blognews-for-elementor' ),
                'type'        => \Elementor\Controls_Manager::SELECT,
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

		$this->add_control(
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
					'{{WRAPPER}} .'.$this->blog_title.' a' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			$slug.'_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
				'type' => \Elementor\Controls_Manager::COLOR,
				'classes' => 'blogfoel-pro-popup-notice',
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
				'default' => '#fff',
				'selectors' => [
					'{{WRAPPER}} .'.$this->blog_title.' a:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}}' => '--title-hover-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			$slug.'_bg_hover_color',
			[
				'label' => esc_html__( 'Background Color', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
				'type' => \Elementor\Controls_Manager::COLOR,
				'classes' => 'blogfoel-pro-popup-notice',
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
		$this->add_control(
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
		$this->add_responsive_control(
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
					'{{WRAPPER}} .'.$this->blog_title => 'text-align: {{VALUE}}',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => $slug.'_typography',
                'label'     => __( 'Typography', 'blognews-for-elementor' ),
				'selector' => '{{WRAPPER}} .'.$this->blog_title,
			]
		);

		$this->add_responsive_control(
			$slug.'_margin',
			[
				'label' => esc_html__( 'Margin', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);

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

		//  Navigation
		blogfoel_navigation_style( $this , [] );
		//  Pagination
		blogfoel_pagination_style( $this , [] );
		Notices::go_premium_notice_style($this, 'notice_two');
	}

	protected function render() {
		$settings          = $this->get_settings_for_display();

		// Corrected paged value
		$paged = (get_query_var('paged')) ? absint(get_query_var('paged')) : (get_query_var('page') ? absint(get_query_var('page')) : 1);
	
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
			$args['category__in'] = (array) $settings['post_category'];
		}
	
		if (!empty($settings['post_tags'])) {
			$args['tag__in'] = (array) $settings['post_tags'];
		}
	
		$wp_query = new WP_Query($args);
		?>
		<div class="blogfoel-widget-wrapper">
			<?php $this->blogfoel_carousel1_template($settings, $wp_query);?>
			<?php
			// Reset post data
			wp_reset_postdata();
			?>
		</div>
		<?php
	}
	function blogfoel_carousel1_template( $settings, $wp_query ) {
		$element_id                  = 'blogfoel-slider-' . $this->get_id();
		$slide_to_show               = isset( $settings['slide_to_show'] ) && $settings['slide_to_show'] ? $settings['slide_to_show'] : 3;
		$slide_to_show_tablet        = isset( $settings['slide_to_show_tablet'] ) && $settings['slide_to_show_tablet'] ? $settings['slide_to_show_tablet'] : 2;
		$slide_to_show_mobile        = isset( $settings['slide_to_show_mobile'] ) && $settings['slide_to_show_mobile'] ? $settings['slide_to_show_mobile'] : 1;
		$slides_to_scroll            = isset( $settings['slides_to_scroll'] ) && $settings['slides_to_scroll'] ? $settings['slides_to_scroll'] : 3;
		$slides_to_scroll_mobile     = isset( $settings['slides_to_scroll_mobile'] ) && $settings['slides_to_scroll_mobile'] ? $settings['slides_to_scroll_mobile'] : 1;
		$slides_to_scroll_tablet     = isset( $settings['slides_to_scroll_tablet'] ) && $settings['slides_to_scroll_tablet'] ? $settings['slides_to_scroll_tablet'] : 1;
		$slides_space_between        = isset( $settings['slides_space_between'] ) && $settings['slides_space_between'] ? $settings['slides_space_between'] : 10;
		$slides_space_between_mobile = isset( $settings['slides_space_between_mobile'] ) && $settings['slides_space_between_mobile'] ? $settings['slides_space_between_mobile'] : 2;
		$slides_space_between_tablet = isset( $settings['slides_space_between_tablet'] ) && $settings['slides_space_between_tablet'] ? $settings['slides_space_between_tablet'] : 5;
		$autoplay       = ( $settings['autoplay'] ?? '' ) === 'yes';
		$autoplay_speed = $settings['autoplay_speed'] ?? 3000;
		$transition_between_slides = $settings['transition_between_slides'] ?? 1000;
		$loop            = ( $settings['loop'] ?? '' ) === 'yes';
		$mousewheel      = ( $settings['mousewheel'] ?? '' ) === 'no';
		$keyboard_control = ( $settings['keyboard_control'] ?? '' ) === 'yes';
		$dot_clickable   = ( $settings['dot_clickable'] ?? '' ) === 'yes';
		$show_category     = $settings['show_category'];
		$show_image        = $settings['show_image'];
		$show_title        = $settings['show_title'];
		$title_html_tag    = $settings['title_html_tag'];
		$layout_style      = $settings['layout_style'];
		$category_style     = $settings['category_style'];

		$title_animation_type = $settings['title_animation_type'];
		$custom_animation     = $settings['title_custom_animation'];
		$title_animation      = $custom_animation;
		$category_position = $settings['blog_category_position'];

		$this->add_render_attribute( 'wrapper', 'class', 'blogfoel-slider-wrap' );
		$this->add_render_attribute( 'wrapper', 'data-wid', $this->get_id() );
		echo '<div ' . wp_kses_post( $this->get_render_attribute_string( 'wrapper' ) ) . '>';
		echo '<div
			class="blogfoel-carousel blogfoel-post-blog slider two '.esc_attr($category_position).'"
			id="' . esc_attr( $element_id ) . '"
			data-slide-to-show="' . esc_html( $slide_to_show ) . '"
			data-slide-to-show-mobile="' . esc_html( $slide_to_show_mobile ) . '"
			data-slide-to-show-tablet="' . esc_html( $slide_to_show_tablet ) . '"
			data-slides-to-scroll="' . esc_html( $slides_to_scroll ) . '"
			data-slides-to-scroll-mobile="' . esc_html( $slides_to_scroll_mobile ) . '"
			data-slides-to-scroll-tablet="' . esc_html( $slides_to_scroll_tablet ) . '"
			data-slides-space-between="' . esc_html( $slides_space_between ) . '"
			data-slides-space-between-mobile="' . esc_html( $slides_space_between_mobile ) . '"
			data-slides-space-between-tablet="' . esc_html( $slides_space_between_tablet ) . '"
			data-autoplay="' . esc_html( $autoplay ) . '"
			data-autoplay-speed="' . esc_html( $autoplay_speed ) . '"
			data-transition_between_slides="' . esc_html( $transition_between_slides ) . '"
			data-loop="' . esc_html( $loop ) . '"
			data-mousewheel="' . esc_html( $mousewheel ) . '"
			data-keyboard_control="' . esc_html( $keyboard_control ) . '"
			data-clickable="' . esc_html( $dot_clickable ) . '"
		>';
	
		$swiper_container_class = 'swiper swiper-container swiper-' . $this->get_id();
		?>
		<!-- Slider main container -->
		<div class="<?php echo esc_attr( $swiper_container_class ); ?>">
			<!-- Additional required wrapper -->
			<div class="swiper-wrapper">
				<?php
				if ( $wp_query->have_posts() ) {
					while ( $wp_query->have_posts() ) {
						$wp_query->the_post();
						$thumbnail_id = get_post_thumbnail_id();
						$image_class = empty($thumbnail_id) ? ' no-image' : '';
						$categories = get_the_category();
						$comments_count = get_comments_number();
						echo '<div class="swiper-slide blogfoel-swiper-slide blogfoel-tabs-' . esc_attr( $this->get_id() ) . '">';
						switch ($layout_style) {
							case 'layout-one':
								$file_path = BLOGFOEL_DIR_PATH . 'modules/layouts/post-blog-2/layout-1.php';
								break;
							default:
								$file_path = BLOGFOEL_DIR_PATH . 'modules/layouts/post-blog-2/layout-1.php';
								break;
						}
						if (file_exists($file_path)) {
							require $file_path;
						}
						echo '</div>';
					}
				} else {
					echo '<p>' . esc_html__( 'No posts found.', 'blognews-for-elementor' ) . '</p>';
				}
				?>
			</div>
	
			<?php if ( ( $settings['display_dots'] ?? '' ) === 'yes' ) : ?>
				<div class="swiper-pagination"></div>
			<?php endif; ?>
	
			<?php if ( ( $settings['show_navigation_arrow'] ?? '' ) === 'yes' ) : ?>
				<div class="swiper-button-prev"></div>
				<div class="swiper-button-next"></div>
			<?php endif; ?>
			<?php if ( ( $settings['show_scrollbar'] ?? '' ) === 'yes' ) : ?>
				<div class="swiper-scrollbar"></div>
			<?php endif; ?>
		</div>
		<?php
		echo '</div>';
		echo '</div>';
	}	
}