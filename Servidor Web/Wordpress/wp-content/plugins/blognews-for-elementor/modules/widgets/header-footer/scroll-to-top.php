<?php 
namespace BlogFoel;

use BlogFoel\Notices;
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

class BLOGFOELScrollToTop extends \Elementor\Widget_Base {

	private $stt_card_class = 'blogfoel-upscr'; 
	private $stt_icon = 'icon';

	public function get_name() {
		return 'blognews-scroll-to-top';
	}

	public function get_title() {
		return __( 'Scroll To Top', 'blognews-for-elementor' );
	}

	public function get_categories() {
		return [ 'blogfoel-hf-elementor' ];
	}

	public function get_icon() {
		return 'blogfoel-widget-icon bnicon-scroll-to-top';
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
			 
			'stt',
			'btt',
			'back to top', 
			'scroll', 
			'scroll to top', 
			'back', 
			'top' 
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
			'show_icon',
			[
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Button Icon', 'blognews-for-elementor' ),
				'label_on' => esc_html__( 'Show', 'blognews-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'blognews-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		
		$this->add_control(
			'stt_icon',
			[
				'label' => __( 'Icon', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::ICONS,
				'default' => [
					'value' => 'fas fa-angle-double-up',
					'library' => 'solid',
				],
				'condition' => [
					'show_icon' => 'yes',
				],
			]
		);

		$this->end_controls_section();

		Notices::go_premium_notice_content($this, 'notice_one');

		$this->start_controls_section(
			'stt_settings',
			[
				'label' => __( 'Scroll To Top Settings', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_control(
			'stt_position',
			[
				'label' => esc_html__( 'Button Position', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'options'     => [
					'fixed' => 'Fixed',
					'inherit' => 'Inherit',
				],
				'default' => 'fixed',
			]
		);

		$this->add_responsive_control(
			'stt_alignment',
			[
				'label'     => __( 'Position', 'blognews-for-elementor' ),
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
				'default'   => 'right',
				'condition' => [
					'stt_position' => 'fixed',
				],
			]
		);

		$this->add_responsive_control(
			'stt_align',
			[
				'label'     => __( 'Alignment', 'blognews-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'start'   => [
						'title' => __( 'Left', 'blognews-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'blognews-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					],
					'end'  => [
						'title' => __( 'Right', 'blognews-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .blogfoel-scroll-to-top' => 'text-align: {{VALUE}}',
				],
				'condition' => [
					'stt_position!' => 'fixed',
				],
			]
		);

		$this->add_responsive_control(
			'left_position',
			[
				'label' => esc_html__( 'Left', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px'  => ['min' => 0, 'max' => 120],
					'em'  => ['min' => 0, 'max' => 100],
					'rem' => ['min' => 0, 'max' => 100],
				],
				'devices'         => [ 'desktop', 'tablet', 'mobile' ],
				'default'         => ['size' => '', 'unit' => 'px'],
				'tablet_default'  => ['size' => '', 'unit' => 'px'],
				'mobile_default'  => ['size' => '', 'unit' => 'px'],
				'selectors'       => [
					'{{WRAPPER}} .'.$this->stt_card_class => 'left :{{SIZE}}{{UNIT}};',
				], 
				'condition' => [
					'stt_alignment' => 'left',
					'stt_position' => 'fixed',
				],
			]
		);

		$this->add_responsive_control(
			'right_position',
			[
				'label' => esc_html__( 'Right', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px'  => ['min' => 0, 'max' => 120],
					'em'  => ['min' => 0, 'max' => 100],
					'rem' => ['min' => 0, 'max' => 100],
				],
				'devices'         => [ 'desktop', 'tablet', 'mobile' ],
				'default'         => ['size' => '', 'unit' => 'px'],
				'tablet_default'  => ['size' => '', 'unit' => 'px'],
				'mobile_default'  => ['size' => '', 'unit' => 'px'],
				'selectors'       => [
					'{{WRAPPER}} .'.$this->stt_card_class => 'right :{{SIZE}}{{UNIT}};',
				], 
				'condition' => [
					'stt_alignment' => 'right',
					'stt_position' => 'fixed',
				],
			]
		);

		$this->add_responsive_control(
			'bottom_position',
			[
				'label' => esc_html__( 'Bottom', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px'  => ['min' => 0, 'max' => 120],
					'em'  => ['min' => 0, 'max' => 100],
					'rem' => ['min' => 0, 'max' => 100],
				],
				'devices'         => [ 'desktop', 'tablet', 'mobile' ],
				'default'         => ['size' => '', 'unit' => 'px'],
				'tablet_default'  => ['size' => '', 'unit' => 'px'],
				'mobile_default'  => ['size' => '', 'unit' => 'px'],
				'selectors'       => [
					'{{WRAPPER}} .'.$this->stt_card_class => 'bottom :{{SIZE}}{{UNIT}};',
				],
				'condition' => [
					'stt_position' => 'fixed',
				],
			]
		);
	
		$this->add_control(
			'position_z_index',
			[
				'label'           => __( 'Z-Index', 'blognews-for-elementor'),
				'type'            => Controls_Manager::SLIDER, 
				'range'           => [
					'' => [
						'min' => 0,
						'max' => 10000,
					],
				],
				'selectors'       => [
					'{{WRAPPER}} .'.$this->stt_card_class => 'z-index: {{SIZE}};',
				],
			]
		);
		
		$this->add_control(
			'card_overlay_opacity',
			[
				'label' => esc_html__( 'Opacity', 'blognews-for-elementor' ),
				'type' => Controls_Manager::SLIDER, 
				'range' => [
					'px' => [
						'max' => 1,
						'step' => 0.01,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .'.$this->stt_card_class => 'opacity: {{SIZE}};',
				]
			]
		);

		$this->end_controls_section();
		$this->start_controls_section(
			'stt_box_settings',
			[
				'label' => __( 'Box Settings', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		
		$slug = 'stt_box';

		$this->start_controls_tabs( $slug.'_tabs' );

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
						'label'     => __( 'Background Color', 'blognews-for-elementor' ),
						'default' => 'classic',
					],
				],
				'selector'  => '{{WRAPPER}} .'.$this->stt_card_class, 
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => $slug.'_border_type',
				'label'    => 'Border Type',
				'selector' => '{{WRAPPER}}  .'.$this->stt_card_class,
			]
		);
		$this->add_responsive_control(
			$slug.'_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .'.$this->stt_card_class => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .'.$this->stt_card_class => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			$slug.'_margin',
			[
				'label' => esc_html__( 'Margin', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .'.$this->stt_card_class => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'      => $slug.'_box_shadow',
				'label'    => 'Box Shadow',
				'selector' => '{{WRAPPER}} .'.$this->stt_card_class.'',
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			$slug.'_style_hover',
			[
				'label' => __( 'Hover', 'blognews-for-elementor' ),
			]
		);

		$this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => $slug.'_bg_hover_color',
				'types'          => [ 'classic', 'gradient' ],
				'exclude'        => [ 'image' ],
				'fields_options' => [
					'background' => [
						'label'     => __( 'Background Color', 'blognews-for-elementor' ),
						'default' => 'classic',
					],
				],
				'selector'  => '{{WRAPPER}} .'.$this->stt_card_class.':hover', 
			]
		);


		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => $slug.'_hover_border_type',
				'label'    => 'Border Type',
				'selector' => '{{WRAPPER}}  .'.$this->stt_card_class.':hover',
			]
		);
		$this->add_responsive_control(
			$slug.'_hover_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .'.$this->stt_card_class.':hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			$slug.'_hover_padding',
			[
				'label' => esc_html__( 'Padding', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .'.$this->stt_card_class.':hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_responsive_control(
			$slug.'_hover_margin',
			[
				'label' => esc_html__( 'Margin', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .'.$this->stt_card_class.':hover' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'      => $slug.'_hover_box_shadow',
				'label'    => 'Box Shadow',
				'selector' => '{{WRAPPER}} .'.$this->stt_card_class.':hover',
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();

		$this->start_controls_section(
			'icon_settings',
			[
				'label' => __( 'Icon Settings', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE, 
				'condition' => [
					'show_icon' => 'yes',
				],
			]
		);
		$this->add_control(
			'icon_position',
			[
				'label' => __('Position', 'blognews-for-elementor') , 
				'type' => Controls_Manager::CHOOSE, 
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
					'column-reverse' => [
						'title' => __('Bottom', 'blognews-for-elementor') , 
						'icon' => 'eicon-v-align-bottom', 
					], 
				], 
				'selectors' => [
					'{{WRAPPER}} .'.$this->stt_card_class => 'flex-direction: {{VALUE}};',
				],
			]
		);

        $this->start_controls_tabs( 'icon_tabs' );

		$this->start_controls_tab(
			'icon_normal_style',
			[
				'label' => __( 'Normal', 'blognews-for-elementor' ),
			]
		); 
		$this->add_control(
			'icon_color',
			[
				'label'     => __( 'Icon Color', 'blognews-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} i.'.$this->stt_icon => 'color: {{VALUE}}',
					'{{WRAPPER}} svg.'.$this->stt_icon => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label'           => __( 'Icon Size', 'blognews-for-elementor' ),
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
					'{{WRAPPER}} i.'.$this->stt_icon => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} svg.'.$this->stt_icon => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_margin',
			[
				'label' => esc_html__( 'Margin', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .'.$this->stt_icon => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'icon_style_hover',
			[
				'label' => __( 'Hover', 'blognews-for-elementor' ),

			]
		);

		$this->add_control(
			'icon_color_hover',
			[
				'label'     => __( 'Icon Color', 'blognews-for-elementor' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'.$this->stt_card_class.':hover i' => 'color: {{VALUE}}',
					'{{WRAPPER}} .'.$this->stt_card_class.':hover svg' => 'fill: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size_hover',
			[
				'label'           => __( 'Icon Size', 'blognews-for-elementor' ),
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
					'{{WRAPPER}} .'.$this->stt_card_class.':hover i' =>  'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .'.$this->stt_card_class.':hover svg' => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_hover_margin',
			[
				'label' => esc_html__( 'Margin', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .'.$this->stt_card_class.':hover i' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->end_controls_tab();
		$this->end_controls_tabs();
		$this->end_controls_section();
		
		Notices::go_premium_notice_style($this, 'notice_two');
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		$show_icon = $settings['show_icon'];
		$icon = $settings['stt_icon'];
		$alignment = $settings['stt_alignment'];
		$position = $settings['stt_position'];
		?>

		<div class="blogfoel-widget-wrapper">
			<div class="blogfoel-scroll-to-top <?php echo esc_attr($alignment)?>">
				<a href="#" class="blogfoel-upscr <?php echo esc_attr( $position) ?>">
					<?php if ( $show_icon === 'yes' ) { 
					\Elementor\Icons_Manager::render_icon( $icon , [ 'aria-hidden' => 'true' ,'class' =>'icon'] ); 
					} ?>
				</a>
			</div>
		</div>
		<?php
	}
}