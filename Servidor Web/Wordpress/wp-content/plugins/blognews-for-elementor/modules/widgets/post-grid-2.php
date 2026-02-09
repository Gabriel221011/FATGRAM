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

class BLOGFOELPostGrid2 extends \Elementor\Widget_Base {

	private $blog_card_class = 'blogfoel-post-grid-two-item';
	private $blog_inner_class = 'blogfoel-post-grid-two-inner';

	public function get_name() {
		return 'blognews-post-grid-2';
	}

	public function get_title() {
		return __( 'BN Post Grid 2', 'blognews-for-elementor' );
	}

	public function get_categories() {
		return [ 'blogfoel-elementor' ];
	}

	public function get_icon() {
		return 'blogfoel-widget-icon bnicon-post-grid-2';
	}

	public function get_style_depends() {
		return [
			'widget-style',
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
            'multi grid',
            'news',
            'blog',
            'post',
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
				'default'     => 'pg2-layout-one',
				'options'     => [
					'pg2-layout-one'      => esc_html__( 'Style 1', 'blognews-for-elementor' ),
					'blogfoel-pro-layout-two' => esc_html__( 'Style 2 (Pro)', 'blognews-for-elementor' ),
				],
			]
		);
		$this->add_control(
			'number_of_posts',
			[
				'label' => esc_html__( 'Number of Posts', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'min'         => 1,
				'max'		  => 7,
				'default'     => 4,
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
		$this->add_control(
			'title_length',
			[
				'label' => esc_html__( 'Title Length (In Lines)', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::NUMBER,
				'placeholder' => '2',
				'min'         => 1,
				'default'     => '',
				'max'		  => 5,
				'selectors'       => [
					'{{WRAPPER}} .blogfoel-post-item .blogfoel-title' => '-webkit-line-clamp: {{VALUE}};',
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
				'devices'        => [ 'desktop', 'tablet', 'mobile' ],
				'default'        => ['size' => 1.5, 'unit' => 'rem'],
				'tablet_default' => ['size' => 1.5, 'unit' => 'rem'],
				'mobile_default' => ['size' => 1.5, 'unit' => 'rem'],
				'selectors'      => [
					'{{WRAPPER}} .blogfoel-post-grid' => 'grid-column-gap: {{SIZE}}{{UNIT}};',
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
				'devices'        => [ 'desktop', 'tablet', 'mobile' ],
				'default'        => ['size' => 1.5, 'unit' => 'rem'],
				'tablet_default' => ['size' => 1.5, 'unit' => 'rem'],
				'mobile_default' => ['size' => 1.5, 'unit' => 'rem'],
				'selectors'      => [
					'{{WRAPPER}} .blogfoel-post-grid' => 'grid-row-gap: {{SIZE}}{{UNIT}};',
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
				'condition' => [
					'show_category' => 'yes',
				],
				'label_block' => false,
				'classes' => 'blogfoel-pro-popup-notice',
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
					[	'post_meta' => 'author',
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

		$this->add_control(
			'repeater_pro_notice',
			[
				'raw' => 'More than 3 are available in <a href="'.BLOGFOEL_PRO_LINK.'" target="_blank">Pro Version!</a>',
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'content_classes' => 'blogfoel-pro-notice',
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
			$slug.'_large_content_align',
			[
				'label'     => __( 'Large Content Alignment', 'blognews-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'flex-start'   => [
						'title' => __( 'Top', 'blognews-for-elementor' ),
						'icon'  => 'eicon-v-align-top',
					],
					'center' => [
						'title' => __( 'Middle', 'blognews-for-elementor' ),
						'icon'  => 'eicon-v-align-middle',
					],
					'flex-end'  => [
						'title' => __( 'Bottom', 'blognews-for-elementor' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .blogfoel-post-item:first-child .'.$this->blog_inner_class => 'justify-content: {{VALUE}}',
				],
			]
		);
		$this->add_responsive_control(
			$slug.'_small_content_align',
			[
				'label'     => __( 'Small Content Alignment', 'blognews-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'flex-start'   => [
						'title' => __( 'Top', 'blognews-for-elementor' ),
						'icon'  => 'eicon-v-align-top',
					],
					'center' => [
						'title' => __( 'Middle', 'blognews-for-elementor' ),
						'icon'  => 'eicon-v-align-middle',
					],
					'flex-end'  => [
						'title' => __( 'Bottom', 'blognews-for-elementor' ),
						'icon'  => 'eicon-v-align-bottom',
					],
				],
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .blogfoel-post-item:not(:first-child) .'.$this->blog_inner_class => 'justify-content: {{VALUE}}',
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
			$slug.'large_inner_padding',
			[
				'label' => esc_html__( 'Large Inner Padding', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .blogfoel-post-item:first-child .'.$this->blog_inner_class => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			$slug.'small_inner_padding',
			[
				'label' => esc_html__( 'Small Inner Padding', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .blogfoel-post-item:not(:first-child) .'.$this->blog_inner_class => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'      => $slug.'_box_shadow',
				'label'    => 'Box Shadow',
				'selector' => '{{WRAPPER}} .'.$this->blog_card_class
			]
		);
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
				'selector'  => '{{WRAPPER}} .'.$this->blog_card_class.':hover'
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => $slug.'_border_type_hover',
				'label'    => 'Border Type',
				'selector' => '{{WRAPPER}} .'.$this->blog_card_class.':hover'
			]
		);
		$this->add_responsive_control(
			$slug.'_border_radius_hover',
			[
				'label' => esc_html__( 'Border Radius', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .'.$this->blog_card_class.':hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .'.$this->blog_card_class.':hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			$slug.'large_inner_padding_hover',
			[
				'label' => esc_html__( 'Large Inner Padding', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .blogfoel-post-item:first-child:hover .'.$this->blog_inner_class => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			$slug.'small_inner_padding_hover',
			[
				'label' => esc_html__( 'Small Inner Padding', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .blogfoel-post-item:not(:first-child):hover .'.$this->blog_inner_class => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'      => $slug.'_box_shadow_hover',
				'label'    => 'Box Shadow',
				'selector' => '{{WRAPPER}} .'.$this->blog_card_class.':hover'
			]
		);
		$this->end_controls_tab();

		$this->end_controls_tabs();
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
			$slug.'_large_height',
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
				'devices'        => [ 'desktop', 'tablet', 'mobile' ],
				'default'        => ['size' => '', 'unit' => 'px'],
				'tablet_default' => ['size' => '', 'unit' => 'px'],
				'mobile_default' => ['size' => '', 'unit' => 'px'],
				'selectors'      => [
					'{{WRAPPER}} .'.$this->blog_card_class.':not(:first-child)' => 'height: calc({{SIZE}}{{UNIT}} / 2); min-height:calc({{SIZE}}{{UNIT}} + {{row_gap.SIZE}}{{row_gap.UNIT}})',
					'{{WRAPPER}} .'.$this->blog_card_class.':first-child' => 'height: calc({{SIZE}}{{UNIT}} + {{row_gap.SIZE}}{{row_gap.UNIT}});',
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
				'devices'        => [ 'desktop', 'tablet', 'mobile' ],
				'default'        => ['size' => '', 'unit' => 'px'],
				'tablet_default' => ['size' => '', 'unit' => 'px'],
				'mobile_default' => ['size' => '', 'unit' => 'px'],
				'selectors'      => [
					'{{WRAPPER}} .'.$this->blog_card_class.' .blogfoel-img-wraper:before' => 'opacity: {{SIZE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => $slug.'_border_type',
				'label'    => 'Border Type',
				'selector' => '{{WRAPPER}} .'.$this->blog_card_class.' .blogfoel-img-wraper',
			]
		);
		$this->add_responsive_control(
			$slug.'_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .'.$this->blog_card_class.' .blogfoel-img-wraper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .'.$this->blog_card_class.' .blogfoel-img-wraper:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
		$this->add_control(
			$slug.'_alignment',
			[
				'label' => esc_html__( 'Alignment', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'flex-start' => [
						'title' => esc_html__( 'Left', 'blognews-for-elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'blognews-for-elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'flex-end' => [
						'title' => esc_html__( 'Right', 'blognews-for-elementor' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => '',
				'toggle' => true,
				'condition' => [
					$slug.'_position' => 'category-bottom'
				],
				'selectors' => [
					'{{WRAPPER}} .blogfoel-post-grid.category-bottom .blogfoel-category' => 'justify-content: {{VALUE}};',
				],
			]
		);
		
		blogfoel_category_style_control( $this , $slug , 'blogfoel-post-item .blogfoel-category' );

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
			$slug.'_alignment',
			[
				'label' => esc_html__( 'Alignment', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'blognews-for-elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'blognews-for-elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'blognews-for-elementor' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'default' => '',
				'toggle' => true,
				'selectors' => [
					'{{WRAPPER}} .blogfoel-post-item .blogfoel-title' => 'text-align: {{VALUE}};',
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
					'{{WRAPPER}} .blogfoel-post-item .blogfoel-title a' => 'color: {{VALUE}};',
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
					'{{WRAPPER}} .blogfoel-post-item .blogfoel-title a:hover' => 'color: {{VALUE}};',
					'{{WRAPPER}}'  => '--title-hover-color: {{VALUE}};',
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
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => $slug.'_typography',
                'label'     => __( 'Typography', 'blognews-for-elementor' ),
				'selector' => '{{WRAPPER}} .'.$this->blog_card_class.':first-child .blogfoel-title',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     => $slug.'_small_typography',
				'label'    => 'Small Typography',
				'selector' => '{{WRAPPER}} .'.$this->blog_card_class.':not(:first-child) .blogfoel-title',
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
					'{{WRAPPER}} .blogfoel-post-item .blogfoel-meta' => 'justify-content: {{VALUE}}',
				],
			]
		);
		
		blogfoel_meta_style_control( $this , $slug , 'blogfoel-post-item .blogfoel-meta' );

		$this->end_controls_section();

		Notices::go_premium_notice_style($this, 'notice_two');
	}

	protected function render() {
		$settings       		= $this->get_settings_for_display();
		$show_category  		= $settings['show_category'];
		$show_image     		= $settings['show_image'];
		$title_html_tag 		= $settings['title_html_tag'];
		$columns                = 1;
		$post_meta_repeater     = array_slice($settings['post_meta_repeater'], 0, 3);
		$title_animation_type   = $settings['title_animation_type'];
		$custom_animation       = $settings['title_custom_animation'];
		$title_animation        = $custom_animation;

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
			<div class="blogfoel-post-grid pg2-layout-one category-bottom <?php echo 'blogfoel-column-' . esc_attr( $columns ) ?>">
				<?php if ($wp_query->have_posts()) {
					while ($wp_query->have_posts()) { $wp_query->the_post();
						$excerpt_length = $settings['excerpt_length'] ?? 20;
						$thumbnail_id = get_post_thumbnail_id();
						$image_class = empty($thumbnail_id) ? ' no-image' : '';
						$categories = get_the_category();
						$comments_count = get_comments_number();
						$image_src = \Elementor\Group_Control_Image_Size::get_attachment_image_src($thumbnail_id, 'thumbnail_size', $settings);
						?>
						<div class="blogfoel-post-item overlay <?php echo esc_attr($this->blog_card_class) . esc_attr($image_class) ?>">
							<div class="blogfoel-post-item-box">
								<?php if ($thumbnail_id && $show_image === 'yes') { ?>
									<div class="blogfoel-img-wraper">
										<img src="<?php echo esc_attr($image_src); ?>" title="<?php echo esc_attr(get_the_title($thumbnail_id));?>" alt="<?php echo esc_attr(blognews_get_attachment_alt($thumbnail_id));?>">
									</div>
								<?php } ?>
								<div class="blogfoel-inner <?php echo esc_attr($this->blog_inner_class);?>">
									<div class="blogfoel-category">
										<?php if (!empty($categories) && $show_category === 'yes') {					
                							echo wp_kses_post(blogfoel_get_all_category());
										} ?>
									</div>
									<?php echo '<' . esc_html($title_html_tag) . ' class="blogfoel-title '.esc_attr($title_animation).'">
											<a href="' . esc_url(get_permalink()) . '" title="' . esc_attr(get_the_title()) . '">
												' . esc_html(get_the_title()) . '
											</a>
										</' . esc_html($title_html_tag) . '>';

									$meta_html = '';

										if (!empty($post_meta_repeater)) {
											$meta_html .= '<div class="blogfoel-meta">';
										
											foreach ($post_meta_repeater as $meta_item) {
												$meta_type            = $meta_item['post_meta'];
												$avatar               = $meta_item['show_avatar'];
												$edit_title           = $meta_item['edit_title'];
												$icon                 = $meta_item['meta_icon'];
												if ($meta_type === 'author') {
													$meta_html .= blogfoel_get_author($avatar, $icon);
												} elseif ($meta_type === 'date') {
													$meta_html .= blogfoel_get_date('', $icon);
												} elseif ($meta_type === 'edit') {
													$meta_html .= blogfoel_get_edit_post_link($icon, $edit_title);
												}
											}
										
											$meta_html .= '</div>';
											echo wp_kses_post($meta_html);
										} ?>
            						<a href="<?php echo esc_url(get_permalink())?>" class="img-link"></a>
								</div>      
							</div>
						</div>
					<?php }
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