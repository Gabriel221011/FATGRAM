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

class BLOGFOELPostBlog1 extends \Elementor\Widget_Base {

	private $blog_card_class = 'blogfoel-post-item';
	private $blog_inner_class = 'blogfoel-inner';
	private $blog_category = 'blogfoel-category';
	private $blog_image = 'blogfoel-img-wraper';
	private $blog_title = 'blogfoel-title';
	private $blog_desc = 'blogfoel-excerpt';
	private $blog_meta = 'blogfoel-meta';
	private $blog_button = 'blogfoel-button';
	private $blog_nav = 'blogfoel-navigation';

	public function get_name() {
		return 'blognews-post-blog-1';
	}

	public function get_title() {
		return __( 'BN Post Blog 1', 'blognews-for-elementor' );
	}

	public function get_categories() {
		return [ 'blogfoel-elementor' ];
	}

	public function get_icon() {
		return 'blogfoel-widget-icon bnicon-post-blog-1';
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
            'grid',
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
			'layout_style',
			[
				'label'       => esc_html__( 'Layout Style', 'blognews-for-elementor' ),
				'placeholder' => esc_html__( 'Choose layout Style from Here', 'blognews-for-elementor' ),
				'type'        => 'blogfoel-select',
				'label_block' => false,
				'default'     => 'layout-one',
				'options'     => [
					'layout-one'   => esc_html__( 'Style 1', 'blognews-for-elementor' ),
					'layout-two'   => esc_html__( 'Style 2', 'blognews-for-elementor' ),
					'blogfoel-pro-layout-three' => esc_html__( 'Style 3 (Pro)', 'blognews-for-elementor' ),
					'blogfoel-pro-layout-four'  => esc_html__( 'Style 4 (Pro)', 'blognews-for-elementor' ),
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
				'placeholder' => '6',
				'min'         => 1,
				'max'		  => 10,
				'default'     => 6,
			]
		);
		$this->add_control(
			'excerpt_length',
			[
				'label' => esc_html__( 'Excerpt Length', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'placeholder' => '20',
				'min'         => 0,
				'max'		  => 50,
				'default'     => 20,
				'description' => 'Enter 0 to hide Excerpt',
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
		$this->add_responsive_control(
			'columns',
			[
				'label' => esc_html__( 'Column', 'blognews-for-elementor' ),
				'type' => Controls_Manager::NUMBER,
				'min' => 1,
				'max' => 5,
				'step' => 1,
				'placeholder' => '3',
				'default' => '',
				'selectors'       => [
					'{{WRAPPER}} .blogfoel-post-blog' => 'grid-template-columns: repeat({{VALUE}}, 1fr);',
				],
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
					'em'  => ['min' => 0, 'max' => 100],
					'rem' => ['min' => 0, 'max' => 100],
				],
				'devices'         => [ 'desktop', 'tablet', 'mobile' ],
				'default'         => ['size' => '', 'unit' => 'px'],
				'tablet_default'  => ['size' => '', 'unit' => 'px'],
				'mobile_default'  => ['size' => '', 'unit' => 'px'],
				'selectors'       => [
					'{{WRAPPER}} .blogfoel-post-blog' => 'grid-column-gap: {{SIZE}}{{UNIT}};',
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
					'em'  => ['min' => 0, 'max' => 100],
					'rem' => ['min' => 0, 'max' => 100],
				],
				'devices'         => [ 'desktop', 'tablet', 'mobile' ],
				'default'         => ['size' => '', 'unit' => 'px'],
				'tablet_default'  => ['size' => '', 'unit' => 'px'],
				'mobile_default'  => ['size' => '', 'unit' => 'px'],
				'selectors'       => [
					'{{WRAPPER}} .blogfoel-post-blog' => 'grid-row-gap: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'show_image',
			[
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Show Image', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
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
			'show_category',
			[
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Show Category', 'blognews-for-elementor' ),
				'label_on' => esc_html__( 'Show', 'blognews-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'blognews-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => [
					'layout_style' => 'layout-two'
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
					'excerpt'	=> esc_html__( 'Excerpt', 'blognews-for-elementor' ),
					'button'	=> esc_html__( 'Button', 'blognews-for-elementor' ),
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
					[
						'post_content' => 'excerpt',
					],
					[
						'post_content' => 'button',
					],
				],
				'condition' => [
					'layout_style' => 'layout-one'
				],
				'title_field' => '{{{ post_content.charAt(0).toUpperCase() + post_content.slice(1) }}}',
			]
		);

		$this->add_control(
			'post_content_two_repeater',
			[
				'label'       => esc_html__('Post Content', 'blognews-for-elementor'),
				'type'        => Controls_Manager::REPEATER,
				'fields'      => $repeater->get_controls(),
				'default' => [
					[
						'post_content' => 'title',
						'show_content' => 'yes',
					],
					[
						'post_content' => 'meta',
						'show_content' => 'yes',
					],
					[
						'post_content' => 'excerpt',
						'show_content' => 'yes',
					],
					[
						'post_content' => 'button',
						'show_content' => 'no',
					],
				],
				'condition' => [
					'layout_style' => 'layout-two'
				],
				'title_field' => '{{{ post_content.charAt(0).toUpperCase() + post_content.slice(1) }}}',
			]
		);
		$repeater = new Repeater();
		$repeater->add_control(
			'post_meta', 
			[
				'label' => __( 'Select Post Meta', 'blognews-for-elementor' ),
				'type'        => 'blogfoel-select',
				'label_block' => false,
				'default'     => 'author',
				'options'     => [
					'author'	=> esc_html__( 'Author', 'blognews-for-elementor' ),
					'date'	=> esc_html__( 'Date', 'blognews-for-elementor' ),
					'blogfoel-pro-comments'	=> esc_html__( 'Comments (Pro)', 'blognews-for-elementor' ),
					'blogfoel-pro-read-time'	=> esc_html__( 'Read Time (Pro)', 'blognews-for-elementor' ),
					'edit'	=> esc_html__( 'Edit', 'blognews-for-elementor' ),
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
				'label' => esc_html__( 'Size', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
				'type' => \Elementor\Controls_Manager::NUMBER,
				'placeholder' => '30',
				'min'         => 1,
				'max'		  => 100,
				'default'     => '',
				'classes' => 'blogfoel-pro-popup-notice',
				'condition' => [
					'show_avatar' => 'yes',
					'post_meta' => 'author',
				],
			]
		);
		$repeater->add_control(
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
					'post_meta' => 'date'
				],
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);
		$repeater->add_control(
			'edit_notice',
			[
				'type' => \Elementor\Controls_Manager::NOTICE,
				'notice_type' => 'info',
				'dismissible' => false,
				'content' => esc_html__( 'This will be for logged-in users', 'blognews-for-elementor' ),
				'condition' => [
					'post_meta' => 'edit'
				],
			]
		);
		$repeater->add_control(
            'edit_title',
            [
                'label'     => __( 'Edit Title', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::TEXT,
                'placeholder'   => __( 'Edit', 'blognews-for-elementor' ),
                'default'   => __( 'Edit', 'blognews-for-elementor' ),
				'condition' => [
					'post_meta' => 'edit'
				],
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
					[
						'post_meta' => 'edit',
						'meta_icon' => 'yes',
					],
				],
				'title_field' => '{{{ post_meta.charAt(0).toUpperCase() + post_meta.slice(1) }}}',
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
			'read_more_btn_settings',
			[
				'label' => __( 'Button', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'btn_title', [
				'label' => __( 'Button Title', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Read More' , 'blognews-for-elementor' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'btn_icon',
			[
				'label' => esc_html__( 'Select Icon', 'blognews-for-elementor' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
                'default' => [
					'value' => 'fas fa-arrow-circle-right',
					'library' => 'fa-solid',
				],
				'label_block' => false,
			]
		);
		$this->add_responsive_control(
			'btn_icon_position',
			[
				'label'     => __( 'Icon Position', 'blognews-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'row-reverse'   => [
						'title' => __( 'Left', 'blognews-for-elementor' ),
						'icon'  => 'eicon-h-align-left',
					],
					'row'  => [
						'title' => __( 'Right', 'blognews-for-elementor' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .'.$this->blog_button.' a' => 'flex-direction: {{VALUE}}',
				],
			]
		);
		$this->add_responsive_control(
			'icon_gap',
			[
				'label' => esc_html__( 'Gap', 'blognews-for-elementor' ),
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
					'{{WRAPPER}} .'.$this->blog_button.' a' => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'pagination',
			[
				'label' => __( 'Pagination', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'pagination_type',
			[
				'label'       => esc_html__( 'Pagination', 'blognews-for-elementor' ),
				'placeholder' => esc_html__( 'Choose pagination to include', 'blognews-for-elementor' ),
				'type'        => 'blogfoel-select',
				'label_block' => false,
				'default'     => 'numbers-dots',
				'options'     => [
					'none' => 'None',
					'numbers' => 'Numbers',		
					'numbers-dots' => 'Numbers With Dots',
					'blogfoel-pro-prev/next' => 'Numbers With Prev/Next (Pro)',
					'blogfoel-pro-dot-prev/next' => 'Numbers With Dots + Prev/Next (Pro)',
				],
			]
		);

        $this->add_responsive_control(
            'pagination_align',
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
					'{{WRAPPER}} ul.page-numbers' => 'justify-content: {{VALUE}}',
				],
				'condition' => [
					'pagination_type!' => 'none'
				],
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
					'{{WRAPPER}} .blogfoel-post-blog .'.$this->blog_card_class.' .'.$this->blog_inner_class => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'layout_style!' => 'layout-two'
				],
			]
		);
		$this->add_control(
			$slug.'_select_align',
			[
				'label'       => __( 'Alignment', 'blognews-for-elementor' ),
				'type'        => 'blogfoel-select',
				'label_block' => false,
				'default'     => 'align-content:flex-start',
				'options'     => [
					'align-content:flex-start'    => esc_html__( 'Top Left', 'blognews-for-elementor' ),
					'justify-content:flex-end'  => esc_html__( 'Top Right', 'blognews-for-elementor' ),
					'blogfoel-pro-right'      => esc_html__( 'Bottom Right (Pro)', 'blognews-for-elementor' ),
					'blogfoel-pro-'    => esc_html__( 'Bottom Left (Pro)', 'blognews-for-elementor' ),
				],
				'selectors' => [
					'{{WRAPPER}} .blogfoel-img-wraper .'. $this->blog_category => '{{VALUE}};',
				],
				'condition' => [
					'layout_style' => 'layout-two'
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
			$slug.'_align',
			[
				'label'     => __( 'Alignment', 'blognews-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'0 auto 0 0'   => [
						'title' => __( 'Left', 'blognews-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					],
					'0 auto' => [
						'title' => __( 'Center', 'blognews-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					],
					'0 0 0 auto'  => [
						'title' => __( 'Right', 'blognews-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .'.$this->blog_image => 'margin: {{VALUE}}',
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
					'px'  => ['min' => 0, 'max' => 1000],
					'em'  => ['min' => 0, 'max' => 100],
					'rem' => ['min' => 0, 'max' => 100],
					'vw'  => ['min' => 0, 'max' => 200],
				],
				'devices'         => [ 'desktop', 'tablet', 'mobile' ],
				'default'         => ['size' => '', 'unit' => 'px'],
				'tablet_default'  => ['size' => '', 'unit' => 'px'],
				'mobile_default'  => ['size' => '', 'unit' => 'px'],
				'selectors'       => [
					'{{WRAPPER}} .'.$this->blog_image => 'width: {{SIZE}}{{UNIT}};',
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
					'px'  => ['min' => 0, 'max' => 1000],
					'em'  => ['min' => 0, 'max' => 100],
					'rem' => ['min' => 0, 'max' => 100],
					'vh'  => ['min' => 0, 'max' => 200],
				],
				'devices'         => [ 'desktop', 'tablet', 'mobile' ],
				'default'         => ['size' => '', 'unit' => 'px'],
				'tablet_default'  => ['size' => '', 'unit' => 'px'],
				'mobile_default'  => ['size' => '', 'unit' => 'px'],
				'selectors'       => [
					'{{WRAPPER}} .'.$this->blog_image => 'height: {{SIZE}}{{UNIT}} !important;',
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
					'{{WRAPPER}} .'.$this->blog_title => 'color: {{VALUE}};',
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
		$this->add_control(
			$slug.'_bg_color',
			[
				'label' => esc_html__( 'Wrapper Background Color', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
				'type' => \Elementor\Controls_Manager::COLOR,
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);
		$this->add_responsive_control(
			$slug.'_padding',
			[
				'label' => esc_html__( 'Wrapper Padding', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);

		blogfoel_meta_style_control( $this , $slug , $this->blog_meta );

		$this->end_controls_section();

		$this->start_controls_section(
			'excerpt_style',
			[
				'label' => __( 'Excerpt', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$slug = 'excerpt';
		
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
					'{{WRAPPER}} .'.$this->blog_desc => 'text-align: {{VALUE}}',
				],
			]
		);

		blogfoel_excerpt_style_control( $this , $slug , $this->blog_desc );

		$this->end_controls_section();

		$this->start_controls_section(
			'button_style',
			[
				'label' => __( 'Button', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$slug = 'blog_button';

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
					'{{WRAPPER}} .'.$this->blog_button => 'text-align: {{VALUE}}',
				],
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
					'{{WRAPPER}} .'.$this->blog_button.' a' => 'color: {{VALUE}};',
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
				'selector'  => '{{WRAPPER}} .'.$this->blog_button.' a',
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
					'{{WRAPPER}} .'.$this->blog_button.' a:hover' => 'color: {{VALUE}};',
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
				'selector'  => '{{WRAPPER}} .'.$this->blog_button.' a:hover',
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
				'selector' => '{{WRAPPER}} .'.$this->blog_button.' a',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => $slug.'_border_type',
				'label'    => 'Border Type',
				'selector' => '{{WRAPPER}} .'.$this->blog_button.' a',
			]
		);
		$this->add_responsive_control(
			$slug.'_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .'.$this->blog_button.' a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .'.$this->blog_button.' a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
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
			'btn_icon_style',
			[
				'label' => __( 'Button Icon', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,				
				'condition' => [
					'btn_icon[value]!' => ''
				],
			]
		);
		$slug = 'blog_button_icon';

		$this->start_controls_tabs( $slug.'_style_tabs' );

		$this->start_controls_tab(
			$slug.'_normal_style',
			[
				'label' => __( 'Normal', 'blognews-for-elementor' ),
			]
		);
		$this->add_control(
			$slug.'_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}}',
				],
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);
		$this->add_control(
			$slug.'_color',
			[
				'label' => esc_html__( 'Color', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'.$this->blog_button.' i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .'.$this->blog_button.' svg' => 'fill: {{VALUE}};',
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
			$slug.'_bg_color_hover',
			[
				'label' => esc_html__( 'Background Color', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
				'type' => \Elementor\Controls_Manager::COLOR,
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);
		$this->add_control(
			$slug.'_color_hover',
			[
				'label' => esc_html__( 'Color', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'.$this->blog_button.' a:hover .blogfoel-btn-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .'.$this->blog_button.' a:hover svg' => 'fill: {{VALUE}};',
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
		$this->add_responsive_control(
			$slug.'_size',
			[
				'label' => esc_html__( 'Font Size', 'blognews-for-elementor' ),
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
				'selectors' => [
					'{{WRAPPER}} .'.$this->blog_button.' .blogfoel-btn-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .'.$this->blog_button.' svg' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
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
				'label'     => esc_html__('Padding', 'blognews-for-elementor') . BLOGFOEL_PRO_ICON,
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);
		$this->end_controls_section();

		// Pagination
		blogfoel_post_pagination_style( $this , [] );
		Notices::go_premium_notice_style($this, 'notice_two');
	}

	protected function render() {
		$settings          = $this->get_settings_for_display();
		$show_category     = $settings['show_category'];
		$show_image        = $settings['show_image'];
		$title_html_tag    = $settings['title_html_tag'];
		$btn_title         = $settings['btn_title'];
		$btn_icon          = $settings['btn_icon']['value'];
		$layout_style      = $settings['layout_style'];
		$pagination_type   = $settings['pagination_type'];
		$pagination        = in_array($pagination_type, ['numbers-dots']) ? false : true;

		$title_animation_type = $settings['title_animation_type'];
		$custom_animation     = $settings['title_custom_animation'];
		$title_animation      = $custom_animation;
		
		$post_content_repeater       = $settings['post_content_repeater'];
		$post_content_two_repeater   = $settings['post_content_two_repeater'];
		$post_meta_repeater          = array_slice($settings['post_meta_repeater'], 0, 3);
		
		$active_repeater = [];

		switch ($layout_style) {
			case 'layout-one':
				$active_repeater = $post_content_repeater;
				break;
			case 'layout-two':
				$active_repeater = $post_content_two_repeater;
				break;
		}

		if (empty($active_repeater) || !is_array($active_repeater)) {
			return;
		}

		
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
	
		// Debugging: Check query arguments
		// echo '<pre>';
		// print_r($args);
		// echo '</pre>';
	
		$wp_query = new WP_Query($args);
		?>
	
		<div class="blogfoel-widget-wrapper">
			<div class="blogfoel-post-blog one d-grid column3">
				<?php if ($wp_query->have_posts()) {
					while ($wp_query->have_posts()) { $wp_query->the_post();
						$excerpt_length = $settings['excerpt_length'] ?? 20;
						$thumbnail_id = get_post_thumbnail_id();
						$image_class = empty($thumbnail_id) ? ' no-image' : '';
						$categories = get_the_category();
						$comments_count = get_comments_number();
						switch ($layout_style) {
							case 'layout-one':
								$file_path = BLOGFOEL_DIR_PATH . 'modules/layouts/post-blog-1/layout-1.php';
								break;
							case 'layout-two':
								$file_path = BLOGFOEL_DIR_PATH . 'modules/layouts/post-blog-1/layout-2.php';
								break;
							default:
								$file_path = BLOGFOEL_DIR_PATH . 'modules/layouts/post-blog-1/layout-1.php';
								break;
						}
						if (file_exists($file_path)) {
							require $file_path;
						}
					}
				} else { ?>
					<p><?php esc_html_e('No posts found.', 'blognews-for-elementor'); ?></p>
				<?php } ?>
			</div>
	
			<?php
			// Reset post data
			wp_reset_postdata();
	
			// Add pagination if enabled
			if (!empty($settings['pagination_type']) && $settings['pagination_type'] !== 'none') {
				blognews_numeric_posts_pagination($wp_query, $pagination);
			}
		echo "</div>";
	}
}