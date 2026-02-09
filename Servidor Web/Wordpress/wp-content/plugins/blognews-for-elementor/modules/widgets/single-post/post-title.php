<?php

namespace BlogFoel;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use BlogFoel\Notices;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class BLOGFOELPostTitle extends \Elementor\Widget_Base {
	
    private $single_title_wrapper = 'blognews-single-post-title-wrapper';
    private $single_title = 'blognews-single-post-title';
	public function get_name() {
		return 'blognews-post-title';
	}

	public function get_title() {
		return esc_html__( 'BN Post Title', 'blognews-for-elementor' );
	}

	public function get_icon() {
		return 'blogfoel-widget-icon bnicon-post-title';
	}

	public function get_categories() {
		return [ 'blogfoel-sng-elementor' ];
	}

	public function get_keywords() {
		return [ 
			'BLOGFOEL',
            'themeansar',
			'post-title',
			'post', 
			'title' 
		];
	}


	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_post_title',
			[
				'label' => esc_html__( 'Single Title', 'blognews-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

        $slug = 'post_title';
		$this->add_control(
			$slug.'_tag',
			[
				'label'       => esc_html__( 'Html Tag', 'blognews-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'h1',
				'options'     => [
					'h1' => esc_html__( 'H1', 'blognews-for-elementor' ),
					'h2' => esc_html__( 'H2', 'blognews-for-elementor' ),
					'h3' => esc_html__( 'H3', 'blognews-for-elementor' ),
					'h4' => esc_html__( 'H4', 'blognews-for-elementor' ),
					'h5' => esc_html__( 'H5', 'blognews-for-elementor' ),
					'h6' => esc_html__( 'H6', 'blognews-for-elementor' ),
					'p' => esc_html__( 'P', 'blognews-for-elementor' ),
					'span' => esc_html__( 'Span', 'blognews-for-elementor' ),
				],
			]
		);

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
					'{{WRAPPER}} .'.$this->single_title => 'text-align: {{VALUE}}',
				],
            ]
        );

		$this->end_controls_section(); // End Controls Section
		Notices::go_premium_notice_content($this, 'notice_one');

		$this->start_controls_section(
			'section_style_title',
			[
				'label' => esc_html__( 'Title', 'blognews-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

        $slug = 'single_post_title';
		$this->add_control(
			$slug.'_color',
			[
				'label' => esc_html__( 'Color', 'blognews-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'.$this->single_title => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => $slug.'_typography',
                'label'     => __( 'Typography', 'blognews-for-elementor' ), 
                'selector'  => '{{WRAPPER}} .'.$this->single_title, 
            )
        );

		$this->add_responsive_control(
			$slug.'_margin',
			[
				'label'     => esc_html__('Margin', 'blognews-for-elementor') . BLOGFOEL_PRO_ICON,
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name'     => $slug.'_text_shadow',
                'label'    => esc_html__( 'Text Shadow', 'blognews-for-elementor' ),
                'selector' => '{{WRAPPER}} .'.$this->single_title,
            ]
        );

        $this->add_group_control(
			\Elementor\Group_Control_Text_Stroke::get_type(),
			[
				'name'     => $slug.'_text_stroke',
                'label'    => esc_html__( 'Text Stroke', 'blognews-for-elementor' ),
                'selector' => '{{WRAPPER}} .'.$this->single_title,
			]
		);

		$this->end_controls_section();
		Notices::go_premium_notice_style($this, 'notice_two');
	}

	protected function render() {
		$settings = $this->get_settings();
		$tag = esc_attr($settings['post_title_tag']);
		$current_url = $_SERVER['REQUEST_URI'];
		$post_id = get_the_ID();
		$elementor = \Elementor\Plugin::$instance;
		
		if (
			(class_exists("\Elementor\Plugin") && $elementor->editor->is_edit_mode()) ||
			(class_exists("\Elementor\Plugin") && isset($_GET['preview'], $_GET['preview_id']) && $_GET['preview'] == true) ||
			(strpos($current_url, 'blogfoel-hf-builder') !== false && get_post_type() == 'blogfoel-hf-builder')
		) {
			$post_id = $elementor->documents->get($post_id, false)->get_settings('blogfoel_demo_post_id');
		}
		
		$post = get_post($post_id);
		if (!$post) {
			return;
		}
		
		$title = $post->post_title;
		?>
		
        <div class="blogfoel-widget-wrapper">
            <div class="blogfoel-single-post-title-wrapper <?php echo esc_attr($this->single_title_wrapper)?>">
			<?php
				echo '<' . esc_attr($tag) . ' class="blogfoel-single-post-title ' . esc_attr($this->single_title) . '" title="' .
					the_title_attribute(array(
						'before' => esc_html__('Permalink to: ', 'blognews-for-elementor'),
						'after' => '',
						'echo' => false,
					)) . '">';
				echo esc_html($title);
				echo '</' . esc_attr($tag) . '>';
				?>
			</div>
		</div>
		<?php
	}	
}