<?php 
namespace BlogFoel;

use BlogFoel\Notices;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

class BLOGFOELCopyright extends Widget_Base {

    protected $copyright_index = 1;

    private $copyright_wrapper = 'blognews-copyright-wrapper';
    private $copyright_text = 'blognews-copyright-text';
    private $copyright_link = 'blognews-copyright-site-info';

    public function __construct( $data = array(), $args = null ) {
        parent::__construct( $data, $args );
    }

    public function get_name() {
        return 'blognews-copyright';
    }

    public function get_title() {
        return __( 'BN Copyright', 'blognews-for-elementor' );
    }

    public function get_icon() {
        return 'blogfoel-widget-icon bnicon-copyright';
    }

    public function get_categories() {
        return array( 'blogfoel-hf-elementor' );
    }
    
    public function get_keywords() {
        return [
            'copyright',
            'header footer', 
            'widget',
            'themeansar',
            'BLOGFOEL',
        ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'section_title',
            array(
                'label' => __( 'Copyright', 'blognews-for-elementor' ),
            )
        );

        $this->add_control(
            'copyright',
            array(
                'label'   => __( 'Copyright Text', 'blognews-for-elementor' ),
                'type'    => Controls_Manager::WYSIWYG,
                'dynamic' => array(
                    'active' => true,
                ),
                'default' => __( 'Copyright © [blogfoel_year] [blogfoel_site_tile] | All Rights Reserved. Designed by [blogfoel_site_tile].', 'blognews-for-elementor' ),
            )
        );

        $this->add_responsive_control(
            'align',
            array(
                'label'     => __( 'Alignment', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::CHOOSE,
                'options'   => array(
                    'start'   => array(
                        'title' => __( 'Left', 'blognews-for-elementor' ),
                        'icon'  => 'eicon-text-align-left',
                    ),
                    'center' => array(
                        'title' => __( 'Center', 'blognews-for-elementor' ),
                        'icon'  => 'eicon-text-align-center',
                    ),
                    'end'  => array(
                        'title' => __( 'Right', 'blognews-for-elementor' ),
                        'icon'  => 'eicon-text-align-right',
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .'.$this->copyright_wrapper => 'text-align: {{VALUE}};',
                    '{{WRAPPER}} .'.$this->copyright_text => 'justify-content: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        Notices::go_premium_notice_content($this, 'notice_one');

        $this->start_controls_section(
            'section_style_copyright',
            array(
                'label' => __( 'Copyright Text', 'blognews-for-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'text_color',
            array(
                'label'     => __( 'Color', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .'.$this->copyright_text => 'color: {{VALUE}};',
                ),
            )
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'     => 'caption_typography',
                'selector' => '{{WRAPPER}} .'.$this->copyright_text,
            )
        );

        $this->end_controls_section();

        $this->start_controls_section(
            'section_style_site_title',
            array(
                'label' => __( 'Site Title', 'blognews-for-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
            )
        );

        $this->add_control(
            'site_title_color',
            array(
                'label'     => __( 'Color', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .'.$this->copyright_link => 'color: {{VALUE}};',
                ),
            )
        );
        $this->add_control(
            'site_title_hover_color',
            array(
                'label'     => __( 'Hover Color', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::COLOR,
                'selectors' => array(
                    '{{WRAPPER}} .'.$this->copyright_link.':hover' => 'color: {{VALUE}};',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Typography::get_type(),
            array(
                'name'      => 'site_title_typography',
                'label'     => __( 'Typography', 'blognews-for-elementor' ),
                'selector' => '{{WRAPPER}} .'.$this->copyright_link,
            )
        );

        $this->end_controls_section();
        
        Notices::go_premium_notice_style($this, 'notice_two');
    }

    protected function render() {

        $settings = $this->get_settings_for_display();
        $copy_right= do_shortcode( shortcode_unautop( $settings['copyright'] ) );
        ?>
        <div class="blogfoel-widget-wrapper">
            <div class="blogfoel-copyright-wrapper">
                <div class="blogfoel-main-wrapper <?php echo esc_attr($this->copyright_wrapper)?>">
                    <span  class="blogfoel-copyright-text <?php echo esc_attr($this->copyright_text)?>">
                        <?php
                            echo wp_kses(
                                $copy_right,
                                array(
                                    'a' => array(
                                        'class' => array(' to'),
                                        'href'  => array(),
                                        'id'    => array(),
                                    ),
                                )
                            );
                        ?>
                    </span>
                </div>
            </div>
        </div>
        <?php
    }
}