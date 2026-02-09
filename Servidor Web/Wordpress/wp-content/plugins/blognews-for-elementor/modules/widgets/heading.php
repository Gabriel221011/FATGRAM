<?php
namespace BlogFoel;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Base;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow; 
use Elementor\Scheme_Color;
use Elementor\Utils;
use Elementor\Repeater;
use BlogFoel\Notices;

class BLOGFOELHeading extends Widget_Base {

	private $heading_title = 'blog-post-heading-title';

    public function get_name() {
        return 'heading-widget';
    }
    public function get_title() {
        return 'BN Heading';
    }
    public function get_icon() {
        return 'blogfoel-widget-icon bnicon-heading';
    }
    public function get_categories() {
        return ['blogfoel-elementor'];
    }
    public function get_keywords() {
		return [
            'heading',
            'title',
            'text',
            'widget',
            'themeansar',
            'BLOGFOEL',
		];
	}
    protected function register_controls() {

        $this->start_controls_section(
            'heading_setting',
            [
                'label' => 'Heading Settings',
            ]
        ); 
        $this->add_control(
			'layout_style',
			[
				'label'       => esc_html__( 'Heading Style', 'blognews-for-elementor' ),
				'placeholder' => esc_html__( 'Choose Heading Style from Here', 'blognews-for-elementor' ),
				'type'        => 'blogfoel-select',
				'label_block' => false,
				'default'     => 'style-one',
				'options'     => [
					'style-one'      => esc_html__( 'Style 1', 'blognews-for-elementor' ),
					'style-two' => esc_html__( 'Style 2', 'blognews-for-elementor' ),
					'blogfoel-pro-style-three' => esc_html__( 'Style 3 (Pro)', 'blognews-for-elementor' ),
					'blogfoel-pro-style-four' => esc_html__( 'Style 4 (Pro)', 'blognews-for-elementor' ),
					'blogfoel-pro-style-five' => esc_html__( 'Style 5 (Pro)', 'blognews-for-elementor' ),
					'blogfoel-pro-style-six' => esc_html__( 'Style 6 (Pro)', 'blognews-for-elementor' ),
					'blogfoel-pro-style-seven' => esc_html__( 'Style 7 (Pro)', 'blognews-for-elementor' ),
					'blogfoel-pro-style-eight' => esc_html__( 'Style 8 (Pro)', 'blognews-for-elementor' ),
					'blogfoel-pro-style-nine' => esc_html__( 'Style 9 (Pro)', 'blognews-for-elementor' ),
					'blogfoel-pro-style-ten' => esc_html__( 'Style 10 (Pro)', 'blognews-for-elementor' ),
				],
			]
		);
        $this->add_control(
            'heading_content', 
            [
                'label' => esc_html__( 'Title', 'blognews-for-elementor' ),
                'type' => \Elementor\Controls_Manager::TEXTAREA,  
                'default' => __('Heading Example', 'blognews-for-elementor'),
            ]
        );
        $this->add_control(
            'heading_html_tag',
            [
                'label'       => esc_html__( 'Html Tag', 'blognews-for-elementor' ),
                'type'        => \Elementor\Controls_Manager::SELECT,
                'default'     => 'h4',
                'options'     => [
                    'h1' => esc_html__( 'H1', 'blognews-for-elementor' ),
                    'h2' => esc_html__( 'H2', 'blognews-for-elementor' ),
                    'h3' => esc_html__( 'H3', 'blognews-for-elementor' ),
                    'h4' => esc_html__( 'H4', 'blognews-for-elementor' ),
                    'h5' => esc_html__( 'H5', 'blognews-for-elementor' ),
                    'h6' => esc_html__( 'H6', 'blognews-for-elementor' ),
                    'div' => esc_html__( 'Div', 'blognews-for-elementor' ),
                    'span' => esc_html__( 'span', 'blognews-for-elementor' ),
                ],
            ]
        );
        $this->add_control(
			'enable_sub_heading',
			[
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Enable Sub Heading', 'blognews-for-elementor' ),
				'label_on' => esc_html__( 'Show', 'blognews-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'blognews-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);
        $this->add_control(
			'sub_heading',
			[
				'label' => esc_html__( 'Sub Heading', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'default' => esc_html__( 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam turpis nulla, vulputate vitae lobortis in.', 'blognews-for-elementor' ),
				'placeholder' => esc_html__( 'Type your Sub Heading here', 'blognews-for-elementor' ),
                'condition' => [
					'enable_sub_heading' => 'yes'
				],
			]
		);
        $this->add_control(
			'enable_icon_heading',
			[
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Enable Icon', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
				'label_on' => esc_html__( 'Show', 'blognews-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'blognews-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'no',
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);
        $this->add_responsive_control(
            'heading_align', 
            [
                'label' => __('Alignment', 'blognews-for-elementor') , 
                'type' => \Elementor\Controls_Manager::CHOOSE, 
                'options' => 
                [
                    'start' => [
                        'title' => __('Left', 'blognews-for-elementor') , 
                        'icon' => 'eicon-text-align-left', 
                    ], 
                    'center' => [
                        'title' => __('Center', 'blognews-for-elementor') ,
                        'icon' => 'eicon-text-align-center', 
                    ], 
                    'end' => [
                        'title' => __('Right', 'blognews-for-elementor') , 
                        'icon' => 'eicon-text-align-right', 
                    ], 
                ],
                'default' =>'',
                'selectors' => [
                    '{{WRAPPER}} .blogfoel-item-heading' => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .blogfoel-sub-heading' => 'text-align: {{VALUE}};',
                ], 
            ]
        );
        $this->end_controls_section();
		
		Notices::go_premium_notice_content($this, 'notice_one');

        // STYLE
        $this->start_controls_section(
            'heading_title_style',
            [
                'label' => __( 'Heading Style', 'blognews-for-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );

        $slug = 'heading_title';
        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name'      => $slug.'_background_color',
				'types'          => [ 'classic', 'gradient' ],
				'exclude'        => [ 'image' ],
				'fields_options' => [
					'background' => [
						'label' => esc_html__( 'Title Background Color', 'blognews-for-elementor' ),
						'default' => 'classic',
					],
				],
				'selector'  => '{{WRAPPER}} .blogfoel-item-heading .blogfoel-heading-title',
			]
		);
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => $slug.'_border_type',
				'label'    => 'Border Type',
				'selector' => '{{WRAPPER}} .blogfoel-item-heading .blogfoel-heading-title',
			]
		);
        $this->add_responsive_control(
			$slug.'_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .blogfoel-item-heading .blogfoel-heading-title' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .blogfoel-item-heading .blogfoel-heading-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'      => $slug.'_box_shadow',
                'label'    => 'Box Shadow',
                'selector' => '{{WRAPPER}} .blogfoel-item-heading .blogfoel-heading-title',
            ]
        );
		$this->add_control(
			$slug.'_seperator',
			[
				'label' => esc_html__( 'Seperator Border', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::HEADING,
                'condition' => [
					'layout_style' => ['style-two']
				],
				'separator' => 'before',
			]
		);
        $this->add_control(
			$slug.'_color',
			[
				'label' => esc_html__( 'Color', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'condition' => [
					'layout_style' => ['style-two']
				],
                'selectors' => [
                    '{{WRAPPER}} .blogfoel-item-heading.style-two' => 'border-bottom-color: {{VALUE}};',
                ],
            ]
        );
		$this->add_responsive_control(
			$slug.'_tickness',
			[
				'label' => esc_html__( 'Tickness', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px'  => ['min' => 0, 'max' => 50],
					'em'  => ['min' => 0, 'max' => 10],
					'rem' => ['min' => 0, 'max' => 10],
				],
				'devices' => [ 'desktop', 'tablet', 'mobile' ],
				'default' => [
					'size' => 2,
					'unit' => 'px',
				],
				'tablet_default' => [
					'size' => 2,
					'unit' => 'px',
				],
				'mobile_default' => [
					'size' => 2,
					'unit' => 'px',
				],
                'condition' => [
					'layout_style' => ['style-two']
				],
				'selectors'       => [
					'{{WRAPPER}} .blogfoel-item-heading.style-two' => 'border-bottom-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
        $this->end_controls_section();   
		// Color & Typo.
		$this->start_controls_section(
			'color_&_typo_style',
			[
				'label' => __( 'Color & Typography Style', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$slug = 'color_typography';
		$this->add_control(
			$slug.'_heading_color',
			[
				'label' => esc_html__( 'Heading Color', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .blogfoel-item-heading .blogfoel-heading-title' => 'color: {{VALUE}};',
                ],
            ]
        );
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
                'name'     => $slug.'_typography',
                'label'     => __( 'Heading Typography', 'blognews-for-elementor' ),
                'selector' => '{{WRAPPER}} .blogfoel-item-heading .blogfoel-heading-title',
            ]
        );
		$this->add_control(
			$slug.'_subtext_color',
			[
				'label' => esc_html__( 'Sub Heading Color', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'condition' => [
					'enable_sub_heading' => 'yes'
				],
                'selectors' => [
                    '{{WRAPPER}} .blogfoel-sub-heading p, {{WRAPPER}} .blogfoel-sub-heading h1, {{WRAPPER}} .blogfoel-sub-heading h2, {{WRAPPER}} .blogfoel-sub-heading h3, {{WRAPPER}} .blogfoel-sub-heading h4, {{WRAPPER}} .blogfoel-sub-heading h5, {{WRAPPER}} .blogfoel-sub-heading h6, {{WRAPPER}} .blogfoel-sub-heading pre' => 'color: {{VALUE}};',
                ],
            ]
        );
		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
                'name'     => $slug.'_subtext_typography',
                'label'     => __( 'Sub Heading Typography', 'blognews-for-elementor' ),
				'condition' => [
					'enable_sub_heading' => 'yes'
				],
                'selector' => '{{WRAPPER}} .blogfoel-sub-heading p, {{WRAPPER}} .blogfoel-sub-heading h1, {{WRAPPER}} .blogfoel-sub-heading h2, {{WRAPPER}} .blogfoel-sub-heading h3, {{WRAPPER}} .blogfoel-sub-heading h4, {{WRAPPER}} .blogfoel-sub-heading h5, {{WRAPPER}} .blogfoel-sub-heading h6, {{WRAPPER}} .blogfoel-sub-heading pre',
            ]
        );
		$this->add_responsive_control(
			$slug.'_margin',
			[
				'label' => esc_html__( 'Sub Heading Margin', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'condition' => [
					'enable_sub_heading' => 'yes'
				],
                'selectors' => [
                    '{{WRAPPER}} .blogfoel-sub-heading' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );
		$this->end_controls_section(); 
		Notices::go_premium_notice_style($this, 'notice_two');
    }
    protected function render() {
        $settings = $this->get_settings_for_display();
        $head_tag = $settings['heading_html_tag'];
        $layout_style = $settings['layout_style'];
        $head_text = $settings['heading_content'];
        ?>
        <div class="blogfoel-widget-wrapper">
            <<?php echo esc_html($head_tag) ?> class="blogfoel-item-heading <?php echo esc_attr($layout_style) ?> <?php echo esc_attr($this->heading_title) ?> ">
                <span class="blogfoel-heading-inner">
                    <span class="blogfoel-heading-title">
                        <?php echo esc_html($head_text); ?>
                    </span>
                </span>
            </<?php echo esc_html($head_tag) ?>> 
            <?php if ('yes' === ($settings['enable_sub_heading'])) { ?>
                <div class="blogfoel-sub-heading">
                    <?php echo wp_kses_post($settings['sub_heading']) ?>
                </div>
            <?php } ?>
        </div>
        <?php
    }
}