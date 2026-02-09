<?php
namespace BlogFoel;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use BlogFoel\Notices;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class BLOGFOELPostComments extends \Elementor\Widget_Base {

	private $single_comment_wrapper = 'blognews-post-comment-wrapper';
	private $comment_title = 'blognews-post-comment-title';
	private $comment_list = 'blognews-post-comment-list';

	public function get_name() {
		return 'blognews-post-comments';
	}

	public function get_title() {
		return esc_html__( 'BN Post Comments', 'blognews-for-elementor' );
	}

	public function get_icon() {
		return 'blogfoel-widget-icon bnicon-post-comments';
	}

	public function get_categories() {
		return [ 'blogfoel-sng-elementor' ];
	}

	public function get_keywords() {
		return [ 'post-comments', 'post', 'comments' , 'post comment', 'post comments','discussion'];
	}

	protected function register_controls() {
		// Blog Category
		$this->start_controls_section(
			'single_blog_comments_area_settings',
			[
				'label' => __( 'Comment Area Settings ', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,				
			]
		);
		
		$slug = 'single_blog_comment_area';
		
		$this->add_control(
			$slug.'_bg_color',
			[
				'label'     => __( 'Background Color', 'blognews-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'. $this->single_comment_wrapper => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => $slug.'_border_type',
				'label'    => 'Border Type',
				'selector' => '{{WRAPPER}} .'. $this->single_comment_wrapper,
			]
		);

		$this->add_responsive_control(
			$slug.'_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .'. $this->single_comment_wrapper => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			$slug.'_padding',
			[
				'label'     => esc_html__('Padding', 'blognews-for-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .'. $this->single_comment_wrapper => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);

		$this->add_responsive_control(
			$slug.'_margin',
			[
				'label'     => esc_html__('Margin', 'blognews-for-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .'. $this->single_comment_wrapper => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'      => $slug.'_box_shadow',
				'label'    => 'Box Shadow',
				'selector' => '{{WRAPPER}} .'. $this->single_comment_wrapper,
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'comment_area_title_section',
			[
				'label' => esc_html__( 'Comment Area Title', 'blognews-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$slug = 'comment_area_title';
		$this->add_responsive_control(
            $slug.'_align',
            [
                'label' => esc_html__( 'Alignment', 'blognews-for-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => '',
                'label_block' => false,
                'options' => [
					'start'    => [
						'title' => __( 'Left', 'blognews-for-elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'blognews-for-elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'end' => [
						'title' => __( 'Right', 'blognews-for-elementor' ),
						'icon' => 'eicon-text-align-right',
					],
                ],
				'selectors' => [
					'{{WRAPPER}} .'. $this->comment_title => 'text-align: {{VALUE}}',
				]
            ]
        );
		$this->add_control(
			$slug.'_color',
			[
				'label' => esc_html__( 'Color', 'blognews-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'. $this->comment_title => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => $slug.'_typography',
                'label'     => __( 'Typography', 'blognews-for-elementor' ), 
                'selector'  => '{{WRAPPER}} .'. $this->comment_title, 
            )
        );

		$this->add_responsive_control(
			$slug.'_margin',
			[
				'label'     => esc_html__('Margin', 'blognews-for-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .'. $this->comment_title => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'post_user_comment_styles',
			[
				'label' => esc_html__( 'User Comment', 'blognews-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);
		
		$slug = 'post_user_comment_box';

		$this->add_control(
			$slug.'_heading',
			[
				'label' => esc_html__( 'User Comment Box', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);
		
		$this->add_control(
			$slug.'_user_name_color',
			[
				'label'     => __( 'User Name Color', 'blognews-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'.$this->comment_list.' .comment-body b.fn .url' => 'color: {{VALUE}};', 
				],
			]
		);
		
		$this->add_control(
			$slug.'_date_color',
			[
				'label'     => __( 'Date Color', 'blognews-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'.$this->comment_list.' .comment-body a:has(:not(.reply a))' => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_control(
			$slug.'_review_text_color',
			[
				'label'     => __( 'Comment Text Color', 'blognews-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'.$this->comment_list.' .comment-body .comment-content' => 'color: {{VALUE}};', 
					'{{WRAPPER}} .'.$this->comment_list.' .comment-body .says' => 'color: {{VALUE}};', 
				],
			]
		);

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => $slug.'_typography',
                'label'     => __( 'Typography', 'blognews-for-elementor' ), 
                'selector'  => '{{WRAPPER}} .'.$this->comment_list.' .comment-body', 
            )
        );
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => $slug.'_border_type',
				'label'    => 'Border Type',
				'selector' => '{{WRAPPER}} .'.$this->comment_list.' .comment-body',
			]
		);

		$this->add_responsive_control(
			$slug.'_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .'.$this->comment_list.' .comment-body' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			$slug.'_margin',
			[
				'label'     => esc_html__('Margin', 'blognews-for-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .'.$this->comment_list.' .comment-body' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		
		$slug = 'post_user_comment_img';

		$this->add_control(
			$slug.'_heading',
			[
				'label' => esc_html__( 'image', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
			$slug.'_img_size',
			[
				'label'           => __( 'Image Size', 'blognews-for-elementor' ),
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
					'{{WRAPPER}} .'.$this->comment_list.' .comment-author.vcard img.avatar' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => $slug.'_border_type',
				'label'    => 'Border Type',
				'selector' => '{{WRAPPER}} .'.$this->comment_list.' .comment-author.vcard img.avatar',
			]
		);

		$this->add_responsive_control(
			$slug.'_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .'.$this->comment_list.' .comment-author.vcard img.avatar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			$slug.'_margin',
			[
				'label'     => esc_html__('Margin', 'blognews-for-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .'.$this->comment_list.' .comment-author.vcard img.avatar' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			$slug.'_btn_heading',
			[
				'label' => esc_html__( 'User Comment Button', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_responsive_control(
            'post_user_comment_button_align',
            [
                'label' => esc_html__( 'Alignment', 'blognews-for-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'end',
                'label_block' => false,
                'options' => [
					'start'    => [
						'title' => __( 'Left', 'blognews-for-elementor' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'blognews-for-elementor' ),
						'icon' => 'eicon-text-align-center',
					],
					'end' => [
						'title' => __( 'Right', 'blognews-for-elementor' ),
						'icon' => 'eicon-text-align-right',
					],
                ],
				'selectors' => [
					'{{WRAPPER}} .'.$this->comment_list.' .comment-body .reply' => 'text-align: {{VALUE}}',
				]
            ]
        );
		
		$slug = 'post_user_comment_button';
		
		$this->add_control(
			$slug.'_color',
			[
				'label'     => __( 'Color', 'blognews-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'.$this->comment_list.' .comment-body .reply a' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			$slug.'_bg_color',
			[
				'label'     => __( 'Background Color', 'blognews-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'.$this->comment_list.' .comment-body .reply a' => 'background-color: {{VALUE}}',
				],
			]
		);
	
		$this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => $slug.'_typography',
                'label'     => __( 'Typography', 'blognews-for-elementor' ), 
                'selector'  => '{{WRAPPER}} .'.$this->comment_list.' .comment-body .reply a', 
            )
        );
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => $slug.'_border_type',
				'label'    => 'Border Type',
				'selector' => '{{WRAPPER}} .'.$this->comment_list.' .comment-body .reply a',
			]
		);

		$this->add_responsive_control(
			$slug.'_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .'.$this->comment_list.' .comment-body .reply a' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			$slug.'_padding',
			[
				'label'     => esc_html__('Padding', 'blognews-for-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .'.$this->comment_list.' .comment-body .reply a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			$slug.'_margin',
			[
				'label'     => esc_html__('Margin', 'blognews-for-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .'.$this->comment_list.' .comment-body .reply a' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();  // End Controls Section

		$this->start_controls_section(
			'post_comment_form_styles',
			[
				'label' => esc_html__( 'Comment Form', 'blognews-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);
		
		$slug = 'post_comment_form';
		
		$this->add_control(
			$slug.'_title_color',
			[
				'label'     => __( 'Form Title Color', 'blognews-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'.$this->single_comment_wrapper.' .comment-respond .comment-reply-title' => 'color: {{VALUE}};', 
				],
			]
		);

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => $slug.'_title_typography',
                'label'     => __( 'Typography', 'blognews-for-elementor' ), 
                'selector'  => '{{WRAPPER}} .'.$this->single_comment_wrapper.' .comment-respond .comment-reply-title',
            )
        );
		
		$this->add_control(
			$slug.'_label_color',
			[
				'label'     => __( 'Label Color', 'blognews-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'.$this->single_comment_wrapper.' form p' => 'color: {{VALUE}};',
					'{{WRAPPER}} .'.$this->single_comment_wrapper.' form a' => 'color: {{VALUE}};',
				],
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			$slug.'_required_color',
			[
				'label'     => __( 'Required Color', 'blognews-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					// '{{WRAPPER}} .'.$this->single_comment_wrapper.' form p ' => 'color: {{VALUE}};', 
					'{{WRAPPER}}  .'.$this->single_comment_wrapper.' .required' => 'color: {{VALUE}};', 
				],
			]
		);

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => $slug.'_required_typography',
                'label'     => __( 'Typography', 'blognews-for-elementor' ), 
                'selector'  => '{{WRAPPER}} .'.$this->single_comment_wrapper.' form p', 
            )
        );
		
		$this->add_control(
			$slug.'_textarea_color',
			[
				'label'     => __( 'Form Text Color', 'blognews-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'.$this->single_comment_wrapper.' input[type="text"]' => 'color: {{VALUE}};', 
					'{{WRAPPER}} .'.$this->single_comment_wrapper.' input[type="email"]' => 'color: {{VALUE}};', 
					'{{WRAPPER}} .'.$this->single_comment_wrapper.' input[type="url"]' => 'color: {{VALUE}};', 
					'{{WRAPPER}} .'.$this->single_comment_wrapper.' textarea' => 'color: {{VALUE}};', 
				],
				'separator' => 'before',
			]
		);
		
		$this->add_control(
			$slug.'_bg_color',
			[
				'label'     => __( 'Background Color', 'blognews-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'.$this->single_comment_wrapper.' input[type="text"]' => 'background-color: {{VALUE}};', 
					'{{WRAPPER}} .'.$this->single_comment_wrapper.' input[type="email"]' => 'background-color: {{VALUE}};', 
					'{{WRAPPER}} .'.$this->single_comment_wrapper.' input[type="url"]' => 'background-color: {{VALUE}};', 
					'{{WRAPPER}} .'.$this->single_comment_wrapper.' textarea' => 'background-color: {{VALUE}};', 
				],
			]
		);

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => $slug.'_typography',
                'label'     => __( 'Typography', 'blognews-for-elementor' ), 
                'selector'  => '{{WRAPPER}} .'.$this->single_comment_wrapper.' textarea, .'.$this->single_comment_wrapper.' input[type="text"], .'.$this->single_comment_wrapper.' input[type="email"], .'.$this->single_comment_wrapper.' input[type="url"]', 
            )
        );
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => $slug.'_border_type',
				'label'    => 'Border Type',
				'selector' => '{{WRAPPER}} .'.$this->single_comment_wrapper.' :where(textarea, input[type="text"], input[type="email"], input[type="url"])',
			]
		);

		$this->add_control(
			$slug.'_border_color',
			[
				'label'     => __( 'Border hover/Focus Color', 'blognews-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'.$this->single_comment_wrapper.' :where(textarea, input[type="text"], input[type="email"], input[type="url"]):hover' => 'border-color: {{VALUE}};', 
					'{{WRAPPER}} .'.$this->single_comment_wrapper.' :where(textarea, input[type="text"], input[type="email"], input[type="url"]):focus' => 'border-color: {{VALUE}};', 
					'{{WRAPPER}} .'.$this->single_comment_wrapper.' :where(textarea, input[type="text"], input[type="email"], input[type="url"]):focus-visible' => 'outline-color: {{VALUE}};', 
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
					'{{WRAPPER}} .'.$this->single_comment_wrapper.'.comments-area input[type="text"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .'.$this->single_comment_wrapper.'.comments-area input[type="email"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .'.$this->single_comment_wrapper.'.comments-area input[type="url"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .'.$this->single_comment_wrapper.'.comments-area textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			$slug.'_margin',
			[
				'label'     => esc_html__('Margin', 'blognews-for-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .'.$this->single_comment_wrapper.'.comments-area input[type="text"]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .'.$this->single_comment_wrapper.'.comments-area input[type="email"]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .'.$this->single_comment_wrapper.'.comments-area input[type="url"]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .'.$this->single_comment_wrapper.'.comments-area textarea' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
			$slug.'_btn_heading',
			[
				'label' => esc_html__( 'Comment Button', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);
		
		$slug = 'post_comment_form_button';
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
				'label'     => __( 'Color', 'blognews-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'.$this->single_comment_wrapper.' .form-submit input[type="button"]' => 'color: {{VALUE}}',
					'{{WRAPPER}} .'.$this->single_comment_wrapper.' .form-submit input[type="submit"]' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			$slug.'_bg_color',
			[
				'label'     => __( 'Background Color', 'blognews-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'.$this->single_comment_wrapper.' .form-submit input[type="button"]' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .'.$this->single_comment_wrapper.' .form-submit input[type="submit"]' => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => $slug.'_border_type',
				'label'    => 'Border Type',
				'selector' => '{{WRAPPER}} .'.$this->single_comment_wrapper.' .form-submit input:where([type="button"] , [type="submit"])',
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
			$slug.'_hover_color',
			[
				'label'     => __( 'Color', 'blognews-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'.$this->single_comment_wrapper.' .form-submit input[type="button"]:hover' => 'color: {{VALUE}}',
					'{{WRAPPER}} .'.$this->single_comment_wrapper.' .form-submit input[type="submit"]:hover' => 'color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			$slug.'_bg_hover_color',
			[
				'label'     => __( 'Background Color', 'blognews-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'.$this->single_comment_wrapper.' .form-submit input[type="button"]:hover' => 'background-color: {{VALUE}}',
					'{{WRAPPER}} .'.$this->single_comment_wrapper.' .form-submit input[type="submit"]:hover' => 'background-color: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			$slug.'_border_hover_color',
			[
				'label'     => __( 'Border Color', 'blognews-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'.$this->single_comment_wrapper.' .form-submit input[type="button"]:hover' => 'border-color: {{VALUE}}',
					'{{WRAPPER}} .'.$this->single_comment_wrapper.' .form-submit input[type="submit"]:hover' => 'border-color: {{VALUE}}',
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
            Group_Control_Typography::get_type(),
            array(
                'name'      => $slug.'_typography',
                'label'     => __( 'Typography', 'blognews-for-elementor' ), 
                'selector'  => '{{WRAPPER}} .'.$this->single_comment_wrapper.' .form-submit input:where([type="button"] , [type="submit"])', 
            )
        );

		$this->add_responsive_control(
			$slug.'_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .'.$this->single_comment_wrapper.' .form-submit input[type="button"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .'.$this->single_comment_wrapper.' .form-submit input[type="submit"]' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			$slug.'_padding',
			[
				'label'     => esc_html__('Padding', 'blognews-for-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .'.$this->single_comment_wrapper.' .form-submit input[type="button"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .'.$this->single_comment_wrapper.' .form-submit input[type="submit"]' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			$slug.'_margin',
			[
				'label'     => esc_html__('Margin', 'blognews-for-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .'.$this->single_comment_wrapper.' .form-submit input[type="button"]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .'.$this->single_comment_wrapper.' .form-submit input[type="submit"]' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		); 

		$this->end_controls_section();  // End Controls Section
		Notices::go_premium_notice_style($this, 'notice_two');
	}

	protected function render() {
		$settings = $this->get_settings();
		$current_url = $_SERVER['REQUEST_URI'];
		$post_id = get_the_ID();
	
		if (
			(class_exists("\Elementor\Plugin") && \Elementor\Plugin::$instance->editor->is_edit_mode()) ||
			(class_exists("\Elementor\Plugin") && isset($_GET['preview'], $_GET['preview_id']) && $_GET['preview'] == 'true') ||
			(strpos($current_url, 'blogfoel-hf-builder') !== false && get_post_type() == 'blogfoel-hf-builder')
		) {
			$blogfoel_demo_post_id = \Elementor\Plugin::$instance->documents->get($post_id, false)->get_settings('blogfoel_demo_post_id');
			if (!empty($blogfoel_demo_post_id)) {
				$post_id = $blogfoel_demo_post_id;
			}
		}
	
		$post = get_post($post_id);
		if (empty($post) || !is_a($post, 'WP_Post')) {
			return;
		}
	
		$title = get_the_title($post);
		$comments_number = get_comments_number($post_id);
		$comments = get_comments(['post_id' => $post_id]);
	
		// Check if comments are open and not password-protected
		if (!post_password_required($post) && comments_open($post)) : ?>
			<div class="blogfoel-widget-wrapper">
				<div id="comments" class="blogfoel-single-post-comments comments-area <?php echo esc_attr($this->single_comment_wrapper) ?>">
					<?php if ($comments_number > 0) : ?>
						<div class="blogfoel-heading-bor-bt">
							<h5 class="comments-title <?php echo esc_attr($this->comment_title) ?>">
								<?php
								if ($comments_number == 1) {
									/* translators: %s: Comments Title. */
									printf(
										esc_html__('One thought on &ldquo;%s&rdquo;', 'blognews-for-elementor'),
										esc_html($title)
									);
								} else {
									/* translators: %s: Comments Number and Title. */
									printf(
										esc_html(
											_nx(
												'%1$s thought on &ldquo;%2$s&rdquo;',
												'%1$s thoughts on &ldquo;%2$s&rdquo;',
												$comments_number,
												'comments title',
												'blognews-for-elementor'
											)
										),
										esc_html(number_format_i18n($comments_number)),
										esc_html($title)
									);
								}
								?>
							</h5>
						</div>
	
						<?php if ($comments_number > 1 && get_option('page_comments')) : ?>
							<nav id="comment-nav-above" class="navigation comment-navigation">
								<h2 class="screen-reader-text"><?php esc_html_e('Comment navigation', 'blognews-for-elementor'); ?></h2>
								<div class="nav-links">
									<div class="nav-previous"><?php previous_comments_link(esc_html__('Older Comments', 'blognews-for-elementor')); ?></div>
									<div class="nav-next"><?php next_comments_link(esc_html__('Newer Comments', 'blognews-for-elementor')); ?></div>
								</div>
							</nav>
						<?php endif; ?>
	
						<ol class="comment-list blogfoel-comment-list <?php echo esc_attr($this->comment_list)?>">
							<?php
							wp_list_comments([
								'style'       => 'ol',
								'avatar_size' => 50,
							], $comments);
							?>
						</ol>
	
						<?php if ($comments_number > 1 && get_option('page_comments')) : ?>
							<nav id="comment-nav-below" class="navigation comment-navigation">
								<h5 class="screen-reader-text"><?php esc_html_e('Comment navigation', 'blognews-for-elementor'); ?></h5>
								<div class="nav-links">
									<div class="nav-previous"><?php previous_comments_link(esc_html__('Older Comments', 'blognews-for-elementor')); ?></div>
									<div class="nav-next"><?php next_comments_link(esc_html__('Newer Comments', 'blognews-for-elementor')); ?></div>
								</div>
							</nav>
						<?php endif; ?>
					<?php endif; ?>
	
					<?php if (!comments_open($post) && $comments_number > 0 && post_type_supports(get_post_type(), 'comments')) : ?>
						<p class="no-comments"><?php esc_html_e('Comments are closed.', 'blognews-for-elementor'); ?></p>
					<?php endif; ?>
	
					<?php comment_form([], $post); ?>
				</div>
			</div>
		<?php endif;
	}	
}