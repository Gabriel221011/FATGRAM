<?php
namespace BlogFoel;

use BlogFoel\Notices;
use Elementor\Controls_Stack;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Schemes\Color;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Base;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow; 
use Elementor\Scheme_Color;
use Elementor\Utils;
use Elementor\Repeater;

class BLOGFOELNavMenu extends Widget_Base {

    private $menu_card_class = 'blognews-menu-wrapper';
    private $menu_card_box = 'blognews-main-menu';
    private $main_nav_menu = 'blognews-main-nav-menu';
	private $menu_toggle = 'blognews-menu-hamburger-icon';
	private $menu_title = 'blognews-menu-title';
	private $submenu_title = 'blognews-submenu-title';
	private $submenu_arrow = 'blognews-submenu-arrow';

    protected $nav_menu_index = 1;

    public function __construct( $data = array(), $args = null ) {
        parent::__construct( $data, $args );
    }

    public function get_name() {
        return 'blognews-nav-menu';
    }

    public function get_title() {
        return __( 'BN Menus', 'blognews-for-elementor' );
    }

    public function get_icon() {
        return 'blogfoel-widget-icon bnicon-menus';
    }

    public function get_categories() {
        return array( 'blogfoel-hf-elementor' );
    }

    public function get_style_depends() {
        return [
            'blognews-styles-css',
            'blognews-menu-css',
        ];
    }

    public function get_script_depends() {
        return [
            'blognews-custom-js',
        ];
    }

    public function get_keywords() {
        return [
            'nav menus',
            'menus',
            'navigation', 
            'widget',
            'themeansar',
            'blognews',
            'header footer',
        ];
    }

    protected function get_nav_menu_index() {
        return $this->nav_menu_index++;
    }

    private function get_available_menus() {
        $menus = wp_get_nav_menus();

        $options = array();

        foreach ( $menus as $menu ) {
            $options[ $menu->slug ] = $menu->name;
        }

        return $options;
    }

