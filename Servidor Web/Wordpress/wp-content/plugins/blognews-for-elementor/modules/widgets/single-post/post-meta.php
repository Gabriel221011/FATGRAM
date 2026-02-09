<?php
namespace BlogFoel;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Repeater;
use BlogFoel\Notices;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class BLOGFOELPostMeta extends \Elementor\Widget_Base {
	
	private $single_meta_wrapper = 'blogfoel-single-post-meta-wrapper';
    private $single_meta = 'blognews-single-post-meta';
    private $single_meta_icon = 'blogfoel-meta-icon';

	public function get_name() {
		return 'blognews-post-meta';
	}

	public function get_title() {
		return esc_html__( 'BN Post Meta', 'blognews-for-elementor' );
	}

	public function get_icon() {
		return 'blogfoel-widget-icon bnicon-advanced-post-meta';
	}

	public function get_categories() {
		return [ 'blogfoel-sng-elementor' ];
	}

	public function get_keywords() {
		return [ 'post-meta', 'post', 'meta' ];
	}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_post_meta',
			[
				'label' => esc_html__( 'General', 'blognews-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);
		$repeater = new Repeater();
		$repeater->add_control(
            'post_meta',
            [
                'label'       => esc_html__( 'Choose Element', 'blognews-for-elementor' ),
                'type'        => 'blogfoel-select',
				'label_block' => false,
				'default'     => 'author',
                'options'     => [
					'author'       => esc_html__( 'Author', 'blognews-for-elementor' ),
					'date'         => esc_html__( 'Date', 'blognews-for-elementor' ),
					'blogfoel-pro-publish-time' => esc_html__( 'Publish Time (Pro)', 'blognews-for-elementor' ),
					'comments'     => esc_html__( 'Comments', 'blognews-for-elementor' ),
					'blogfoel-pro-read-time'	   => esc_html__( 'Read Time (Pro)', 'blognews-for-elementor' ),
					'blogfoel-pro-views'	       => esc_html__( 'Views (Pro)', 'blognews-for-elementor' ),
                ],
				'separator' => 'after'
            ]
        );
		$repeater->add_control(
			'by_author',
			[
				'label' => esc_html__( 'Show "By" Author', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
				'type' => Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Yes', 'blognews-for-elementor' ),
				'label_off' => esc_html__( 'No', 'blognews-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
				'condition' => [
					'post_meta' => 'author',
				],
				'classes' => 'blogfoel-pro-popup-notice',
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

		$repeater->add_control(
			'date_format',
			[
				'label'       => esc_html__( 'Date Format', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'default',
				'options'     => [
					'default'       => esc_html__( 'Default', 'blognews-for-elementor' ),
					'wordpress'       => esc_html__( 'Wordpress', 'blognews-for-elementor' ),
				],
				'classes' => 'blogfoel-pro-popup-notice',
				'condition' => [
					'post_meta' => 'date'
				],
			]
		);
		$repeater->add_control(
            'no_comments_title',
            [
                'label'     => __( 'No Comments', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::TEXT,
                'placeholder'   => __( 'No Comments', 'blognews-for-elementor' ),
                'default'   => __( 'No Comments', 'blognews-for-elementor' ),
				'condition' => [
					'post_meta' => 'comments'
				],
            ]
        );
        $repeater->add_control(
            'one_comment_title',
            [
                'label'     => __( 'One Comment', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::TEXT,
                'placeholder'   => __( 'Comment', 'blognews-for-elementor' ),
                'default'   => __( 'Comment', 'blognews-for-elementor' ),
				'condition' => [
					'post_meta' => 'comments'
				],
            ]
        );
        $repeater->add_control(
            'multi_comments_title',
            [
                'label'     => __( 'Multi Comments', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::TEXT,
                'placeholder'   => __( 'Comments', 'blognews-for-elementor' ),
                'default'   => __( 'Comments', 'blognews-for-elementor' ),
				'condition' => [
					'post_meta' => 'comments'
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
						'post_meta' => 'comments',
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

		$this->add_responsive_control(
            'post_meta_align',
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
					'space-between' => [
						'title' => __( 'Justify', 'blognews-for-elementor' ),
						'icon' => 'eicon-text-align-justify',
					],
                ],
				'selectors' => [
					'{{WRAPPER}} .'. $this->single_meta_wrapper => 'justify-content: {{VALUE}}',
				],
				'separator' => 'before'
            ]
        );

		$this->end_controls_section(); // End Controls Section
		Notices::go_premium_notice_content($this, 'notice_one');

		// Blog Meta Author
		$this->start_controls_section(
			'single_blog_meta_author_img_settings',
			[
				'label' => __( 'Author Image Settings ', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		
		$slug = 'single_blog_meta_author';

		$this->add_responsive_control(
			$slug.'_img_size',
			[
				'label' => esc_html__( 'Size', 'blognews-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem','vw', 'custom' ],
				'range' => [
					'px'  => ['min' => 0, 'max' => 100],
					'em'  => ['min' => 0, 'max' => 100],
					'rem' => ['min' => 0, 'max' => 100],
					'vw'  => ['min' => 0, 'max' => 200],
				],
				'default_desktop' => ['size' => '', 'unit' => 'px'],
				'tablet_default'  => ['size' => '', 'unit' => 'px'],
				'mobile_default'  => ['size' => '', 'unit' => 'px'],
				'selectors' => [
					'{{WRAPPER}} .blogfoel-avatar' => 'width: {{SIZE}}{{UNIT}};',
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
			Group_Control_Border::get_type(),
			[
				'name'     => $slug.'_border_type',
				'label'    => 'Border Type',
				'selector' => '{{WRAPPER}} .blogfoel-avatar',
			]
		);
		$this->add_responsive_control(
			$slug.'_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .blogfoel-avatar' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
		
		$this->end_controls_tab();

		$slug = 'hover_'.$slug;
		$this->start_controls_tab(
			$slug.'_style',
			[
				'label' => __( 'Hover', 'blognews-for-elementor' ),
			]
		);
		$this->add_control(
			$slug . '_border_type',
			[
				'label' => esc_html__( 'Border Type', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
                'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'default',
				'options'     => [
					'default' => 'Default',
				  ],
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);
		$this->start_popover();
		$this->end_popover();
		
		$this->add_responsive_control(
			$slug.'_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'blognews-for-elementor' ). BLOGFOEL_PRO_ICON,
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

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'post_meta_style',
			[
				'label' => __( 'Metas Settings', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$slug = 'post_meta';

		blogfoel_meta_style_control( $this , $slug , $this->single_meta_wrapper );

		$this->end_controls_section();
		Notices::go_premium_notice_style($this, 'notice_two');
	}

	protected function render() {
		$settings   = $this->get_settings();		
		$post_meta_repeater          = array_slice($settings['post_meta_repeater'], 0, 3);
		$current_url = $_SERVER['REQUEST_URI'];

		if (
			(class_exists("\Elementor\Plugin") && \Elementor\Plugin::$instance->editor->is_edit_mode()) ||
			(class_exists("\Elementor\Plugin") && isset($_GET['preview'], $_GET['preview_id']) && $_GET['preview'] == true) ||
			(strpos($current_url, 'blogfoel-hf-builder') !== false && get_post_type() == 'blogfoel-hf-builder')
		) {
			$post_id = get_the_ID();
			$document = \Elementor\Plugin::$instance->documents->get($post_id, false);
			$post_id = $document ? $document->get_settings('blogfoel_demo_post_id') : $post_id;
			$post = get_post($post_id);
		} else {
			$post_id = get_the_ID();
			$post = get_post($post_id);
		}

		if (!$post) {
			return;
		}

		?>

		<div class="blogfoel-widget-wrapper">
			<div class="blogfoel-single-post-meta-wrapper">
				<?php 
				if (empty($post_meta_repeater) || !is_array($post_meta_repeater)) {
					return;
				}
				foreach ($post_meta_repeater as $meta_item) {
					$meta_type            = $meta_item['post_meta'];
					$avatar               = $meta_item['show_avatar'];
					$date_format          = $meta_item['date_format'];
					$no_comments          = $meta_item['no_comments_title'];
					$one_comment          = $meta_item['one_comment_title'];
					$multi_comments       = $meta_item['multi_comments_title'];
					$icon                 = $meta_item['meta_icon'];
				
					// Define meta templates inside the loop so $meta_item is accessible
					$meta_templates = [
						'author' => blogfoel_get_author($avatar, $icon),
						'date' => blogfoel_get_date('', $icon),
						'comments'  => blogfoel_get_comments($icon, $no_comments, $one_comment, $multi_comments),
					];
					if (isset($meta_templates[$meta_type])) {
						echo wp_kses_post($meta_templates[$meta_type]);
					}
				}
				?>
			</div>
		</div>
		<?php
	}
}