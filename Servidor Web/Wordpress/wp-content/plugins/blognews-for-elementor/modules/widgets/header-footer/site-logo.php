<?php 
namespace BlogFoel;

use BlogFoel\Notices;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;

if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

class BLOGFOELSiteLogo extends \Elementor\Widget_Base {

    private $site_logo_wrapper = 'blognews-site-logo-wrapper';
    private $site_logo = 'blognews-site-logo';

    public function __construct( $data = array(), $args = null ) {
        parent::__construct( $data, $args );
    }

    public function get_name() {
        return 'blognews-site-logo';
    }

    public function get_title() {
        return __( 'Site Logo', 'blognews-for-elementor' );
    }

    public function get_icon() {
        return 'blogfoel-widget-icon bnicon-logo';
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
            'site logo',
            'logo',
            'widget',
            'themeansar',
            'blognews',
            'header footer',
        ];
    }

    protected function register_controls() {

        $this->start_controls_section(
            'section_content',
            array(
                'label' => esc_html__( 'Logo', 'blognews-for-elementor' ),                    
                'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
            )
        );

        $this->add_control(
            'logo_type',
            array(
                'label'   => __( 'Select Logo Type', 'blognews-for-elementor' ),
                'type'    => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => array(
                    'default'   => 'Default Logo',
                    'custom' => 'Custom Logo',
                ),
            )
        );

        $this->add_control(
            'custom_logo',
            array(
                'label'     => __( 'Logo Custom', 'blognews-for-elementor' ),
                'type'      => \Elementor\Controls_Manager::MEDIA,
                'default' => [
                    'url' => \Elementor\Utils::get_placeholder_image_src(),
                ],
                'condition' => array(
                    'logo_type' => 'custom',
                ),
            )
        );

        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            array(
                'name'        => 'custom_thumbnail',
                'default'     => '',
                'label'       => esc_html__( 'Logo Size', 'blognews-for-elementor' ),
                'condition'   => array(
                    'logo_type' => 'custom',
                ),
            )
        );

        $this->add_responsive_control(
            'align',
            array(
                'label'     => esc_html__( 'Alignment', 'blognews-for-elementor' ),
                'type'      => Controls_Manager::CHOOSE,
                'default'   => '',
                'options'   => array(
                    'left'   => array(
                        'icon'  => 'eicon-h-align-left',
                        'title' => 'Left',
                    ),
                    'center' => array(
                        'icon'  => 'eicon-h-align-center',
                        'title' => 'Center',
                    ),
                    'right'  => array(
                        'icon'  => 'eicon-h-align-right',
                        'title' => 'Right',
                    ),
                ),
                'selectors' => array(
                    '{{WRAPPER}} .'.$this->site_logo_wrapper => 'text-align: {{VALUE}};',
                ),
            )
        );

        $this->end_controls_section();

        Notices::go_premium_notice_content($this, 'notice_one');

        $this->start_controls_section(
            'section_style',
            array(
                'label' => esc_html__( 'Logo Setting', 'blognews-for-elementor' ),                    
                'tab' => \Elementor\Controls_Manager::TAB_STYLE,
            )
        );

        $slug = 'site_logo';
        $this->add_responsive_control(
            $slug.'_width',
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
                'selectors' => [
                    '{{WRAPPER}} .'.$this->site_logo => 'width: {{SIZE}}{{UNIT}};',
                ],
            ]
        );
        $this->add_responsive_control(
			$slug.'_height',
			[
				'label' => esc_html__( 'Height', 'blognews-for-elementor' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'custom' ],
				'range' => [
					'px'  => ['min' => 0, 'max' => 1000],
					'em'  => ['min' => 0, 'max' => 100],
					'rem' => ['min' => 0, 'max' => 100],
					'vh'  => ['min' => 0, 'max' => 200],
				],
				'default_desktop' => ['size' => '', 'unit' => 'px'],
				'tablet_default'  => ['size' => '', 'unit' => 'px'],
				'mobile_default'  => ['size' => '', 'unit' => 'px'],
				'selectors' => [
					'{{WRAPPER}} .'.$this->site_logo => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
        $this->add_responsive_control(
			 $slug.'_object_fit',
			[
				'label' => esc_html__( 'Object Fit', 'blognews-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'condition' => [
					$slug.'_height[size]!' => '',
				],
				'options' => [
					'' => esc_html__( 'Default', 'blognews-for-elementor' ),
					'fill' => esc_html__( 'Fill', 'blognews-for-elementor' ),
					'cover' => esc_html__( 'Cover', 'blognews-for-elementor' ),
					'contain' => esc_html__( 'Contain', 'blognews-for-elementor' ),
					'scale-down' => esc_html__( 'Scale Down', 'blognews-for-elementor' ),
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .'.$this->site_logo => 'object-fit: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			$slug.'_object_position',
			[
				'label' => esc_html__( 'Object Position', 'blognews-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'center center' => esc_html__( 'Center Center', 'blognews-for-elementor' ),
					'center left' => esc_html__( 'Center Left', 'blognews-for-elementor' ),
					'center right' => esc_html__( 'Center Right', 'blognews-for-elementor' ),
					'top center' => esc_html__( 'Top Center', 'blognews-for-elementor' ),
					'top left' => esc_html__( 'Top Left', 'blognews-for-elementor' ),
					'top right' => esc_html__( 'Top Right', 'blognews-for-elementor' ),
					'bottom center' => esc_html__( 'Bottom Center', 'blognews-for-elementor' ),
					'bottom left' => esc_html__( 'Bottom Left', 'blognews-for-elementor' ),
					'bottom right' => esc_html__( 'Bottom Right', 'blognews-for-elementor' ),
				],
				'default' => 'center center',
				'selectors' => [
					'{{WRAPPER}} .'.$this->site_logo => 'object-position: {{VALUE}};',
				],
				'condition' => [
					$slug.'_height[size]!' => '',
					$slug.'_object_fit' => [ 'cover', 'contain', 'scale-down' ],
				],
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => $slug.'_border_type',
				'label'    => 'Border Type',
				'selector' => '{{WRAPPER}}  .'.$this->site_logo,
			]
		);
		$this->add_responsive_control(
			$slug.'_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .'.$this->site_logo => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();
        Notices::go_premium_notice_style($this, 'notice_two');
    }
    protected function render() {
        $settings = $this->get_settings_for_display();
        $logo_type = $settings['logo_type'];
        ?>
        <div class="blogfoel-widget-wrapper">
            <div class="blogfoel-site-logo-wrapper <?php echo esc_attr($this->site_logo_wrapper)?>">
                <?php
                if ( 'default' === $logo_type ) {
                    if ( has_custom_logo() ) {
                        $custom_logo_id = get_theme_mod('custom_logo');
                        $logo = wp_get_attachment_image($custom_logo_id, 'full', false, array(
                            'class' => 'custom-logo '.$this->site_logo,
                            'alt'   => get_bloginfo('name'),
                        ));
                        echo '<a href="' . esc_url(home_url('/')) . '" rel="home">' . wp_kses_post($logo) . '</a>';
                    } else {
                        echo '<p>';
                        esc_html_e( 'Please Go to the ', 'blognews-for-elementor' );
                        ?>
                        <a href="<?php echo esc_url( admin_url( 'customize.php' ) ); ?>" target="_blank" title="<?php esc_attr_e( 'Customize', 'blognews-for-elementor' ); ?>">
                            <?php esc_html_e( 'Customize', 'blognews-for-elementor' ); ?>
                        </a>
                        <?php
                        esc_html_e( ' and add the Site Logo.', 'blognews-for-elementor' );
                        echo '</p>';
                    }
                } else {
                    $image_id = $settings['custom_logo']['id'];
                    $image_url = \Elementor\Group_Control_Image_Size::get_attachment_image_src( $image_id, 'custom_thumbnail', $settings );
        
                    if ( empty( $image_url ) ) {
                        $image_url = \Elementor\Utils::get_placeholder_image_src();
                    }
                    ?>
                    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home">
                        <img src="<?php echo esc_url( $image_url ); ?>" 
                            alt="<?php get_bloginfo('name') ?>" 
                            class="custom-logo <?php echo esc_attr($this->site_logo)?>">
                    </a>
                    <?php
                }
                ?>
            </div>
        </div>
        <?php
    }
}