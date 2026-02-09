<?php
namespace BlogFoel;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Base;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow; 
use Elementor\Scheme_Color;
use Elementor\Utils;
use BlogFoel\Notices;
use Elementor\Group_Control_Background;

class BLOGFOELCreativeButton extends Widget_Base {

	private $blog_button = 'blogfoel-more-link';

    public function get_name() {
        return 'blognews-creative-button';
    }
    public function get_title() {
        return 'BN Creative Button';
    }
    public function get_icon() {
        return 'blogfoel-widget-icon bnicon-button';
    }
    public function get_categories() {
        return ['blogfoel-elementor'];
    }
    public function get_keywords() {
		return [ 'BLOGFOEL', 'themeansar', 'button','more','link'];
	}
    protected function register_controls() {

        $this->start_controls_section('button_section_content', ['label' => 'Settings', ]);

        $this->add_control(
			'button_title', [
				'label' => __( 'Button Title', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Click Here' , 'blognews-for-elementor' ),
				'label_block' => true,
			]
		);
		$this->add_control(
			'button_link',
			[
				'label' => __( 'Link', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => __( 'https://your-link.com', 'blognews-for-elementor' ),
				'show_external' => true,
				'default' => [
					'url' => '',
					'is_external' => true,
					'nofollow' => true,
				],
			]
		);
		$this->add_control(
			'button_animation_type',
			[
				'label'     => __( 'Animation', 'blognews-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'custom'   => [
						'title' => __( 'Custom', 'blognews-for-elementor' ),
						'icon'  => 'eicon-click',
					],
					'elementor'  => [
						'title' => __( 'Elementor (Pro)', 'blognews-for-elementor' ),
						'icon'  => 'eicon-elementor',
					],
				],
				'default'   => 'custom',
                'label_block'   =>  true,
			]
		);
		$this->add_control(
            'button_custom_animation',
            [
                'label'       => esc_html__( 'Animation', 'blognews-for-elementor' ),
                'type'        => 'blogfoel-select',
				'label_block' => false,
                'default'     => 'winona',
                'options'     => [
					'none'       => esc_html__( 'None', 'blognews-for-elementor' ),
					'winona'     => esc_html__( 'Winona', 'blognews-for-elementor' ),
					'ujarak'     => esc_html__( 'Ujarak', 'blognews-for-elementor' ),
					'diagonal'   => esc_html__( 'Diagonal Open', 'blognews-for-elementor' ),
					'blogfoel-pro-swipe'      => esc_html__( 'Swipe (Pro)', 'blognews-for-elementor' ),
					'blogfoel-pro-wayra'      => esc_html__( 'Wayra (Pro)', 'blognews-for-elementor' ),
					'blogfoel-pro-tamaya'     => esc_html__( 'Tamaya (Pro)', 'blognews-for-elementor' ),
					'blogfoel-pro-shine'      => esc_html__( 'Shine (Pro)', 'blognews-for-elementor' ),
					'blogfoel-pro-alternate'  => esc_html__( 'Alternate (Pro)', 'blognews-for-elementor' ),
					'blogfoel-pro-collision'  => esc_html__( 'Collision (Pro)', 'blognews-for-elementor' ),
					'blogfoel-pro-rayen'      => esc_html__( 'Rayen (Pro)', 'blognews-for-elementor' ),
					'blogfoel-pro-aylen'      => esc_html__( 'Aylen (Pro)', 'blognews-for-elementor' ),
					'blogfoel-pro-saqui'      => esc_html__( 'Saqui (Pro)', 'blognews-for-elementor' ),
                ],
				'condition' => [
					'button_animation_type' => 'custom'
				],
            ]
        );

		$this->add_control(
            'button_elementor_animation',
            [
                'label'       => esc_html__( 'Animation', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
                'type' => 'blogfoel-select',
				'label_block' => false,
                'default'     => 'none',
				'options'     => [
					'none'  => esc_html__( 'None', 'blognews-for-elementor' ),
				],
				'condition' => [
					'button_animation_type!' => 'custom'
				],
				'classes' => 'blogfoel-pro-popup-notice',
            ]
        );

		$this->add_control(
			'btn_icon',
			[
				'label' => esc_html__( 'Select Icon', 'blognews-for-elementor' ),
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
                'default' => [
					'value' => 'fas fa-arrow-right',
					'library' => 'solid',
				],
				'label_block' => false,
			]
		);
		$this->add_responsive_control(
			'btn_icon_position',
			[
				'label'     => __( 'Icon Position', 'blognews-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'right'   => [
						'title' => __( 'Left', 'blognews-for-elementor' ),
						'icon'  => 'eicon-h-align-left',
					],
					'left'  => [
						'title' => __( 'Right', 'blognews-for-elementor' ),
						'icon'  => 'eicon-h-align-right',
					],
				],
				'default'   => 'left',
			]
		);

		$this->add_responsive_control(
			'icon_gap',
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
				'default' => ['size' => '5', 'unit' => 'px'],
				'tablet_default'  => ['size' => '5', 'unit' => 'px'],
				'mobile_default'  => ['size' => '5', 'unit' => 'px'],
				'selectors'       => [
					'{{WRAPPER}} .'.$this->blog_button.' .blogfoel-btn-icon' => 'margin-{{btn_icon_position.VALUE}}: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();

		Notices::go_premium_notice_content($this, 'notice_one');

        // STYLE
       	$this->start_controls_section(
			'button_style',
			[
				'label' => __( 'Button', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$slug = 'blog_button';

		$this->add_responsive_control(
			$slug.'_position',
			[
				'label'     => __( 'Position', 'blognews-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'    => [
						'title' => esc_html__( 'Left', 'blognews-for-elementor' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'blognews-for-elementor' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'blognews-for-elementor' ),
						'icon' => 'eicon-h-align-right',
					],
					'justify' => [
						'title' => esc_html__( 'Stretch', 'blognews-for-elementor' ),
						'icon' => 'eicon-h-align-stretch',
					],
				],
				'default'   => '',
				'prefix_class' => 'blogfoel-align-',
				'selectors' => [
					'{{WRAPPER}} .blogfoel-creative-button' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->add_responsive_control(
			$slug.'_align',
			[
				'label'     => __( 'Alignment', 'blognews-for-elementor' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
					'left'   => [
						'title' => __( 'Left', 'blognews-for-elementor' ),
						'icon'  => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'blognews-for-elementor' ),
						'icon'  => 'eicon-text-align-center',
					],
					'right'  => [
						'title' => __( 'Right', 'blognews-for-elementor' ),
						'icon'  => 'eicon-text-align-right',
					],
				],
				'default'   => '',
				'selectors' => [
					'{{WRAPPER}} .'.$this->blog_button => 'justify-content: {{VALUE}}',
				],
				'condition' => [
					$slug.'_position' => 'justify',
				],
			]
		);
		
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name'     =>  $slug.'_typography',
                'label'     => __( 'Typography', 'blognews-for-elementor' ),
				'selector' => '{{WRAPPER}} .'.$this->blog_button,
			]
		);

		$this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name'     => $slug.'_text_shadow',
                'label'    => esc_html__( 'Text Shadow', 'blognews-for-elementor' ),
                'selector' => '{{WRAPPER}} .'.$this->blog_button,
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
					'{{WRAPPER}} .'.$this->blog_button => 'color: {{VALUE}};',
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
						'label' => esc_html__( 'Background Color', 'blognews-for-elementor' ),
						'default' => 'classic',
					],
				],
				'selector'  => '{{WRAPPER}} .blogfoel-more-link',
			]
		);
		
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => $slug.'_border_type',
				'label'    => 'Border Type',
				'selector' => '{{WRAPPER}} .'.$this->blog_button,
			]
		);

		$this->add_responsive_control(
			$slug.'_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .'.$this->blog_button => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
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
					'{{WRAPPER}} .'.$this->blog_button.':hover, {{WRAPPER}} .'.$this->blog_button.'.winona:after' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_group_control(
			Group_Control_Background::get_type(),
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
				'selector'  => '{{WRAPPER}} .blogfoel-more-link:where( .none, .winona):hover, {{WRAPPER}} .blogfoel-more-link.ujarak::before, {{WRAPPER}} .blogfoel-more-link.diagonal span::before',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => $slug.'_border_hover_type',
				'label'    => 'Border Type',
				'selector' => '{{WRAPPER}} .'.$this->blog_button.':hover',
			]
		);

		$this->add_responsive_control(
			$slug.'_border_hover_radius',
			[
				'label' => esc_html__( 'Border Radius', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .'.$this->blog_button.':hover, {{WRAPPER}} .blogfoel-more-link.ujarak::before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
		$this->add_responsive_control(
			$slug.'_padding',
			[
				'label' => esc_html__( 'Padding', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .'.$this->blog_button.':after' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .'.$this->blog_button.':not(.tamaya) span' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					'{{WRAPPER}} .'.$this->blog_button.'.none' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .'.$this->blog_button => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'      => $slug.'_shadow',
				'label'    => 'Box Shadow',
				'selector' => '{{WRAPPER}} .'.$this->blog_button,
			]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'btn_icon_style',
			[
				'label' => __( 'Button Icon', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,				
				'condition' => [
					'btn_icon[value]!' => ''
				],
			]
		);
		$slug = 'blog_button_icon';

		$this->start_controls_tabs( $slug.'_style_tabs' );

		$this->start_controls_tab(
			$slug.'_normal_style',
			[
				'label' => __( 'Normal', 'blognews-for-elementor' ),
			]
		);
		$this->add_control(
			$slug.'_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'.$this->blog_button.' small' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			$slug.'_color',
			[
				'label' => esc_html__( 'Color', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'.$this->blog_button.' i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .'.$this->blog_button.' svg' => 'fill: {{VALUE}};',
				],
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
			$slug.'_bg_color_hover',
			[
				'label' => esc_html__( 'Background Color', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'.$this->blog_button.':hover small' => 'background-color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			$slug.'_color_hover',
			[
				'label' => esc_html__( 'Color', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'.$this->blog_button.':hover .blogfoel-btn-icon' => 'color: {{VALUE}};',
					'{{WRAPPER}} .'.$this->blog_button.':hover svg' => 'fill: {{VALUE}};',
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
		$this->add_responsive_control(
			$slug.'_size',
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
					'{{WRAPPER}} .'.$this->blog_button.' .blogfoel-btn-icon' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .'.$this->blog_button.' svg' => 'width: {{SIZE}}{{UNIT}};',
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
					'{{WRAPPER}} .'.$this->blog_button.' small' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
					'{{WRAPPER}} .'.$this->blog_button.' small' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
				],
			]
		);
		$this->end_controls_section();
		Notices::go_premium_notice_style($this, 'notice_two');
    }
    protected function render() {
        $settings = $this->get_settings_for_display();
		
		$btn_title = $settings['button_title'];
		$btn_link = $settings['button_link']['url'];
		$target = $settings['button_link']['is_external'] ? ' target=_blank' : '';
		$nofollow = $settings['button_link']['nofollow'] ? ' rel=nofollow' : '';

		$btn_animation_type = $settings['button_animation_type'];
		$custom_animation     = $settings['button_custom_animation'];
		$btn_animation      = $custom_animation;

        ?>
        <div class="blogfoel-widget-wrapper">
            <div class="blogfoel-creative-button">
            <a href="<?php echo esc_url($btn_link) ?>"<?php echo esc_attr($target) . esc_attr($nofollow); ?> class="blogfoel-more-link <?php echo esc_attr($btn_animation)?>" data-text="<?php echo esc_attr($btn_title)?>">
                <span><?php
				if ($settings['btn_icon_position'] == 'right'){
					echo '<small>';
					\Elementor\Icons_Manager::render_icon( $settings['btn_icon'], [ 'aria-hidden' => 'true', 'class' => 'blogfoel-btn-icon' ] );
					echo '</small>';
				}
				echo esc_html($btn_title);
				if ($settings['btn_icon_position'] == 'left'){
					echo '<small>';
					\Elementor\Icons_Manager::render_icon( $settings['btn_icon'], [ 'aria-hidden' => 'true', 'class' => 'blogfoel-btn-icon' ] );
					echo '</small>';
				} ?>
				</span>
            </a>
        </div>
        </div>
        <?php
    }
}