    protected function register_controls() {

        $this->start_controls_section(
            'section_menu',
            array(
                'label' => __( 'Menu', 'blognews-for-elementor' ),
            )
        );

        $menus = $this->get_available_menus();

        if ( ! empty( $menus ) ) {
            $this->add_control(
                'menu',
                array(
                    'label'       => __( 'Menu', 'blognews-for-elementor' ),
                    'type'        => Controls_Manager::SELECT,
                    'options'     => $menus,
                    'default'     => array_keys( $menus )[0],
                    // Translators: %s is the URL to the Menus admin page
                    'description' => sprintf( __( 'To manage nav menus, navigate to <a href="%s" target="_blank">Menus admin</a>.', 'blognews-for-elementor' ), admin_url( 'nav-menus.php' ) ),
                )
            );
        } else {
            $this->add_control(
                'menu',
                array(
                    'type'            => Controls_Manager::RAW_HTML,
                    // Translators: %s is the URL to the Menus admin page
                    'raw'             => sprintf( __( '<strong>It seems no menus are created.</strong><br>Navigate to <a href="%s" target="_blank">Menus admin</a> and create one.', 'blognews-for-elementor' ), admin_url( 'nav-menus.php?action=edit&menu=0' ) ),
                    'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
                )
            );
        }

        $this->add_control(
            'menu_type',
            array(
                'label'       => __( 'Type', 'blognews-for-elementor' ),
				'type'        => 'blogfoel-select',
				'label_block' => false,
                'options'   => array(
                    'horizontal'    => 'Horizontal',
                    'blogfoel-pro-vertical'    => 'Vertical (Pro)',
                ),
                'default'     => 'horizontal',
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_layout',
            array(
                'label' => __( 'Layout', 'blognews-for-elementor' ),
            )
        );

        $this->add_responsive_control(
            'align_items',
            [
                'label' => esc_html__( 'Menu Alignment', 'blognews-for-elementor' ),
                'type' => \Elementor\Controls_Manager::CHOOSE,
                'default' => 'center', 
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Left', 'blognews-for-elementor' ),
                        'icon' => 'eicon-text-align-left',
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'blognews-for-elementor' ),
                        'icon' => 'eicon-text-align-center',
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'Right', 'blognews-for-elementor' ),
                        'icon' => 'eicon-text-align-right',
                    ],
                ],
                'selectors' =>[
                    '{{WRAPPER}} .'.$this->main_nav_menu => 'justify-content:{{VALUE}};',
                ],
            ]
        );

        $this->add_control(
            'indicator_icon',
            [
                'label'       => esc_html__( 'Submenu Indicator', 'blognews-for-elementor' ),
                'type'        => \Elementor\Controls_Manager::SELECT,
                'default'     => 'classic',
                'options'     => [
                    'none'        => esc_html__( 'None', 'blognews-for-elementor' ),
                    'classic'   => esc_html__( 'Classic ', 'blognews-for-elementor' ),
                    'angle'   => esc_html__( 'Angle ', 'blognews-for-elementor' ),
                    'double-angle'   => esc_html__( 'Double Angle ', 'blognews-for-elementor' ),
                    'chevron'   => esc_html__( 'Chevron ', 'blognews-for-elementor' ),
                    'plus'   => esc_html__( 'Plus ', 'blognews-for-elementor' ),
                ],
				'prefix_class' => 'blogfoel-submenu-icon-',
            ]
        );
        $this->add_responsive_control(
            'icon_gap',
            [
                'label' => esc_html__( 'Icon Gap', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
                'type' => \Elementor\Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'range' => [
                    'px'  => ['min' => 0, 'max' => 100],
                    'em'  => ['min' => 0, 'max' => 100],
                    'rem' => ['min' => 0, 'max' => 100],
                ],
                'devices'         => [ 'desktop', 'tablet', 'mobile' ],
                'default'         => ['size' => '', 'unit' => 'px'],
                'tablet_default'  => ['size' => '', 'unit' => 'px'],
                'mobile_default'  => ['size' => '', 'unit' => 'px'],
                'classes' => 'blogfoel-pro-popup-notice',
            ]
        );
        $this->add_control(
            'heading_responsive',
            [
                'type'      => Controls_Manager::HEADING,
                'label'     => __( 'Responsive', 'blognews-for-elementor' ),
                'separator' => 'before',
            ]
        );
	    $this->add_control(
			'menu_full_width',
			[
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Full Width', 'blognews-for-elementor' ),
				'description' => esc_html__( 'Enable this option to stretch the Sub Menu to Full Width.', 'blognews-for-elementor' ),
				'label_on' => esc_html__( 'On', 'blognews-for-elementor' ),
				'label_off' => esc_html__( 'Off', 'blognews-for-elementor' ),
				'return_value' => 'yes',
                'default' => 'no',
				'prefix_class'  => 'blognews-mobile-menu-full-width-',
                'render_type' => 'template',
			]
		);
        $this->add_control(
            'toggle_responsive',
            [
                'label'        => __( 'Breakpoint', 'blognews-for-elementor' ),
                'type'         => Controls_Manager::SELECT,
                'default'      => 'nav-menu-dropdown-tablet',
                'options'      => [
                    'nav-menu-dropdown-tablet' => __( 'Tablet', 'blognews-for-elementor' ),
                    'nav-menu-dropdown-mobile' => __( 'Mobile', 'blognews-for-elementor' ),
                    'nav-menu-dropdown-none' => __( 'None', 'blognews-for-elementor' ),
                ],
            ]
        );

        $this->add_responsive_control(
            'toggle_align',
            array(
                'label'                => __( 'Toggle Align', 'blognews-for-elementor' ),
                'type'                 => Controls_Manager::CHOOSE,
                'default'              => 'center',
                'options'              => array(
                    'left'   => array(
                        'title' => __( 'Left', 'blognews-for-elementor' ),
                        'icon'  => 'eicon-h-align-left',
                    ),
                    'center' => array(
                        'title' => __( 'Center', 'blognews-for-elementor' ),
                        'icon'  => 'eicon-h-align-center',
                    ),
                    'right'  => array(
                        'title' => __( 'Right', 'blognews-for-elementor' ),
                        'icon'  => 'eicon-h-align-right',
                    ),
                ),
                'condition' => [
                    'toggle_responsive!' => 'none',
                ],
                'selectors' =>[
                    '{{WRAPPER}} .blogfoel-menu-hamburger-btn'=> 'justify-content:{{VALUE}};' 
                ],
            )
        );

        $this->end_controls_section();

        Notices::go_premium_notice_content($this, 'notice_one');

        $this->start_controls_section(
            'menus_style',
            array(
                'label' => __( 'Menus', 'blognews-for-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );
        $slug = 'menus';
        $this->add_control(
			$slug.'_animation_type',
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
            $slug.'_custom_animation',
            [
                'label'       => esc_html__( 'Animation', 'blognews-for-elementor' ),
                'type'        => 'blogfoel-select',
				'label_block' => false,
                'default'     => 'animate-one',
                'options'     => [
					'animate-none'  => esc_html__( 'None', 'blognews-for-elementor' ),
					'animate-one'   => esc_html__( 'Effect 1', 'blognews-for-elementor' ),
					'blogfoel-pro-animate-two'   => esc_html__( 'Effect 2 (Pro)', 'blognews-for-elementor' ),
					'blogfoel-pro-animate-three' => esc_html__( 'Effect 3 (Pro)', 'blognews-for-elementor' ),
					'blogfoel-pro-animate-four'  => esc_html__( 'Effect 4 (Pro)', 'blognews-for-elementor' ),
					'blogfoel-pro-animate-five'  => esc_html__( 'Effect 5 (Pro)', 'blognews-for-elementor' ),
					'blogfoel-pro-animate-six'   => esc_html__( 'Effect 6 (Pro)', 'blognews-for-elementor' ),
                ],
				'condition' => [
					$slug.'_animation_type' => 'custom'
				],
            ]
        );
		$this->add_control(
            $slug.'_elementor_animation',
            [
                'label'       => esc_html__( 'Animation', 'blognews-for-elementor' ). BLOGFOEL_PRO_ICON,
                'type' => 'blogfoel-select',
				'label_block' => false,
                'default'     => 'none',
				'options'     => [
					'none'  => esc_html__( 'None', 'blognews-for-elementor' ),
				],
				'condition' => [
					$slug.'_animation_type!' => 'custom'
				],
				'classes' => 'blogfoel-pro-popup-notice',
            ]
        );

        $this->start_controls_tabs(
            'menus_tabs'
        );
        
        $this->start_controls_tab(
            'menus_normal_tab',
            [
                'label' => esc_html__( 'Normal', 'blognews-for-elementor' ),
            ]
        );

        $this->add_control(
            'menus_color',
            array(
                'label'     => __( 'Color', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' =>[
                    '{{WRAPPER}} .'.$this->menu_title => 'color: {{VALUE}}',
                    '{{WRAPPER}} .'.$this->submenu_arrow => 'color: {{VALUE}}',
                ],
            )
        );

        $this->add_control(
            'menus_bg_color',
            array(
                'label'     => __( 'Background Color', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' =>[
                    '{{WRAPPER}} .'.$this->main_nav_menu.' > .menu-item' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .'.$this->submenu_arrow => 'background-color: {{VALUE}}',
                ],
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'menus_hover_tab',
            [
                'label' => esc_html__( 'Hover', 'blognews-for-elementor' ),
            ]
        );

        $this->add_control(
            'menus_color_hover',
            array(
                'label'     => __( ' Color', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' =>[
                    '{{WRAPPER}} .menu-item:hover .'.$this->menu_title => 'color: {{VALUE}};',
					'{{WRAPPER}} .menu-item.active .'.$this->menu_title => 'color: {{VALUE}};',
					'{{WRAPPER}} .menu-item:hover .'.$this->submenu_arrow => 'color: {{VALUE}};',
					'{{WRAPPER}}' => '--title-hover-color: {{VALUE}};',
                ],
            )
        );

        $this->add_control(
            'menus_color_bg_hover',
            array(
                'label'     => __( 'Background Color', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' =>[
                    '{{WRAPPER}} .'.$this->main_nav_menu.'>.menu-item:hover' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .'.$this->main_nav_menu.' .active.menu-item' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .'.$this->main_nav_menu.' .menu-item:hover .'.$this->submenu_arrow => 'background-color: {{VALUE}}',    
                ],
            )
        );
    
        $this->end_controls_tab();
        $this->start_controls_tab(
            'menus_active_tab',
            [
                'label' => esc_html__( 'Active', 'blognews-for-elementor' ),
            ]
        );

        $this->add_control(
            'menus_color_active',
            array(
                'label'     => __( ' Color', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' =>[
                    '{{WRAPPER}} .menu-item.current-menu-item .'.$this->menu_title => 'color: {{VALUE}};',
                    '{{WRAPPER}} .menu-item.current-menu-parent .'.$this->menu_title => 'color: {{VALUE}};',
					'{{WRAPPER}} .menu-item.current-menu-parent.'.$this->submenu_arrow => 'color: {{VALUE}};',
					'{{WRAPPER}} .menu-item.current-menu-item .'.$this->submenu_arrow => 'color: {{VALUE}};',
                ],
            )
        );

        $this->add_control(
            'menus_color_bg_active',
            array(
                'label'     => __( 'Background Color', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' =>[
                    '{{WRAPPER}} .'.$this->main_nav_menu.'>.menu-item.current-menu-parent, {{WRAPPER}} .menu-item.current-menu-item' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .'.$this->main_nav_menu.' .menu-item.current-menu-parent, {{WRAPPER}} .menu-item.current-menu-item .'.$this->submenu_arrow => 'background-color: {{VALUE}}',   
                ],
            )
        );
    
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control(
			'hr',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => 'menus_typography',
                'label'     => __( 'Typography', 'blognews-for-elementor' ), 
                'selector'  => '{{WRAPPER}} .'.$this->menu_title,
            )
        ); 
        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name' => 'menus_border',
                'label' => esc_html__( 'Border', 'blognews-for-elementor' ),
                'selector' => '{{WRAPPER}} .'.$this->main_nav_menu.' > .menu-item',
            ]
        );
            
        $this->add_responsive_control(
            'menus_border_radius',
            array(
                'label'      => __( 'Border Radius', 'blognews-for-elementor' ),
                'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'selectors'  => [
                    '{{WRAPPER}} .'.$this->main_nav_menu.' > .menu-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            )
        );
        $this->add_responsive_control(
            'menus_padding',
            array(
                'label'      => __( 'Padding', 'blognews-for-elementor' ),
                'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ], 
                'selectors'  => [
                    '{{WRAPPER}} .'.$this->menu_title => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .'.$this->menu_title.':hover' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .'.$this->menu_title.':focus' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                    '{{WRAPPER}} .'.$this->main_nav_menu.' > .menu-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            )
        );
        $this->add_responsive_control(
            'menus_margin',
			[
				'label' => esc_html__( 'Margin', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'classes' => 'blogfoel-pro-popup-notice',
			]
        );
	    $this->add_control(
			'menus_box_shadow',
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
            'section_style_dropdown',
            array(
                'label' => __( 'Dropdown', 'blognews-for-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );
        $this->add_control(
            'dropdown_description',
            array(
                'raw'             => __( 'On desktop, this will affect the submenu. On mobile, this will affect the entire menu.', 'blognews-for-elementor' ),
                'type'            => Controls_Manager::RAW_HTML,
                'content_classes' => 'elementor-descriptor',
            )
        );

        $this->start_controls_tabs( 'tabs_dropdown_item_style' );

        $this->start_controls_tab(
            'tab_dropdown_item_normal',
            array(
                'label' => __( 'Normal', 'blognews-for-elementor' ),
            )
        );

        $this->add_control(
            'color_dropdown_item',
            array(
                'label'     => __( 'Color', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => array(
                    '{{WRAPPER}} .'.$this->submenu_title => 'color: {{VALUE}}',
                    '{{WRAPPER}} .blogfoel-main-sb-menu .'.$this->submenu_arrow => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'background_color_dropdown_item',
            array(
                'label'     => __( 'Background Color', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => array(
                    '{{WRAPPER}} .blogfoel-main-sb-menu .menu-item' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .blogfoel-main-sb-menu .'.$this->submenu_arrow => 'background-color: {{VALUE}}',
                ),
                'separator' => 'none',
            )
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            'tab_dropdown_item_hover',
            array(
                'label' => __( 'Hover', 'blognews-for-elementor' ),
            )
        );

        $this->add_control(
            'color_dropdown_item_hover',
            array(
                'label'     => __( 'Color', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => array(
                    '{{WRAPPER}} .blogfoel-main-sb-menu .menu-item:hover > .'.$this->submenu_title => 'color: {{VALUE}}',
                    '{{WRAPPER}} .blogfoel-main-sb-menu .menu-item:hover:hover .'.$this->submenu_arrow => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'background_color_dropdown_item_hover',
            array(
                'label'     => __( 'Background Color', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => array(
                    '{{WRAPPER}} .blogfoel-main-sb-menu>.menu-item:hover' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .blogfoel-main-sb-menu .menu-item:hover:hover .'.$this->submenu_arrow => 'background-color: {{VALUE}}',
                ),
                'separator' => 'none',
            )
        );

        $this->end_controls_tab();
        $this->start_controls_tab(
            'tab_dropdown_item_active',
            array(
                'label' => __( 'Active', 'blognews-for-elementor' ),
            )
        );
        $this->add_control(
            'color_dropdown_item_active',
            array(
                'label'     => __( 'Color', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => array(
                    '{{WRAPPER}} .current-menu-item >.'.$this->submenu_title => 'color: {{VALUE}}',
                    '{{WRAPPER}} .blogfoel-main-sb-menu > .current-menu-item .'.$this->submenu_arrow => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            'background_color_dropdown_item_active',
            array(
                'label'     => __( 'Background Color', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'default'   => '',
                'selectors' => array(
                    '{{WRAPPER}} .blogfoel-main-sb-menu .menu-item.current-menu-item' => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .blogfoel-main-sb-menu .current-menu-item .'.$this->submenu_arrow => 'background-color: {{VALUE}}',
                ),
                'separator' => 'none',
            )
        );

        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control(
			'tab_dropdown_hr',
			[
				'type' => Controls_Manager::DIVIDER,
			]
		);

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => 'dropdown_typography',
                'label'     => __( 'Typography', 'blognews-for-elementor' ), 
                'selector'  => '{{WRAPPER}} .'.$this->submenu_title, 
            )
        );

        $this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'      => 'dropdown_border',
                'label'     => __( 'Border', 'blognews-for-elementor' ),
                'selector'  => '{{WRAPPER}} .blogfoel-main-sb-menu .menu-item', 
            ]
        );

        $this->add_responsive_control(
            'dropdown_border_radius',
            array(
                'label'      => __( 'Border Radius', 'blognews-for-elementor' ),
                'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'selectors'  => [
                    '{{WRAPPER}} .blogfoel-main-sb-menu .menu-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
                ],
            )
        );

        $this->add_responsive_control(
            'dropdown_padding',
            array(
                'label'      => __( 'Padding', 'blognews-for-elementor' ),
                'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
                'selectors'  => [
                    '{{WRAPPER}} .blogfoel-main-menu .menu-item a.dropdown-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            )
        );
          $this->add_responsive_control(
            'dropdown_margin',
			[
				'label' => esc_html__( 'Margin', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'classes' => 'blogfoel-pro-popup-notice',
			]
        );
	    $this->add_control(
			'dropdown_box_shadow',
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
            'section_style_toggle',
            array(
                'label' => __( 'Toggle Button', 'blognews-for-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );
        $slug = 'toggle';
        $this->start_controls_tabs( $slug.'_tabs_style' );

        $this->start_controls_tab(
            $slug.'_tab_style_normal',
            array(
                'label' => __( 'Normal', 'blognews-for-elementor' ),
            )
        );

        $this->add_control(
            $slug.'_color',
            array(
                'label'     => __( 'Color', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .'.$this->menu_toggle => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            $slug.'_background_color',
            array(
                'label'     => __( 'Background Color', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .'.$this->menu_toggle => 'background-color: {{VALUE}}',
                ),
            )
        );

		$this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => $slug.'_border_type',
                'label'    => 'Border Type',
                'selector' => '{{WRAPPER}} .'.$this->menu_toggle,
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            $slug.'_tab_style_hover',
            array(
                'label' => __( 'Focus / Hover', 'blognews-for-elementor' ),
            )
        );

        $this->add_control(
            $slug.'_color_hover',
            array(
                'label'     => __( 'Color', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .'.$this->menu_toggle.':hover' => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            $slug.'_background_color_hover',
            array(
                'label'     => __( 'Background Color', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .'.$this->menu_toggle.':hover' => 'background-color: {{VALUE}}',
                ),
            )
        );
        $this->add_control(
            $slug.'_border_color_hover',
            array(
                'label'     => __( 'Border Color', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array( 
                    '{{WRAPPER}} .'.$this->menu_toggle.':hover' => 'border-color: {{VALUE}}',
                ),
            )
        );
        $this->end_controls_tab(); 
        $this->end_controls_tabs();

        $this->add_control( $slug.'_hr', [ 'type' => Controls_Manager::DIVIDER, ] );

        $this->add_responsive_control(
            $slug.'_size',
            array(
                'label'     => __( 'Size', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px'  => ['min' => 0, 'max' => 100],
					'em'  => ['min' => 0, 'max' => 100],
					'rem' => ['min' => 0, 'max' => 100],
				],
				'default_desktop' => ['size' => '', 'unit' => 'px'],
				'tablet_default'  => ['size' => '', 'unit' => 'px'],
				'mobile_default'  => ['size' => '', 'unit' => 'px'],
                'selectors' => array(
                    '{{WRAPPER}} .'.$this->menu_toggle => 'font-size: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->add_responsive_control(
            $slug.'_wsize',
            array(
                'label'     => __( 'Wrap Size', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px'  => ['min' => 0, 'max' => 120],
					'em'  => ['min' => 0, 'max' => 100],
					'rem' => ['min' => 0, 'max' => 100],
				],
				'default_desktop' => ['size' => '', 'unit' => 'px'],
				'tablet_default'  => ['size' => '', 'unit' => 'px'],
				'mobile_default'  => ['size' => '', 'unit' => 'px'],
                'selectors' => array(
                    '{{WRAPPER}} .'.$this->menu_toggle => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ),
            )
        );
        $this->add_responsive_control(
            $slug.'_border_radius',
            array(
                'label'      => __( 'Border Radius', 'blognews-for-elementor' ),
                'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ], 
                'selectors'  => [
                    '{{WRAPPER}} .'.$this->menu_toggle => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            )
        );
        $this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
                'name'      => $slug.'_box_shadow',
                'label'    => 'Box Shadow',
                'selector' => '{{WRAPPER}} .'.$this->menu_toggle,
            ]
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'style_resposive_menu',
            array(
                'label' => __( 'Mobile Menu', 'blognews-for-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );
        $slug = 'responsive_menu';
        $this->add_control(
			'menus_heading',
			[
				'label' => esc_html__( 'Menus', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
        $this->start_controls_tabs( $slug.'_tabs' );
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
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .show .'.$this->menu_title => 'color: {{VALUE}};',
					'{{WRAPPER}} .show .'.$this->submenu_arrow => 'color: {{VALUE}};',
				],
			]
		);
        $this->add_control(
			$slug.'_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'blognews-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .show .'.$this->menu_card_box.' .'.$this->main_nav_menu.'>.menu-item' => 'background-color: {{VALUE}};',
				],
			]
		);

        $this->end_controls_tab();

		$this->start_controls_tab(
			$slug.'_style_hover',
			[
				'label' => __( 'Hover', 'blognews-for-elementor' ),

			]
		);
        $this->add_control(
			$slug.'_hover_color',
			[
				'label' => esc_html__( 'Color', 'blognews-for-elementor' ),
				'type' => Controls_Manager::COLOR,
                'selectors' =>[
					'{{WRAPPER}} .show .menu-item:hover .'.$this->menu_title => 'color: {{VALUE}};',
					'{{WRAPPER}} .show .menu-item.active .'.$this->menu_title => 'color: {{VALUE}};',
					'{{WRAPPER}} .show .menu-item:hover .'.$this->submenu_arrow => 'color: {{VALUE}};',
                ],
			]
		);
        $this->add_control(
			$slug.'_bg_hover_color',
			[
				'label' => esc_html__( 'Background Color', 'blognews-for-elementor' ),
				'type' => Controls_Manager::COLOR,
                'selectors' =>[
                    '{{WRAPPER}} .show.'.$this->menu_card_box.' .'.$this->main_nav_menu.'>.menu-item:hover' => 'background-color: {{VALUE}}',    
                    '{{WRAPPER}} .show.'.$this->menu_card_box.' .'.$this->main_nav_menu.'>.menu-item.active' => 'background-color: {{VALUE}}',    
                ],
			]
		);
        $this->end_controls_tab();
        $this->start_controls_tab(
			$slug.'_style_active',
			[
				'label' => __( 'Active', 'blognews-for-elementor' ),

			]
		);
        $this->add_control(
			$slug.'_active_color',
			[
				'label' => esc_html__( 'Color', 'blognews-for-elementor' ),
				'type' => Controls_Manager::COLOR,
                'selectors' =>[
					'{{WRAPPER}} .show .menu-item.current-menu-parent .'.$this->menu_title => 'color: {{VALUE}};',
					'{{WRAPPER}} .show .menu-item.current-menu-parent .'.$this->submenu_arrow => 'color: {{VALUE}};',
                ],
			]
		);
        $this->add_control(
			$slug.'_bg_active_color',
			[
				'label' => esc_html__( 'Background Color', 'blognews-for-elementor' ),
				'type' => Controls_Manager::COLOR,
                'selectors' =>[
                    '{{WRAPPER}} .show.'.$this->menu_card_box.' .'.$this->main_nav_menu.'>.menu-item.current-menu-parent' => 'background-color: {{VALUE}}', 
                    '{{WRAPPER}} .show .menu-item.current-menu-parent .'.$this->submenu_arrow => 'background-color: {{VALUE}};',
                ],
			]
		);
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $slug = 'dropdown';
        $this->add_control(
			'dropdown_heading',
			[
				'label' => esc_html__( 'Dropdown', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
        $this->start_controls_tabs( $slug.'_tabs' );
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
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .show .'.$this->submenu_title => 'color: {{VALUE}};',
					'{{WRAPPER}} .show .'.$this->submenu_arrow => 'color: {{VALUE}};',
				],
			]
		);
        $this->add_control(
			$slug.'_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'blognews-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .show .blogfoel-main-sb-menu>.menu-item' => 'background-color: {{VALUE}};',
				],
			]
		);

        $this->end_controls_tab();

		$this->start_controls_tab(
			$slug.'_style_hover',
			[
				'label' => __( 'Hover', 'blognews-for-elementor' ),

			]
		);
        $this->add_control(
			$slug.'_hover_color',
			[
				'label' => esc_html__( 'Color', 'blognews-for-elementor' ),
				'type' => Controls_Manager::COLOR,
                'selectors' =>[
					'{{WRAPPER}} .show .menu-item:hover > .'.$this->submenu_title => 'color: {{VALUE}};',
					'{{WRAPPER}} .show .menu-item:hover .'.$this->submenu_arrow => 'color: {{VALUE}};',
                ],
			]
		);
        $this->add_control(
			$slug.'_bg_hover_color',
			[
				'label' => esc_html__( 'Background Color', 'blognews-for-elementor' ),
				'type' => Controls_Manager::COLOR,
                'selectors' =>[
                    '{{WRAPPER}} .show .blogfoel-main-sb-menu>.menu-item:hover' => 'background-color: {{VALUE}}',
                ],
			]
		);
        $this->end_controls_tab();
        $this->start_controls_tab(
            $slug.'_style_active',
            array(
                'label' => __( 'Active', 'blognews-for-elementor' ),
            )
        );
        $this->add_control(
			$slug.'_active_color',
			[
				'label' => esc_html__( 'Color', 'blognews-for-elementor' ),
				'type' => Controls_Manager::COLOR,
                'selectors' =>[
					'{{WRAPPER}} .show .menu-item.current-menu-item .'.$this->submenu_title => 'color: {{VALUE}};',
					'{{WRAPPER}} .show .current-menu-item .'.$this->submenu_arrow => 'color: {{VALUE}};',
                ],
			]
		);
        $this->add_control(
			$slug.'_bg_active_color',
			[
				'label' => esc_html__( 'Background Color', 'blognews-for-elementor' ),
				'type' => Controls_Manager::COLOR,
                'selectors' =>[
                    '{{WRAPPER}} .show .menu-item.current-menu-item' => 'background-color: {{VALUE}}',    
                    '{{WRAPPER}} .show .current-menu-item .'.$this->submenu_arrow => 'background-color: {{VALUE}}',    
                ],
			]
		);

        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_dropdown_icon',
            array(
                'label' => __( 'Dropdown Menu Icon', 'blognews-for-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );
        $slug = 'dropdown_icon';
        $this->add_control(
            $slug.'_description',
            array(
                'raw'             => __( 'This will impact the submenu icons on mobile and tablet devices.', 'blognews-for-elementor' ),
                'type'            => Controls_Manager::RAW_HTML,
                'content_classes' => 'elementor-descriptor',
            )
        );
        $this->start_controls_tabs( $slug.'_tabs_style' );

        $this->start_controls_tab(
            $slug.'_tab_style_normal',
            array(
                'label' => __( 'Normal', 'blognews-for-elementor' ),
            )
        );

        $this->add_control(
            $slug.'_color',
            array(
                'label'     => __( 'Color', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .show .menu-item .'.$this->submenu_arrow => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            $slug.'_background_color',
            array(
                'label'     => __( 'Background Color', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .show .menu-item .'.$this->submenu_arrow => 'background-color: {{VALUE}}',
                ),
            )
        );

		$this->add_group_control(
            \Elementor\Group_Control_Border::get_type(),
            [
                'name'     => $slug.'_border_type',
                'label'    => 'Border Type',
                'selector' => '{{WRAPPER}} .show .'.$this->submenu_arrow,
            ]
        );

        $this->end_controls_tab();

        $this->start_controls_tab(
            $slug.'_tab_style_hover',
            array(
                'label' => __( 'Hover', 'blognews-for-elementor' ),
            )
        );        

        $this->add_control(
            $slug.'_color_hover',
            array(
                'label'     => __( 'Color', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .show .menu-item:hover > .'.$this->submenu_arrow => 'color: {{VALUE}}',
                    '{{WRAPPER}} .show .blogfoel-main-sb-menu>.menu-item:hover>.'.$this->submenu_arrow => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            $slug.'_background_color_hover',
            array(
                'label'     => __( 'Background Color', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .show .menu-item:hover > .'.$this->submenu_arrow => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .show .blogfoel-main-sb-menu>.menu-item:hover>.'.$this->submenu_arrow => 'background-color: {{VALUE}}',
                ),
            )
        );
        $this->add_control(
            $slug.'_border_color_hover',
            array(
                'label'     => __( 'Border Color', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array( 
                    '{{WRAPPER}} .show .menu-item:hover > .'.$this->submenu_arrow => 'border-color: {{VALUE}}',
                ),
            )
        );
        $this->end_controls_tab();

        $this->start_controls_tab(
            $slug.'_tab_style_active',
            array(
                'label' => __( 'Active', 'blognews-for-elementor' ),
            )
        );        

        $this->add_control(
            $slug.'_color_active',
            array(
                'label'     => __( 'Color', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .show .menu-item.current-menu-item > .'.$this->submenu_arrow => 'color: {{VALUE}}',
                    '{{WRAPPER}} .show .blogfoel-main-sb-menu>.menu-item.current-menu-item>.'.$this->submenu_arrow => 'color: {{VALUE}}',
                ),
            )
        );

        $this->add_control(
            $slug.'_background_color_active',
            array(
                'label'     => __( 'Background Color', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .show .menu-item.current-menu-item > .'.$this->submenu_arrow => 'background-color: {{VALUE}}',
                    '{{WRAPPER}} .show .blogfoel-main-sb-menu>.menu-item.current-menu-item>.'.$this->submenu_arrow => 'background-color: {{VALUE}}',
                ),
            )
        );
        $this->add_control(
            $slug.'_border_color_active',
            array(
                'label'     => __( 'Border Color', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array( 
                    '{{WRAPPER}} .show .menu-item.current-menu-item > .'.$this->submenu_arrow => 'border-color: {{VALUE}}',
                ),
            )
        );
        $this->end_controls_tab();  
        $this->end_controls_tabs();

        $this->add_control( $slug.'_hr', [ 'type' => Controls_Manager::DIVIDER, ] );

        $this->add_responsive_control(
            $slug.'_size',
            array(
                'label'     => __( 'Size', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px'  => ['min' => 0, 'max' => 100],
					'em'  => ['min' => 0, 'max' => 100],
					'rem' => ['min' => 0, 'max' => 100],
				],
				'default_desktop' => ['size' => '', 'unit' => 'px'],
				'tablet_default'  => ['size' => '', 'unit' => 'px'],
				'mobile_default'  => ['size' => '', 'unit' => 'px'],
                'selectors' => array(
                    '{{WRAPPER}} .show .'.$this->submenu_arrow => 'font-size: {{SIZE}}{{UNIT}}',
                ),
            )
        );

        $this->add_responsive_control(
            $slug.'_wsize',
            array(
                'label'     => __( 'Wrap Size', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px'  => ['min' => 0, 'max' => 120],
					'em'  => ['min' => 0, 'max' => 100],
					'rem' => ['min' => 0, 'max' => 100],
				],
				'default_desktop' => ['size' => '', 'unit' => 'px'],
				'tablet_default'  => ['size' => '', 'unit' => 'px'],
				'mobile_default'  => ['size' => '', 'unit' => 'px'],
                'selectors' => array(
                    '{{WRAPPER}} .show .'.$this->submenu_arrow => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
                ),
            )
        );
        $this->add_responsive_control(
            $slug.'_border_radius',
            array(
                'label'      => __( 'Border Radius', 'blognews-for-elementor' ),
                'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ], 
                'selectors'  => [
                    '{{WRAPPER}} .show .'.$this->submenu_arrow => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            )
        );
        $this->end_controls_section();
        
        Notices::go_premium_notice_style($this, 'notice_two');
    }

    protected function render() {
        
        $available_menus = $this->get_available_menus();
        $widget_id = $this->get_id();      
        
        $settings = $this->get_settings_for_display();
        $toggle_responsive = $settings['toggle_responsive'];
        $menu_type = $settings['menu_type'];


        $menus_animation_type = $settings['menus_animation_type'];
		$custom_animation     = $settings['menus_custom_animation'];
		$elementor_animation  = $settings['menus_elementor_animation'];
		$menus_animation      = $menus_animation_type == 'custom' ? ' '.$custom_animation : ' elementor-animation-'. $elementor_animation;
        if ( ! $available_menus ) { ?>
            <p class='empty-menus-text'>
                <?php esc_html_e( 'Please navigate to the', 'blognews-for-elementor' ); ?>
                <a href='<?php echo esc_url( admin_url( 'nav-menus.php' ) ); ?>' title='<?php esc_attr_e('Menus','blognews-for-elementor'); ?>'>
                    <?php esc_html_e( 'Menus', 'blognews-for-elementor' ); ?>
                </a>
                <?php esc_html_e( 'page and create a new menu.', 'blognews-for-elementor' ); ?>
            </p><?php
        } else {
            if($menu_type == 'horizontal'){ ?>
                <div class="blogfoel-widget-wrapper">
                    <div class="blogfoel-menus-wrapper <?php echo esc_attr($this->menu_card_class) ?> <?php echo esc_attr($toggle_responsive); ?>">     
                        <div class="blogfoel-menu-hamburger-btn">
                            <button class="blogfoel-menu-hamburger-icon <?php echo esc_attr($this->menu_toggle) ?>">
                                <i class="fas fa-bars"></i>
                            </button>
                        </div>
                        <nav id="blogfoel-main-nav" class="blogfoel-main-menu <?php echo esc_attr($this->menu_card_box) ?>">
                            <?php wp_nav_menu( array(
                                'menu'        => $settings['menu'],
                                'menu_class'  => 'blogfoel-main-nav-menu '. $this->main_nav_menu.' blogfoel-main-nav-menu-'.$widget_id.' mobile-nav',
                                'menu_id'     => 'blogfoel-main-nav-menu',
                                'fallback_cb' => 'blogfoel_nav_walker::fallback',
                                'container'   => '',
                                'walker' => new blogfoel_nav_walker($menus_animation),
                            ) ); ?>
                        </nav>
                    </div>
                </div>
            <?php }
        }
    }
}