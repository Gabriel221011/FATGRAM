<?php
namespace BlogFoel;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Text_Stroke;
use BlogFoel\Notices;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class BLOGFOELPostShareIcons extends \Elementor\Widget_Base {
	
	private $single_share_wrapper = 'blognews-single-post-share-wrapper';
	private $single_share = 'blognews-single-post-share-icons';
	private $single_share_icon = 'blognews-single-post-share-icon';
	
	public function get_name() {
		return 'blognews-post-share-icons';
	}

	public function get_title() {
		return esc_html__( 'BN Post Share Icons', 'blognews-for-elementor' );
	}

	public function get_icon() {
		return 'blogfoel-widget-icon bnicon-post-share-icon';
	}

	public function get_categories() {
		return [ 'blogfoel-sng-elementor' ];
	}

	public function get_keywords() {
		return [ 'post-title', 'post', 'share icons', 'post social icons', 'social icons' ];
	}


	public function add_options_post_share_select() {
		return [
			'facebook'	=> esc_html__( 'Facebook', 'blognews-for-elementor' ),
			'x-twitter'	=> esc_html__( 'X Twitter', 'blognews-for-elementor' ),
			'envelope'	=> esc_html__( 'Envelope', 'blognews-for-elementor' ),
			'linkedin'	=> esc_html__( 'LinkedIn', 'blognews-for-elementor' ),
			'pinterest'	=> esc_html__( 'Pintrest', 'blognews-for-elementor' ),
			'telegram'	=> esc_html__( 'Telegram', 'blognews-for-elementor' ),
			'whatsapp'	=> esc_html__( 'Whatsapp', 'blognews-for-elementor' ),
			'reddit'	=> esc_html__( 'Reddit', 'blognews-for-elementor' ),
			'print'		=> esc_html__( 'Print', 'blognews-for-elementor' ),
			'none'		=> esc_html__( 'None', 'blognews-for-elementor' ),
		];
	}
	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		
		$slug = 'social_share_icon';
		$this->start_controls_section(
			$slug.'_section_title',
			[
				'label' => esc_html__( 'General', 'blognews-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			$slug.'_one',
			[
				'label'       => esc_html__( 'Icon 1', 'blognews-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'facebook',
				'options'     => $this->add_options_post_share_select(),
			]
		);

		$this->add_control(
			$slug.'_two',
			[
				'label'       => esc_html__( 'Icon 2', 'blognews-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'x-twitter',
				'options'     => $this->add_options_post_share_select(),
			]
		);

		$this->add_control(
			$slug.'_three',
			[
				'label'       => esc_html__( 'Icon 3', 'blognews-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'envelope',
				'options'     => $this->add_options_post_share_select(),
			]
		);

		$this->add_control(
			$slug.'_four',
			[
				'label'       => esc_html__( 'Icon 4', 'blognews-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'linkedin',
				'options'     => $this->add_options_post_share_select(),
			]
		);
		$this->add_control(
			'repeater_pro_notice',
			[
				'raw' => 'More than 4 are available in <a href="'.BLOGFOEL_PRO_LINK.'" target="_blank">Pro Version!</a>',
				'type' => \Elementor\Controls_Manager::RAW_HTML,
				'content_classes' => 'blogfoel-pro-notice',
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
					'{{WRAPPER}} .'. $this->single_share => 'justify-content: {{VALUE}}',
				],
				'separator' => 'before'
            ]
        );

		$this->end_controls_section(); // End Controls Section
		Notices::go_premium_notice_content($this, 'notice_one');

		$this->start_controls_section(
			$slug.'_style_section',
			[
				'label' => esc_html__( 'Icon', 'blognews-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			$slug.'_color_option', 
			[
				'label' => __('Color', 'blognews-for-elementor'),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'options' => [
					'official'       => esc_html__( 'Official Color', 'blognews-for-elementor' ),
					'custom'       => esc_html__( 'Custom', 'blognews-for-elementor' ),
				],
				'default' => ['official'],
			]
		);

		$this->start_controls_tabs( $slug.'_style_tabs' );

		$this->start_controls_tab(
			$slug.'_normal_style',
			[
				'label' => __( 'Normal', 'blognews-for-elementor' ),
				'condition' => [
					$slug.'_color_option' => 'custom'
				],
			]
		);
		$this->add_control(
			$slug.'_color',
			[
				'label'     => __( 'Primary Color', 'blognews-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'. $this->single_share_icon => 'color: {{VALUE}}',
				],
				'condition' => [
					$slug.'_color_option' => 'custom'
				],
			]
		);
		
		$this->add_control(
			$slug.'_bg_color',
			[
				'label'     => __( 'Secondary Color', 'blognews-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'. $this->single_share_icon => 'background-color: {{VALUE}}',
				],
				'condition' => [
					$slug.'_color_option' => 'custom'
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => $slug.'_border_type',
				'label'    => 'Border Type',
				'selector' => '{{WRAPPER}} .'. $this->single_share_icon,
				'condition' => [
					$slug.'_color_option' => 'custom'
				],
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			$slug.'_hover_style',
			[
				'label' => __( 'Hover', 'blognews-for-elementor' ),
				'condition' => [
					$slug.'_color_option' => 'custom'
				],
			]
		);
		$this->add_control(
			$slug.'_hover_color',
			[
				'label'     => __( 'Primary Color', 'blognews-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'. $this->single_share_icon.':hover' => 'color: {{VALUE}}',
				],
				'condition' => [
					$slug.'_color_option' => 'custom'
				],
			]
		);
		
		$this->add_control(
			$slug.'_bg_hover_color',
			[
				'label'     => __( 'Secondary Color', 'blognews-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'. $this->single_share_icon.':hover' => 'background-color: {{VALUE}}',
				],
				'condition' => [
					$slug.'_color_option' => 'custom'
				],
			]
		);
		
		$this->add_control(
			$slug.'_boder_hover_color',
			[
				'label'     => __( 'Border Color', 'blognews-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'. $this->single_share_icon.':hover' => 'border-color: {{VALUE}}',
				],
				'condition' => [
					$slug.'_color_option' => 'custom'
				],
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_control(
			$slug.'_hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
				'condition' => [
					$slug.'_color_option' => 'custom'
				],
			]
		);
		$this->add_responsive_control(
			$slug.'_size',
			[
				'label'           => __( 'Icon Size', 'blognews-for-elementor'),
				'type'            => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px'  => ['min' => 0, 'max' => 100],
					'em'  => ['min' => 0, 'max' => 100],
					'rem' => ['min' => 0, 'max' => 100],
				],
				'default_desktop' => ['size' => '', 'unit' => 'px'],
				'tablet_default'  => ['size' => '', 'unit' => 'px'],
				'mobile_default'  => ['size' => '', 'unit' => 'px'],
				'selectors'       => [
					'{{WRAPPER}} .'. $this->single_share_icon => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			$slug.'_width',
			[
				'label'           => __( 'Icon Width', 'blognews-for-elementor'),
				'type'            => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px'  => ['min' => 0, 'max' => 120],
					'em'  => ['min' => 0, 'max' => 100],
					'rem' => ['min' => 0, 'max' => 100],
				],
				'default_desktop' => ['size' => '', 'unit' => 'px'],
				'tablet_default'  => ['size' => '', 'unit' => 'px'],
				'mobile_default'  => ['size' => '', 'unit' => 'px'],
				'selectors'       => [
					'{{WRAPPER}} .'. $this->single_share_icon => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .'. $this->single_share_icon => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			$slug.'_margin',
			[
				'label'     => esc_html__('Margin', 'blognews-for-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .'. $this->single_share_icon => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();

		Notices::go_premium_notice_style($this, 'notice_two');
	}
	protected function render() {
		$settings = $this->get_settings();
		$current_url = sanitize_text_field($_SERVER['REQUEST_URI']);
	
		// Check if in Elementor editor or preview mode
		$is_elementor_edit_mode = class_exists("\Elementor\Plugin") && \Elementor\Plugin::$instance->editor->is_edit_mode();
		$is_elementor_preview = class_exists("\Elementor\Plugin") && isset($_GET['preview']) && isset($_GET['preview_id']) && $_GET['preview'] == true;
	
		// Determine post ID based on context
		if ($is_elementor_edit_mode || $is_elementor_preview || (strpos($current_url, 'blogfoel-hf-builder') !== false && get_post_type() == 'blogfoel-hf-builder')) {
			$post_id = get_the_ID();
			$post_id = \Elementor\Plugin::$instance->documents->get($post_id, false)->get_settings('blogfoel_demo_post_id');
		} else {
			$post_id = get_the_ID();
		}
	
		// Validate post
		$post = get_post($post_id);
		if (!$post) {
			return;
		}
		?>
		<div class="blogfoel-widget-wrapper">
			<div class="blogfoel-single-post-social-share-wrapper <?php echo esc_attr($this->single_share_wrapper)?>">
				<?php $this->social_icons($post_id, $settings); ?>
			</div>
		</div><?php
	}
	
	function social_icons($post_id, $args) {
		$post_link = esc_url(get_the_permalink($post_id));
		$post_title = get_the_title($post_id);
	
		// Define default icons if not set in $args
		$icons = array_filter([
			$args['social_share_icon_one'] ?? '',
			$args['social_share_icon_two'] ?? '',
			$args['social_share_icon_three'] ?? '',
			$args['social_share_icon_four'] ?? '',
			$args['social_share_icon_five'] ?? '',
			$args['social_share_icon_six'] ?? '',
			$args['social_share_icon_seven'] ?? '',
			$args['social_share_icon_eight'] ?? '',
			$args['social_share_icon_nine'] ?? '',
		]);
	
		// Helper function to generate share URLs
		$generate_share_url = function ($platform, $params) use ($post_link, $post_title) {
			$base_urls = [
				'facebook' => 'https://www.facebook.com/share.php',
				'twitter' => 'http://twitter.com/share',
				'email' => 'mailto:',
				'linkedin' => 'https://www.linkedin.com/sharing/share-offsite/?url',
				'pinterest' => 'http://pinterest.com/pin/create/link/?url=',
				'reddit' => 'https://www.reddit.com/submit',
				'telegram' => 'https://t.me/share/url?url=',
				'whatsapp' => 'https://api.whatsapp.com/send?text=',
			];
	
			return add_query_arg($params, $base_urls[$platform] ?? '');
		};
	
		// Generate URLs for each platform
		$urls = [
			'facebook' => $generate_share_url('facebook', ['url' => $post_link]),
			'twitter' => $generate_share_url('twitter', ['url' => $post_link, 'text' => rawurlencode(wp_strip_all_tags($post_title))]),
			'email' => $generate_share_url('email', ['subject' => wp_strip_all_tags($post_title), 'body' => $post_link]),
			'linkedin' => $generate_share_url('linkedin', ['url' => $post_link, 'title' => rawurlencode(wp_strip_all_tags($post_title))]),
			'pinterest' => $generate_share_url('pinterest', ['url' => $post_link, 'title' => rawurlencode(wp_strip_all_tags($post_title))]),
			'reddit' => $generate_share_url('reddit', ['url' => $post_link, 'title' => rawurlencode(wp_strip_all_tags($post_title))]),
			'telegram' => $generate_share_url('telegram', ['url' => $post_link, 'title' => rawurlencode(wp_strip_all_tags($post_title))]),
			'whatsapp' => $generate_share_url('whatsapp', ['text' => $post_link]),
		];
		?>
		<script>
		function pinIt() {
			var e = document.createElement('script');
			e.setAttribute('type', 'text/javascript');
			e.setAttribute('charset', 'UTF-8');
			e.setAttribute('src', 'https://assets.pinterest.com/js/pinmarklet.js?r=' + Math.random() * 99999999);
			document.body.appendChild(e);
		}
		</script>

		<div class="blogfoel-single-post-social-share-icons <?php echo esc_attr($this->single_share)?>">
		<?php foreach ($icons as $icon):
			switch ($icon): 
				case 'facebook': ?>
					<a class="elementor-social-icon-facebook <?php echo esc_attr($this->single_share_icon)?>" href="<?php echo esc_url($urls['facebook']); ?>" target="_blank"><i class="fab fa-facebook"></i></a>
					<?php break;

				case 'x-twitter': ?>
					<a class="elementor-social-icon-x-twitter <?php echo esc_attr($this->single_share_icon)?>" href="<?php echo esc_url($urls['twitter']); ?>" target="_blank"><i class="fab fa-x-twitter"></i></a>
					<?php break;

				case 'envelope': ?>
					<a class="elementor-social-icon-envelope <?php echo esc_attr($this->single_share_icon)?>" href="<?php echo esc_url($urls['email']); ?>" target="_blank"><i class="fas fa-envelope-open"></i></a>
					<?php break;

				case 'linkedin': ?>
					<a class="elementor-social-icon-linkedin <?php echo esc_attr($this->single_share_icon)?>" href="<?php echo esc_url($urls['linkedin']); ?>" target="_blank"><i class="fab fa-linkedin"></i></a>
					<?php break;

				case 'pinterest': ?>
					<a class="elementor-social-icon-pinterest <?php echo esc_attr($this->single_share_icon)?>" href="javascript:pinIt();"><i class="fab fa-pinterest"></i></a>
					<?php break;

				case 'telegram': ?>
					<a class="elementor-social-icon-telegram <?php echo esc_attr($this->single_share_icon)?>" href="<?php echo esc_url($urls['telegram']); ?>" target="_blank"><i class="fab fa-telegram"></i></a>
					<?php break;

				case 'whatsapp': ?>
					<a class="elementor-social-icon-whatsapp <?php echo esc_attr($this->single_share_icon)?>" href="<?php echo esc_url($urls['whatsapp']); ?>" target="_blank"><i class="fab fa-whatsapp"></i></a>
					<?php break;

				case 'reddit': ?>
					<a class="elementor-social-icon-reddit <?php echo esc_attr($this->single_share_icon)?>" href="<?php echo esc_url($urls['reddit']); ?>" target="_blank"><i class="fab fa-reddit"></i></a>
					<?php break;

				case 'print': ?>
					<a class="elementor-social-icon-print-r <?php echo esc_attr($this->single_share_icon)?>" href="javascript:window.print()"><i class="fas fa-print"></i></a>
					<?php break;

				default:
					break;
			endswitch; 
		endforeach;
		echo '</div>';
	}
}