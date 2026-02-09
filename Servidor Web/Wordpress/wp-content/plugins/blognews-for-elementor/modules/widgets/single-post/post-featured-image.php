<?php 
namespace BlogFoel;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use BlogFoel\Notices;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class BLOGFOELPostImage extends \Elementor\Widget_Base {
	
    private $single_image_wrapper = 'blognews-single-post-image-wrapper';
    private $single_image = 'blognews-single-post-image';

	public function get_name() {
		return 'blog-post-post-image';
	}

	public function get_title() {
		return esc_html__( 'BN Post Featured Image', 'blognews-for-elementor' );
	}

	public function get_icon() {
		return 'blogfoel-widget-icon bnicon-post-featured-image';
	}

	public function get_categories() {
		return [ 'blogfoel-sng-elementor' ];
	}

	public function get_keywords() {
		return ['post-image', 'post', 'image', 'post image' ];
	}


	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_post_image',
			[
				'label' => esc_html__( 'General', 'blognews-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Image_Size::get_type(),
			[
				'name' => 'post_featured_image_size', 
				'default' => 'large',
			]
		);

		$this->add_responsive_control(
            'post_image_align',
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
					'{{WRAPPER}} .'.$this->single_image_wrapper => 'text-align: {{VALUE}}',
				],
            ]
        );
		
		$this->add_control(
			'caption_type',
			[
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Show Caption', 'blognews-for-elementor' ),
				'label_on' => esc_html__( 'Show', 'blognews-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'blognews-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);
		$this->end_controls_section(); // End Controls Section

		Notices::go_premium_notice_content($this, 'notice_one');

		$this->start_controls_section(
			'image_section_style',
			[
				'label' => esc_html__( 'Image', 'blognews-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$slug = 'featured_image';

		$this->add_responsive_control(
			$slug.'_width',
			[
				'label' => esc_html__( 'Width', 'blognews-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem','vw', 'custom' ],
				'range' => [
					'px'  => ['min' => 0, 'max' => 1000],
					'em'  => ['min' => 0, 'max' => 100],
					'rem' => ['min' => 0, 'max' => 100],
					'vw'  => ['min' => 0, 'max' => 200],
				],
				'default_desktop' => ['size' => '', 'unit' => 'px'],
				'tablet_default'  => ['size' => '', 'unit' => 'px'],
				'mobile_default'  => ['size' => '', 'unit' => 'px'],
				'selectors' => [
					'{{WRAPPER}} .'.$this->single_image => 'width: {{SIZE}}{{UNIT}}!important;',
				],
			]
		);

		$this->add_responsive_control(
			$slug.'_height',
			[
				'label' => esc_html__( 'Height', 'blognews-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'custom' ],
				'range' => [
					'px'  => ['min' => 0, 'max' => 1000],
					'em'  => ['min' => 0, 'max' => 100],
					'rem' => ['min' => 0, 'max' => 100],
					'vh'  => ['min' => 0, 'max' => 200],
				],
				'default_desktop' => ['size' => '', 'unit' => 'px'],
				'tablet_default'  => ['size' => '', 'unit' => 'px'],
				'mobile_default'  => ['size' => '', 'unit' => 'px'],
				'selectors' => [
					'{{WRAPPER}} .'.$this->single_image => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
        $this->add_responsive_control(
			 $slug.'_object_fit',
			[
				'label' => esc_html__( 'Object Fit', 'blognews-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'condition' => [
					$slug.'_height[size]!' => '',
				],
				'options' => [
					'' => esc_html__( 'Default', 'blognews-for-elementor' ),
					'fill' => esc_html__( 'Fill', 'blognews-for-elementor' ),
					'cover' => esc_html__( 'Cover', 'blognews-for-elementor' ),
					'contain' => esc_html__( 'Contain', 'blognews-for-elementor' ),
					'scale-down' => esc_html__( 'Scale Down', 'blognews-for-elementor' ),
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .'.$this->single_image => 'object-fit: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			$slug.'_object_position',
			[
				'label' => esc_html__( 'Object Position', 'blognews-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'center center' => esc_html__( 'Center Center', 'blognews-for-elementor' ),
					'center left' => esc_html__( 'Center Left', 'blognews-for-elementor' ),
					'center right' => esc_html__( 'Center Right', 'blognews-for-elementor' ),
					'top center' => esc_html__( 'Top Center', 'blognews-for-elementor' ),
					'top left' => esc_html__( 'Top Left', 'blognews-for-elementor' ),
					'top right' => esc_html__( 'Top Right', 'blognews-for-elementor' ),
					'bottom center' => esc_html__( 'Bottom Center', 'blognews-for-elementor' ),
					'bottom left' => esc_html__( 'Bottom Left', 'blognews-for-elementor' ),
					'bottom right' => esc_html__( 'Bottom Right', 'blognews-for-elementor' ),
				],
				'default' => 'center center',
				'selectors' => [
					'{{WRAPPER}} .'.$this->single_image => 'object-position: {{VALUE}};',
				],
				'condition' => [
					$slug.'_height[size]!' => '',
					$slug.'_object_fit' => [ 'cover', 'contain', 'scale-down' ],
				],
			]
		);
		$this->add_control(
			$slug.'_separator_panel_style',
			[
				'type' => Controls_Manager::DIVIDER,
				'style' => 'thick',
			]
		);

		$this->start_controls_tabs( 'image_effects' );

		$this->start_controls_tab( 'normal',
			[
				'label' => esc_html__( 'Normal', 'blognews-for-elementor' ),
			]
		);

		$this->add_control(
			$slug.'_opacity',
			[
				'label' => esc_html__( 'Opacity', 'blognews-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .'.$this->single_image => 'opacity: {{SIZE}};',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Css_Filter::get_type(),
			[
				'name' => $slug.'_css_filters',
				'selector' => '{{WRAPPER}} .'.$this->single_image,
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab( 'hover',
			[
				'label' => esc_html__( 'Hover', 'blognews-for-elementor' ),
			]
		);

		$this->add_control(
			$slug.'_opacity_hover',
			[
				'label' => esc_html__( 'Opacity', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 1,
						'min' => 0.10,
						'step' => 0.01,
					],
				],
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);

		$this->add_control(
		$slug . '_css_filters_hover',
			[
				'label' => esc_html__( 'CSS Filters', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
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

		$this->add_control(
			$slug.'_background_hover_transition',
			[
				'label' => esc_html__( 'Transition Duration', 'blognews-for-elementor' ) . ' (s)'. BLOGFOEL_PRO_ICON,
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 3,
						'step' => 0.1,
					],
				],
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);

		$this->add_control(
			$slug.'_hover_animation',
			[
				'label' => esc_html__( 'Hover Animation', 'blognews-for-elementor' ). BLOGFOEL_PRO_ICON,
				'type' => Controls_Manager::HOVER_ANIMATION,
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => $slug.'_image_border',
				'selector' => '{{WRAPPER}} .'.$this->single_image,
				'separator' => 'before',
			]
		);

		$this->add_responsive_control(
			$slug.'_image_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'blognews-for-elementor' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .'.$this->single_image => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_control(
		$slug . '_image_box_shadow',
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
			'section_style_caption',
			[
				'label' => esc_html__( 'Caption', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'caption_type' => 'yes',
				],
			]
		);

		$slug = 'featured_image_caption';

		$this->add_responsive_control(
			$slug.'_align',
			[
				'label' => esc_html__( 'Alignment', 'blognews-for-elementor' ). BLOGFOEL_PRO_ICON,
				'type' => Controls_Manager::CHOOSE,
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
					'justify' => [
						'title' => esc_html__( 'Justified', 'blognews-for-elementor' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'default' => '',
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);

		$this->add_control(
			$slug.'_text_color',
			[
				'label' => esc_html__( 'Text Color', 'blognews-for-elementor' ). BLOGFOEL_PRO_ICON,
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);

		$this->add_control(
			$slug.'_background_color',
			[
				'label' => esc_html__( 'Background Color', 'blognews-for-elementor' ). BLOGFOEL_PRO_ICON,
				'type' => Controls_Manager::COLOR,
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => $slug.'_typography',
				'selector' => '{{WRAPPER}} .featured-image-caption',
			]
		);
		
		$this->add_control(
		$slug . '_text_shadow',
			[
				'label' => esc_html__( 'Text Shadow', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
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

		$this->add_responsive_control(
			$slug.'_space',
			[
				'label' => esc_html__( 'Spacing', 'blognews-for-elementor' ). BLOGFOEL_PRO_ICON,
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em', 'rem', 'custom' ],
				'range' => [
					'px' => [
						'max' => 100,
					],
					'em' => [
						'min' => 0,
						'max' => 10,
					],
					'rem' => [
						'min' => 0,
						'max' => 10,
					],
				],			
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);

		$this->end_controls_section();
		Notices::go_premium_notice_style($this, 'notice_two');
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$current_url = $_SERVER['REQUEST_URI'];
		$post_id = get_the_ID();
		$elementor = \Elementor\Plugin::$instance;

		// Detect editor or preview context
		if (
			(class_exists("\Elementor\Plugin") && $elementor->editor->is_edit_mode()) ||
			(class_exists("\Elementor\Plugin") && isset($_GET['preview'], $_GET['preview_id']) && $_GET['preview'] == true) ||
			(strpos($current_url, 'blogfoel-hf-builder') !== false && get_post_type() == 'blogfoel-hf-builder')
		) {
			$document = $elementor->documents->get($post_id, false);
			if ( $document ) {
				$blogfoel_demo_post_id = $document->get_settings('blogfoel_demo_post_id');
				if ( ! empty( $blogfoel_demo_post_id ) ) {
					$post_id = $blogfoel_demo_post_id;
				}
			}
		}

		$post = get_post( $post_id );
		if ( ! $post ) {
			return;
		}

		$size = $settings['post_featured_image_size_size'];
		$caption_type = $settings['caption_type'];
		$elementor_animation = $settings['featured_image_hover_animation'];
		$thumbnail_id = get_post_thumbnail_id( $post_id );
		$caption = $thumbnail_id ? get_post( $thumbnail_id )->post_excerpt : '';

		?>
		<div class="blogfoel-widget-wrapper">
			<div class="blogfoel-single-post-image-wrapper <?php echo esc_attr( $this->single_image_wrapper ); ?>">
				<?php
				// Display the featured image
				echo get_the_post_thumbnail( $post_id, $size, [
					'class' => 'img-fluid ' . esc_attr( $this->single_image ) . ' elementor-animation-' . $elementor_animation,
				] );

				// Display the caption, if enabled and available
				if ( ! empty( $caption ) && $caption_type === 'yes' ) {
					echo '<p class="featured-image-caption">' . esc_html( $caption ) . '</p>';
				}
				?>
			</div>
		</div>
		<?php
	}

}