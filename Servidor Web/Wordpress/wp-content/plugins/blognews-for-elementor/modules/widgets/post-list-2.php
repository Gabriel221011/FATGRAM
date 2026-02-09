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

class BLOGFOELPostList2 extends \Elementor\Widget_Base {

	private $blog_card_class = 'blogfoel-post-item';
	private $small_blog_card_class = 'blogfoel-small-post';
	private $blog_inner_class = 'blog-post-list-inner';

	public function get_name() {
		return 'blognews-post-list-2';
	}

	public function get_title() {
		return __( 'BN Post List 2', 'blognews-for-elementor' );
	}

	public function get_categories() {
		return [ 'blogfoel-elementor' ];
	}

	public function get_icon() {
		return 'blogfoel-widget-icon bnicon-post-list-2';
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
            'post list',
            'news',
            'list',
            'themeansar',
		];
	}
	protected function register_controls() {
		$this->start_controls_section(
			'post_query_setting',
			[
				'label' => __( 'Post Query', 'blognews-for-elementor' ),
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
					'blogfoel-pro-layout-two' => esc_html__( 'Style 2', 'blognews-for-elementor' ),
					'blogfoel-pro-layout-three' => esc_html__( 'Style 3', 'blognews-for-elementor' ),
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
				'placeholder' => '4',
				'min'         => 1,
				'max'         => 5,
				'default'     => '4',
			]
		);
		$this->add_control(
			'excerpt_length',
			[
				'label' => esc_html__( 'Excerpt Length', 'blognews-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'placeholder' => '20',
				'min'         => 0,
				'max'		  => 50,
				'default'     => 20,
				'description' => 'Enter 0 to hide Excerpt'
			]
		);
		$this->add_control(
            'order_by',
            [
                'label'       => esc_html__( 'Order By', 'blognews-for-elementor' ),
                'placeholder' => esc_html__( 'Order By', 'blognews-for-elementor' ),
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
                'placeholder' => esc_html__( 'Order', 'blognews-for-elementor' ),
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
				'max'		  => 100,
				'selectors'       => [
					'{{WRAPPER}} .blogfoel-title' => '-webkit-line-clamp: {{VALUE}};',
				],
			]
		);
	
		$this->end_controls_section();

		$this->start_controls_section(
			'post_setting',
			[
				'label' => __( 'Post Settings', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
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
					'em'  => ['min' => 0, 'max' => 100],
					'rem' => ['min' => 0, 'max' => 100],
				],
				'default_desktop' => ['size' => '', 'unit' => 'px'],
				'tablet_default'  => ['size' => '', 'unit' => 'px'],
				'mobile_default'  => ['size' => '', 'unit' => 'px'],
				'selectors'       => [
					'{{WRAPPER}} .blogfoel-post-list-grid' => 'grid-row-gap: {{SIZE}}{{UNIT}};',
				],
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
					'show_image' => 'yes',
				],
			]
		);
		$this->add_control(
			'show_small_image',
			[
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Show Small Image', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
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
				'name' => 'thumbnail_small_size', 
				'default' => 'large',
				'condition' => [
					'show_small_image' => 'yes',
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
			'show_small_category',
			[
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Show Small Category', 'blognews-for-elementor' ),
				'label_on' => esc_html__( 'Show', 'blognews-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'blognews-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
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
			'small_post_meta_section',
			[
				'label' => __( 'Small Post Meta', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'show_small_author',
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
			'show_small_avatar',
			[
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Show Avatar', 'blognews-for-elementor' ),
				'label_on' => esc_html__( 'Show', 'blognews-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'blognews-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'no',
				'condition' => [
					'show_small_author' => 'yes',
				],
			]
		);
		$this->add_responsive_control(
			'small_avatar_size',
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
			'small_author_icon',
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
			'show_small_date',
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
			'small_date_icon',
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
			'small_date_format',
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
			'show_small_comments',
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
			'show_small_read_time',
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
			'show_small_edit',
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

		Notices::go_premium_notice_content($this, 'notice_one');

		$this->start_controls_section(
			'post_style',
			[
				'label' => __( 'Post Style', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$slug = 'post';

		$this->add_responsive_control(
			$slug.'_content_align',
			[
				'label'     => __( 'Content Alignment', 'blognews-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'start'   => [
						'title' => __( 'Left', 'blognews-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'blognews-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					],
					'end'  => [
						'title' => __( 'Right', 'blognews-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .blogfoel-post-list-grid .blogfoel-post-item-box .blogfoel-category, {{WRAPPER}} .blogfoel-post-list-grid .blogfoel-post-item-box .blogfoel-meta' => 'justify-content: {{VALUE}}',
					'{{WRAPPER}} .blogfoel-post-list-grid .blogfoel-post-item-box .blogfoel-title, {{WRAPPER}} .blogfoel-post-list-grid .blogfoel-post-item-box .blogfoel-excerpt' => 'text-align: {{VALUE}}',
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
				'selector'  => '{{WRAPPER}} .'.$this->blog_card_class,
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => $slug.'_border_type',
				'label'    => 'Border Type',
				'selector' => '{{WRAPPER}} .'.$this->blog_card_class,
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
			$slug.'_inner_padding',
			[
				'label' => esc_html__( 'Large Inner Padding', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .'.$this->blog_card_class .' .blogfoel-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
	
		$this->end_controls_section();

		$this->start_controls_section(
			'small_post_style',
			[
				'label' => __( 'Small Post Style', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$slug = 'small_post';

		$this->add_responsive_control(
			$slug.'_content_align',
			[
				'label'     => __( 'Content Alignment', 'blognews-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'start'   => [
						'title' => __( 'Left', 'blognews-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'blognews-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					],
					'end'  => [
						'title' => __( 'Right', 'blognews-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .blogfoel-small-post .blogfoel-category, {{WRAPPER}} .blogfoel-small-post .blogfoel-meta' => 'justify-content: {{VALUE}}',
					'{{WRAPPER}} .blogfoel-small-post .blogfoel-title' => 'text-align: {{VALUE}}',
				],
			]
		);
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
					'{{WRAPPER}} .'. $this->small_blog_card_class => 'align-items: {{VALUE}};',
				],
			]
		);
		$this->add_responsive_control(
			$slug.'_gap',
			[
				'label' => esc_html__( 'Column Space', 'blognews-for-elementor' ). BLOGFOEL_PRO_ICON,
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px'  => ['min' => 0, 'max' => 300],
					'em'  => ['min' => 0, 'max' => 100],
					'rem' => ['min' => 0, 'max' => 100],
				],
				'default_desktop' => ['size' => '', 'unit' => 'px'],
				'tablet_default'  => ['size' => '', 'unit' => 'px'],
				'mobile_default'  => ['size' => '', 'unit' => 'px'],
				'classes' => 'blogfoel-pro-popup-notice',
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
				'selector'  => '{{WRAPPER}} .'.$this->small_blog_card_class,
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => $slug.'_border_type',
				'label'    => 'Border Type',
				'selector' => '{{WRAPPER}} .'.$this->small_blog_card_class,
			]
		);
		$this->add_responsive_control(
			$slug.'_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .'.$this->small_blog_card_class => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .'.$this->small_blog_card_class => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			$slug.'_inner_padding',
			[
				'label' => esc_html__( 'Small Inner Padding', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .'.$this->small_blog_card_class .' .blogfoel-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
		
		$this->end_controls_section();

		$this->start_controls_section(
			'image_style',
			[
				'label' => __( 'Image Style', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$slug = 'image';

		$this->add_responsive_control(
			$slug.'large_height',
			[
				'label' => esc_html__( 'Large Image Height', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px'  => ['min' => 0, 'max' => 1000],
					'%'  => ['min' => 0, 'max' => 100],
					'em'  => ['min' => 0, 'max' => 100],
					'rem' => ['min' => 0, 'max' => 100],
				],
				'default_desktop' => ['size' => '', 'unit' => 'px'],
				'tablet_default'  => ['size' => '', 'unit' => 'px'],
				'mobile_default'  => ['size' => '', 'unit' => 'px'],
				'selectors'       => [
					'{{WRAPPER}} .'.$this->blog_card_class.' .blogfoel-img-wraper' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			$slug.'small_height',
			[
				'label' => esc_html__( 'Small Image Height', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px'  => ['min' => 0, 'max' => 1000],
					'%'  => ['min' => 0, 'max' => 100],
					'em'  => ['min' => 0, 'max' => 100],
					'rem' => ['min' => 0, 'max' => 100],
				],
				'default_desktop' => ['size' => '', 'unit' => 'px'],
				'tablet_default'  => ['size' => '', 'unit' => 'px'],
				'mobile_default'  => ['size' => '', 'unit' => 'px'],
				'selectors'       => [
					'{{WRAPPER}} .'.$this->small_blog_card_class.' .blogfoel-back-img' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			$slug.'small_width',
			[
				'label' => esc_html__( 'Small Image Width', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px'  => ['min' => 0, 'max' => 1000],
					'%'  => ['min' => 0, 'max' => 100],
					'em'  => ['min' => 0, 'max' => 100],
					'rem' => ['min' => 0, 'max' => 100],
				],
				'default_desktop' => ['size' => '', 'unit' => 'px'],
				'tablet_default'  => ['size' => '', 'unit' => 'px'],
				'mobile_default'  => ['size' => '', 'unit' => 'px'],
				'selectors'       => [
					'{{WRAPPER}} .'.$this->small_blog_card_class.' .blogfoel-back-img' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			$slug.'_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .'.$this->blog_card_class.' .blogfoel-img-wraper, {{WRAPPER}} .'.$this->small_blog_card_class.' .blogfoel-back-img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'category_tab_style',
			[
				'label' => __( 'Category Style', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'show_category' => 'yes'
				],
			]
		);
		$slug = 'blog_category';
	
		blogfoel_category_style_control( $this , $slug , 'blogfoel-post-list-grid .blogfoel-category' );

		$this->end_controls_section();

		$this->start_controls_section(
			'title_style',
			[
				'label' => __( 'Title Style', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
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
					'{{WRAPPER}} .blogfoel-post-list-grid .blogfoel-title a' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .blogfoel-post-list-grid .blogfoel-title a:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}}' => '--title-hover-color: {{VALUE}};',
				],
			]
		);
	
		$this->end_controls_tab();
		$this->end_controls_tabs();

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
				'selector' => '{{WRAPPER}} .'.$this->blog_card_class.' .blogfoel-title',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => $slug.'_small_typography',
				'label'    => 'Small Typography',
				'selector' => '{{WRAPPER}} .'.$this->small_blog_card_class.' .blogfoel-title',
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
				'label' => __( 'Meta Style', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$slug = 'metas';

		blogfoel_meta_style_control( $this , $slug , 'blogfoel-post-list-grid .blogfoel-meta' );

		$this->end_controls_section();

		$this->start_controls_section(
			'excerpt_style',
			[
				'label' => __( 'Excerpt Style', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$slug = 'excerpt';
		$this->add_control(
			$slug.'_color',
			[
				'label' => esc_html__( 'Color', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'.$this->blog_card_class.' .blogfoel-excerpt' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => $slug.'_typography',
                'label'     => __( 'Typography', 'blognews-for-elementor' ),
				'selector' => '{{WRAPPER}} .'.$this->blog_card_class.' .blogfoel-excerpt',
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
		
		Notices::go_premium_notice_style($this, 'notice_two');
	}

	protected function render() {
		$settings       = $this->get_settings_for_display();
		$show_category  = $settings['show_category'];
		$show_small_category  = $settings['show_small_category'];
		$show_image      = $settings['show_image'];
		$show_small_image      = $settings['show_small_image'];
		$title_html_tag = $settings['title_html_tag'];
		$layout_style = $settings['layout_style']; 
		
		$title_animation_type = $settings['title_animation_type'];
		$custom_animation     = $settings['title_custom_animation'];
		$title_animation      = $custom_animation;
		
		// Corrected paged value
		$paged = (get_query_var('paged')) ? absint(get_query_var('paged')) : (get_query_var('page') ? absint(get_query_var('page')) : 1);
	
		// Updated query arguments
		$args = [
			'posts_per_page' => $settings['number_of_posts'] ?? 4,
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
			<div class="blogfoel-post-list-grid <?php echo esc_attr($layout_style) ?>">
				<?php if ($wp_query->have_posts()) {
					$post_count = 0;
					while ($wp_query->have_posts()) { $wp_query->the_post();
						$excerpt_length = $settings['excerpt_length'] ?? 20;
						$thumbnail_id = get_post_thumbnail_id();
						$image_class = empty($thumbnail_id) ? ' no-image' : '';
						$categories = get_the_category();
						$comments_count = get_comments_number();
						$file_path = BLOGFOEL_DIR_PATH . 'modules/layouts/post-list-2/layout-1.php';
				
						if (file_exists($file_path)) {
							require $file_path;
						}
						$post_count++;
					}
				} else { ?>
					<p><?php esc_html_e('No posts found.', 'blognews-for-elementor'); ?></p>
				<?php } ?>
			</div>
			<?php
			// Reset post data
			wp_reset_postdata();
			?>
		</div>
		<?php
	}
}