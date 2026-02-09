<?php
namespace BlogFoel;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Typography;
use BlogFoel\Notices;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class BLOGFOELPostAuthor extends \Elementor\Widget_Base {
		
	private $single_author_wrapper = 'blognews-single-post-author-wrapper';
    private $single_author_name = 'blognews-single-post-author-name';
    private $single_author_desc = 'blognews-single-post-author-description';
    private $single_author_img = 'blognews-single-post-author-image';

	public function get_name() {
		return 'blognews-post-author';
	}

	public function get_title() {
		return esc_html__( 'BN Post Author', 'blognews-for-elementor' );
	}

	public function get_icon() {
		return 'blogfoel-widget-icon bnicon-post-author';
	}

	public function get_categories() {
		return [ 'blogfoel-sng-elementor' ];
	}

	public function get_keywords() {
		return [ 'post-title', 'post', 'title', 'post author', 'author' ];
	}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		
		$slug = 'single_post_author';
		$this->start_controls_section(
			$slug.'_section',
			[
				'label' => esc_html__( 'General', 'blognews-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			$slug.'_by_author',
			[
				'label' => esc_html__( 'Show "By" Author', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'blognews-for-elementor' ),
				'label_off' => esc_html__( 'No', 'blognews-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'no',
				'escape' => false,
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);

		$this->add_responsive_control(
			$slug.'_img_postition',
			[
				'label' => __('Image Position', 'blognews-for-elementor') , 
				'type' => Controls_Manager::CHOOSE, 
                'default' => '',
				'options' => [
					'row' => [
						'title' => __('Left', 'blognews-for-elementor') , 
						'icon' => 'eicon-h-align-left', 
					], 
					'column' => [
						'title' => __('Top', 'blognews-for-elementor') , 
						'icon' => 'eicon-v-align-top', 
					], 
					'row-reverse' => [
						'title' => __('Right', 'blognews-for-elementor') , 
						'icon' => 'eicon-h-align-right',
					], 
				], 
				'selectors' => [
					'{{WRAPPER}} .'. $this->single_author_wrapper => 'flex-direction: {{VALUE}};',
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
					'{{WRAPPER}} .'. $this->single_author_wrapper => 'align-items: {{VALUE}};',
				],
				'condition' => [
					$slug.'_img_postition!' => 'column'
				],
			]
		);

		$this->add_responsive_control(
			$slug.'_horizontal_align',
			[
				'label' => __('Horizontal Alignment', 'blognews-for-elementor') , 
				'type' => Controls_Manager::CHOOSE, 
                'default' => '',
				'options' => [
					'flex-start' => [
						'title' => __('Start', 'blognews-for-elementor') , 
						'icon' => 'eicon-align-start-h', 
					], 
					'center' => [
						'title' => __('Center', 'blognews-for-elementor') , 
						'icon' => 'eicon-align-center-h',
					], 
					'flex-end' => [
						'title' => __('End', 'blognews-for-elementor') , 
						'icon' => 'eicon-align-end-h',
					], 
				], 
				'selectors' => [
					'{{WRAPPER}} .'. $this->single_author_wrapper => 'align-items: {{VALUE}};',
				],
				'condition' => [
					$slug.'_img_postition' => 'column'
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
						'icon' => 'eicon-justify-start-h',
					],
					'center' => [
						'title' => __( 'Center', 'blognews-for-elementor' ),
						'icon' => 'eicon-justify-center-h',
					],
					'end' => [
						'title' => __( 'Right', 'blognews-for-elementor' ),
						'icon' => 'eicon-justify-end-h',
					],
					'space-between' => [
						'title' => __( 'Space Between', 'blognews-for-elementor' ),
						'icon' => 'eicon-justify-space-between-h',
					],
                ],
				'selectors' => [
					'{{WRAPPER}} .'. $this->single_author_wrapper => 'justify-content: {{VALUE}};',
				],
				'condition' => [
					$slug.'_img_postition!' => 'column'
				],
            ]
        );
		$this->add_responsive_control(
            $slug.'text_align',
            [
                'label' => esc_html__( 'Text Alignment', 'blognews-for-elementor' ),
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
					'{{WRAPPER}} .'. $this->single_author_wrapper => 'text-align: {{VALUE}};',
				],
            ]
        );
		
		$this->end_controls_section(); // End Controls Section

		Notices::go_premium_notice_content($this, 'notice_one');

		$this->start_controls_section(
			$slug.'_box_area',
			[
				'label' => esc_html__( 'Author Box', 'blognews-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			$slug.'_box_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'blognews-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'. $this->single_author_wrapper => 'background-color: {{VALUE}}',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => $slug.'_box_border_type',
				'label'    => 'Border Type',
				'selector' => '{{WRAPPER}} .'. $this->single_author_wrapper,
			]
		);
		$this->add_responsive_control(
			$slug.'_box_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .'. $this->single_author_wrapper => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			$slug.'_box_padding',
			[
				'label'     => esc_html__('Padding', 'blognews-for-elementor') . BLOGFOEL_PRO_ICON,
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);

		$this->add_responsive_control(
			$slug.'_box_margin',
			[
				'label'     => esc_html__('Margin', 'blognews-for-elementor') . BLOGFOEL_PRO_ICON,
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'      => $slug.'_box_shadow',
				'label'    => 'Box Shadow',
				'selector' => '{{WRAPPER}} .'. $this->single_author_wrapper,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			$slug.'_img_style',
			[
				'label' => esc_html__( 'Image', 'blognews-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);
		
		$slug = $slug.'_img';

		$this->add_responsive_control(
			$slug.'_size',
			[
				'label'           => __( 'Image Size', 'blognews-for-elementor' ),
				'type'            => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px'  => ['min' => 0, 'max' => 150],
					'em'  => ['min' => 0, 'max' => 100],
					'rem' => ['min' => 0, 'max' => 100],
				],
				'default_desktop' => ['size' => '', 'unit' => 'px'],
				'tablet_default'  => ['size' => '', 'unit' => 'px'],
				'mobile_default'  => ['size' => '', 'unit' => 'px'],
				'selectors'       => [
					'{{WRAPPER}} .'. $this->single_author_img.' img' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .'. $this->single_author_img => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => $slug.'_border_type',
				'label'    => 'Border Type',
				'selector' => '{{WRAPPER}} .'. $this->single_author_img,
			]
		);
		$this->add_responsive_control(
			$slug.'_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .'. $this->single_author_img => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'      => $slug.'_box_shadow',
				'label'    => 'Box Shadow',
				'selector' => '{{WRAPPER}} .'. $this->single_author_img,
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			$slug.'_name_style',
			[
				'label' => esc_html__( 'Name', 'blognews-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);
		$slug = $slug.'_name';
		
		$this->add_control(
			$slug.'_color',
			[
				'label'     => __( 'Color', 'blognews-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'. $this->single_author_name => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_control(
			$slug.'_hover_color',
			[
				'label'     => __( 'Hover Color', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
				'type'      => Controls_Manager::COLOR,
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);

		$this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => $slug.'_typography',
                'label'     => __( 'Typography', 'blognews-for-elementor' ), 
                'selector'  => '{{WRAPPER}} .'. $this->single_author_name, 
            )
        );

		$this->add_responsive_control(
			$slug.'_margin',
			[
				'label'     => esc_html__('Margin', 'blognews-for-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .'. $this->single_author_name => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			$slug.'iption_style',
			[
				'label' => esc_html__( 'Description', 'blognews-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);
		$slug = $slug.'_description';

		$this->add_control(
			$slug.'_color',
			[
				'label' => esc_html__( 'Color', 'blognews-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'. $this->single_author_desc => 'color: {{VALUE}}',
				],
			]
		);

		
		$this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => $slug.'_typography',
                'label'     => __( 'Typography', 'blognews-for-elementor' ), 
                'selector'  => '{{WRAPPER}} .'. $this->single_author_desc, 
            )
        );

		$this->add_responsive_control(
			$slug.'_margin',
			[
				'label'     => esc_html__('Margin', 'blognews-for-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .'. $this->single_author_desc => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
		
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

		$title = esc_html($post->post_title);
		$link = esc_url(get_permalink($post_id));
		$author_id = (int) $post->post_author;

		$author_name = get_the_author_meta('display_name', $author_id);
		$author_description = get_the_author_meta('description', $author_id);

		$author_url = esc_url(get_author_posts_url($author_id));
		$avatar = get_avatar($author_id, 150);
		?>

		<div class="blogfoel-widget-wrapper">
			<div class="blogfoel-single-post-author-wrapper <?php echo esc_attr($this->single_author_wrapper); ?>">
				<a class="blogfoel-author-pic <?php echo esc_attr($this->single_author_img); ?>" href="<?php echo esc_url($author_url); ?>">
					<?php echo wp_kses_post($avatar); ?>
				</a>
				<div class="blogfoel-author-meta">
					<h4 class="title <?php echo esc_attr($this->single_author_name); ?>">
						<a href="<?php echo esc_url($author_url); ?>">
							<?php esc_html_e('By', 'blognews-for-elementor')?> <?php echo esc_html($author_name); ?>
						</a>
					</h4>
					<?php if (!empty($author_description)) : ?>
						<p class="desc <?php echo esc_attr($this->single_author_desc); ?>">
							<?php echo esc_html($author_description); ?>
						</p>
					<?php endif; ?>
				</div>
			</div>
		</div>
	<?php
	}

}