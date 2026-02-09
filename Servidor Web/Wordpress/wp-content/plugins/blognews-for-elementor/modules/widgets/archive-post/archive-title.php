<?php
namespace BlogFoel;

use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Plugin;
use BlogFoel\Notices;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class BLOGFOELArchiveTitle extends \Elementor\Widget_Base {
	
	private $archive_title_wrapper = 'blognews-archive-post-title-wrapper';
	private $archive_title = 'blognews-archive-post-title';
	private $archive_before_title = 'blognews-before-archive-post-title';

	public function get_name() {
		return 'blognews-archive-post-title';
	}

	public function get_title() {
		return esc_html__( 'BN Archive Title', 'blognews-for-elementor' );
	}

	public function get_icon() {
		return 'blogfoel-widget-icon bnicon-archive-title';
	}

	public function get_categories() {
		return [ 'blogfoel-sng-elementor' ];
	}

	public function get_keywords() {
		return ['archive-title', 'archive', 'archive title', 'title' ];
	}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_post_title',
			[
				'label' => esc_html__( 'General', 'blognews-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$this->add_control(
			'archive_title_tag',
			[
				'label' => esc_html__( 'Title HTML Tag', 'blognews-for-elementor' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'div' => 'div',
					'span' => 'span',
					'P' => 'p'
				],
				'default' => 'h1',
			]
		);

		$this->add_control (
			'cat_archive_title_before_text_toggle',
			[
				'type' => Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Category Before Text Toggle', 'blognews-for-elementor' ),
				'default' => 'no',
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'cat_archive_title_before_text', [
				'label' => __( 'Category Before Text', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Category:' , 'blognews-for-elementor' ),
				'label_block' => true,
				'condition' =>[
					'cat_archive_title_before_text_toggle' => 'yes', 
				],
			]
		);

		$this->add_control (
			'tag_archive_title_before_text_toggle',
			[
				'type' => Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Tag Before Text Toggle', 'blognews-for-elementor' ),
				'default' => 'no',
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'tag_archive_title_before_text', [
				'label' => __( 'Tag Before Text', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Tag:' , 'blognews-for-elementor' ),
				'label_block' => true,
				'condition' =>[
					'tag_archive_title_before_text_toggle' => 'yes', 
				],
			]
		);

		$this->add_control (
			'author_archive_title_before_text_toggle',
			[
				'type' => Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Author Before Text Toggle', 'blognews-for-elementor' ),
				'default' => 'no',
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'author_archive_title_before_text', [
				'label' => __( 'Author Before Text', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Author:' , 'blognews-for-elementor' ),
				'label_block' => true,
				'condition' =>[
					'author_archive_title_before_text_toggle' => 'yes', 
				],
			]
		);

		$this->add_control (
			'date_archive_title_before_text_toggle',
			[
				'type' => Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Date Before Text Toggle', 'blognews-for-elementor' ),
				'default' => 'no',
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'date_archive_title_before_text', [
				'label' => __( 'Date Before Text', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Date:' , 'blognews-for-elementor' ),
				'label_block' => true,
				'condition' =>[
					'date_archive_title_before_text_toggle' => 'yes', 
				],
			]
		);

		$this->add_control (
			'search_result_title_before_text_toggle',
			[
				'type' => Controls_Manager::SWITCHER,
				'label' => esc_html__( 'Search Before Text Toggle', 'blognews-for-elementor' ),
				'default' => 'no',
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'search_result_title_before_text', [
				'label' => __( 'Search Before Text', 'blognews-for-elementor' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => __( 'Search Results for:' , 'blognews-for-elementor' ),
				'label_block' => true,
				'condition' =>[
					'search_result_title_before_text_toggle' => 'yes', 
				],
			]
		);

		$this->add_responsive_control(
            'post_title_align',
            [
                'label' => esc_html__( 'Alignment', 'blognews-for-elementor' ),
                'type' => Controls_Manager::CHOOSE,
                'default' => 'left',
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
				'selectors' => [
					'{{WRAPPER}} .'.$this->archive_title_wrapper => 'justify-content: {{VALUE}}',
				],
				'separator' => 'before'
            ]
        );

		$this->end_controls_section(); // End Controls Section

		Notices::go_premium_notice_content($this, 'notice_one');

		$this->start_controls_section(
			'archive_type_title_section',
			[
				'label' => esc_html__( 'Archive Type', 'blognews-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'cat_archive_title_before_text_toggle',
							'operator' => '===',
							'value'    => 'yes',
						],
						[
							'name'     => 'tag_archive_title_before_text_toggle',
							'operator' => '===',
							'value'    => 'yes',
						],
						[
							'name'     => 'author_archive_title_before_text_toggle',
							'operator' => '===',
							'value'    => 'yes',
						],
						[
							'name'     => 'date_archive_title_before_text_toggle',
							'operator' => '===',
							'value'    => 'yes',
						],
						[
							'name'     => 'search_result_title_before_text_toggle',
							'operator' => '===',
							'value'    => 'yes',
						],
					],
				],
			]
		);

		$this->add_control(
			'archive_type_title_color',
			[
				'label' => esc_html__( 'Color', 'blognews-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'.$this->archive_before_title => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name'      => 'archive_type_title_typography',
                'label'     => __( 'Typography', 'blognews-for-elementor' ), 
				'selector' => '{{WRAPPER}} .'.$this->archive_before_title,
            )
        );
		$this->add_responsive_control(
			'archive_type_title_margin',
			[
				'label'     => esc_html__('Margin', 'blognews-for-elementor'),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .'.$this->archive_before_title => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section(); // End Controls Section

		$this->start_controls_section(
			'archive_title_section',
			[
				'label' => esc_html__( 'Archive Title', 'blognews-for-elementor' ),
				'tab' => Controls_Manager::TAB_STYLE,
				'show_label' => false,
			]
		);

		$this->add_control(
			'archive_title_color',
			[
				'label' => esc_html__( 'Color', 'blognews-for-elementor' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .'.$this->archive_title => 'color: {{VALUE}}',
				],
			]
		);
		
		$this->add_group_control(
            \Elementor\Group_Control_Typography::get_type(),
            array(
                'name'      => 'archive_title_typography',
                'label'     => __( 'Typography', 'blognews-for-elementor' ), 
				'selector' => '{{WRAPPER}} .'.$this->archive_title,
            )
        );

		$this->add_responsive_control(
            'archive_title_margin',
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
		$settings = $this->get_settings();
		$URL = esc_url_raw($_SERVER['REQUEST_URI']);
	
		$is_elementor = class_exists("\Elementor\Plugin");
		$title = ''; 
	
		// Archive Settings Toggles
		$cat_before_text_toggle = $settings['cat_archive_title_before_text_toggle'];
		$tag_before_text_toggle = $settings['tag_archive_title_before_text_toggle'];
		$author_before_text_toggle = $settings['author_archive_title_before_text_toggle'];
		$date_before_text_toggle = $settings['date_archive_title_before_text_toggle'];
		$search_before_text_toggle = $settings['search_result_title_before_text_toggle'];
	
		// Archive Before Texts
		$cat_before_text = $settings['cat_archive_title_before_text'];
		$tag_before_text = $settings['tag_archive_title_before_text'];
		$author_before_text = $settings['author_archive_title_before_text'];
		$date_before_text = $settings['date_archive_title_before_text'];
		$search_before_text = $settings['search_result_title_before_text'];
	
		if ( $is_elementor && ( 
			\Elementor\Plugin::$instance->editor->is_edit_mode() || 
			( isset($_GET['preview'], $_GET['preview_id']) && $_GET['preview'] == true ) || 
			( strpos($URL, 'blogfoel-hf-builder') !== false && get_post_type() == 'blogfoel-hf-builder' )
		)) {
			$post_id = get_the_ID();
			$document = \Elementor\Plugin::$instance->documents->get($post_id, false);
	
			if ($document) {
				$archive_type = $document->get_settings('blogfoel_demo_archive_select');
				$cat_archive = $document->get_settings('blogfoel_demo_cat_archive_select');
				$tag_archive = $document->get_settings('blogfoel_demo_tag_archive_select');
				$author_archive = $document->get_settings('blogfoel_demo_author_archive_select');
				$date_archive = $document->get_settings('blogfoel_demo_date_year_archive_select');
				$search_result = $document->get_settings('blogfoel_demo_search_result_archive_select');
	
				switch ($archive_type) {
					case 'category':
						$title = $cat_before_text_toggle === 'yes' ? "<span class='before-archive ". esc_attr($this->archive_before_title)."'>{$cat_before_text}</span><span class='archive-text ". esc_attr($this->archive_title)."'>{$cat_archive}</span>" : "<span class='archive-text ". esc_attr($this->archive_title)."'>{$cat_archive}</span>";
						break;
					case 'tag':
						$title = $tag_before_text_toggle === 'yes' ? "<span class='before-archive ". esc_attr($this->archive_before_title)."'>{$tag_before_text}</span><span class='archive-text ". esc_attr($this->archive_title)."'>{$tag_archive}</span>" : "<span class='archive-text ". esc_attr($this->archive_title)."'>{$tag_archive}</span>";
						break;
					case 'author':
						$user = get_user_by('id', $author_archive);
						$user_name = $user ? esc_html($user->display_name) : '';
						$title = $author_before_text_toggle === 'yes' ? "<span class='before-archive ". esc_attr($this->archive_before_title)."'>{$author_before_text}</span><span class='archive-text ". esc_attr($this->archive_title)."'>{$user_name}</span>" : "<span class='archive-text ". esc_attr($this->archive_title)."'>{$user_name}</span>";
						break;
					case 'date':
						$title = $date_before_text_toggle === 'yes' ? "<span class='before-archive ". esc_attr($this->archive_before_title)."'>{$date_before_text}</span><span class='archive-text ". esc_attr($this->archive_title)."'>{$date_archive}</span>" : "<span class='archive-text ". esc_attr($this->archive_title)."'>{$date_archive}</span>";
						break;
					case 'search':
						$title = $search_before_text_toggle === 'yes' ? "<span class='before-archive ". esc_attr($this->archive_before_title)."'>{$search_before_text}</span><span class='archive-text ". esc_attr($this->archive_title)."'>{$search_result}</span>" : "<span class='archive-text ". esc_attr($this->archive_title)."'>{$search_result}</span>";
						break;
				}
			}
		} else {
			if (is_category()) {
				$category = get_category(get_queried_object_id());
				$category_name = $category ? esc_html($category->name) : '';
				$title = $cat_before_text_toggle === 'yes' ? "<span class='before-archive ". esc_attr($this->archive_before_title)."'>{$cat_before_text}</span><span class='archive-text ". esc_attr($this->archive_title)."'>{$category_name}</span>" : "<span class='archive-text ". esc_attr($this->archive_title)."'>{$category_name}</span>";
			} elseif (is_tag()) {
				$tag = get_term_by('id', get_queried_object_id(), 'post_tag');
				$tag_name = $tag ? esc_html($tag->name) : '';
				$title = $tag_before_text_toggle === 'yes' ? "<span class='before-archive ". esc_attr($this->archive_before_title)."'>{$tag_before_text}</span><span class='archive-text ". esc_attr($this->archive_title)."'>{$tag_name}</span>" : "<span class='archive-text ". esc_attr($this->archive_title)."'>{$tag_name}</span>";
			} elseif (is_author()) {
				$user = get_user_by('id', get_queried_object_id());
				$user_name = $user ? esc_html($user->display_name) : '';
				$title = $author_before_text_toggle === 'yes' ? "<span class='before-archive ". esc_attr($this->archive_before_title)."'>{$author_before_text}</span><span class='archive-text ". esc_attr($this->archive_title)."'>{$user_name}</span>" : "<span class='archive-text ". esc_attr($this->archive_title)."'>{$user_name}</span>";
			} elseif (is_date()) {
				$date = '';
				if ($date_before_text_toggle === 'yes') {
					$date .= "<span class='before-archive ". esc_attr($this->archive_before_title)."'>{$date_before_text}</span>";
				}
				$date .= "<span class='archive-text ". esc_attr($this->archive_title)."'>";
				if (is_day()) {
					$date .= get_the_date();
				} elseif (is_month()) {
					$date .= get_the_date('F Y');
				} elseif (is_year()) {
					$date .= get_the_date('Y');
				}
				$date .= "</span>";
				$title = $date;
			} elseif (is_search()) {
				$search_query = get_search_query();
				$title = $search_before_text_toggle === 'yes' ? "<span class='before-archive ". esc_attr($this->archive_before_title)."'>{$search_before_text}</span><span class='archive-text ". esc_attr($this->archive_title)."'>{$search_query}</span>" : "<span class='archive-text ". esc_attr($this->archive_title)."'>{$search_query}</span>";
			}
		}
	
		echo '<div class="blogfoel-widget-wrapper">';
			echo '<' . esc_attr($settings['archive_title_tag']) . ' class="blogfoel-archive-title '.esc_attr($this->archive_title_wrapper).'">';
			echo wp_kses_post($title);
			echo "</" . esc_attr($settings['archive_title_tag']) . ">";
		echo '</div>';

	}	
}