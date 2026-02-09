<?php 
namespace BlogFoel;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use BlogFoel\Notices;

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

class BLOGFOELSiteTitle extends Widget_Base {

    private $site_title_wrapper = 'blognews-site-title-wrapper';
    private $site_title = 'blognews-site-title';

    public function __construct( $data = array(), $args = null ) {
        parent::__construct( $data, $args );
    }

    public function get_name() {
        return 'blognews-site-title';
    }

    public function get_title() {
        return __( 'Site Title', 'blognews-for-elementor' );
    }

    public function get_icon() {
        return 'blogfoel-widget-icon bnicon-site-title';
    }

    public function get_categories() {
        return array( 'blogfoel-hf-elementor' );
    }

    public function get_style_depends() {
        return array( '' );
    }

    public function get_script_depends() {
        return array('');

    }

    public function get_keywords() {
        return [
            'site title',
            'title',
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
                'label' => __( 'Style', 'blognews-for-elementor' ),
            ]
        );

        $slug = 'site_title';
        $this->add_control(
            $slug.'_before',
            [
                'label'   => __( 'Before Title Text', 'blognews-for-elementor' ),
                'type'    => Controls_Manager::TEXTAREA,
                'rows'    => '1',
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->add_control(
            $slug.'_after',
            [
                'label'   => __( 'After Title Text', 'blognews-for-elementor' ),
                'type'    => Controls_Manager::TEXTAREA,
                'rows'    => '1',
                'dynamic' => [
                    'active' => true,
                ],
            ]
        );

        $this->add_responsive_control(
            $slug.'_text_align',
            [
                'label'              => __( 'Alignment', 'blognews-for-elementor' ),
                'type'               => Controls_Manager::CHOOSE,
                'options'            => [
                    'left'    => [
                        'title' => __( 'Left', 'blognews-for-elementor' ),
                        'icon'  => 'eicon-text-align-left',
                    ],
                    'center'  => [
                        'title' => __( 'Center', 'blognews-for-elementor' ),
                        'icon'  => 'eicon-text-align-center',
                    ],
                    'right'   => [
                        'title' => __( 'Right', 'blognews-for-elementor' ),
                        'icon'  => 'eicon-text-align-right',
                    ],
                    'justify' => [
                        'title' => __( 'Justify', 'blognews-for-elementor' ),
                        'icon'  => 'eicon-text-align-justify',
                    ],
                ],
                'selectors'          => [
                    '{{WRAPPER}} .'.$this->site_title.' ' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
			$slug.'_disable',
			[
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Default Title', 'blognews-for-elementor' ),
				'label_on' => esc_html__( 'Show', 'blognews-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'blognews-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
        
        $this->add_control(
            $slug.'_html_tag',
            [
                'label'       => esc_html__( 'Html Tag', 'blognews-for-elementor' ),
                'type'        => \Elementor\Controls_Manager::SELECT,
                'default'     => 'h1',
                'options'     => [
                    'h1' => esc_html__( 'H1', 'blognews-for-elementor' ),
                    'h2' => esc_html__( 'H2', 'blognews-for-elementor' ),
                    'h3' => esc_html__( 'H3', 'blognews-for-elementor' ),
                    'h4' => esc_html__( 'H4', 'blognews-for-elementor' ),
                    'h5' => esc_html__( 'H5', 'blognews-for-elementor' ),
                    'h6' => esc_html__( 'H6', 'blognews-for-elementor' ),
                    'div' => esc_html__( 'Div', 'blognews-for-elementor' ),
                    'span' => esc_html__( 'span', 'blognews-for-elementor' ),
                    'p' => esc_html__( 'p', 'blognews-for-elementor' ),
                ],
            ]
        );
        $this->add_control(
			$slug.'_link_disable',
			[
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Link', 'blognews-for-elementor' ),
				'label_on' => esc_html__( 'Enable', 'blognews-for-elementor' ),
				'label_off' => esc_html__( 'Disable', 'blognews-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
        $this->end_controls_section();
        Notices::go_premium_notice_content($this, 'notice_one');
        
        $this->start_controls_section(
            'section_style',
            array(
                'label' => esc_html__( 'Site Title', 'blognews-for-elementor' ),                    
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            )
        );
        
        $slug = 'site_title';
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => $slug.'_typography',
                'selector' => '{{WRAPPER}} .'.$this->site_title,
            ]
        );
        $this->add_control(
            $slug.'_color',
            [
                'label'     => __( 'Color', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .'.$this->site_title=> 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            $slug.'_hover_color',
            [
                'label'     => __( 'Hover Color', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .'.$this->site_title.' a:hover'=> 'color: {{VALUE}};',
                ],
				'condition' => [
					'site_title_link_disable' => 'yes'
				],
            ]
        );
        $this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name'     => $slug.'_text_shadow',
                'label'    => esc_html__( 'Text Shadow', 'blognews-for-elementor' ),
                'selector' => '{{WRAPPER}} .'.$this->site_title,
            ]
        );
        $this->add_group_control(
			\Elementor\Group_Control_Text_Stroke::get_type(),
			[
				'name'     => $slug.'_text_stroke',
                'label'    => esc_html__( 'Text Stroke', 'blognews-for-elementor' ),
                'selector' => '{{WRAPPER}} .'.$this->site_title,
			]
		);

       	$this->add_responsive_control(
			$slug.'_margin',
			[
				'label' => esc_html__( 'Margin', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);
        
        $this->end_controls_section();

        Notices::go_premium_notice_style($this, 'notice_two');
    }

    protected function render() {

        $settings = $this->get_settings_for_display();
        $before = $settings['site_title_before'];
        $after = $settings['site_title_after'];
        $tag = $settings['site_title_html_tag'];
        $default = $settings['site_title_disable'];
        $link = $settings['site_title_link_disable'];
        ?>
        <div class="blogfoel-widget-wrapper">
            <div class="blogfoel-site-title-wrapper <?php echo esc_attr($this->site_title_wrapper)?>">
                <?php
                    if ( '' !== $before || '' !== get_bloginfo( 'name' ) || '' !== $after ) {
                        echo '<'. esc_html($tag).' class="blogfoel-site-title '.esc_attr($this->site_title).'">';
                            if ($link == 'yes'){
                                echo '<a href="'. esc_url( home_url( '/' ) ).'">';
                            }
                            if ( '' !== $before ) {
                                echo wp_kses_post( $before ).' ';
                            }
                            if ($default == 'yes'){
                                echo wp_kses_post( get_bloginfo( 'name' ) ); 
                            }
                            if ( '' !== $after ) {
                                echo ' ' . wp_kses_post( $after );
                            }
                            if ($link == 'yes'){
                                echo '</a>';
                            }
                        echo '</<'. esc_html($tag).'>';
                    } else {
                        echo '<p>';
                        esc_html_e( 'Please Go to the ', 'blognews-for-elementor' ); ?>
                        <a href='<?php echo esc_url( admin_url( 'customize.php' ) ); ?>' target="_blank" title='<?php esc_attr_e('Customize','blognews-for-elementor'); ?>'>
                            <?php esc_html_e( 'Customize', 'blognews-for-elementor' ); ?>
                        </a>
                        <?php esc_html_e( 'and add the Site Title.', 'blognews-for-elementor' );
                        echo '</p>';
                    }
                ?>
            </div>
        </div>
        <?php
    }
}