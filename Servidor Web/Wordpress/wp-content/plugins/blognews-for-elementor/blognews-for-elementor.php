<?php
/**
 * Plugin Name:       BlogNews For Elementor
 * Plugin URI:        https://blognews.themeansar.com/
 * Description:       BlogNews is a fast WordPress plugin for bloggers and news sites, fully Elementor-ready with stylish layouts and easy customization.
 * Version:           2.0.0
 * Author:            Themeansar
 * Author URI:        https://themeansar.com/
 * Text Domain:       blognews-for-elementor
 * License:           GPLv3
 * License URI:       https://opensource.org/licenses/GPL-3.0
 * Elementor tested up to: 3.33.1
 * Elementor Pro tested up to: 3.33.1
 **/

if (!defined('ABSPATH') ) : exit(); endif; // no direct access allowed

if ( ! function_exists( 'bfe_fs' ) ) {
    // Create a helper function for easy SDK access.
    function bfe_fs() {
        global $bfe_fs;

        if ( ! isset( $bfe_fs ) ) {
            // Include Freemius SDK.
            require_once dirname( __FILE__ ) . '/vendor/freemius/start.php';
            $bfe_fs = fs_dynamic_init( array(
                'id'                  => '20273',
                'slug'                => 'blognews-for-elementor',
                'premium_slug'        => 'blognews-for-elementor-pro',
                'type'                => 'plugin',
                'public_key'          => 'pk_b27a8b11494ab56e4f6de75232e3b',
                'is_premium'          => false,
                // If your plugin is a serviceware, set this option to false.
                'has_premium_version' => true,
                'has_addons'          => false,
                'has_paid_plans'      => true,
                'menu'                => array(
                    'slug'        => 'blognews_admin_menu',
					'first-path'     => 'admin.php?page=blognews_admin_menu&tab=starter-sites',
                ),
            ) );
        }

        return $bfe_fs;
    }

    // Init Freemius.
    bfe_fs();
    // Signal that SDK was initiated.
    do_action( 'bfe_fs_loaded' );
}

