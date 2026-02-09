<?php

namespace BlogFoel;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use BlogFoel\Notices;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class BLOGFOELPostDescription extends \Elementor\Widget_Base {
	
    private $single_content_wrapper = 'blognews-single-post-content-wrapper';
    private $single_content = 'blognews-single-post-content';

	public function get_name() {
		return 'blognews-post-description';
	}

	public function get_title() {
		return esc_html__( 'BN Post Description', 'blognews-for-elementor' );
	}

	public function get_icon() {
		return 'blogfoel-widget-icon bnicon-post-description';
	}

	public function get_categories() {
		return [ 'blogfoel-sng-elementor' ];
	}

	public function get_keywords() {
		return [ 
			'BLOGFOEL',
            'themeansar',
			'post-description', 
			'post', 
			'description', 
			'content',
			'post content',
			'excerpt'
		];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_post_description',
			[
				'label' => esc_html__( 'Post Description', 'blognews-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

        $slug = 'post_desc';
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
					'{{WRAPPER}} .'.$this->single_content => 'text-align: {{VALUE}}',
				],
            ]
        );

		$this->end_controls_section(); // End Controls Section
		Notices::go_premium_notice_content($this, 'notice_one');

		$this->start_controls_section(
			'post_description_styles',
			[
				'label' => esc_html__( 'Description', 'blognews-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

        $slug = 'single_post_desc';
		$this->add_control(
			$slug.'_color',
			[
				'label' => esc_html__( 'Color', 'blognews-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'.$this->single_content => 'color: {{VALUE}}',
					'{{WRAPPER}} .'.$this->single_content.' p' => 'color: {{VALUE}}',
					'{{WRAPPER}} .'.$this->single_content.' a' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			$slug.'_links_color',
			[
				'label' => esc_html__( 'Links Color', 'blognews-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'.$this->single_content.' a' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => $slug.'_typography',
                'label'     => __( 'Typography', 'blognews-for-elementor' ), 
                'selector'  => '{{WRAPPER}} .'.$this->single_content, 
            )
        );
	
		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name'     => $slug.'_text_shadow',
                'label'    => esc_html__( 'Text Shadow', 'blognews-for-elementor' ),
                'selector' => '{{WRAPPER}} .'.$this->single_content,
            ]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'post_description_heading_styles',
			[
				'label' => esc_html__( 'Heading', 'blognews-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

        $slug = 'single_post_heading';

		$this->add_control(
			$slug.'_color',
			[
				'label' => esc_html__( 'Color', 'blognews-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'.$this->single_content.' h1' => 'color: {{VALUE}}',
					'{{WRAPPER}} .'.$this->single_content.' h2' => 'color: {{VALUE}}',
					'{{WRAPPER}} .'.$this->single_content.' h3' => 'color: {{VALUE}}',
					'{{WRAPPER}} .'.$this->single_content.' h4' => 'color: {{VALUE}}',
					'{{WRAPPER}} .'.$this->single_content.' h5' => 'color: {{VALUE}}',
					'{{WRAPPER}} .'.$this->single_content.' h6' => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => $slug.'_typography',
                'label'     => __( 'Typography', 'blognews-for-elementor' ), 
				'exclude'   => [ 'font_size' ],
                'selector'  => '{{WRAPPER}} .'.$this->single_content.' h1, {{WRAPPER}} .'.$this->single_content.' h2, {{WRAPPER}} .'.$this->single_content.' h3, {{WRAPPER}} .'.$this->single_content.' h4, {{WRAPPER}} .'.$this->single_content.' h5, {{WRAPPER}} .'.$this->single_content.' h6 ', 
            )
        );

		$this->add_responsive_control(
			$slug.'_h1_font_size',
			[
                'label'     => __( 'Font Size (H1)', 'blognews-for-elementor' ). BLOGFOEL_PRO_ICON,
                'type'      => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'classes' => 'blogfoel-pro-popup-notice',
				'range' => [
					'px'  => ['min' => 0, 'max' => 200],
					'em'  => ['min' => 0, 'max' => 100],
					'rem' => ['min' => 0, 'max' => 100],
				],
				'default_desktop' => ['size' => '', 'unit' => 'px'],
				'tablet_default'  => ['size' => '', 'unit' => 'px'],
				'mobile_default'  => ['size' => '', 'unit' => 'px'],
			]
		);
		$this->add_responsive_control(
			$slug.'_h2_font_size',
			[
                'label'     => __( 'Font Size (H2)', 'blognews-for-elementor' ). BLOGFOEL_PRO_ICON,
                'type'      => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'classes' => 'blogfoel-pro-popup-notice',
				'range' => [
					'px'  => ['min' => 0, 'max' => 200],
					'em'  => ['min' => 0, 'max' => 100],
					'rem' => ['min' => 0, 'max' => 100],
				],
				'default_desktop' => ['size' => '', 'unit' => 'px'],
				'tablet_default'  => ['size' => '', 'unit' => 'px'],
				'mobile_default'  => ['size' => '', 'unit' => 'px'],
			]
		);
		$this->add_responsive_control(
			$slug.'_h3_font_size',
			[
                'label'     => __( 'Font Size (H3)', 'blognews-for-elementor' ). BLOGFOEL_PRO_ICON,
                'type'      => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'classes' => 'blogfoel-pro-popup-notice',
				'range' => [
					'px'  => ['min' => 0, 'max' => 200],
					'em'  => ['min' => 0, 'max' => 100],
					'rem' => ['min' => 0, 'max' => 100],
				],
				'default_desktop' => ['size' => '', 'unit' => 'px'],
				'tablet_default'  => ['size' => '', 'unit' => 'px'],
				'mobile_default'  => ['size' => '', 'unit' => 'px'],
			]
		);
		$this->add_responsive_control(
			$slug.'_h4_font_size',
			[
                'label'     => __( 'Font Size (H4)', 'blognews-for-elementor' ). BLOGFOEL_PRO_ICON,
                'type'      => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'classes' => 'blogfoel-pro-popup-notice',
				'range' => [
					'px'  => ['min' => 0, 'max' => 200],
					'em'  => ['min' => 0, 'max' => 100],
					'rem' => ['min' => 0, 'max' => 100],
				],
				'default_desktop' => ['size' => '', 'unit' => 'px'],
				'tablet_default'  => ['size' => '', 'unit' => 'px'],
				'mobile_default'  => ['size' => '', 'unit' => 'px'],
			]
		);
		$this->add_responsive_control(
			$slug.'_h5_font_size',
			[
                'label'     => __( 'Font Size (H5)', 'blognews-for-elementor' ). BLOGFOEL_PRO_ICON,
                'type'      => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'classes' => 'blogfoel-pro-popup-notice',
				'range' => [
					'px'  => ['min' => 0, 'max' => 200],
					'em'  => ['min' => 0, 'max' => 100],
					'rem' => ['min' => 0, 'max' => 100],
				],
				'default_desktop' => ['size' => '', 'unit' => 'px'],
				'tablet_default'  => ['size' => '', 'unit' => 'px'],
				'mobile_default'  => ['size' => '', 'unit' => 'px'],
			]
		);
		$this->add_responsive_control(
			$slug.'_h6_font_size',
			[
                'label'     => __( 'Font Size (H6)', 'blognews-for-elementor' ). BLOGFOEL_PRO_ICON,
                'type'      => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'classes' => 'blogfoel-pro-popup-notice',
				'range' => [
					'px'  => ['min' => 0, 'max' => 200],
					'em'  => ['min' => 0, 'max' => 100],
					'rem' => ['min' => 0, 'max' => 100],
				],
				'default_desktop' => ['size' => '', 'unit' => 'px'],
				'tablet_default'  => ['size' => '', 'unit' => 'px'],
				'mobile_default'  => ['size' => '', 'unit' => 'px'],
			]
		);

		
		$this->add_responsive_control(
			$slug.'_margin',
			[
				'label'     => esc_html__('Margin', 'blognews-for-elementor'). BLOGFOEL_PRO_ICON,
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);

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

		$content = $post->post_content;
		?>
		<div class="blogfoel-widget-wrapper">
			<div class="blogfoel-single-post-content-wrapper <?php echo esc_attr($this->single_content_wrapper)?>">
				<article class="blogfoel-single-post-content <?php echo esc_attr($this->single_content)?>">
					<?php echo wp_kses_post($content); ?>
				</article>
			</div>
		</div>
		<?php
	}	
}