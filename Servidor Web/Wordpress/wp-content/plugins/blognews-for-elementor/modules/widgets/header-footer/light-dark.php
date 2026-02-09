<?php 
namespace BlogFoel;

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Image_Size;
use Elementor\Widget_Base;
use Elementor\this;
use Elementor\Utils;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Background;

class BLOGFOELLightDark extends \Elementor\Widget_Base {

	private $stt_card_class = 'blogfoel-upscr'; 
	private $stt_title = 'title';
	private $stt_icon = 'icon';

	public function get_name() {
		return 'blognews-light-dark-toggle';
	}

	public function get_title() {
		return __( 'Light & Dark Toggle', 'blognews-for-elementor' );
	}

	public function get_categories() {
		return [ 'blogfoel-hf-elementor' ];
	}

	public function get_icon() {
		return 'blogfoel-widget-icon bnicon-light-dark-toggle';
	}

	public function get_style_depends() {
		return [
			'blognews-widget-css',
		];
	}

	public function get_script_depends() {
		return [
			'blognews-widget-js',
		];
	}

	public function get_keywords() {
		return [ 
			'theme mode',
			'light dark toggle',
			'light and dark', 
			'dark mode', 
			'light and dark mode'
		];
	}
	protected function register_controls() {

		$this->start_controls_section(
			'content_section',
			[
				'label' => __( 'Content', 'blognews-for-elementor' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
		$this->add_control(
			'layout_style',
			[
				'label'       => esc_html__( 'Layout Style', 'blognews-for-elementor' ),
				'placeholder' => esc_html__( 'Choose layout Style from Here', 'blognews-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'layout-one',
				'options'     => [
					'layout-one'   => esc_html__( 'Style 1', 'blognews-for-elementor' ),
					'layout-two'   => esc_html__( 'Style 2', 'blognews-for-elementor' ),
				],
			]
		);

    	$this->add_responsive_control(
            'l_d_align',
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
					'{{WRAPPER}} .blogfoel-light-dark-wrapper' => 'text-align: {{VALUE}}',
				],
			]
		);
		$this->add_control(
			'l_d_position',
			[
				'label' => esc_html__( 'Position', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options'     => [
					'fixed' => 'Fixed',
					'relative' => 'Inherit',
				],
				'default' => 'relative',
				'selectors' => [
					'{{WRAPPER}} .blogfoel-switch' => 'position: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'l_d_fixed_position',
			[
				'label'     => __( 'Fixed Position', 'blognews-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'blognews-for-elementor' ),
						'icon'  => 'eicon-h-align-left',
					],
					'right'  => [
						'title' => __( 'Right', 'blognews-for-elementor' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'default'   => 'left',
				'condition' => [
					'l_d_position' => 'fixed'
				],
			]
		);
		$this->add_responsive_control(
			'offset_x',
			[
				'label' => esc_html__( 'Offset X', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
				'range' => [
					'px'  => ['min' => 0, 'max' => 1000],
					'em'  => ['min' => 0, 'max' => 100],
					'rem' => ['min' => 0, 'max' => 100],
					'vw' => ['min' => 0, 'max' => 200],
				],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'default' => ['size' => '', 'unit' => 'px'],
				'tablet_default'  => ['size' => '', 'unit' => 'px'],
				'mobile_default'  => ['size' => '', 'unit' => 'px'],
				'selectors'       => [
					'{{WRAPPER}} .blogfoel-switch' => '{{l_d_fixed_position.VALUE}}: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'l_d_position' => 'fixed'
				],
			]
		);
		$this->add_responsive_control(
			'offset_bottom',
			[
				'label' => esc_html__( 'Offset Bottom', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'custom' ],
				'range' => [
					'px'  => ['min' => 0, 'max' => 1000],
					'em'  => ['min' => 0, 'max' => 100],
					'rem' => ['min' => 0, 'max' => 100],
					'vh' => ['min' => 0, 'max' => 200],
				],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'default' => ['size' => '350', 'unit' => 'px'],
				'tablet_default'  => ['size' => '', 'unit' => 'px'],
				'mobile_default'  => ['size' => '', 'unit' => 'px'],
				'selectors'       => [
					'{{WRAPPER}} .blogfoel-switch' => 'bottom: {{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'l_d_position' => 'fixed'
				],
			]
		);
		$this->add_control(
			'l_d_orientation',
			[
				'label'       => esc_html__( 'Orientation', 'blognews-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => '0',
				'options'     => [
					'0'      => esc_html__( 'Default', 'blognews-for-elementor' ),
					'90' => esc_html__( 'Vertical', 'blognews-for-elementor' ),
				],
				'selectors' => [
					'{{WRAPPER}} .blogfoel-switch' => 'transform: rotate({{VALUE}}deg)',
				],
				'condition' => [
					'l_d_position' => 'fixed'
				],
			]
		);
		$this->add_control(
			'save_cookies',
			[
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Save in Cookies', 'blognews-for-elementor' ),
				'label_on' => esc_html__( 'Yes', 'blognews-for-elementor' ),
				'label_off' => esc_html__( 'No', 'blognews-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		$this->add_control(
			'save_cookies_info',
			[
				'type' => \Elementor\Controls_Manager::ALERT,
				'alert_type' => 'info',
				'content' => esc_html__( 'If enabled, It will remember choice of user and load accordingly on next website visit.', 'blognews-for-elementor' ),
				'condition' => [
					'save_cookies' => 'yes'
				],
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'main_globel_settings',
			[
				'label' => __( 'Globel Color', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$slug = 'main_globel';

		$this->add_control(
			$slug.'_bg_dark_color',
			[
				'label' => esc_html__( 'Dark Background Color', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'body.blogfoel-dark-mode' => '--dark-bg-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			$slug.'_text_dark_color',
			[
				'label' => esc_html__( 'Dark Text Color', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'body.blogfoel-dark-mode' => '--dark-title-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			$slug.'_a_dark_color',
			[
				'label' => esc_html__( 'Dark Link Color', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'body.blogfoel-dark-mode' => '--dark-link-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			$slug.'_a_dark_hover_color',
			[
				'label' => esc_html__( 'Dark Link Hover Color', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'body.blogfoel-dark-mode' => '--dark-link-hover-color: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'l_n_d_w_settings',
			[
				'label' => __( 'Light & Dark Wrapper', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'layout_style' => 'layout-one'
				],
			]
		);

		$slug = 'l_n_d_w';
	
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
				'selector'  => '{{WRAPPER}} .blogfoel-switch .blogfoel-slider',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => $slug.'_border_type',
				'label'    => 'Border Type',
				'selector' => '{{WRAPPER}} .blogfoel-switch .blogfoel-slider',
			]
		);

		$this->add_responsive_control(
			$slug.'_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .blogfoel-switch .blogfoel-slider' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .blogfoel-switch' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'      => $slug.'_box_shadow',
				'label'    => 'Box Shadow',
				'selector' => '{{WRAPPER}} .blogfoel-switch .blogfoel-slider',
			]
		);
		$this->add_responsive_control(
			$slug.'_width',
			[
				'label' => esc_html__( 'Width', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'range' => [
					'px'  => ['min' => 0, 'max' => 100],
				],
				'devices'         => [ 'desktop', 'tablet', 'mobile' ],
				'default'         => ['size' => '', 'unit' => 'px'],
				'tablet_default'  => ['size' => '', 'unit' => 'px'],
				'mobile_default'  => ['size' => '', 'unit' => 'px'],
				'selectors'       => [
					'{{WRAPPER}} .blogfoel-switch' => 'width: {{SIZE}}{{UNIT}};',
					// '{{WRAPPER}} .blogfoel-switch input:checked + .blogfoel-slider::before' => 'transform: translateX(calc({{SIZE}}{{UNIT}} /2));',
				],
			]
		);
		
		$this->add_responsive_control(
			$slug.'_height',
			[
				'label' => esc_html__( 'Height', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'range' => [
					'px'  => ['min' => 0, 'max' => 100],
				],
				'devices'         => [ 'desktop', 'tablet', 'mobile' ],
				'default'         => ['size' => '', 'unit' => 'px'],
				'tablet_default'  => ['size' => '', 'unit' => 'px'],
				'mobile_default'  => ['size' => '', 'unit' => 'px'],
				'selectors'       => [
					'{{WRAPPER}} .blogfoel-switch' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'l_n_d_icon_settings',
			[
				'label' => __( 'Light & Dark Icon', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		
		$slug = 'l_n_d_icon';

		$this->start_controls_tabs( $slug.'_tabs' );

		$this->start_controls_tab(
			$slug.'_light_style',
			[
				'label' => __( 'Light', 'blognews-for-elementor' ),
			]
		);
 
		$this->add_control(
			$slug.'_color',
			[
				'label' => esc_html__( 'Color', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .blogfoel-switch .blogfoel-slider::before' => 'color: {{VALUE}};',
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
						'label'     => __( 'Background Color', 'blognews-for-elementor' ),
						'default' => 'classic',
					],
				],
				'selector'  => '{{WRAPPER}} .blogfoel-switch .blogfoel-slider::before', 
			]
		);
		$this->end_controls_tab();

		$this->start_controls_tab(
			$slug.'_style_dark',
			[
				'label' => __( 'Dark', 'blognews-for-elementor' ),
			]
		);

		$this->add_control(
			$slug.'_dark_color',
			[
				'label' => esc_html__( 'Color', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .blogfoel-switch input:checked + .blogfoel-slider::before' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => $slug.'_bg_dark_color',
				'types'          => [ 'classic', 'gradient' ],
				'exclude'        => [ 'image' ],
				'fields_options' => [
					'background' => [
						'label'     => __( 'Background Color', 'blognews-for-elementor' ),
						'default' => 'classic',
					],
				],
				'selector'  => '{{WRAPPER}} .blogfoel-switch input:checked + .blogfoel-slider::before', 
			]
		);

		$this->end_controls_tab();
		$this->end_controls_tabs();

		$this->add_responsive_control(
			$slug.'_icon_width',
			[
				'label'           => __( 'Width', 'blognews-for-elementor' ),
				'type'            => Controls_Manager::SLIDER,
				'size_units' 	  => [ 'px', 'custom' ],
				'range'           => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => ['size' => '19', 'unit' => 'px'],
				'tablet_default'  => ['size' => '19', 'unit' => 'px'],
				'mobile_default'  => ['size' => '19', 'unit' => 'px'],
				'selectors'       => [
					'{{WRAPPER}} .blogfoel-switch .blogfoel-slider::before' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .layout-one .blogfoel-switch input:checked + .blogfoel-slider::before' => 'left: calc(100% - ({{SIZE}}{{UNIT}} + 6px));',
					'{{WRAPPER}} .layout-two .blogfoel-switch' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		
		$this->add_responsive_control(
			$slug.'_icon_size',
			[
				'label'           => __( 'Size', 'blognews-for-elementor' ),
				'type'            => Controls_Manager::SLIDER,
				'size_units' 	  => [ 'px','custom' ],
				'range'           => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'separator' => 'before',
				'default_desktop' => ['size' => '', 'unit' => 'px'],
				'tablet_default'  => ['size' => '', 'unit' => 'px'],
				'mobile_default'  => ['size' => '', 'unit' => 'px'],
				'selectors'       => [
					'{{WRAPPER}} .blogfoel-switch .blogfoel-slider::before' => 'font-size: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .blogfoel-switch .blogfoel-slider::before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_section();
	}

	protected function render() {

		$settings = $this->get_settings_for_display();

		$save_cookies = $settings['save_cookies'] == 'yes' ? 'blogfoel_skin_mode' :'';
		$layout_style = $settings['layout_style'];
		?>
		
		<div class="blogfoel-widget-wrapper">
			<div class="blogfoel-light-dark-wrapper <?php echo esc_attr($layout_style)?>">
				<label class="blogfoel-switch" for="blogfoel-switch">
					<input type="checkbox" id="blogfoel-switch" class="blogfoel-light-mode" data-cookie="<?php echo esc_attr($save_cookies)?>">
					<span class="blogfoel-slider"></span>
				</label>
			</div>
		</div>
		<?php
	}
}