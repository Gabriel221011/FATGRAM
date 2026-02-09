<?php
namespace BlogFoel;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Typography;
use BlogFoel\Notices;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class BLOGFOELPostTags extends \Elementor\Widget_Base {
	
	private $single_tag_wrapper = 'blognews-single-post-tags-wrapper';
    private $single_tag = 'blognews-single-post-tag';

	public function get_name() {
		return 'blognews-post-tags';
	}

	public function get_title() {
		return esc_html__( 'BN Post Tags', 'blognews-for-elementor' );
	}

	public function get_icon() {
		return 'blogfoel-widget-icon bnicon-post-tag';
	}

	public function get_categories() {
		return [ 'blogfoel-sng-elementor' ];
	}

	public function get_keywords() {
		return [ 
			'BLOGFOEL',
			'post-tags', 
			'tag', 
			'tags',
			'#',
            'themeansar',
			'taxonomy',
		];
	}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_post_tags',
			[
				'label' => esc_html__( 'Single Tags', 'blognews-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$slug = 'blog_tag';
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
					'{{WRAPPER}} .'.$this->single_tag_wrapper => 'justify-content: {{VALUE}}',
				],
            ]
        );
		$this->add_control(
			'tag_icon',
			[
				'label' => esc_html__( 'Tags Icon', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
                'default' => [
					'value' => 'fas fa-hashtag',
					'library' => 'solid',
				],
				'label_block' => false,
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);
		$this->end_controls_section(); // End Controls Section
		Notices::go_premium_notice_content($this, 'notice_one');

		// Blog Category
		$this->start_controls_section(
			'single_blog_tag_settings',
			[
				'label' => __( 'Tag Settings ', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,   
			]
		);
		
		$slug = 'single_blog_tag';
		
		$this->add_responsive_control(
			$slug.'_icon_position',
			[
				'label'     => __( 'Icon Position', 'blognews-for-elementor' ),
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
					'{{WRAPPER}} .'.$this->single_tag => 'flex-direction: {{VALUE}}',
				],
				'condition' => [
					'tag_icon[value]!' => ''
				],
			]
		);
		
		$this->add_responsive_control(
			$slug.'_icon_gap',
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
					'{{WRAPPER}} .'.$this->single_tag => 'gap: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'tag_icon[value]!' => ''
				],
			]
		);
		$this->add_responsive_control(
			$slug.'_icon_size',
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
					'{{WRAPPER}} .'.$this->single_tag.' .blogfoel-tag-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .'.$this->single_tag.' svg' => 'width: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'tag_icon[value]!' => ''
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
					'{{WRAPPER}} .'.$this->single_tag => 'color: {{VALUE}};',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
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
				'selector'  => '{{WRAPPER}} .'.$this->single_tag,
			]
		);
		$this->add_control(
			$slug.'_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'.$this->single_tag.' .blogfoel-tag-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .'.$this->single_tag.' svg' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'tag_icon[value]!' => ''
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => $slug.'_border_type',
				'label'    => 'Border Type',
				'selector' => '{{WRAPPER}}  .'.$this->single_tag,
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'      => $slug.'_box_shadow',
				'label'    => 'Box Shadow',
				'selector' => '{{WRAPPER}} .'.$this->single_tag,
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
					'{{WRAPPER}} .'.$this->single_tag.':hover' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
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
				'selector'  => '{{WRAPPER}} .'.$this->single_tag.':hover',
			]
		);
		$this->add_control(
			$slug.'_icon_hover_color',
			[
				'label' => esc_html__( 'Icon Color', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'.$this->single_tag.':hover .blogfoel-tag-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .'.$this->single_tag.':hover svg' => 'fill: {{VALUE}};',
				],
				'condition' => [
					'tag_icon[value]!' => ''
				],
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => $slug.'_border_hover_type',
				'label'    => 'Border Type',
				'selector' => '{{WRAPPER}}  .'.$this->single_tag.':hover',
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'      => $slug.'_box_hover_shadow',
				'label'    => 'Box Shadow',
				'selector' => '{{WRAPPER}} .'.$this->single_tag.':hover',
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
                'label'    => __( 'Typography', 'blognews-for-elementor' ),
				'selector' => '{{WRAPPER}} .'.$this->single_tag.' span',
			]
		);
		
		$this->add_responsive_control(
			$slug.'_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .'.$this->single_tag => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
		Notices::go_premium_notice_style($this, 'notice_two');
	}

	protected function render() {
		$settings = $this->get_settings();
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

		$tags = wp_get_object_terms($post_id, 'post_tag');
		?>

		<div class="blogfoel-widget-wrapper">
			<div class="blogfoel-tags <?php echo esc_attr($this->single_tag_wrapper)?>">
				<?php
				if (!empty($tags) && !is_wp_error($tags)) : ?>
					<?php foreach ($tags as $tag) : ?>
						<a href="<?php echo esc_url(get_term_link($tag)); ?>" class="single-tag <?php echo esc_attr($this->single_tag)?>">
							<?php echo '<span>'.esc_html($tag->name).'</span>'; ?>
						</a>
					<?php endforeach; ?>
				<?php else : ?>
					<span class="single-tag <?php echo esc_attr($this->single_tag)?>"><?php esc_html_e("Tags have not been defined.", "blognews-for-elementor"); ?></span>
				<?php endif; ?>
			</div>
		</div>
		<?php 
	}
}