if ( !class_exists('BLOG_FOEL') ) {
	class BLOG_FOEL {

		private static $instance;
		public static function get_instance() {
			if ( ! isset( self::$instance ) ) {
				self::$instance = new BLOG_FOEL();
				self::$instance->constants();
				self::$instance->init();
			}
			return self::$instance;
		}

		// Setup constants.
		public function constants() { 
			if(!defined('BLOGFOEL')){
				define('BLOGFOEL_FILE', __FILE__);
				define('BLOGFOEL_DIR_PATH', plugin_dir_path(BLOGFOEL_FILE));
				define('BLOGFOEL_DIR_URL', plugin_dir_url(BLOGFOEL_FILE));
				define('BLOGFOEL_VERSION', '2.0.0');
				define('BLOGFOEL_MIN_PHP_VERSION', '7.4');
				define( 'BLOGFOEL_PRO_LINK', 'https://blognews.themeansar.com/' ); //Pro Link
				define( 'BLOGFOEL_GO_PRO_HTML', '<span class="blogfoel-pro-feature"> Get the  <a href="'.BLOGFOEL_PRO_LINK.'" target="_blank">Pro version</a> for more stunning Features and Controls.</span>' ); //Pro Link
				if ( ! defined( 'BLOGFOEL_PRO_ICON' ) ) {
					define( 'BLOGFOEL_PRO_ICON', ' <i class="eicon-pro-icon"></i>' );
				}
				if ( ! defined( 'BLOGFOEL_FAV_ICON' ) ) {
					define( 'BLOGFOEL_FAV_ICON', ' <i class="blogfoel-section-icon bnicon-blognews-favicon"></i>' );
				}
			}
		}

		//initialize Plugin
		public function init() {
			$this->include_files();
			add_action( 'enqueue_block_assets', [ $this, 'blogfoel_assets' ] );
			add_action( 'plugins_loaded', [ $this, 'blogfoel_addons_register_function' ] );
			add_action( 'elementor/elements/categories_registered', [ $this, 'blogfoel_elementor_elments_categories_registered' ] );
			add_action( 'wp_enqueue_scripts', [ $this, 'blogfoel_enqueue_font_awesome' ], 20 );
			add_action( 'after_setup_theme', [ $this, 'blogfoel_block_styles_support' ] );
            add_action('init', [ $this, 'blogfoel_elementor_sitesetting' ], 5);
            add_action('import_end', [ $this, 'blogfoel_elementor_sitesetting' ], 999);
            add_action('pt-ocdi/after_import', [ $this, 'blogfoel_elementor_sitesetting' ], 999);
            add_action( 'wp_head', [ $this,'blogfoel_elementor_set_var'], 3 );
		}

		//Include all files
		public function include_files() {
			require_once('inc/function.php');
			require_once('inc/custom-navwalker.php');
			require_once('inc/class-site-builder.php');
			require_once('inc/elementor/classes/utils.php');
			require_once('inc/elementor/classes/notices.php');
		}

		public function blogfoel_assets() {
			// Styles
			wp_enqueue_style(
				'blogfoel-all-min',
				BLOGFOEL_DIR_URL . 'assets/css/all.min.css',
				array(),
				BLOGFOEL_VERSION
			);
			wp_enqueue_style(
				'blogfoel-style',
				BLOGFOEL_DIR_URL .('assets/css/style.css'), 
				array(), 
				BLOGFOEL_VERSION
			); 
			wp_enqueue_style(
				'blogfoel-core-style',
				BLOGFOEL_DIR_URL .('assets/css/sass/core.css'), 
				array(), 
				BLOGFOEL_VERSION
			); 
		}

		public function blogfoel_addons_register_function() {
			BlogNews_Header_Footer_Elementor::instance();
		}

		// add category for Blog widgets
		public function blogfoel_elementor_elments_categories_registered( $elements_manager  ){
			$custom_categories = [];

			$custom_categories['blogfoel-elementor'] = [
				'title' => __('BlogNews Elementor', 'blognews-for-elementor'),
				'icon'  => 'fas fa-plug',
			];

			$custom_categories['blogfoel-hf-elementor'] = [
				'title' => __('BlogNews HF Elementor', 'blognews-for-elementor'),
				'icon'  => 'fas fa-plug',
			];

			// Conditional: add single blog category based on current post type/meta
			$screen = function_exists('get_current_screen') ? get_current_screen() : null;
			if ( $screen && $screen->post_type === 'blogfoel-hf-builder' ) {
				$post_id = get_the_ID();
				if ( $post_id ) {
					$selected_template = get_post_meta( $post_id, 'blogfoel_display_condition', true );
					if ( is_array( $selected_template ) && array_intersect( [ 'singlePost', 'blogArchive', 'search' ], $selected_template ) ) {
						$custom_categories['blogfoel-sng-elementor'] = [
							'title' => __('BlogNews Single Elementor', 'blognews-for-elementor'),
							'icon'  => 'fas fa-plug',
						];
					}
				}
			}

			// Merge categories at the top
			$existing_categories = $elements_manager->get_categories();
			$new_categories = array_merge( $custom_categories, $existing_categories );

			// Override Elementor's internal category list
			$set_categories = function( $categories ) {
				$this->categories = $categories;
			};
			$set_categories->call( $elements_manager, $new_categories );
		}
		/** Function for Font Awesome 5, Social Icons, Icon List */
		public function blogfoel_enqueue_font_awesome() {

			if ( class_exists( 'Elementor\Plugin' ) ) {

				// Ensure Elementor Icons CSS is loaded.
				wp_enqueue_style(
					'blogfoel-elementor-icons',
					plugins_url( '/elementor/assets/lib/eicons/css/elementor-icons.min.css', 'elementor' ),
					[],
					'5.34.0'
				);
				wp_enqueue_style(
					'blogfoel-icons-list',
					plugins_url( '/elementor/assets/css/widget-icon-list.min.css', 'elementor' ),
					[],
					'3.24.3'
				);
				wp_enqueue_style(
					'blogfoel-social-icons',
					plugins_url( '/elementor/assets/css/widget-social-icons.min.css', 'elementor' ),
					[],
					'3.24.0'
				);
				wp_enqueue_style(
					'blogfoel-social-share-icons-brands',
					plugins_url( '/elementor/assets/lib/font-awesome/css/brands.css', 'elementor' ),
					[],
					'5.15.3'
				);

				wp_enqueue_style(
					'blogfoel-social-share-icons-fontawesome',
					plugins_url( '/elementor/assets/lib/font-awesome/css/fontawesome.css', 'elementor' ),
					[],
					'5.15.3'
				);
				wp_enqueue_style(
					'blogfoel-nav-menu-icons',
					plugins_url( '/elementor/assets/lib/font-awesome/css/solid.css', 'elementor' ),
					[],
					'5.15.3'
				);
			}
		}
		public function blogfoel_block_styles_support() {
			add_theme_support( 'wp-block-styles' );
		}
		public function blogfoel_elementor_sitesetting() {
            $kit_id = get_option('elementor_active_kit');
            
            if ($kit_id) {
                $settings = get_post_meta($kit_id, '_elementor_page_settings', true);
                
                // Only set if not already customized by user
                if (!is_array($settings)) {
                    $settings = [];
                }
                
                // Set defaults (users can still override per page/section)
                if (empty($settings['container_width'])) {
                    $settings['container_width'] = ['unit' => 'px', 'size' => 1320, 'sizes' => []];
                }
                
                if (empty($settings['boxed_width'])) {
                    $settings['boxed_width'] = ['unit' => 'px', 'size' => 1320, 'sizes' => []];
                }
                
                update_post_meta($kit_id, '_elementor_page_settings', $settings);
            }
            
            // Set global defaults
            if (get_option('elementor_container_width') == '1170' || !get_option('elementor_container_width')) {
                update_option('elementor_container_width', '1320');
            }
            
            if (get_option('elementor_stretched_section_container') == '1170' || !get_option('elementor_stretched_section_container')) {
                update_option('elementor_stretched_section_container', '1320');
            }
        }
        
        public function blogfoel_elementor_set_var() {
            $kit_id = get_option( 'elementor_active_kit' );
            $container_width = 1140; // default value
            $container_unit  = 'px'; // default unit

            if ( $kit_id ) {
                $settings = get_post_meta( $kit_id, '_elementor_page_settings', true );
                if ( isset( $settings['container_width'] ) ) {
                    // width may be stored as number or array with unit
                    if ( is_array( $settings['container_width'] ) ) {
                        $container_width = intval( $settings['container_width']['size'] ?? 1140 );
                        $container_unit  = $settings['container_width']['unit'] ?? 'px';
                    } else {
                        $container_width = intval( $settings['container_width'] );
                    }
                }
            }

            echo '<style>
                .e-con {
                    --container-max-width: ' . $container_width . $container_unit . ';
                }
            </style>';
        }
	}
	BLOG_FOEL::get_instance();
}