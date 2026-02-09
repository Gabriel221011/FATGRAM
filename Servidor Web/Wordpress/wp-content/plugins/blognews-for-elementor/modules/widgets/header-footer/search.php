<?php
namespace BlogFoel;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Border;
use BlogFoel\Notices;

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

class BLOGFOELSearch extends Widget_Base {

    private $search_input = 'blognews-search-input';
    private $search_submit = 'blognews-search-submit-button';
    private $search_reset = 'blognews-search-reset';
    private $search_btn = 'blognews-search-button';

    public function __construct( $data = array(), $args = null ) {
        parent::__construct( $data, $args );
    }

    public function get_name() {
        return 'blognews-search';
    }

    public function get_title() {
        return __( 'BN Advanced Search', 'blognews-for-elementor' );
    }

    public function get_icon() {
        return 'blogfoel-widget-icon bnicon-search';
    }

    public function get_categories() {
        return array( 'blogfoel-hf-elementor' );
    }

    public function get_style_depends() {
        return [
            'jquery-auto-complete-css',
            'blogfoel-styles-css',
        ];
    }

    public function get_script_depends() {
        return ['blognews-search-js'];
    }

    public function get_keywords() {
        return [
            'search',
            'find',
            'widget',
            'themeansar',
            'BLOGFOEL',
            'header footer',
        ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'section_general_fields',
            [
                'label' => __( 'Search Box', 'blognews-for-elementor' ),
            ]
        );

        $this->add_control(
            'search_layout',
            [
                'label'        => __( 'Layout', 'blognews-for-elementor' ),
				'type'        => 'blogfoel-select',
				'label_block' => false,
                'default'      => 'icon',
                'options'      => [
                    'icon'         => __( 'Icon', 'blognews-for-elementor' ),
                    'icon_text'    => __( 'Input Box With Icon', 'blognews-for-elementor' ),
                    'blogfoel-pro-text_title'   => __( 'Input Box With Text (Pro)', 'blognews-for-elementor' ),
                ],
                'prefix_class' => 'blogfoel-search-layout-',
                'render_type'  => 'template',
            ]
        );

        $this->add_control(
            'placeholder',
            [
                'label'     => __( 'Placeholder', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::TEXT,
                'placeholder'   => __( 'Type Here & Search...', 'blognews-for-elementor' ),
                'default'   => __( 'Type Here & Search...', 'blognews-for-elementor' ),
            ]
        );

        $this->add_responsive_control(
            'search_width',
            [
                'label' => esc_html__( 'Width', 'blognews-for-elementor' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
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
               
                'selectors'       => [
                    '{{WRAPPER}} .blogfoel_search_container' => 'width: {{SIZE}}{{UNIT}};',
                ],
            ],
        );

        $this->add_responsive_control(
            'search_height',
            [
                'label'           => __( 'Height', 'blognews-for-elementor' ),
                'type'            => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem','vh', 'custom' ],
				'range' => [
					'px'  => ['min' => 0, 'max' => 100],
					'em'  => ['min' => 0, 'max' => 100],
					'rem' => ['min' => 0, 'max' => 100],
					'vh'  => ['min' => 0, 'max' => 200],
				],
				'default_desktop' => ['size' => '', 'unit' => 'px'],
				'tablet_default'  => ['size' => '', 'unit' => 'px'],
				'mobile_default'  => ['size' => '', 'unit' => 'px'],
                'selectors'       => [
                    '{{WRAPPER}} .blogfoel_search_container' => 'height: {{SIZE}}{{UNIT}};',
                ],
            ]
        );

		$this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name'      => 'search_box_shadow',
				'label'    => 'Box Shadow',
				'selector' => '{{WRAPPER}} .blogfoel_search_container',
			]
		);

        $this->add_responsive_control(
            'search_align',
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
                'prefix_class' => 'blogfoel-search-align-',
				'selectors' => [
					'{{WRAPPER}} .blogfoel-search-wrapper' => 'text-align: {{VALUE}}',
				],
            ]
        );

		$this->end_controls_section();

		Notices::go_premium_notice_content($this, 'notice_one');

