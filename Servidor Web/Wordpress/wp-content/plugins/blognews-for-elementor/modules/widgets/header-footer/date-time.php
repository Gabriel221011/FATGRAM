<?php
namespace BlogFoel;

use BlogFoel\Notices;
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

class BLOGFOELDateTime extends Widget_Base {

	private $date_time_class = 'blog-post-date-time-wrapper';
	private $date_title = 'blog-post-date';
	private $time_title = 'blog-post-time';

    public function get_name() {
        return 'date-time-widget';
    }
    public function get_title() {
        return 'BN Date & Time';
    }
    public function get_icon() {
        return 'blogfoel-widget-icon bnicon-date-and-time';
    }
    public function get_categories() {
        return ['blogfoel-hf-elementor'];
    }
    public function get_keywords() {
		return [
            'date',
            'time',
            'widget',
            'themeansar',
            'BLOGFOEL',
		];
	}
    protected function register_controls() {
        $this->start_controls_section('date_time_section_content', ['label' => 'Settings', ]);

        $this->add_control(
			'date_time_type',
			[
				'label'       => esc_html__( 'Formate Type', 'blognews-for-elementor' ),
				'placeholder' => esc_html__( 'Choose Template from Here', 'blognews-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'default',
				'options'     => [
					'default'      => esc_html__( 'Default', 'blognews-for-elementor' ),
					'wordpress' => esc_html__( 'From Wordpress', 'blognews-for-elementor' ),
				],
			]
		);

        $this->add_control(
			'show_date',
			[
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Show Date', 'blognews-for-elementor' ),
				'label_on' => esc_html__( 'Show', 'blognews-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'blognews-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
        $this->add_control(
			'show_time',
			[
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Show time', 'blognews-for-elementor' ),
				'label_on' => esc_html__( 'Show', 'blognews-for-elementor' ),
				'label_off' => esc_html__( 'Hide', 'blognews-for-elementor' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
        $this->add_responsive_control(
			'date_time_gap',
			[
				'label' => esc_html__( 'Gap', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'range' => [
					'px'  => ['min' => 0, 'max' => 100],
					'em'  => ['min' => 0, 'max' => 100],
					'rem' => ['min' => 0, 'max' => 100],
				],
				'default_desktop' => ['size' => '', 'unit' => 'px'],
				'tablet_default'  => ['size' => '', 'unit' => 'px'],
				'mobile_default'  => ['size' => '', 'unit' => 'px'],
				'selectors'       => [
					'{{WRAPPER}} .'.$this->date_time_class => 'gap: {{SIZE}}{{UNIT}};',
				],
			]
		);
        $this->add_responsive_control(
            'date_time_align', 
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
                    '{{WRAPPER}} .'.$this->date_time_class => 'justify-content: {{VALUE}};',
                ], 
            ]
        );

        $this->end_controls_section();

        Notices::go_premium_notice_content($this, 'notice_one');

        // STYLE
        $this->start_controls_section(
            'date_style',
            [
                'label' => __( 'Date', 'blognews-for-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' =>[
                    'show_date' => 'yes',
                ]
            ]
        );

        $slug = 'date';
		$this->add_control(
			$slug.'_color',
			[
				'label' => esc_html__( 'Color', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .'.$this->date_title => 'color: {{VALUE}};',
                ],
            ]
        );

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
                'name'     => $slug.'_typography',
                'label'     => __( 'Typography', 'blognews-for-elementor' ),
                'selector' => '{{WRAPPER}} .'.$this->date_title,
            ]
        );
        $this->end_controls_section();

        // Time
        $this->start_controls_section(
            'time_style',
            [
                'label' => __( 'Time', 'blognews-for-elementor' ),
                'tab'   => Controls_Manager::TAB_STYLE,
                'condition' =>[
                    'show_time' => 'yes',
                ]
            ]
        );

        $slug = 'time';
		$this->add_control(
			$slug.'_background_color',
			[
				'label' => esc_html__( 'Background Color', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .'.$this->time_title => 'background-color: {{VALUE}};',
                ],
            ]
        );

		$this->add_control(
			$slug.'_color',
			[
				'label' => esc_html__( 'Color', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'selectors' => [
                    '{{WRAPPER}} .'.$this->time_title => 'color: {{VALUE}};',
                ],
            ]
        );

		$this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
                'name'     => $slug.'_typography',
                'label'     => __( 'Typography', 'blognews-for-elementor' ),
                'selector' => '{{WRAPPER}} .'.$this->time_title,
            ]
        );
        
		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name'     => $slug.'_border_type',
				'label'    => 'Border Type',
				'selector' => '{{WRAPPER}}  .'.$this->time_title,
			]
		);

		$this->add_responsive_control(
			$slug.'_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
				'selectors' => [
					'{{WRAPPER}} .'.$this->time_title => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
                    '{{WRAPPER}} .'.$this->time_title => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ],
            ]
        );

        $this->end_controls_section();
        
        Notices::go_premium_notice_style($this, 'notice_two');
    }
    protected function render() {
        $settings = $this->get_settings_for_display();
        $type = $settings['date_time_type'];
        $date = $settings['show_date'];
        $time = $settings['show_time'];
        ?>
        <div class="blogfoel-widget-wrapper">
            <div class="blogfoel-date-time-wrapper <?php echo esc_attr($this->date_time_class) ?>">
                <?php if ( $type == 'default' ) {
                    if($date == true) { ?>
                        <span class="blogfoel-day <?php echo esc_attr($this->date_title) ?>">
                            <?php echo esc_html(date_i18n('j M  Y, D', strtotime(current_time("Y-m-d"))));?>
                        </span>
                        <?php } if ($time == true) { ?>
                    <span id="time" class="blogfoel-time <?php echo esc_attr($this->time_title) ?>"></span>
                <?php } } elseif( $type == 'wordpress') {
                    if($date == true) { ?>
                        <span class="blogfoel-day <?php echo esc_attr($this->date_title) ?>">
                            <?php echo esc_html(date_i18n( get_option( 'date_format' ) )); ?>
                        </span>
                    <?php } if ($time == true) { ?>
                        <span class="blogfoel-time <?php echo esc_attr($this->time_title) ?>"> <?php $format = get_option('') . ' ' . get_option('time_format'); echo esc_html(date_i18n($format, current_time('timestamp'))); ?></span>
                    <?php }
                } ?>
            </div>
        </div>
        <?php
    }
}
