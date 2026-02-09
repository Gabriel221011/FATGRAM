<?php 
namespace BlogFoel;

use BlogFoel\Notices;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

class BLOGFOELSiteTagline extends Widget_Base {

    private $site_tagline_wrapper = 'blognews-site-tagline-wrapper';
    private $site_tagline = 'blognews-site-tagline';

    protected $copyright_index = 1;

    public function __construct( $data = array(), $args = null ) {
        parent::__construct( $data, $args );
    }

    public function get_name() {
        return 'blognews-site-tagline';
    }

    public function get_title() {
        return __( 'Site Tagline', 'blognews-for-elementor' );
    }

    public function get_icon() {
        return 'blogfoel-widget-icon bnicon-site-tagline';
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
            'site tagline',
            'tagline',
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

        $slug = 'site_tagline';
        $this->add_control(
            $slug.'_before',
            [
                'label'   => __( 'Before Tagline Text', 'blognews-for-elementor' ),
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
                'label'   => __( 'After Tagline Text', 'blognews-for-elementor' ),
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
                    '{{WRAPPER}} .'.$this->site_tagline.' ' => 'text-align: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_section();
        Notices::go_premium_notice_content($this, 'notice_one');
        $this->start_controls_section(
            'section_style',
            array(
                'label' => esc_html__( 'Site Tagline', 'blognews-for-elementor' ),                    
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            )
        );
        
        $slug = 'site_tagline';
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name'     => $slug.'_typography',
                'selector' => '{{WRAPPER}} .'.$this->site_tagline,
            ]
        );
        $this->add_control(
            $slug.'_color',
            [
                'label'     => __( 'Color', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .'.$this->site_tagline=> 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control(
            $slug.'_hover_color',
            [
                'label'     => __( 'Hover Color', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .'.$this->site_tagline.' a:hover'=> 'color: {{VALUE}};',
                ],
				'condition' => [
					'site_tagline_link_disable' => 'yes'
				],
            ]
        );
        $this->add_group_control(
			\Elementor\Group_Control_Text_Shadow::get_type(),
            [
                'name'     => $slug.'_text_shadow',
                'label'    => esc_html__( 'Text Shadow', 'blognews-for-elementor' ),
                'selector' => '{{WRAPPER}} .'.$this->site_tagline,
            ]
        );
        $this->add_group_control(
			\Elementor\Group_Control_Text_Stroke::get_type(),
			[
				'name'     => $slug.'_text_stroke',
                'label'    => esc_html__( 'Text Stroke', 'blognews-for-elementor' ),
                'selector' => '{{WRAPPER}} .'.$this->site_tagline,
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
        $before = $settings['site_tagline_before'];
        $after = $settings['site_tagline_after'];
        ?>
        <div class="blogfoel-widget-wrapper">
            <div class="blogfoel-site-tagline-wrapper <?php echo esc_attr($this->site_tagline_wrapper)?>">
                <p class="blogfoel-site-tagline <?php echo esc_attr($this->site_tagline)?>">
                    <?php
                    if ( '' !== $before || '' !== get_bloginfo( 'description' ) || '' !== $after ) {
                        echo wp_kses_post( $before ).' ';
                        echo wp_kses_post(  get_bloginfo( 'description' ) );  
                        echo ' ' . wp_kses_post( $after );
                    } else {
                        esc_html_e( 'Please Go to the ', 'blognews-for-elementor' ); ?>
                        <a href='<?php echo esc_url( admin_url( 'customize.php' ) ); ?>' target="_blank" title='<?php esc_attr_e('Customize','blognews-for-elementor'); ?>'>
                            <?php esc_html_e( 'Customize', 'blognews-for-elementor' ); ?>
                        </a>
                        <?php esc_html_e( 'and add the Tagline.', 'blognews-for-elementor' );
                    }
                    ?>
                </p>
            </div>
        </div>
        <?php
    }
}