		$this->start_controls_section(
			'search_icon_style',
			[
				'label' => __( 'Search Icon', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
				'condition' => [
					'search_layout' => 'icon'
				],
			]
		);

		$slug = 'search_icon';
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
					'{{WRAPPER}} .'.$this->search_btn => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			$slug.'_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'.$this->search_btn => 'background-color: {{VALUE}};',
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
					'{{WRAPPER}} .'.$this->search_btn.':hover' => 'color: {{VALUE}};',
					'{{WRAPPER}} .'.$this->search_btn.':focus' => 'color: {{VALUE}};',
				],
			]
		);
		$this->add_control(
			$slug.'_bg_color_hover',
			[
				'label' => esc_html__( 'Background Color', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'.$this->search_btn.':hover' => 'background-color: {{VALUE}};',
					'{{WRAPPER}} .'.$this->search_btn.':focus' => 'background-color: {{VALUE}};',
				],
			]
		);
        
        $this->add_control(
            $slug.'_border_color_focus',
            [
                'label'     => __( 'Border Color', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .'.$this->search_btn.':focus' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .'.$this->search_btn.':hover' => 'border-color: {{VALUE}}',
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
            $slug.'_width',
            [
                'label' => esc_html__( 'Width', 'blognews-for-elementor' ),
                'type' => \Elementor\Controls_Manager::SLIDER,
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
                    '{{WRAPPER}} .'.$this->search_btn => 'width: {{SIZE}}{{UNIT}};height: {{SIZE}}{{UNIT}};',
                ],
            ],
        );

        $this->add_responsive_control(
            $slug.'_size',
            [
                'label'           => __( 'Size', 'blognews-for-elementor' ),
                'type'            => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem','vh', 'custom' ],
				'range' => [
					'px'  => ['min' => 0, 'max' => 100],
					'em'  => ['min' => 0, 'max' => 100],
					'rem' => ['min' => 0, 'max' => 100],
				],
				'default_desktop' => ['size' => '', 'unit' => 'px'],
				'tablet_default'  => ['size' => '', 'unit' => 'px'],
				'mobile_default'  => ['size' => '', 'unit' => 'px'],
                'selectors'       => [
                    '{{WRAPPER}} .'.$this->search_btn => 'font-size: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        
        $this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => $slug.'_border_style',
				'label'    => 'Border Type',
				'selector' => '{{WRAPPER}} .'.$this->search_btn,
			]
		);

        $this->add_responsive_control(
            $slug.'_border_radius',
            [
				'label' => esc_html__( 'Border Radius', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .'.$this->search_btn => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        $this->end_controls_section();

        $this->start_controls_section(
            'section_input_style',
            [
                'label' => __( 'Input', 'blognews-for-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_control(
            'input_text_color',
            [
                'label'     => __( 'Color', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .'.$this->search_input => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'input_placeholder_color',
            [
                'label'     => __( 'Placeholder Color', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .'.$this->search_input.'::placeholder' => 'color: {{VALUE}}',
                ], 
            ]
        );

        $this->add_control(
            'input_background_color',
            [
                'label'     => __( 'Background Color', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => [
                    '{{WRAPPER}} .'.$this->search_input => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            [
                'name'     => 'input_typography',
                'selector' => '{{WRAPPER}} .'.$this->search_input,
            ]
        );
        $this->add_group_control(
			\Elementor\Group_Control_Border::get_type(),
			[
				'name'     => 'border_style',
				'label'    => 'Border Type',
				'selector' => '{{WRAPPER}} .'.$this->search_input,
			]
		);
      
        $this->add_control(
            'input_border_color_focus',
            [
                'label'     => __( 'Border Hover Color', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .'.$this->search_input.':focus' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .'.$this->search_input.':hover' => 'border-color: {{VALUE}}',
                ],
            ]
        );

        $this->add_responsive_control(
            'border_radius',
            [
				'label' => esc_html__( 'Border Radius', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .'.$this->search_input => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        $this->end_controls_section();

        $this->start_controls_section(
            'section_button_style',
            [
                'label'     => __( 'Button', 'blognews-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->start_controls_tabs( 'tabs_button_colors' );

        $this->start_controls_tab(
            'tab_button_normal',
            [
                'label' => __( 'Normal', 'blognews-for-elementor' ),
            ]
        );

        $this->add_control(
            'button_icon_color',
            [
                'label'     => __( 'Color', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .'.$this->search_submit => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'button_icon_bg_color',
            [
                'label'     => __( 'Background Color', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .'.$this->search_submit => 'background-color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_button_hover',
            [
                'label' => __( 'Hover', 'blognews-for-elementor' ),
            ]
        );

        $this->add_control(
            'button_text_color_hover',
            [
                'label'     => __( ' Color', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .'.$this->search_submit.':hover' => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->add_control(
            'button_background_color_hover',
            [
                'label'     => __( 'Background Color', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .'.$this->search_submit.':hover' => 'background-color: {{VALUE}}',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_control(
			'button_hr',
			[
				'type' => \Elementor\Controls_Manager::DIVIDER,
			]
		);
        $this->add_responsive_control(
            'icon_size',
            [
                'label'              => __( 'Icon Size', 'blognews-for-elementor' ),
                'type'               => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'range' => [
					'px'  => ['min' => 0, 'max' => 100],
				],
				'default_desktop' => ['size' => '', 'unit' => 'px'],
				'tablet_default'  => ['size' => '', 'unit' => 'px'],
				'mobile_default'  => ['size' => '', 'unit' => 'px'],
                'selectors'          => [
                    '{{WRAPPER}} .'.$this->search_submit => 'font-size: {{SIZE}}{{UNIT}}',
                ],
                'render_type'        => 'template',
                'frontend_available' => true,
            ]
        );

        $this->add_responsive_control(
            'button_width',
            [
                'label'              => __( 'Width', 'blognews-for-elementor' ),
                'type'               => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'range' => [
					'px'  => ['min' => 0, 'max' => 500],
				],
				'default_desktop' => ['size' => '', 'unit' => 'px'],
				'tablet_default'  => ['size' => '', 'unit' => 'px'],
				'mobile_default'  => ['size' => '', 'unit' => 'px'],
                'selectors'          => [
                    '{{WRAPPER}} .'.$this->search_submit => 'width: {{SIZE}}{{UNIT}}',
                ],
                'render_type'        => 'template',
                'frontend_available' => true,
            ]
        );
        $this->add_responsive_control(
            'button_border_radius',
            array(
                'label'      => __( 'Border Radius', 'blognews-for-elementor' ),
                'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'selectors'  => [
                    '{{WRAPPER}} .'.$this->search_submit => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            )
        );
        $this->end_controls_section();

        $this->start_controls_section(
            'section_close_icon',
            [
                'label'     => __( 'Reset Icon', 'blognews-for-elementor' ),
                'tab'       => Controls_Manager::TAB_STYLE,
            ]
        );

        $this->add_responsive_control(
            'close_icon_size',
            [
                'label'              => __( 'Size', 'blognews-for-elementor' ),
                'type'               => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'custom' ],
				'range' => [
					'px'  => ['min' => 0, 'max' => 50],
				],
				'default_desktop' => ['size' => '', 'unit' => 'px'],
				'tablet_default'  => ['size' => '', 'unit' => 'px'],
				'mobile_default'  => ['size' => '', 'unit' => 'px'],
                'selectors'       => [
                    '{{WRAPPER}} .'.$this->search_reset.' i' => 'font-size: {{SIZE}}{{UNIT}};',
                ],
                'frontend_available' => true,
            ]
        );

        $this->add_control(
            'text_color',
            [
                'label'     => __( 'Color', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .'.$this->search_reset => 'color: {{VALUE}}',
                ],
            ]
        );

        $this->end_controls_section();
        
		Notices::go_premium_notice_style($this, 'notice_two');
    }

    protected function render() {

        $settings = $this->get_settings_for_display();
        $search_layout = $settings['search_layout'];
        $this->add_render_attribute(
            'input',
            [
                'placeholder' => $settings['placeholder'],
                'class'       => 'blogfoel_search_input '.$this->search_input,
                'id'          => 'blogfoel-search-input',
                'type'        => 'search',
                'name'        => 's',
                'title'       => __( 'Search', 'blognews-for-elementor' ),
                'value'       => get_search_query(),

            ]
        );

        $this->add_render_attribute(
            'container',
            [
                'class' => [ 'blogfoel_search_container' ],
                'role'  => 'tablist',
            ]
        ); ?>
        <div class="blogfoel-widget-wrapper">
            <div class="blogfoel-search-wrapper">
                <?php if ( 'icon' === $search_layout ) { ?>
                    <a href="#" class="blogfoel-search-btn <?php echo esc_attr($this->search_btn); ?>">
                        <i class="fas fa-search" aria-hidden="true"></i>
                    </a>
                <?php } ?>
                <form class="blogfoel-search-from" role="search" action="<?php echo esc_url(home_url()); ?>" method="get">
                    <div <?php echo wp_kses_post( $this->get_render_attribute_string( 'container' ) ); ?>>
                            <input <?php echo wp_kses_post($this->get_render_attribute_string( 'input' )); ?>>
                            <button id="clear-with-button" class="blogfoel-search-reset <?php echo esc_attr($this->search_reset); ?>" type="reset">
                                <i class="fas fa-times" aria-hidden="true"></i>
                            </button>
                            <button class="blogfoel-search-submit <?php echo esc_attr($this->search_submit); ?>" type="submit">
                                <i class="fas fa-search" aria-hidden="true"></i>
                            </button>
                    </div>
                    <div id="blogfoel-suggestions-container" class="blogfoel-search-icon ">
                    </div>
                </form>
            </div>
        </div>
        <?php
    }
}