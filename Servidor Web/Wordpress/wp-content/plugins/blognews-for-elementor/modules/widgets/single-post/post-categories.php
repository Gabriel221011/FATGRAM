<?php
namespace BlogFoel;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Text_Stroke;
use Elementor\Group_Control_Typography;
use BlogFoel\Notices;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

class BLOGFOELPostCategories extends \Elementor\Widget_Base {
	
	private $single_cat_wrapper = 'blognews-single-post-category-wrapper';
    private $single_cat = 'blognews-single-post-category';

	public function get_name() {
		return 'blognews-post-categories';
	}

	public function get_title() {
		return esc_html__( 'BN Post Categories', 'blognews-for-elementor' );
	}

	public function get_icon() {
		return 'blogfoel-widget-icon bnicon-post-categories';
	}

	public function get_categories() {
		return [ 'blogfoel-sng-elementor' ];
	}

	public function get_keywords() {
		return [ 
			'BLOGFOEL',
			'post-categories', 
			'category', 
			'categories',
            'themeansar',
			'taxonomy',
		];
	}

	protected function register_controls() {

		// Tab: Content ==============
		// Section: General ----------
		$this->start_controls_section(
			'section_post_categories',
			[
				'label' => esc_html__( 'Single Categories', 'blognews-for-elementor' ),
				'tab' => Controls_Manager::TAB_CONTENT,
			]
		);

		$slug = 'blog_category';
		$this->add_responsive_control(
            $slug.'_align',
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
				'selectors' => [
					'{{WRAPPER}} .'.$this->single_cat_wrapper => 'justify-content: {{VALUE}}',
				],
            ]
        );
		$this->add_control(
			'cat_icon',
			[
				'label' => esc_html__( 'Category Icon', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
				'type' => Controls_Manager::ICONS,
				'skin' => 'inline',
                'default' => [
					'value' => '',
					'library' => '',
				],
				'label_block' => false,
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);
		$this->add_control(
			'category_style',
			[
				'label'       => esc_html__( 'Category Style', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
				'placeholder' => esc_html__( 'Choose layout Style from Here', 'blognews-for-elementor' ),
				'type'        => \Elementor\Controls_Manager::SELECT,
				'default'     => 'one',
				'options'     => [
					'one'   => esc_html__( 'Style 1', 'blognews-for-elementor' ),
				],				
				'classes' => 'blogfoel-pro-popup-notice',
			]
		);
		$this->end_controls_section(); // End Controls Section

		Notices::go_premium_notice_content($this, 'notice_one');

		// Blog Category
		$this->start_controls_section(
			'single_blog_category_settings',
			[
				'label' => __( 'Category Settings ', 'blognews-for-elementor' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);
		
		$slug = 'single_blog_category';
		
		
		blogfoel_category_style_control( $this , $slug , $this->single_cat_wrapper );

		$this->end_controls_section();
		Notices::go_premium_notice_style($this, 'notice_two');
	}

	protected function render() {
		$settings = $this->get_settings();
		$category_style     = $settings['category_style'];
		$current_url = $_SERVER['REQUEST_URI'];
		$post_id = get_the_ID();
		$elementor = \Elementor\Plugin::$instance;

		if (
			(class_exists("\Elementor\Plugin") && $elementor->editor->is_edit_mode()) ||
			(class_exists("\Elementor\Plugin") && isset($_GET['preview'], $_GET['preview_id']) && $_GET['preview'] == true) ||
			(strpos($current_url, 'blogfoel-hf-builder') !== false && get_post_type() == 'blogfoel-hf-builder')
		) {
			$post_id = $elementor->documents->get($post_id, false)->get_settings('blogfoel_demo_post_id');
		}

		$post = get_post($post_id);
		if (!$post) {
			return;
		}

		$cats = wp_get_object_terms($post_id, 'category');
		?>

		<div class="blogfoel-widget-wrapper">
			<div class="blogfoel-category <?php echo esc_attr($this->single_cat_wrapper) ?> <?php echo esc_attr($category_style); ?>">
				<?php
				if (!empty($cats) && !is_wp_error($cats)) : ?>
					<?php foreach ($cats as $cat) :
						// Get the saved color for obj category
						$bg_color = get_term_meta($cat->term_id, 'category_color', true);
						$text_color = get_term_meta($cat->term_id, 'category_text_color', true);
						
						// Set inline style if color exists
						$style = '';
						if ( ! empty( $bg_color ) ) {
							$style .= '--category-color:' . esc_attr( $bg_color ) . ';';
						}
						if ( ! empty( $text_color ) ) {
							$style .= '--category-text-color:' . esc_attr( $text_color ) . ';';
						}
						$style_attr = $style ? ' style=' . $style . '' : ''; ?>
						<a href="<?php echo esc_url(get_term_link($cat)); ?>" class="single-cat <?php echo esc_attr($this->single_cat)?>" <?php echo esc_attr($style_attr)?>>
							<?php echo esc_html($cat->name); ?>
						</a>
					<?php endforeach; ?>
				<?php else : ?>
					<span class="single-cat <?php echo esc_attr($this->single_cat)?>"><?php esc_html_e("Categories have not been defined.", "blognews-for-elementor"); ?></span>
				<?php endif; ?>
			</div>
		</div>
		<?php 
	}
}