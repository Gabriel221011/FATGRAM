<?php
namespace BlogFoel;

if (!defined('ABSPATH')) exit; // Exit if accessed directly

use Elementor\Controls_Manager;
use Elementor\Group_Control_Base;

class BlogNewsPlugin {
    private static $_instance = null;
    private $admin_instance = null;

    // Singleton pattern to ensure only one instance of this class
    public static function instance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    // Constructor to hook the methods
    public function __construct() {

		// Remove All Third Party Notices
		add_action( 'admin_enqueue_scripts',  [ $this, 'remove_notices' ]);
		
		// Include Files	
		$this->file_include();

        // Initialize and store BlogNews_Admin instance
        $this->admin_instance = new BlogNews_Admin();

        add_action('elementor/widgets/register', [$this, 'register_widgets'], 3);
        // add_action('elementor/frontend/before_enqueue_scripts', [$this, 'elementor_js']);

		add_shortcode('blogfoel_year', [ $this, 'get_year' ] );
		add_shortcode('blogfoel_site_tile', [ $this, 'get_site_name' ] );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ) );
        add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );
		add_action( 'elementor/editor/after_enqueue_scripts', array( $this, 'enqueue_custom_elementor_script' ) );
        add_action( 'elementor/documents/register_controls', array( $this, 'select_for_demo_content'), 10);
		add_action( 'elementor/editor/after_enqueue_styles', array( $this, 'enqueue_custom_elementor_style' ) );
        add_action('elementor/frontend/before_enqueue_scripts', [$this, 'blogfoel_elementor_js']);

       
		add_action('elementor/controls/register', [$this, 'init_controls']);
    }

    // better method to access the admin instance (optional but recommended)
    public function get_admin_instance() {
        return $this->admin_instance;
    }

	public function remove_notices() {
		$screen = get_current_screen();
        if ( isset( $screen->base ) && $screen->base == 'toplevel_page_blognews_admin_menu') {
            remove_all_actions( 'admin_notices' );
        }
	}

	public function file_include(){
		// Admin Page
		require_once BLOGFOEL_DIR_PATH . 'inc/admin/admin.php';
	}

	public function get_site_name() {
        return '<a class="blogfoel-copyright-site-info blognews-copyright-site-info" href="' . esc_url( home_url( '/' ) ) . '">' . esc_html( get_bloginfo( 'name' ) ) . '</a>';
    }

    public function get_year() {
        return esc_html( date( 'Y' ) );
    }

    // Load widget files
    private function include_widgets_files()  { 
		require_once BLOGFOEL_DIR_PATH . 'modules/widgets/heading.php';
		require_once BLOGFOEL_DIR_PATH . 'modules/widgets/taxonomy.php';
		require_once BLOGFOEL_DIR_PATH . 'modules/widgets/post-blog-1.php';
		require_once BLOGFOEL_DIR_PATH . 'modules/widgets/post-blog-2.php';
        require_once BLOGFOEL_DIR_PATH . 'modules/widgets/post-grid-1.php';
        require_once BLOGFOEL_DIR_PATH . 'modules/widgets/post-grid-2.php';
		require_once BLOGFOEL_DIR_PATH . 'modules/widgets/post-list-1.php';
		require_once BLOGFOEL_DIR_PATH . 'modules/widgets/post-list-2.php';
        require_once BLOGFOEL_DIR_PATH . 'modules/widgets/filter-tabs-1.php';
        require_once BLOGFOEL_DIR_PATH . 'modules/widgets/post-carousel-1.php';
        require_once BLOGFOEL_DIR_PATH . 'modules/widgets/post-ticker.php';
		require_once BLOGFOEL_DIR_PATH . 'modules/widgets/creative-button.php';
        require_once BLOGFOEL_DIR_PATH . 'modules/widgets/header-footer/menus.php';
        require_once BLOGFOEL_DIR_PATH . 'modules/widgets/header-footer/site-logo.php';
        require_once BLOGFOEL_DIR_PATH . 'modules/widgets/header-footer/site-title.php';
        require_once BLOGFOEL_DIR_PATH . 'modules/widgets/header-footer/site-tagline.php';
        require_once BLOGFOEL_DIR_PATH . 'modules/widgets/header-footer/copyright.php';
        require_once BLOGFOEL_DIR_PATH . 'modules/widgets/header-footer/search.php';
        require_once BLOGFOEL_DIR_PATH . 'modules/widgets/header-footer/date-time.php';
        require_once BLOGFOEL_DIR_PATH . 'modules/widgets/header-footer/scroll-to-top.php';
        require_once BLOGFOEL_DIR_PATH . 'modules/widgets/header-footer/light-dark.php';

        require_once BLOGFOEL_DIR_PATH . 'modules/widgets/single-post/post-title.php';
        require_once BLOGFOEL_DIR_PATH . 'modules/widgets/single-post/post-content.php';
        require_once BLOGFOEL_DIR_PATH . 'modules/widgets/single-post/post-categories.php';
        require_once BLOGFOEL_DIR_PATH . 'modules/widgets/single-post/post-tags.php';
        require_once BLOGFOEL_DIR_PATH . 'modules/widgets/single-post/post-featured-image.php';
        require_once BLOGFOEL_DIR_PATH . 'modules/widgets/single-post/post-meta.php';
        require_once BLOGFOEL_DIR_PATH . 'modules/widgets/single-post/post-pagination.php';
        require_once BLOGFOEL_DIR_PATH . 'modules/widgets/single-post/post-share-icons.php';
        require_once BLOGFOEL_DIR_PATH . 'modules/widgets/single-post/post-author.php';
        require_once BLOGFOEL_DIR_PATH . 'modules/widgets/single-post/post-comment.php';

        require_once BLOGFOEL_DIR_PATH . 'modules/widgets/archive-post/archive-title.php';
        require_once BLOGFOEL_DIR_PATH . 'modules/widgets/archive-post/archive-post.php';
        require_once BLOGFOEL_DIR_PATH . 'modules/widgets/archive-post/archive-post-list.php';
    }

	public function total_widget_options(){
		$All_Widgets = [];
		foreach ($this->get_admin_instance()->blogfoel_load_widgets() as $widget_setting) {
			foreach ( $widget_setting['widgets'] as $widget) {
                                    
                $widget_option = get_option($widget['widgetcls'], true);
			   
				$All_Widgets[] .= $widget_option == true ? true : false ;
			}
		}

		return $All_Widgets;
	}

    // Register widgets with Elementor
    public function register_widgets() {
        $this->include_widgets_files();

		define("BLOGFOEL_WIDGETS", $this->total_widget_options());
		$widget_manager = \Elementor\Plugin::instance()->widgets_manager;
		$i = 0;

		foreach ( $this->get_admin_instance()->blogfoel_load_widgets() as $widgets ) {
			foreach ( $widgets['widgets'] as $widget ) {
				$class_name = 'BlogFoel\\'. $widget['widgetcls'];
			
				if($widget['type'] == 'lite') {		
					if( !$widget['name'] == '' && !BLOGFOEL_WIDGETS[$i] == false ){
						$widget_manager->register( new $class_name() );
					}
				}
				$i++;
				
			}

		}
    }

    // Register controls with Elementor
    public function register_controls() {
        // Here you can add your custom control registration code if you have any
        // For example, register a custom control class (replace with your own class if needed)
        // \Elementor\Plugin::$instance->controls_manager->register_control('custom_control_id', new Custom_Control_Class());
    }
    public function elementor_js() {
    }
	public function blogfoel_elementor_js() {
		wp_enqueue_script( 'blognews-swiper-js', BLOGFOEL_DIR_URL . 'assets/js/swiper-bundle.min.js', ['jquery'], BLOGFOEL_VERSION, true );
    }
    public function enqueue_styles() {
        wp_enqueue_style( 'blognews-swiper-css', BLOGFOEL_DIR_URL . 'assets/css/swiper-bundle.min.css', null, BLOGFOEL_VERSION );
    }
    
    public function enqueue_scripts() {
        wp_enqueue_script( 'blognews-custom-js', BLOGFOEL_DIR_URL . 'assets/js/custom.js', ['jquery'], BLOGFOEL_VERSION, true );
        wp_enqueue_script( 'blognews-search-js', BLOGFOEL_DIR_URL . 'assets/js/search.js', ['jquery'], BLOGFOEL_VERSION, true );
		wp_enqueue_script( 'blognews-filter-tabs', BLOGFOEL_DIR_URL . 'assets/js/filter-tabs.js', [], BLOGFOEL_VERSION, true );
        wp_enqueue_script( 'blognews-swiper-custom-js', BLOGFOEL_DIR_URL . 'assets/js/widget.js', [], BLOGFOEL_VERSION, true );
		wp_enqueue_script( 'blognews-ticker-js', BLOGFOEL_DIR_URL . 'assets/js/jquery.marquee.min.js', [], BLOGFOEL_VERSION, true );
    }

	function enqueue_custom_elementor_style() {
		
		if (is_admin()) {
			wp_enqueue_style( 'elementor-editor-style', BLOGFOEL_DIR_URL . 'assets/css/elem-editor.css', null, BLOGFOEL_VERSION );
            wp_enqueue_style( 'elementor-editor-icons', BLOGFOEL_DIR_URL . 'assets/css/editor.css', null, BLOGFOEL_VERSION );
		}
	}

	function enqueue_custom_elementor_script() {
		
		if (is_admin()) {
			wp_enqueue_script('refresh-elementor-script', BLOGFOEL_DIR_URL .'assets/js/elem-editor.js', array('jquery'), '1.0', true);
		}
	}
    
	function select_for_demo_content($element) {
        $post_type = get_post_type();
        if ($post_type == 'blogfoel-hf-builder') {
            $element->start_controls_section(
                'blogfoel_demo_post_section',
                [
                    'label' => __('Demo Post Section', 'blognews-for-elementor') .BLOGFOEL_FAV_ICON,
                    'tab' => \Elementor\Controls_Manager::TAB_SETTINGS,
                ]
            );

            $element->add_control(
                'blogfoel_demo_post_id', 
                [
                    'label' => __('Choose Post for Demo', 'blognews-for-elementor'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'label_block' => true,
                    'multiple' => false,
                    'options' => blogfoel_get_post_title(),
                ]
            );

            $element->end_controls_section();

            
            $element->start_controls_section(
                'blogfoel_demo_archive_post_section',
                [
                    'label' => __('Demo Archive Section', 'blognews-for-elementor') . BLOGFOEL_FAV_ICON,
                    'tab' => \Elementor\Controls_Manager::TAB_SETTINGS,
                ]
            );

            $element->add_control(
                'blogfoel_demo_archive_select', 
                [
                    'label' => __('Choose Archive Type for Demo', 'blognews-for-elementor'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'label_block' => true,
                    'multiple' => false,
                    'options' => [
                        'category' => esc_html__( 'Category', 'blognews-for-elementor' ),
                        'tag' => esc_html__( 'Tag', 'blognews-for-elementor' ),
                        'author'  => esc_html__( 'Author', 'blognews-for-elementor' ),
                        'date'  => esc_html__( 'Date', 'blognews-for-elementor' ),
                        'search'  => esc_html__( 'Search Result', 'blognews-for-elementor' ),
                    ],
                ]
            );

            $element->add_control(
                'blogfoel_demo_cat_archive_select', 
                [
                    'label' => __('Choose Category for Archive Post Demo', 'blognews-for-elementor'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'label_block' => true,
                    'multiple' => false,
                    'options' => blognews_get_categories( $demo = 1 ),
                    'condition' =>[
                        'blogfoel_demo_archive_select' => 'category', 
                    ],
                ]
            );

            $element->add_control(
                'blogfoel_demo_tag_archive_select', 
                [
                    'label' => __('Choose Tag for Archive Post Demo', 'blognews-for-elementor'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'label_block' => true,
                    'multiple' => false,
                    'options' => blognews_get_tags( $demo = 1 ),
                    'condition' =>[
                        'blogfoel_demo_archive_select' => 'tag', 
                    ],
                ]
            );

            $element->add_control(
                'blogfoel_demo_author_archive_select', 
                [
                    'label' => __('Choose Author for Archive Post Demo', 'blognews-for-elementor'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'label_block' => true,
                    'multiple' => false,
                    'options' => blognews_get_all_authors( $demo = 0 ),
                    'condition' =>[
                        'blogfoel_demo_archive_select' => 'author', 
                    ],
                ]
            );

            $element->add_control(
                'blogfoel_demo_date_year_archive_select', 
                [
                    'label' => __('Choose Category for Archive Post Demo', 'blognews-for-elementor'),
                    'type' => \Elementor\Controls_Manager::SELECT,
                    'label_block' => true,
                    'multiple' => false,
                    'options' => blognews_get_post_years(),
                    'condition' =>[
                        'blogfoel_demo_archive_select' => 'date', 
                    ],
                ]
            );

            $element->add_control(
                'blogfoel_demo_search_result_archive_select', [
                    'label' => __( 'Demo Search', 'blognews-for-elementor' ),
                    'type' => \Elementor\Controls_Manager::TEXT,
                    'default' => __( 'Hello' , 'blognews-for-elementor' ),
                    'label_block' => true,
                    'condition' =>[
                        'blogfoel_demo_archive_select' => 'search', 
                    ],
                ]
            );

            $element->end_controls_section();
        }
    }
   
    public function init_controls() {
        require_once BLOGFOEL_DIR_PATH . 'inc/controls/blogfoel-select-control.php';
		\Elementor\Plugin::instance()->controls_manager->register_control( 'blogfoel-select', new \BLOGFOELSELECT() ); 
	}

}
// Initialize the plugin
BlogNewsPlugin::instance();