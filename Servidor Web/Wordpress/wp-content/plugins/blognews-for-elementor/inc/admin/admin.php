<?php namespace BlogFoel;

// Admin Page
class BlogNews_Admin {

    const NONCE_ACTION = 'blogfoel_admin_nonce_check';
    const NONCE_NAME   = 'blogfoel_admin_nonce';

    public function __construct() {

        // Add admin page
        add_action('admin_menu', [$this, 'blogfoel_admin_page']);

        // Remove all third party notices and enqueue style and script
        add_action('admin_enqueue_scripts', [$this, 'admin_script_n_style'], 9999);

        add_action('wp_ajax_admin_install_plug', array($this, 'install_plug_ajax'));

        add_action('wp_ajax_blogfoel_create_temp_type', array($this, 'blogfoel_create_temp_type'));

        add_action('wp_ajax_blogfoel_actions', array($this, 'blogfoel_actions'));
        
        add_action( 'admin_init',  [ $this, 'blogfoel_register_settings' ]   );

    }

    public function blogfoel_admin_page() {
        $site_favi_icon = BLOGFOEL_DIR_URL .'/assets/images/siteicon.png';
        $customMenu = add_menu_page('blognews-for-elementor','BlogNews','manage_options','blognews_admin_menu',[$this, 'blogfoel_admin_page_content'], $site_favi_icon ,30 );
        
    }

    function blogfoel_register_settings() { 
        foreach ($this->blogfoel_load_widgets() as $widgets) {
            foreach ($widgets['widgets'] as $widget_setting) { 
                $widget_id = $widget_setting['widgetcls'];
                register_setting( 'blogfoel_widget_settings', $widget_id, [ 'default' => true ] );
            }
        }
        
        register_setting( 'blogfoel_widget_settings', 'blogfoel_all_widget_setting' );
        
    }

    public function admin_script_n_style() {
      $screen = get_current_screen();
      if (isset( $screen->base ) && $screen->base == 'toplevel_page_blognews_admin_menu') {

        // If Elementor is not loaded, don't load admin scripts
        if (!did_action('elementor/loaded')) {
            remove_all_actions('admin_notices');
            return; // Don't load other assets
        }

        remove_all_actions('admin_notices');
        wp_enqueue_script('blogfoel-admin', BLOGFOEL_DIR_URL . '/assets/js/admin.js', array('jquery'), BLOGFOEL_VERSION, true);

        wp_localize_script(
            'blogfoel-admin',
            'admin_ajax_obj', 
            array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce'    => wp_create_nonce(self::NONCE_ACTION),
            )
        );

        wp_enqueue_style('blogfoel-admin-styles', BLOGFOEL_DIR_URL . 'assets/css/admin.css', array(), BLOGFOEL_VERSION);
        wp_enqueue_style('elementor-editor-icons', BLOGFOEL_DIR_URL . 'assets/css/editor.css', null, BLOGFOEL_VERSION);

        // Add Gooogle Font
        wp_enqueue_style( 
            'admin-google-fonts', 
            'https://fonts.googleapis.com/css2?family=DM+Sans:ital,opsz,wght@0,9..40,100..1000;1,9..40,100..1000&display=swap',
            [], 
            BLOGFOEL_VERSION
        );

        add_filter('admin_footer_text', [$this, 'blogfoel_admin_footer_text']);
      }
    }

    function blogfoel_admin_footer_text() {
        return 'Enjoyed <span class="blogfoel-footer-thankyou"><strong>BlogNews Elementor</strong>? Please leave us a <a href="https://wordpress.org/plugins/blognews-for-elementor/" target="_blank">★★★★★</a></span> rating. We really appreciate your support!';
    }

    function blogfoel_load_widgets(){
      
        $widgets = [
            "blogfoel-hf-elementor" => array(
                "widget_cat" => "Header Footer Elements",
                "widgets" => array(
                    array( 'type' => 'lite', 'icon' => 'bnicon-logo', 'name' => 'Site Logo', 'doc' => 'https://docs.themeansar.com/docs/blognews-for-elementor/header-footer-widgets/site-logo/', 'demo' => 'https://blognews.themeansar.com/menu' , 'widgetcls' => 'BLOGFOELSiteLogo', 'label' => 'Header', 'label2' => 'Footer' ),
                    array( 'type' => 'lite', 'icon' => 'bnicon-site-title', 'name' => 'Site Title', 'doc' => 'https://docs.themeansar.com/docs/blognews-for-elementor/header-footer-widgets/site-title/', 'demo' => 'https://blognews.themeansar.com/menu' , 'widgetcls' => 'BLOGFOELSiteTitle', 'label' => 'Header', 'label2' => 'Footer' ),
                    array( 'type' => 'lite', 'icon' => 'bnicon-site-tagline', 'name' => 'Site Tagline', 'doc' => 'https://docs.themeansar.com/docs/blognews-for-elementor/header-footer-widgets/site-tagline/', 'demo' => 'https://blognews.themeansar.com/menu' , 'widgetcls' => 'BLOGFOELSiteTagline', 'label' => 'Header', 'label2' => 'Footer' ),
                    array( 'type' => 'lite', 'icon' => 'bnicon-copyright', 'name' => 'Copyright', 'doc' => 'https://docs.themeansar.com/docs/blognews-for-elementor/header-footer-widgets/copyright/', 'demo' => 'https://blognews.themeansar.com/menu' , 'widgetcls' => 'BLOGFOELCopyright', 'label' => 'Footer' ),
                    array( 'type' => 'lite', 'icon' => 'bnicon-search', 'name' => 'Advanced Search', 'doc' => 'https://docs.themeansar.com/docs/blognews-for-elementor/header-footer-widgets/search/', 'demo' => 'https://blognews.themeansar.com/menu' , 'widgetcls' => 'BLOGFOELSearch', 'label' => 'Header', 'label2' => 'General' ),
                    array( 'type' => 'lite', 'icon' => 'bnicon-menus', 'name' => 'Menus', 'doc' => 'https://docs.themeansar.com/docs/blognews-for-elementor/header-footer-widgets/menu/', 'demo' => 'https://blognews.themeansar.com/menu' , 'widgetcls' => 'BLOGFOELNavMenu', 'label' => 'Header', 'label2' => 'General' ),
                    array( 'type' => 'lite', 'icon' => 'bnicon-date-and-time', 'name' => 'Date & Time', 'doc' => 'https://docs.themeansar.com/docs/blognews-for-elementor/header-footer-widgets/date-time/', 'demo' => 'https://blognews.themeansar.com/menu' , 'widgetcls' => 'BLOGFOELDateTime', 'label' => 'Header', 'label2' => 'General' ),
                    array( 'type' => 'pro', 'icon' => 'bnicon-breadcrumb', 'name' => 'Breadcrumb', 'doc' => 'https://docs.themeansar.com/docs/blognews-for-elementor/header-footer-widgets/breadcrumb/', 'demo' => 'https://blognews.themeansar.com/menu' , 'widgetcls' => 'BLOGFOELBreadcrumb', 'label' => 'Pages' ),
                    array( 'type' => 'lite', 'icon' => 'bnicon-scroll-to-top', 'name' => 'Scroll To Top', 'doc' => 'https://docs.themeansar.com/docs/blognews-for-elementor/header-footer-widgets/scroll-to-top/', 'demo' => 'https://blognews.themeansar.com/menu' , 'widgetcls' => 'BLOGFOELScrollToTop', 'label' => 'Footer' ),

                    array( 'type' => 'lite', 'icon' => 'bnicon-light-dark-toggle', 'name' => 'Light & Dark Toggle', 'doc' => 'https://docs.themeansar.com/docs/blognews-for-elementor/header-footer-widgets/', 'demo' => 'https://blognews.themeansar.com/menu/' , 'widgetcls' => 'BLOGFOELLightDark', 'label' => 'Header' ),
                )
            ),
            "blogfoel-elementor" => array(
                "widget_cat" => "Blog Elements",
                "widgets" => array(
                    array( 'type' => 'lite', 'icon' => 'bnicon-post-list-1','name' => 'Post List 1 ', 'doc' => 'https://docs.themeansar.com/docs/blognews-for-elementor/exclusive-blog-widgets/post-list-1/', 'demo' => 'https://blognews.themeansar.com/post-list-1/' , 'widgetcls' => 'BLOGFOELPostList1', 'label' => 'Frontpage' ),
                    array( 'type' => 'lite', 'icon' => 'bnicon-post-list-2','name' => 'Post List 2 ', 'doc' => 'https://docs.themeansar.com/docs/blognews-for-elementor/exclusive-blog-widgets/post-list-2/', 'demo' => 'https://blognews.themeansar.com/post-list-2/' , 'widgetcls' => 'BLOGFOELPostList2', 'label' => 'Frontpage' ),
                    array( 'type' => 'pro', 'icon' => 'bnicon-post-blog-slider','name' => 'Post Blog Slider', 'doc' => 'https://docs.themeansar.com/docs/blognews-for-elementor/exclusive-blog-widgets/post-blog-slider/', 'demo' => 'https://blognews.themeansar.com/post-blog-slider/' , 'widgetcls' => 'BLOGFOELPostBlogSlider', 'label' => 'Frontpage' ), 
                    array( 'type' => 'lite', 'icon' => 'bnicon-ticker','name' => 'Post Ticker ', 'doc' => 'https://docs.themeansar.com/docs/blognews-for-elementor/exclusive-blog-widgets/post-ticker/', 'demo' => 'https://blognews.themeansar.com/ticker/' , 'widgetcls' => 'BLOGFOELPostTicket', 'label' => 'Header', 'label2' => 'Frontpage' ),
                    array( 'type' => 'lite', 'icon' => 'bnicon-post-blog-1','name' => 'Post Blog 1 ', 'doc' => 'https://docs.themeansar.com/docs/blognews-for-elementor/exclusive-blog-widgets/post-blog-1/', 'demo' => 'https://blognews.themeansar.com/post-blog-1/' , 'widgetcls' => 'BLOGFOELPostBlog1', 'label' => 'Frontpage', 'label2' => 'Sidebar' ),
                    array( 'type' => 'lite', 'icon' => 'bnicon-post-blog-2','name' => 'Post Blog 2 ', 'doc' => 'https://docs.themeansar.com/docs/blognews-for-elementor/exclusive-blog-widgets/post-blog-2/', 'demo' => 'https://blognews.themeansar.com/post-blog-2/' , 'widgetcls' => 'BLOGFOELPostBlog2', 'label' => 'Header', 'label2' => 'Frontpage' ),
                    array( 'type' => 'lite', 'icon' => 'bnicon-post-grid-1','name' => 'Post Grid 1 ', 'doc' => 'https://docs.themeansar.com/docs/blognews-for-elementor/exclusive-blog-widgets/post-grid-1/', 'demo' => 'https://blognews.themeansar.com/post-grid-1/' , 'widgetcls' => 'BLOGFOELPostGrid1', 'label' => 'Frontpage' ),
                    array( 'type' => 'lite', 'icon' => 'bnicon-post-grid-2','name' => 'Post Grid 2', 'doc' => 'https://docs.themeansar.com/docs/blognews-for-elementor/exclusive-blog-widgets/post-grid-2/', 'demo' => 'https://blognews.themeansar.com/post-grid-2/' , 'widgetcls' => 'BLOGFOELPostGrid2', 'label' => 'Frontpage' ),
                    array( 'type' => 'pro', 'icon' => 'bnicon-post-grid-3','name' => 'Post Grid 3', 'doc' => 'https://docs.themeansar.com/docs/blognews-for-elementor/exclusive-blog-widgets/post-grid-3/', 'demo' => 'https://blognews.themeansar.com/post-grid-3/' , 'widgetcls' => 'BLOGFOELPostGrid3', 'label' => 'Frontpage' ),
                    array( 'type' => 'pro', 'icon' => 'bnicon-post-grid-4','name' => 'Post Grid 4', 'doc' => 'https://docs.themeansar.com/docs/blognews-for-elementor/exclusive-blog-widgets/post-grid-4/', 'demo' => 'https://blognews.themeansar.com/post-grid-4/' , 'widgetcls' => 'BLOGFOELPostGrid4', 'label' => 'Frontpage' ),
                    array( 'type' => 'pro', 'icon' => 'bnicon-post-grid-5','name' => 'Post Grid 5', 'doc' => 'https://docs.themeansar.com/docs/blognews-for-elementor/exclusive-blog-widgets/post-grid-5/', 'demo' => 'https://blognews.themeansar.com/post-grid-5/' , 'widgetcls' => 'BLOGFOELPostGrid5', 'label' => 'Frontpage' ),
                    array( 'type' => 'lite', 'icon' => 'bnicon-heading','name' => 'Heading ', 'doc' => 'https://docs.themeansar.com/docs/blognews-for-elementor/exclusive-blog-widgets/heading/', 'demo' => 'https://blognews.themeansar.com/heading/' , 'widgetcls' => 'BLOGFOELHeading', 'label' => 'General' ),

                    array( 'type' => 'lite', 'icon' => 'bnicon-taxonomy','name' => 'Taxonomy ', 'doc' => 'https://docs.themeansar.com/docs/blognews-for-elementor/exclusive-blog-widgets/taxonomy/', 'demo' => 'https://blognews.themeansar.com/taxonomy/' , 'widgetcls' => 'BLOGFOELTaxonomy', 'label' => 'Frontpage', 'label2' => 'Sidebar' ),

                    array( 'type' => 'lite', 'icon' => 'bnicon-button','name' => 'Creative Button', 'doc' => 'https://docs.themeansar.com/docs/blognews-for-elementor/exclusive-blog-widgets/creative-button/', 'demo' => 'https://blognews.themeansar.com/creative-button/' , 'widgetcls' => 'BLOGFOELCreativeButton', 'label' => 'General' ),
                    array( 'type' => 'lite', 'icon' => 'bnicon-filter-tab-1', 'name' => 'Filter Tab Post 1', 'doc' => 'https://docs.themeansar.com/docs/blognews-for-elementor/exclusive-blog-widgets/filter-tab-post-1/', 'demo' => 'https://blognews.themeansar.com/filter-tab/' , 'widgetcls' => 'BLOGFOELFilterTabPost', 'label' => 'Frontpage' ),
                    array( 'type' => 'pro', 'icon' => 'bnicon-filter-tab-2','name' => 'Filter Tab Post 2', 'doc' => 'https://docs.themeansar.com/docs/blognews-for-elementor/exclusive-blog-widgets/filter-tab-post-2/', 'demo' => 'https://blognews.themeansar.com/filter-tab-post/' , 'widgetcls' => 'BLOGFOELFiterTabs2', 'label' => 'Frontpage' ),
                    array( 'type' => 'lite', 'icon' => 'bnicon-post-carousel-1','name' => 'Post Carousel 1', 'doc' => 'https://docs.themeansar.com/docs/blognews-for-elementor/exclusive-blog-widgets/post-carousel-1/', 'demo' => 'https://blognews.themeansar.com/post-carousel-1/' , 'widgetcls' => 'BLOGFOELPostCarousel1', 'label' => 'Frontpage' ),
                    array( 'type' => 'pro', 'icon' => 'bnicon-post-carousel-2','name' => 'Post Carousel 2', 'doc' => 'https://docs.themeansar.com/docs/blognews-for-elementor/exclusive-blog-widgets/post-carousel-2/', 'demo' => 'https://blognews.themeansar.com/post-carousel-2/' , 'widgetcls' => 'BLOGFOELPostCarousel2', 'label' => 'Frontpage' ),
                    array( 'type' => 'pro', 'icon' => 'bnicon-featured-post','name' => 'Featured Post', 'doc' => 'https://docs.themeansar.com/docs/blognews-for-elementor/exclusive-blog-widgets/featured-post/', 'demo' => 'https://blognews.themeansar.com/featured-post/' , 'widgetcls' => 'BLOGFOELFeaturedPost', 'label' => 'Frontpage' ),
                    array( 'type' => 'pro', 'icon' => 'bnicon-express-post','name' => 'Express Post', 'doc' => 'https://docs.themeansar.com/docs/blognews-for-elementor/exclusive-blog-widgets/express-post/', 'demo' => 'https://blognews.themeansar.com/express-post/' , 'widgetcls' => 'BLOGFOELExpressPost', 'label' => 'Frontpage' ),
                    array( 'type' => 'pro', 'icon' => 'bnicon-featured-list','name' => 'Featured List', 'doc' => 'https://docs.themeansar.com/docs/blognews-for-elementor/exclusive-blog-widgets/featured-list/', 'demo' => 'https://blognews.themeansar.com/featured-list/' , 'widgetcls' => 'BLOGFOELFeaturedList', 'label' => 'Frontpage', 'label2' => 'Sidebar' ),
                    array( 'type' => 'pro', 'icon' => 'bnicon-offcanvas','name' => 'Off Canvas', 'doc' => 'https://docs.themeansar.com/docs/blognews-for-elementor/exclusive-blog-widgets/off-canvas/', 'demo' => 'https://blognews.themeansar.com/off-canvas/' , 'widgetcls' => 'BLOGFOELOffCanvas', 'label' => 'Header', 'label2' => 'Frontpage' ),
                    array( 'type' => 'pro', 'icon' => 'bnicon-template','name' => 'Template', 'doc' => 'https://docs.themeansar.com/docs/blognews-for-elementor/exclusive-blog-widgets/template/', 'demo' => '#demolink' , 'widgetcls' => 'BLOGFOELTemplate', 'label' => 'General' ),
                    array( 'type' => 'pro', 'icon' => 'bnicon-accordion','name' => 'Post Accordion', 'doc' => 'https://docs.themeansar.com/docs/blognews-for-elementor/exclusive-blog-widgets/post-accordion/', 'demo' => 'https://blognews.themeansar.com/post-accordian/' , 'widgetcls' => 'BLOGFOELPostAccordion', 'label' => 'Frontpage' ),
                )
            ),

            "blogfoel-sng-elementor" => array(
                "widget_cat" => "Archive/Single Post Elements",
                "widgets" => array(
                    array( 'type' => 'lite', 'icon' => 'bnicon-archive-post', 'name' => 'Archive Post', 'doc' => 'https://docs.themeansar.com/docs/blognews-for-elementor/archive-page/archive-post/', 'demo' => '#demolink' , 'widgetcls' => 'BLOGFOELArchivePost', 'label' => 'Archive','label2' => 'Search' ),
            
                    array( 'type' => 'lite', 'icon' => 'bnicon-archive-post-list', 'name' => 'Archive Post List', 'doc' => 'https://docs.themeansar.com/docs/blognews-for-elementor/archive-page/archive-post-list/', 'demo' => '#demolink' , 'widgetcls' => 'BLOGFOELArchivePostList', 'label' => 'Archive','label2' => 'Search' ),
            
                    array( 'type' => 'lite', 'icon' => 'bnicon-archive-title', 'name' => 'Archive Title', 'doc' => 'https://docs.themeansar.com/docs/blognews-for-elementor/archive-page/archive-title/', 'demo' => '#demolink' , 'widgetcls' => 'BLOGFOELArchiveTitle', 'label' => 'Archive','label2' => 'Search' ),

                    array( 'type' => 'pro', 'icon' => 'bnicon-archive-description', 'name' => 'Archive Description', 'doc' => 'https://docs.themeansar.com/docs/blognews-for-elementor/archive-page/archive-description/', 'demo' => '#demolink' , 'widgetcls' => 'BLOGFOELArchiveDescription', 'label' => 'Archive' ),
            
                    array( 'type' => 'lite', 'icon' => 'bnicon-post-title', 'name' => 'Post Title', 'doc' => 'https://docs.themeansar.com/docs/blognews-for-elementor/single-page/post-title/', 'demo' => '#demolink' , 'widgetcls' => 'BLOGFOELPostTitle', 'label' => 'Single' ),
            
                    array( 'type' => 'lite', 'icon' => 'bnicon-post-description', 'name' => 'Post Description', 'doc' => 'https://docs.themeansar.com/docs/blognews-for-elementor/single-page/post-description/', 'demo' => '#demolink' , 'widgetcls' => 'BLOGFOELPostDescription', 'label' => 'Single' ),
            
                    array( 'type' => 'lite', 'icon' => 'bnicon-post-categories', 'name' => 'Post Cateogries', 'doc' => 'https://docs.themeansar.com/docs/blognews-for-elementor/single-page/post-categories/', 'demo' => '#demolink' , 'widgetcls' => 'BLOGFOELPostCategories', 'label' => 'Single' ),
            
                    array( 'type' => 'lite', 'icon' => 'bnicon-post-tag', 'name' => 'Post Tags', 'doc' => 'https://docs.themeansar.com/docs/blognews-for-elementor/single-page/post-tags/', 'demo' => '#demolink' , 'widgetcls' => 'BLOGFOELPostTags', 'label' => 'Single' ),
            
                    array( 'type' => 'lite', 'icon' => 'bnicon-post-featured-image', 'name' => 'Post Image', 'doc' => 'https://docs.themeansar.com/docs/blognews-for-elementor/single-page/post-featured-image/', 'demo' => '#demolink' , 'widgetcls' => 'BLOGFOELPostImage', 'label' => 'Single' ),
            
                    array( 'type' => 'lite', 'icon' => 'bnicon-advanced-post-meta', 'name' => 'Post Meta', 'doc' => 'https://docs.themeansar.com/docs/blognews-for-elementor/single-page/post-meta/', 'demo' => '#demolink' , 'widgetcls' => 'BLOGFOELPostMeta', 'label' => 'Single' ),
            
                    array( 'type' => 'lite', 'icon' => 'bnicon-post-pagination', 'name' => 'Post Pagination', 'doc' => 'https://docs.themeansar.com/docs/blognews-for-elementor/single-page/post-pagination/', 'demo' => '#demolink' , 'widgetcls' => 'BLOGFOELPostPagination', 'label' => 'Single' ),
            
                    array( 'type' => 'lite', 'icon' => 'bnicon-post-share-icon', 'name' => 'Post Share Icons', 'doc' => 'https://docs.themeansar.com/docs/blognews-for-elementor/single-page/post-share-icons/', 'demo' => '#demolink' , 'widgetcls' => 'BLOGFOELPostShareIcons', 'label' => 'Single' ),
            
                    array( 'type' => 'lite', 'icon' => 'bnicon-post-author', 'name' => 'Post Author', 'doc' => 'https://docs.themeansar.com/docs/blognews-for-elementor/single-page/post-author/', 'demo' => '#demolink' , 'widgetcls' => 'BLOGFOELPostAuthor', 'label' => 'Single' ),
                    
                    array( 'type' => 'lite', 'icon' => 'bnicon-post-comments', 'name' => 'Post Comments', 'doc' => 'https://docs.themeansar.com/docs/blognews-for-elementor/single-page/post-comments/', 'demo' => '#demolink' , 'widgetcls' => 'BLOGFOELPostComments', 'label' => 'Single' ),
                    
                    array( 'type' => 'pro', 'icon' => 'bnicon-related-post', 'name' => 'Related Post', 'doc' => 'https://docs.themeansar.com/docs/blognews-for-elementor/single-page/related-post/', 'demo' => '#demolink' , 'widgetcls' => 'BLOGFOELRelatedPost', 'label' => 'Single' ),
                )
            ),
        ];
    
      return $widgets;
    } 
    public function blogfoel_admin_page_content() {

        // Check if Elementor is loaded - if not, show notice and exit
        if (!did_action('elementor/loaded')) {
            $this->display_elementor_dependency_notice();
            return; // Don't show the rest of the admin page
        }
        
        $blogfoel_load_widgets = $this->blogfoel_load_widgets();
        // Query arguments
        $args = array(
            'post_type'      => 'blogfoel-hf-builder', // Specify the custom post type
            'posts_per_page' => -1,                // Get all posts
            'post_status'    => 'any',         // Only published posts
        );

        // The Query
        $query = new \WP_Query($args); ?>

        <div class="blogfoel-page-content">
            <div class="blogfoel-tabbed">
                <?php
                    // phpcs:ignore WordPress.Security.NonceVerification.Recommended
                    $active_tab = isset( $_GET['tab'] ) ? sanitize_text_field( wp_unslash( $_GET['tab'] ) ) : 'welcome';
                ?>
                <input type="radio" id="tab1" name="css-tabs" <?php checked( $active_tab, 'welcome' ); ?> >
                <input type="radio" id="tab2" name="css-tabs" <?php checked( $active_tab, 'widgets' ); ?> >
                <input type="radio" id="tab3" name="css-tabs" <?php checked( $active_tab, 'site-builder' ); ?> >
                <input type="radio" id="tab4" name="css-tabs" <?php checked( $active_tab, 'starter-sites' ); ?> >
                <div class="blogfoel-head-top-items">
                    <div class="blogfoel-head-item">
                        <a href="<?php echo esc_url( add_query_arg( [ 'tab'   => 'welcome'] ) ); ?>" class="blogfoel-site-icon"><img src=<?php echo esc_url(BLOGFOEL_DIR_URL) .'/assets/images/mainlogo.png'; ?>  alt="mainlogo"></a>
                    </div>
                    <ul class="blogfoel-tabs">
                        <li class="blogfoel-tab">
                            <label for="tab1">
                            <a href="<?php echo esc_url( add_query_arg( [ 'tab'   => 'welcome'] ) ); ?>">
                                <?php esc_attr_e('Dashboard','blognews-for-elementor'); ?>
                            </a>
                            </label>
                        </li>
                        <li class="blogfoel-tab">
                            <label for="tab2">
                            <a href="<?php echo esc_url( add_query_arg( [ 'tab'   => 'widgets'] ) ); ?>">
                                <?php esc_attr_e('Widgets','blognews-for-elementor'); ?>
                            </a>
                            </label>
                        </li>
                        <li class="blogfoel-tab">
                            <label for="tab3">
                            <a href="<?php echo esc_url( add_query_arg( [ 'tab'   => 'site-builder'] ) ); ?>">
                                <?php esc_attr_e('Site Builder','blognews-for-elementor'); ?>
                            </a>
                            </label>
                        </li>
                        <li class="blogfoel-tab">
                            <label for="tab4">
                            <a href="<?php echo esc_url( add_query_arg( [ 'tab'   => 'starter-sites'] ) ); ?>">
                                <?php esc_attr_e('Starter Sites','blognews-for-elementor'); ?>
                            </a>
                            </label>
                        </li>
                    </ul>
                    <div class="blogfoel-right-top-area">
                        <div class="blogfoel-ask-help">            
                            <div class="blogfoel-ask-icon">
                                <svg class="svg-inline--fa fa-circle-question" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="circle-question" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM169.8 165.3c7.9-22.3 29.1-37.3 52.8-37.3h58.3c34.9 0 63.1 28.3 63.1 63.1c0 22.6-12.1 43.5-31.7 54.8L280 264.4c-.2 13-10.9 23.6-24 23.6c-13.3 0-24-10.7-24-24V250.5c0-8.6 4.6-16.5 12.1-20.8l44.3-25.4c4.7-2.7 7.6-7.7 7.6-13.1c0-8.4-6.8-15.1-15.1-15.1H222.6c-3.4 0-6.4 2.1-7.5 5.3l-.4 1.2c-4.4 12.5-18.2 19-30.6 14.6s-19-18.2-14.6-30.6l.4-1.2zM224 352a32 32 0 1 1 64 0 32 32 0 1 1 -64 0z"></path></svg>
                            </div>                            
                            <a href="https://themeansar.ticksy.com/" target="_blank" class="blogfoel-btn-link">Help </a>
                        </div>
                        <div class="blogfoel-ask-help">            
                            <div class="blogfoel-ask-icon">
                                <svg class="svg-inline--fa fa-book" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="book" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg=""><path fill="currentColor" d="M96 0C43 0 0 43 0 96V416c0 53 43 96 96 96H384h32c17.7 0 32-14.3 32-32s-14.3-32-32-32V384c17.7 0 32-14.3 32-32V32c0-17.7-14.3-32-32-32H384 96zm0 384H352v64H96c-17.7 0-32-14.3-32-32s14.3-32 32-32zm32-240c0-8.8 7.2-16 16-16H336c8.8 0 16 7.2 16 16s-7.2 16-16 16H144c-8.8 0-16-7.2-16-16zm16 48H336c8.8 0 16 7.2 16 16s-7.2 16-16 16H144c-8.8 0-16-7.2-16-16s7.2-16 16-16z"></path></svg>
                            </div>                            
                            <a href="https://docs.themeansar.com/docs/blognews-for-elementor/" target="_blank" class="blogfoel-btn-link">Docs </a>
                        </div>
                        <div class="blogfoel-feature-pro">
                            <a href="https://blognews.themeansar.com/pricing/" target="_blank">
                                <span class="head-icon"><svg
                                        xmlns="http://www.w3.org/2000/svg" width="800px" height="800px" viewBox="0 0 24 24"
                                        fill="none" style="fill: #fff;">
                                        <path
                                            d="M19.6872 14.0931L19.8706 12.3884C19.9684 11.4789 20.033 10.8783 19.9823 10.4999L20 10.5C20.8284 10.5 21.5 9.82843 21.5 9C21.5 8.17157 20.8284 7.5 20 7.5C19.1716 7.5 18.5 8.17157 18.5 9C18.5 9.37466 18.6374 9.71724 18.8645 9.98013C18.5384 10.1814 18.1122 10.606 17.4705 11.2451L17.4705 11.2451C16.9762 11.7375 16.729 11.9837 16.4533 12.0219C16.3005 12.043 16.1449 12.0213 16.0038 11.9592C15.7492 11.847 15.5794 11.5427 15.2399 10.934L13.4505 7.7254C13.241 7.34987 13.0657 7.03557 12.9077 6.78265C13.556 6.45187 14 5.77778 14 5C14 3.89543 13.1046 3 12 3C10.8954 3 10 3.89543 10 5C10 5.77778 10.444 6.45187 11.0923 6.78265C10.9343 7.03559 10.759 7.34984 10.5495 7.7254L8.76006 10.934C8.42056 11.5427 8.25081 11.847 7.99621 11.9592C7.85514 12.0213 7.69947 12.043 7.5467 12.0219C7.27097 11.9837 7.02381 11.7375 6.5295 11.2451C5.88787 10.606 5.46156 10.1814 5.13553 9.98012C5.36264 9.71724 5.5 9.37466 5.5 9C5.5 8.17157 4.82843 7.5 4 7.5C3.17157 7.5 2.5 8.17157 2.5 9C2.5 9.82843 3.17157 10.5 4 10.5L4.01771 10.4999C3.96702 10.8783 4.03162 11.4789 4.12945 12.3884L4.3128 14.0931C4.41458 15.0393 4.49921 15.9396 4.60287 16.75H19.3971C19.5008 15.9396 19.5854 15.0393 19.6872 14.0931Z"
                                            fill="#1C274C" style="&#10;    fill: #fff;&#10;" />
                                        <path
                                            d="M10.9121 21H13.0879C15.9239 21 17.3418 21 18.2879 20.1532C18.7009 19.7835 18.9623 19.1172 19.151 18.25H4.84896C5.03765 19.1172 5.29913 19.7835 5.71208 20.1532C6.65817 21 8.07613 21 10.9121 21Z"
                                            fill="#1C274C" style="&#10;    fill: #fff;&#10;" />
                                    </svg>
                                </span><?php esc_attr_e('Upgrade To Pro','blognews-for-elementor'); ?>
                            </a>
                        </div>
                    </div>
                </div>
                <div class="blogfoel-main-area">
                    <div class="blogfoel-tab-contents">
                        <div class="blogfoel-tab-content blogfoel-welcome">
                            <div class="blogfoel-getstart d-grid column4 gap-30">
                                <!--  -->
                                <div class="blogfoel-getstart-inner col-span-4">
                                    <div class="blogfoel-wrapper first">
                                        <div class="blogfoel-getstart-content">
                                                <h1 class="blogfoel-content-title"><?php esc_attr_e('Welcome, ','blognews-for-elementor');   $current_user = wp_get_current_user();
                                                echo esc_html( $current_user->display_name );?></h1>
                                                <p class="blogfoel-content-description"><?php esc_attr_e("Thank you for installing BlogNews for Elementor — your all-in-one solution for building stunning News, Magazine, and Blog websites with Elementor!. With BlogNews for Elementor plugin , you can easily design eye-catching layouts using our collection of powerful widgets.",'blognews-for-elementor'); ?></p>
                                                    <ul class="blogfoel-features">
                                                        <p>
                                                            <img src="<?php echo esc_url(BLOGFOEL_DIR_URL) . 'assets/images/features.svg' ?>"><?php esc_attr_e("Key Features:","blognews-for-elementor" ); ?>
                                                        </p>
                                                        <li> <?php esc_attr_e("Ready-made news and blog widgets for Elementor","blognews-for-elementor"); ?> </li>
                                                        <li> <?php esc_attr_e("Beautiful demo templates you can import with one click","blognews-for-elementor"); ?> </li>
                                                        <li><?php esc_attr_e("Site Builder tools to create custom Headers, Footers, and Single Post layouts","blognews-for-elementor"); ?> </li>
                                                        <li> <?php esc_attr_e("100% responsive and optimized for performance","blognews-for-elementor"); ?></li>
                                                        <li><?php  esc_attr_e("Fully compatible with any Elementor-based WordPress theme.","blognews-for-elementor");?></li>
                                                    </ul>
                                                <a href="<?php echo esc_url( add_query_arg( [ 'tab'   => 'site-builder'] ) ); ?>" class="blogfoel-content-btn"><?php esc_attr_e('Start Building','blognews-for-elementor'); ?></a>
                                                <a href="<?php 
                                                        if ( is_plugin_active( 'ansar-import/ansar-import.php' ) ) {
                                                            echo esc_url( admin_url( 'admin.php?page=ansar-plugin-demos' ) );
                                                        } else{
                                                            echo esc_url( add_query_arg( [ 'tab'   => 'starter-sites'] ) );
                                                        }
                                                 ?>" class="blogfoel-content-btn blogfoel-btn-secondary"><?php esc_attr_e('Starter Sites','blognews-for-elementor'); ?></a>
                                        </div>
                                        <div class="blogfoel-getstart-image">
                                                <img src="<?php echo esc_url(BLOGFOEL_DIR_URL) . 'assets/images/blognews-image.png' ?>">
                                        </div>
                                    </div>
                                    <div class="blogfoel-center-area d-grid column3 align-start">
                                        <div class="blogfoel-center-box">  
                                            <div class="d-flex justify-between align-center mb-20">
                                                <h3 class="blogfoel-center-area-title"><?php esc_attr_e('Total Widgets','blognews-for-elementor'); ?></h3>
                                                <div class="blogfoel-center-icon">
                                                    <svg class="svg-inline--fa fa-puzzle-piece" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="puzzle-piece" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M192 104.8c0-9.2-5.8-17.3-13.2-22.8C167.2 73.3 160 61.3 160 48c0-26.5 28.7-48 64-48s64 21.5 64 48c0 13.3-7.2 25.3-18.8 34c-7.4 5.5-13.2 13.6-13.2 22.8v0c0 12.8 10.4 23.2 23.2 23.2H336c26.5 0 48 21.5 48 48v56.8c0 12.8 10.4 23.2 23.2 23.2v0c9.2 0 17.3-5.8 22.8-13.2c8.7-11.6 20.7-18.8 34-18.8c26.5 0 48 28.7 48 64s-21.5 64-48 64c-13.3 0-25.3-7.2-34-18.8c-5.5-7.4-13.6-13.2-22.8-13.2v0c-12.8 0-23.2 10.4-23.2 23.2V464c0 26.5-21.5 48-48 48H279.2c-12.8 0-23.2-10.4-23.2-23.2v0c0-9.2 5.8-17.3 13.2-22.8c11.6-8.7 18.8-20.7 18.8-34c0-26.5-28.7-48-64-48s-64 21.5-64 48c0 13.3 7.2 25.3 18.8 34c7.4 5.5 13.2 13.6 13.2 22.8v0c0 12.8-10.4 23.2-23.2 23.2H48c-26.5 0-48-21.5-48-48V343.2C0 330.4 10.4 320 23.2 320v0c9.2 0 17.3 5.8 22.8 13.2C54.7 344.8 66.7 352 80 352c26.5 0 48-28.7 48-64s-21.5-64-48-64c-13.3 0-25.3 7.2-34 18.8C40.5 250.2 32.4 256 23.2 256v0C10.4 256 0 245.6 0 232.8V176c0-26.5 21.5-48 48-48H168.8c12.8 0 23.2-10.4 23.2-23.2v0z"></path></svg>
                                                </div>
                                            </div>
                                              <?php 
                                                // Calculate total widgets dynamically
                                                $total_widgets = 0;
                                                foreach($this->blogfoel_load_widgets() as $widgetCategory) {
                                                     foreach($widgetCategory['widgets'] as $widget) {
                                                        if ($widget['type'] == 'lite') { 
                                                            $total_widgets ++;
                                                        }
                                                    }
                                                }
                                            ?>
                                            <h4 class="blogfoel-center-counts"><?php echo esc_html($total_widgets); ?></h4>
                                            <p class="blogfoel-center-area-desc"><?php esc_attr_e('All available widgets','blognews-for-elementor'); ?></p>
                                            <a href="<?php echo esc_url( add_query_arg( [ 'tab'   => 'widgets'] ) ); ?>" class="blogfoel-center-link"></a>
                                        </div>
                                        <div class="blogfoel-center-box">     
                                            <div class="d-flex justify-between align-center mb-20">                                   
                                                <h3 class="blogfoel-center-area-title"><?php esc_attr_e('Active Widgets','blognews-for-elementor'); ?></h3>
                                                <div class="blogfoel-center-icon"><svg class="svg-inline--fa fa-circle-check" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="circle-check" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" data-fa-i2svg=""><path fill="currentColor" d="M256 512A256 256 0 1 0 256 0a256 256 0 1 0 0 512zM369 209L241 337c-9.4 9.4-24.6 9.4-33.9 0l-64-64c-9.4-9.4-9.4-24.6 0-33.9s24.6-9.4 33.9 0l47 47L335 175c9.4-9.4 24.6-9.4 33.9 0s9.4 24.6 0 33.9z"></path></svg>
                                                </div> 
                                            </div>
                                            <?php 
                                            // Calculate active widgets dynamically
                                            $active_widgets = 0;
                                            foreach($this->blogfoel_load_widgets() as $widgetCategory) {
                                                foreach($widgetCategory['widgets'] as $widget) {
                                                     if ($widget['type'] == 'lite') { 
                                                    $widget_id = $widget['widgetcls'];
                                                    $enabled = get_option($widget_id, true); // Default to enabled
                                                    if($enabled) {
                                                        $active_widgets++;
                                                    }
                                                } }
                                            }
                                           ?>
                                            <h4 class="blogfoel-center-counts"><?php echo esc_html($active_widgets); ?></h4>
                                            <p class="blogfoel-center-area-desc"><?php esc_attr_e('Currently in use on your site','blognews-for-elementor'); ?></p>
                                            <a href="<?php echo esc_url( add_query_arg( [ 'tab'   => 'widgets'] ) ); ?>" class="blogfoel-center-link"></a>
                                        </div>
                                        <div class="blogfoel-center-box">
                                            <div class="d-flex justify-between align-center mb-20">  
                                                <h3 class="blogfoel-center-area-title"><?php esc_attr_e('Plugin Version','blognews-for-elementor'); ?></h3>
                                                <div class="blogfoel-center-icon"><svg class="svg-inline--fa fa-code-branch" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="code-branch" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg=""><path fill="currentColor" d="M80 104a24 24 0 1 0 0-48 24 24 0 1 0 0 48zm80-24c0 32.8-19.7 61-48 73.3v87.8c18.8-10.9 40.7-17.1 64-17.1h96c35.3 0 64-28.7 64-64v-6.7C307.7 141 288 112.8 288 80c0-44.2 35.8-80 80-80s80 35.8 80 80c0 32.8-19.7 61-48 73.3V160c0 70.7-57.3 128-128 128H176c-35.3 0-64 28.7-64 64v6.7c28.3 12.3 48 40.5 48 73.3c0 44.2-35.8 80-80 80s-80-35.8-80-80c0-32.8 19.7-61 48-73.3V352 153.3C19.7 141 0 112.8 0 80C0 35.8 35.8 0 80 0s80 35.8 80 80zm232 0a24 24 0 1 0 -48 0 24 24 0 1 0 48 0zM80 456a24 24 0 1 0 0-48 24 24 0 1 0 0 48z"></path></svg>
                                                </div>
                                            </div>
                                            <h4 class="blogfoel-center-counts"><?php echo esc_html(BLOGFOEL_VERSION); ?></h4>
                                            <p class="blogfoel-center-area-desc"><span><?php esc_attr_e('Current Version','blognews-for-elementor'); ?></span></p>
                                        </div>
                                    </div>
                                </div> 
                                <div class="blogfoel-getstart-inner col-span-3">
                                    <div class="blogfoel-wrapper second">
                                        <img src=<?php echo esc_url(BLOGFOEL_DIR_URL) .'/assets/images/dashboard-banner.jpg'; ?> alt="">    
                                    </div>
                                </div> 
                                <div class="blogfoel-right-area d-grid align-start">
                                    <div class="blogfoel-right-box">
                                        <h3 class="blogfoel-right-area-title"><div class="blogfoel-right-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="800px" height="800px" viewBox="0 0 24 24" fill="none">
                                        <path d="M9.15316 5.40838C10.4198 3.13613 11.0531 2 12 2C12.9469 2 13.5802 3.13612 14.8468 5.40837L15.1745 5.99623C15.5345 6.64193 15.7144 6.96479 15.9951 7.17781C16.2757 7.39083 16.6251 7.4699 17.3241 7.62805L17.9605 7.77203C20.4201 8.32856 21.65 8.60682 21.9426 9.54773C22.2352 10.4886 21.3968 11.4691 19.7199 13.4299L19.2861 13.9372C18.8096 14.4944 18.5713 14.773 18.4641 15.1177C18.357 15.4624 18.393 15.8341 18.465 16.5776L18.5306 17.2544C18.7841 19.8706 18.9109 21.1787 18.1449 21.7602C17.3788 22.3417 16.2273 21.8115 13.9243 20.7512L13.3285 20.4768C12.6741 20.1755 12.3469 20.0248 12 20.0248C11.6531 20.0248 11.3259 20.1755 10.6715 20.4768L10.0757 20.7512C7.77268 21.8115 6.62118 22.3417 5.85515 21.7602C5.08912 21.1787 5.21588 19.8706 5.4694 17.2544L5.53498 16.5776C5.60703 15.8341 5.64305 15.4624 5.53586 15.1177C5.42868 14.773 5.19043 14.4944 4.71392 13.9372L4.2801 13.4299C2.60325 11.4691 1.76482 10.4886 2.05742 9.54773C2.35002 8.60682 3.57986 8.32856 6.03954 7.77203L6.67589 7.62805C7.37485 7.4699 7.72433 7.39083 8.00494 7.17781C8.28555 6.96479 8.46553 6.64194 8.82547 5.99623L9.15316 5.40838Z" fill="#1C274C"/></svg>
                                        </div><?php esc_attr_e(' Share your experience!','blognews-for-elementor'); ?></h3>
                                        <p class="blogfoel-right-area-desc"><?php esc_attr_e('Your feedback about our widget means the world to us!','blognews-for-elementor'); ?></p>
                                        <a href="https://wordpress.org/support/plugin/blognews-for-elementor/reviews/" target="_blank" class="blogfoel-btn-link"><?php esc_attr_e('Rate Us','blognews-for-elementor'); ?> <span class="blogfoel-icon-btn"><svg xmlns="http://www.w3.org/2000/svg" width="800px" height="800px" viewBox="0 0 24 24" fill="none">
                                            <path d="M6 12H18M18 12L13 7M18 12L13 17" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            </span></a>
                                    </div>
                                    <div class="blogfoel-right-box">
                                        <h3 class="blogfoel-right-area-title"><div class="blogfoel-right-icon">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="800px" height="800px" viewBox="0 0 24 24" fill="none">
                                        <path d="M16.5189 16.5013C16.6939 16.3648 16.8526 16.2061 17.1701 15.8886L21.1275 11.9312C21.2231 11.8356 21.1793 11.6708 21.0515 11.6264C20.5844 11.4644 19.9767 11.1601 19.4083 10.5917C18.8399 10.0233 18.5356 9.41561 18.3736 8.94849C18.3292 8.82066 18.1644 8.77687 18.0688 8.87254L14.1114 12.8299C13.7939 13.1474 13.6352 13.3061 13.4987 13.4811C13.3377 13.6876 13.1996 13.9109 13.087 14.1473C12.9915 14.3476 12.9205 14.5606 12.7786 14.9865L12.5951 15.5368L12.3034 16.4118L12.0299 17.2323C11.9601 17.4419 12.0146 17.6729 12.1708 17.8292C12.3271 17.9854 12.5581 18.0399 12.7677 17.9701L13.5882 17.6966L14.4632 17.4049L15.0135 17.2214L15.0136 17.2214C15.4394 17.0795 15.6524 17.0085 15.8527 16.913C16.0891 16.8004 16.3124 16.6623 16.5189 16.5013Z" fill="#1C274C"/>
                                        <path d="M22.3665 10.6922C23.2112 9.84754 23.2112 8.47812 22.3665 7.63348C21.5219 6.78884 20.1525 6.78884 19.3078 7.63348L19.1806 7.76071C19.0578 7.88348 19.0022 8.05496 19.0329 8.22586C19.0522 8.33336 19.0879 8.49053 19.153 8.67807C19.2831 9.05314 19.5288 9.54549 19.9917 10.0083C20.4545 10.4712 20.9469 10.7169 21.3219 10.847C21.5095 10.9121 21.6666 10.9478 21.7741 10.9671C21.945 10.9978 22.1165 10.9422 22.2393 10.8194L22.3665 10.6922Z" fill="#1C274C"/>
                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M4.17157 3.17157C3 4.34315 3 6.22876 3 10V14C3 17.7712 3 19.6569 4.17157 20.8284C5.34315 22 7.22876 22 11 22H13C16.7712 22 18.6569 22 19.8284 20.8284C20.9812 19.6756 20.9997 17.8316 21 14.1801L18.1817 16.9984C17.9119 17.2683 17.691 17.4894 17.4415 17.6841C17.1491 17.9121 16.8328 18.1076 16.4981 18.2671C16.2124 18.4032 15.9159 18.502 15.5538 18.6225L13.2421 19.3931C12.4935 19.6426 11.6682 19.4478 11.1102 18.8898C10.5523 18.3318 10.3574 17.5065 10.607 16.7579L10.8805 15.9375L11.3556 14.5121L11.3775 14.4463C11.4981 14.0842 11.5968 13.7876 11.7329 13.5019C11.8924 13.1672 12.0879 12.8509 12.316 12.5586C12.5106 12.309 12.7317 12.0881 13.0017 11.8183L17.0081 7.81188L18.12 6.70004L18.2472 6.57282C18.9626 5.85741 19.9003 5.49981 20.838 5.5C20.6867 4.46945 20.3941 3.73727 19.8284 3.17157C18.6569 2 16.7712 2 13 2H11C7.22876 2 5.34315 2 4.17157 3.17157ZM7.25 9C7.25 8.58579 7.58579 8.25 8 8.25H14.5C14.9142 8.25 15.25 8.58579 15.25 9C15.25 9.41421 14.9142 9.75 14.5 9.75H8C7.58579 9.75 7.25 9.41421 7.25 9ZM7.25 13C7.25 12.5858 7.58579 12.25 8 12.25H10.5C10.9142 12.25 11.25 12.5858 11.25 13C11.25 13.4142 10.9142 13.75 10.5 13.75H8C7.58579 13.75 7.25 13.4142 7.25 13ZM7.25 17C7.25 16.5858 7.58579 16.25 8 16.25H9.5C9.91421 16.25 10.25 16.5858 10.25 17C10.25 17.4142 9.91421 17.75 9.5 17.75H8C7.58579 17.75 7.25 17.4142 7.25 17Z" fill="#1C274C"/>
                                        </svg>
                                        </div><?php esc_attr_e(' Discover the Features','blognews-for-elementor'); ?></h3>
                                        <p class="blogfoel-right-area-desc"><?php esc_attr_e('Struggling to figure it out? Let our detailed guides be your ultimate problem-solver!','blognews-for-elementor'); ?></p>
                                        <a href="https://blognews.themeansar.com/" target="_blank" class="blogfoel-btn-link"><?php esc_attr_e('Explore Now','blognews-for-elementor'); ?> <span class="blogfoel-icon-btn"><svg xmlns="http://www.w3.org/2000/svg" width="800px" height="800px" viewBox="0 0 24 24" fill="none">
                                            <path d="M6 12H18M18 12L13 7M18 12L13 17" stroke="#000000" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                            </span></a>
                                    </div>
                                </div>                               
                            </div>
                        </div>
                        <div class="blogfoel-tab-content blogfoel-widget-switches">
                          
                          <form id="blogfoel-widget-dashboard-form" method="post" action="options.php">
                              <?php
                                    settings_fields( 'blogfoel_widget_settings' );
                                    do_settings_sections( 'blogfoel_widget_settings' );
                                    //   wp_nonce_field('save_widgets_dashboard', 'blogfoel_widget_dashboard_nonce');
                                    $all_blocks_toggle = sanitize_text_field(get_option('blogfoel_all_widget_setting', true));
                                    //   $saved_states = get_option('block_states', []);  
                              ?>
                              
                              <div class="blogfoel-admin-filter-nav">
                                  <div class="search-wrapper d-flex align-center justify-between">
                                    <div class="search-bar">
                                      <input type="text" class="search" placeholder="Search..">
                                      <a href="#"><span class="dashicons dashicons-search"></span></a>
                                    </div>
                                    <fieldset class="slide-btn">
                                      <input type="radio" id="radio-1" name="blogfoel_all_widget_setting" <?php checked($all_blocks_toggle, 1); ?> value="1">
                                      <label class="blogfoel-tab active" for="radio-1"><?php esc_html_e('Activate All', 'blognews-for-elementor' ); ?></label>
                                      <input type="radio" id="radio-2" name="blogfoel_all_widget_setting" <?php checked($all_blocks_toggle, 0); ?> value="0">
                                      <label class="blogfoel-tab" for="radio-2"><?php esc_html_e('Deactivate All', 'blognews-for-elementor' ); ?></label>
                                      <span class="glider"></span>
                                    </fieldset>
                                  </div>
                              </div> 
                              <?php foreach($blogfoel_load_widgets as $widgetCat){ ?>
                                <div class="heading">
                                  <h3 class="tittle"><?php echo esc_html($widgetCat['widget_cat']); ?> </h3>
                                </div>

                                <div class="d-grid column3 gap-30">
                                  <?php foreach ($widgetCat['widgets'] as $widgets) { 
                                    
                                    $enabled = get_option($widgets['widgetcls'], true); // Default to enabled 
                                    if ($widgets['type'] == 'lite') { ?>
                                        <div class="blogfoel-admin-widget free" data-item="free">
                                            <div class="blogfoel-widget-top d-flex align-center justify-between">
                                                <div class="blogfoel-admin-wid-title-area blogfoel-admin-f-center d-flex align-center gap-10">
                                                    <div class="blogfoel-admin-wid-title-icon blogfoel-admin-f-center">
                                                        <span class="blogfoel-admin-icons d-flex align-center <?php echo esc_attr($widgets['icon']); ?>"></span>
                                                    </div>
                                                    <h5 class="tittle"><a href="<?php echo esc_url( $widgets['demo']); ?>" target="_blank"><?php echo esc_html( $widgets['name']); ?></a></h5>
                                                </div>
                                                <div class="blogfoel-admin-wid-btn-area blogfoel-admin-f-center d-flex align-center gap-10">                                              
                                                    <div class="form-input">
                                                        <input type="checkbox" name="<?php echo esc_attr($widgets['widgetcls']); ?>" value="1" <?php echo checked($enabled, true, false); ?> class= "toggleable" />
                                                        <span class="slider"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="blogfoel-widget-bottom d-flex align-center justify-between">
                                                <div class="blogfoel-widgets-labels d-flex align-center">
                                                    <?php if (!empty( $widgets['label'])) {
                                                        echo '<span class="blogfoel-widgets-label">'. esc_html( $widgets['label']).'</span>';
                                                    } if (!empty( $widgets['label2'])) {
                                                        echo '<span class="blogfoel-widgets-label2">'. esc_html( $widgets['label2']).'</span>';
                                                    } ?>
                                                </div>
                                                <div class="blogfoel-widget-links d-flex align-center justify-center">
                                                    <a href="<?php echo esc_url( $widgets['doc']); ?>" target="_blank" class="doc" data-text="Docs">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 24 24" fill="none" style="">
                                                    <path d="M9 17H15M9 13H15M9 9H10M13 3H8.2C7.0799 3 6.51984 3 6.09202 3.21799C5.71569 3.40973 5.40973 3.71569 5.21799 4.09202C5 4.51984 5 5.0799 5 6.2V17.8C5 18.9201 5 19.4802 5.21799 19.908C5.40973 20.2843 5.71569 20.5903 6.09202 20.782C6.51984 21 7.0799 21 8.2 21H15.8C16.9201 21 17.4802 21 17.908 20.782C18.2843 20.5903 18.5903 20.2843 18.782 19.908C19 19.4802 19 18.9201 19 17.8V9M13 3L19 9M13 3V7.4C13 7.96005 13 8.24008 13.109 8.45399C13.2049 8.64215 13.3578 8.79513 13.546 8.89101C13.7599 9 14.0399 9 14.6 9H19" stroke="#666666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    </svg>
                                                </a>
                                                    <a href="<?php echo esc_url( $widgets['demo']); ?>" target="_blank" class="edit" data-text="Demo">
                                                        <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 24 24" fill="none">
                                                    <path d="M20 4L12 12M20 4V8.5M20 4H15.5M19 12.5V16.8C19 17.9201 19 18.4802 18.782 18.908C18.5903 19.2843 18.2843 19.5903 17.908 19.782C17.4802 20 16.9201 20 15.8 20H7.2C6.0799 20 5.51984 20 5.09202 19.782C4.71569 19.5903 4.40973 19.2843 4.21799 18.908C4 18.4802 4 17.9201 4 16.8V8.2C4 7.0799 4 6.51984 4.21799 6.09202C4.40973 5.71569 4.71569 5.40973 5.09202 5.21799C5.51984 5 6.07989 5 7.2 5H11.5" stroke="#666666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    </svg></a>  
                                                </div>
                                            </div>
                                        </div>

                                    <?php } else { ?>                                        
                                        <div class="blogfoel-admin-widget pro" data-item="pro">
                                            <div class="blogfoel-widget-top d-flex align-center justify-between">
                                                <div class="blogfoel-admin-wid-title-area blogfoel-admin-f-center d-flex align-center gap-10">
                                                    <div class="blogfoel-admin-wid-title-icon blogfoel-admin-f-center">
                                                        <span class="blogfoel-admin-icons d-flex align-center <?php echo esc_attr($widgets['icon']); ?>"></span>
                                                    </div>
                                                    <h5 class="tittle"><a href="<?php echo esc_url( $widgets['demo']); ?>" target="_blank"><?php echo esc_html( $widgets['name']); ?></a></h5>
                                                </div>
                                                <div class="blogfoel-admin-wid-btn-area blogfoel-admin-f-center d-flex align-center gap-10">                                              
                                                    <div class="form-input">
                                                        <input type="checkbox" name="<?php echo esc_attr($widgets['widgetcls']); ?>" value="1" class= "toggleable" />
                                                        <a class="overlay" href="<?php echo esc_url( $widgets['demo']); ?>" target="_blank"></a>
                                                        <span class="slider"></span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="blogfoel-widget-bottom d-flex align-center justify-between">
                                                <div class="blogfoel-widgets-labels d-flex align-center">
                                                    <?php if (!empty( $widgets['label'])) {
                                                        echo '<span class="blogfoel-widgets-label">'. esc_html( $widgets['label']).'</span>';
                                                    } if (!empty( $widgets['label2'])) {
                                                        echo '<span class="blogfoel-widgets-label2">'. esc_html( $widgets['label2']).'</span>';
                                                    } ?>
                                                </div>
                                                <div class="blogfoel-widget-links d-flex align-center justify-center">
                                                 <a href="<?php echo esc_url( $widgets['doc']); ?>" target="_blank" class="doc" data-text="Docs"><svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 24 24" fill="none" style="">
                                                <path d="M9 17H15M9 13H15M9 9H10M13 3H8.2C7.0799 3 6.51984 3 6.09202 3.21799C5.71569 3.40973 5.40973 3.71569 5.21799 4.09202C5 4.51984 5 5.0799 5 6.2V17.8C5 18.9201 5 19.4802 5.21799 19.908C5.40973 20.2843 5.71569 20.5903 6.09202 20.782C6.51984 21 7.0799 21 8.2 21H15.8C16.9201 21 17.4802 21 17.908 20.782C18.2843 20.5903 18.5903 20.2843 18.782 19.908C19 19.4802 19 18.9201 19 17.8V9M13 3L19 9M13 3V7.4C13 7.96005 13 8.24008 13.109 8.45399C13.2049 8.64215 13.3578 8.79513 13.546 8.89101C13.7599 9 14.0399 9 14.6 9H19" stroke="#666666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                </svg></a>
                                                <a href="<?php echo esc_url( $widgets['demo']); ?>" target="_blank" class="edit" data-text="Demo"><svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 24 24" fill="none">
                                                <path d="M20 4L12 12M20 4V8.5M20 4H15.5M19 12.5V16.8C19 17.9201 19 18.4802 18.782 18.908C18.5903 19.2843 18.2843 19.5903 17.908 19.782C17.4802 20 16.9201 20 15.8 20H7.2C6.0799 20 5.51984 20 5.09202 19.782C4.71569 19.5903 4.40973 19.2843 4.21799 18.908C4 18.4802 4 17.9201 4 16.8V8.2C4 7.0799 4 6.51984 4.21799 6.09202C4.40973 5.71569 4.71569 5.40973 5.09202 5.21799C5.51984 5 6.07989 5 7.2 5H11.5" stroke="#666666" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                </svg></a>  
                                                </div>
                                            </div>
                                        </div>
                                    <?php }
                                  } ?>                  
                                </div>
                              <?php } ?>

                            <?php submit_button(esc_html( 'Save Settings', 'blognews-for-elementor' ), 'submit pri', 'save-set1', true); ?>
                          </form>
                        </div>
                        <div class="blogfoel-tab-content blogfoel-builder">
                            <div id="overlay"></div>
                            <div id="popup">                                
                                <h2><?php esc_attr_e('Specify where your Template will be applied on your site.','blognews-for-elementor'); ?></h2>
                                <select id="templateType">
                                    <option value="" disabled selected><?php esc_html_e('Select an option','blognews-for-elementor')?></option>
                                    <option value="all"><?php esc_attr_e('Entire Site','blognews-for-elementor'); ?></option>
                                    <option value="home" disabled><?php esc_attr_e('Front Page (Pro)','blognews-for-elementor'); ?></option>
                                    <option value="archive" disabled><?php esc_attr_e('Post Archive (Pro)','blognews-for-elementor'); ?></option>
                                    <option value="singlePost" disabled><?php esc_attr_e('Single Post (Pro)','blognews-for-elementor'); ?></option>
                                    <option value="search" disabled><?php esc_attr_e('Search Page (Pro)','blognews-for-elementor'); ?></option>
                                    <option value="not_found" disabled><?php esc_attr_e('404 Page (Pro)','blognews-for-elementor'); ?></option>
                                </select>
                                <button class="button" id="confirmButton"><?php esc_attr_e('create template','blognews-for-elementor'); ?></button>
                                <button class="close" id="closePopup"><?php esc_attr_e('Close','blognews-for-elementor'); ?></button>
                            </div>
                            <div class="blogfoel-site-builder-container">
                                <div class="blogfoel-site-builder-tabs-content">
                                    <div class="blogfoel-site-builder-headings">
                                        <h3 class="blogfoel-heading-title blogfoel-heading"><?php esc_attr_e('Header','blognews-for-elementor'); ?></h3>
                                        <p class="blogfoel-heading-description"><?php esc_attr_e('Manage Your Custom Template Pages','blognews-for-elementor'); ?></p>
                                    </div>
                                    <div class="blogfoel-site-heading">
                                        <button class="blogfoel-site-builder-create-template hidden"><span class="dashicons dashicons-plus-alt"></span> <?php esc_attr_e('Create Template ','blognews-for-elementor'); ?></button>
                                        <button id="openPopup" class="blogfoel-site-builder-create-template-head-foot" temp-type="header"><span class="dashicons dashicons-plus-alt"></span> <?php esc_attr_e('Create Template ','blognews-for-elementor'); ?></button>
                                        <a href="https://www.youtube.com/watch?v=NbXcNMZfIsI" class="blogfoel-btn-video-link" target="_blank"><span class="dashicons dashicons-video-alt3"></span> <?php esc_attr_e('Site Builder Tutorial ','blognews-for-elementor'); ?></a>
                                    </div>                                    
                                </div>
                                <div class="blogfoel-site-builder-tabs">
                                    <button class="blogfoel-builder-tab-button  active" temp-type="header"><?php esc_attr_e('Header','blognews-for-elementor'); ?></button>
                                    <button class="blogfoel-builder-tab-button" temp-type="footer"><?php esc_attr_e('Footer','blognews-for-elementor'); ?></button>
                                    <button class="blogfoel-builder-tab-button" temp-type="home"><?php esc_attr_e('Frontpage','blognews-for-elementor'); ?></button>
                                    <button class="blogfoel-builder-tab-button" temp-type="singlePost"><?php esc_attr_e('Single Post','blognews-for-elementor'); ?></button>
                                    <button class="blogfoel-builder-tab-button" temp-type="blogArchive"><?php esc_attr_e('Post Archive','blognews-for-elementor'); ?></button>
                                    <button class="blogfoel-builder-tab-button" temp-type="search"><?php esc_attr_e('Search Page','blognews-for-elementor'); ?></button>
                                    <button class="blogfoel-builder-tab-button" temp-type="404"><?php esc_attr_e('404 Page','blognews-for-elementor'); ?></button>
                                    <button class="blogfoel-builder-tab-button" temp-type="all"><?php esc_attr_e('Templates','blognews-for-elementor'); ?></button>
                                </div>
                                <div class="blogfoel-builder-list">
                                        <?php // The Loop
                                            if ($query->have_posts()) {
                                                while ($query->have_posts()) {
                                                    $query->the_post(); 
                                                    $id = get_the_ID(); 
                                                    $edit_link = get_edit_post_link($id, 'raw'); 
                                                    $template_type    = get_post_meta($id, 'template_type', true);
                                                    $display_on = get_post_meta($id, 'blogfoel_display_condition', true);

                                                    if($template_type == 'header' || $template_type == 'footer'){
                                                        $temp_type = $template_type;
                                                    }else{
                                                        if(in_array("home", $display_on)){
                                                            $temp_type = 'home';
                                                        } else if(in_array("singlePost", $display_on)){
                                                            $temp_type = 'singlePost';
                                                        } else if(in_array("blogArchive", $display_on)){
                                                            $temp_type = 'blogArchive';
                                                        } else if(in_array("search", $display_on)){
                                                            $temp_type = 'search';
                                                        } else if(in_array("not_found", $display_on)){
                                                            $temp_type = '404';
                                                        }
                                                    }                                                     
                                                    ?>
                                                
                                                    <div class="blogfoel-template-type <?php echo esc_attr($template_type !== 'header' ? 'hidden' : ''); ?>" temp-type="<?php echo esc_attr($temp_type); ?>">
                                                        <div class="blogfoel-template-meta">
                                                            <div class="blogfoel-template-img">
                                                                <img src="<?php echo esc_url(BLOGFOEL_DIR_URL .'/assets/images/template-dummy.jpg');?>" alt="template-dummy">
                                                            </div>
                                                            <span><?php the_title(); ?></span>
                                                        </div>
                                                        <div class="blogfoel-template-control">
                                                            <span class="status-temp <?php echo esc_html(get_post_status($id)); ?>"><?php echo esc_html(get_post_status($id)); ?></span>
                                                            <a class="blogfoel-edit-action" data-text="<?php echo esc_html__('Edit', 'blognews-for-elementor'); ?>" href="<?php echo esc_url($edit_link); ?>"target="_blank">
                                                                <span class="dashicons dashicons-edit-large"></span>
                                                            </a>
                                                            <?php
                                                            global $post;
                                                            if ( current_user_can( 'edit_post', $post->ID ) && \Elementor\Plugin::$instance->db->is_built_with_elementor( $post->ID ) ) {
                                                                $elementor_edit_link = add_query_arg( array('action' => 'elementor', 'post'   => $post->ID, ), admin_url('post.php') ); ?>
                                                                <a class="blogfoel-el-edit-action" data-text="<?php echo esc_html__('Edit with Elementor', 'blognews-for-elementor'); ?>" href="<?php echo esc_url($elementor_edit_link); ?>"target="_blank">
                                                                    <span class="eicon-elementor"></span>
                                                                </a>
                                                            <?php } ?>
                                                            <span class="delete-temp" pid="<?php echo esc_attr($id); ?>" data-text="<?php esc_html_e('Delete','blognews-for-elementor')?>"><span class="dashicons dashicons-trash"></span></span>
                                                        </div>
                                                    </div>
                                                <?php }
                                            } wp_reset_postdata();
                                        ?>
                                    </div>
                            </div>
                        </div>
                        <div class="blogfoel-tab-content starter-sites">
                            <?php if(!$this->is_plugin_installed('ansar-import') || !is_plugin_active($this->retrive_plugin_install_path('ansar-import'))){ ?>
                                <div class="blogfoel-modal-main">
                                    <div class="blogfoel-modal-image overlay">
                                        <img src="<?php echo esc_url(BLOGFOEL_DIR_URL) . 'assets/images/demos.jpg' ?>" alt="">
                                    </div>
                                    <div class="blogfoel-modal-popup">
                                        <div class="blogfoel-modal-popup-content">
                                            <div class="blogfoel-modal-icon">
                                                <img src="<?php echo esc_url(BLOGFOEL_DIR_URL) . 'assets/images/ansar-import-logo.png' ?>" alt="">
                                            </div>
                                            <div>
                                                <h4>Ansar Import</h4>
                                                <p>Unlock 13+ Elementor templates from Ansar Import, designed to elevate your Blog, News and Magazine effortlessly.</p>
                                                <a href="#" class="blogfoel-btn-ins ins-ansar-imp" plug="ansar-import" >
                                                    <?php if (!$this->is_plugin_installed('ansar-import')) {
                                                        esc_html_e('Install Ansar Import', 'blognews-for-elementor');
                                                    } elseif (!is_plugin_active($this->retrive_plugin_install_path('ansar-import'))) {
                                                        esc_html_e('Activate Ansar Import', 'blognews-for-elementor');
                                                    } else {
                                                        esc_html_e( 'Import Demo', 'blognews-for-elementor' );
                                                    }
                                                    ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } else {  ?>
                                <div class="blogfoel-modal-main">
                                    <div class="blogfoel-modal-image overlay">
                                        <img src="<?php echo esc_url(BLOGFOEL_DIR_URL) . 'assets/images/demos.jpg' ?>" alt="">
                                    </div>
                                    <div class="blogfoel-modal-popup">
                                        <div class="blogfoel-modal-popup-content">
                                            <div class="blogfoel-modal-icon">
                                                <img src="<?php echo esc_url(BLOGFOEL_DIR_URL) . 'assets/images/ansar-import-logo.png' ?>" alt="">
                                            </div>
                                            <div>
                                                <h4><?php esc_attr_e("Ansar Import","blognews-for-elementor"); ?></h4>
                                                <p><?php esc_attr_e("Click View Demo Button to install a ready-made News & Blog Elementor template — fast, simple, and customizable.","blognews-for-elementor"); ?></p>
                                                <a href="<?php echo esc_url( admin_url( 'admin.php?page=ansar-plugin-demos' ) ); ?>" class="blogfoel-btn-ins" >
                                                    <?php 
                                                        esc_html_e( 'View Demos', 'blognews-for-elementor' );
                                                    ?>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php } ?>
                        </div>
                    </div>                    
                </div>
            </div>
        </div>
    <?php }

    private function display_elementor_dependency_notice() {
        ?>
        <div class="wrap">
            <div class="blogfoel-elementor-required-notice">
                <?php
                // Call your existing function to display the notice
                blogfoel_elements_widget_fail_load();

        // Fixed: Use modern notice class
                ?>
                
                <div class="elementor-info">
                    <h4><?php esc_html_e('Why do I need Elementor?', 'blognews-for-elementor'); ?></h4>
                    <p>
                        <?php esc_html_e('BlogNews for Elementor provides powerful widgets that extend Elementor\'s functionality. Without Elementor, these widgets cannot be used.', 'blognews-for-elementor'); ?>
                    </p>
                    <a href="https://elementor.com/" target="_blank" class="button">
                        <?php esc_html_e('Learn More About Elementor', 'blognews-for-elementor'); ?>
                    </a>
                </div>
            </div>
        </div>
        <?php
    }

    public function blogfoel_create_temp_type() {
        // // Verify nonce
        // if (!isset($_POST['blogfoel_admin_nonce']) && !wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['blogfoel_admin_nonce'])), 'blogfoel_admin_nonce_check')) {
        //     wp_send_json_error('Nonce verification failed');
        // }
        if (
            ! isset($_POST[self::NONCE_NAME]) ||
            ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[self::NONCE_NAME] ) ), self::NONCE_ACTION )
        ) {
            wp_send_json_error('Nonce verification failed');
        }

        // Check user capabilities
        if (!current_user_can('edit_posts')) {
            wp_send_json_error('Insufficient permissions');
        }

        if (isset($_POST['temp_type'])) {
            $temp_type = $_POST['temp_type'];
        }

        if (isset($_POST['show_type'])) {
            $show_type = $_POST['show_type'];
        }

        if($temp_type == 'home'){
            $post_type = 'blogfoel-hf-builder';
            $post_title = 'Front Page Template';
            $post_meta = array(
                'template_type' => 'body',
                'blogfoel_display_condition' => array('home'),
            );
        }else if($temp_type == 'singlePost'){
            $post_type = 'blogfoel-hf-builder';
            $post_title = 'Single Post Page Template';
            $post_meta = array(
                'template_type' => 'body',
                'blogfoel_display_condition' => array('singlePost'),
            );
            
        }else if($temp_type == 'header'){
            $post_type = 'blogfoel-hf-builder';
            $post_title = 'Header Template';
            $post_meta = array(
                'template_type' => 'header',
                'blogfoel_display_condition' => array($show_type),
            );
            
        }else if($temp_type == 'footer'){
            $post_type = 'blogfoel-hf-builder';
            $post_title = 'Footer Template';
            $post_meta = array(
                'template_type' => 'footer',
                'blogfoel_display_condition' => array($show_type),
            );
            
        }else if($temp_type == 'blogArchive'){
            $post_type = 'blogfoel-hf-builder';
            $post_title = 'Post Archive Page Template';
            $post_meta = array(
                'template_type' => 'body',
                'blogfoel_display_condition' => array('blogArchive'),
            );
            
        }else if($temp_type == 'search'){
            $post_type = 'blogfoel-hf-builder';
            $post_title = 'Search Page Template';
            $post_meta = array(
                'template_type' => 'body',
                'blogfoel_display_condition' => array('search'),
            );
            
        }else if($temp_type == '404'){
            $post_type = 'blogfoel-hf-builder';
            $post_title = '404 Page Template';
            $post_meta = array(
                'template_type' => 'body',
                'blogfoel_display_condition' => array('not_found'),
            );
            
        }
        // Insert the custom post
        $post_id = wp_insert_post(array(
            'post_type'    => $post_type,
            'post_title'   => $post_title,
            'post_status'  => 'draft', // Set as 'publish', 'draft', etc.
        ));
        
        if ($post_id && !is_wp_error($post_id)) {
            foreach ($post_meta as $key => $value) {
                update_post_meta($post_id, $key, $value);
            }

            if ( in_array( $temp_type, array( 'header', 'footer' ), true ) ) {
                // Set Elementor Canvas layout
                update_post_meta( $post_id, '_wp_page_template', 'elementor_canvas' );
            } else {
                // Set Elementor Full Width layout
                update_post_meta( $post_id, '_wp_page_template', 'elementor_header_footer' );
            }

            // Get edit link
            // $edit_link = get_edit_post_link($post_id, 'raw');
            $edit_link = admin_url('post.php?post=' . $post_id . '&action=elementor');
        
            // Send success response with edit link
            wp_send_json_success(array(
                'message' => 'Template created successfully',
                'edit_link' => $edit_link,
            ));
        } else {
            wp_send_json_error('Template Creation Failed');
        }

    }

    public function blogfoel_actions() {
        // Nonce check
        if (
            ! isset($_POST[self::NONCE_NAME]) ||
            ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[self::NONCE_NAME] ) ), self::NONCE_ACTION )
        ) {
            wp_send_json_error('Nonce verification failed');
        }

        // Check user capabilities
        if (!current_user_can('edit_posts')) {
            wp_send_json_error('Insufficient permissions');
        }

        if (isset($_POST['post_id'])) {
            $post_id = $_POST['post_id'];

            // Delete the post
            if (wp_delete_post($post_id, true)) {
                wp_send_json_success(array(
                    'message' => "Template with ID $post_id has been deleted.",
                    'edit_link' => $edit_link,
                ));
            } else {
                wp_send_json_error("Failed to delete Template with ID $post_id.");
            }
        }

        if (isset($_POST['demp_post_id']) && isset($_POST['temp_id'])) {
            $demp_post_id = $_POST['demp_post_id'];
            $temp_id = $_POST['temp_id'];
            
            update_post_meta($temp_id, 'blogfoel_demo_post_id', $demp_post_id);
            wp_send_json_success('Update Demo Post Meta');
        }
    }

    public function install_plug_ajax() {
        // Nonce check
        if (
            ! isset($_POST[self::NONCE_NAME]) ||
            ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST[self::NONCE_NAME] ) ), self::NONCE_ACTION )
        ) {
            wp_send_json_error('Nonce verification failed');
        }

        // Capability: installing plugins requires install_plugins
        if ( ! current_user_can( 'install_plugins' ) ) {
            wp_send_json_error('Insufficient permissions');
        }

        if ( empty( $_POST['plugin_name'] ) ) {
            wp_send_json_error('Missing plugin name.');
        }

        $plugin_slug = sanitize_key( wp_unslash( $_POST['plugin_name'] ) );

        // Ensure plugin APIs are available
        if ( ! function_exists( 'get_plugins' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        require_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
        require_once ABSPATH . 'wp-admin/includes/plugin-install.php';
        require_once ABSPATH . 'wp-admin/includes/file.php';

        // Get installed plugins
        $all_wp_plugins = get_plugins();

        $plugin_status = $this->blogfoel_get_required_plugin_status( $plugin_slug, $all_wp_plugins );

        try {
            if ( $plugin_status === 'not-installed' ) {
                $install_result = $this->install_plugin( ['slug' => $plugin_slug] );
                if ( is_wp_error( $install_result ) ) {
                    wp_send_json_error( 'Install error: ' . $install_result->get_error_message() );
                }
                // After install, find plugin path and activate
                $plugin_file = $this->get_plugin_install_path( $plugin_slug );
                if ( $plugin_file ) {
                    $activate_result = activate_plugin( $plugin_file );
                    if ( is_wp_error( $activate_result ) ) {
                        wp_send_json_error( 'Activation error: ' . $activate_result->get_error_message() );
                    }
                }
                wp_send_json_success( 'Plugin installed and activated successfully.' );
            } elseif ( $plugin_status === 'inactive' ) {
                $plugin_file = $this->get_plugin_install_path( $plugin_slug );
                if ( $plugin_file ) {
                    $activate_result = activate_plugin( $plugin_file );
                    if ( is_wp_error( $activate_result ) ) {
                        wp_send_json_error( 'Activation error: ' . $activate_result->get_error_message() );
                    }
                    wp_send_json_success( 'Plugin activated successfully.' );
                } else {
                    wp_send_json_error( 'Plugin file not found for activation.' );
                }
            } elseif ( $plugin_status === 'active' ) {
                wp_send_json_success( 'Plugin already active.' );
            } else {
                wp_send_json_error( 'Unexpected plugin status.' );
            }
        } catch ( \Exception $e ) {
            wp_send_json_error( 'Exception: ' . $e->getMessage() );
        }
    }

    /**
     * Install Plugin
     *
     * @param array $plugin ['slug' => 'plugin-slug']
     * @return string|WP_Error
     */
    public function install_plugin( $plugin = array() ) {
        if ( empty( $plugin['slug'] ) ) {
            return new \WP_Error( 'invalid-slug', __( 'Invalid plugin slug', 'blognews-for-elementor' ) );
        }

        $slug = sanitize_key( wp_unslash( $plugin['slug'] ) );

        // Get plugin info from WP.org
        $api = plugins_api(
            'plugin_information',
            [
                'slug'   => $slug,
                'fields' => [ 'sections' => false ],
            ]
        );

        if ( is_wp_error( $api ) ) {
            return $api;
        }

        // Use the WP_Upgrader to install
        $skin     = new \WP_Ajax_Upgrader_Skin();
        $upgrader = new \Plugin_Upgrader( $skin );
        $result   = $upgrader->install( $api->download_link );

        if ( is_wp_error( $result ) ) {
            return $result;
        } elseif ( is_wp_error( $skin->result ) ) {
            return $skin->result;
        } elseif ( $skin->get_errors()->has_errors() ) {
            return new \WP_Error( 'upgrader-errors', implode( ', ', $skin->get_error_messages() ) );
        } elseif ( is_null( $result ) ) {
            global $wp_filesystem;
            if ( $wp_filesystem instanceof \WP_Filesystem_Base && is_wp_error( $wp_filesystem->errors ) && $wp_filesystem->errors->has_errors() ) {
                return new \WP_Error( 'filesystem-error', $wp_filesystem->errors->get_error_message() );
            }
            return new \WP_Error( 'no-filesystem', __( 'Unable to connect to the filesystem. Confirm credentials.', 'blognews-for-elementor' ) );
        }
        /* translators: %s: Plugin name. */
        return sprintf(esc_html__('Successfully installed "%s" plugin!', 'blognews-for-elementor'), $api->name);
    }

    /**
     * Get plugin install path (plugin main file within plugin folder key used by WP)
     * Example return: 'akismet/akismet.php'
     */
    private function get_plugin_install_path( $plugin_slug ) {
        if ( ! function_exists( 'get_plugins' ) ) {
            require_once ABSPATH . 'wp-admin/includes/plugin.php';
        }
        $all_plugins = get_plugins();
        foreach ( $all_plugins as $key => $wp_plugin ) {
            $folder_arr = explode( '/', $key );
            if ( $folder_arr[0] === $plugin_slug ) {
                return (string) $key;
            }
        }
        return false;
    }

    private function blogfoel_get_required_plugin_status( $plugin, $all_plugins ) {
        $response = 'not-installed';
        foreach ( $all_plugins as $key => $wp_plugin ) {
            $folder_arr = explode( "/", $key );
            $folder = $folder_arr[0];
            if ( $folder == $plugin ) {
                if ( is_plugin_inactive( $key ) ) {
                    $response = 'inactive';
                } else {
                    $response = 'active';
                }
                return $response;
            }
        }
        return $response;
    }

    private function is_plugin_installed( $plugin_slug ) {
        return (bool) $this->get_plugin_install_path( $plugin_slug );
    }
    public function retrive_plugin_install_path($plugin_slug) {
        include_once ABSPATH . 'wp-admin/includes/plugin.php';
        $all_plugins = get_plugins();
        foreach ($all_plugins as $key => $wp_plugin) {
            $folder_arr = explode("/", $key);
            $folder = $folder_arr[0];
            if ($folder == $plugin_slug) {
                return (string) $key;
                break;
            }
        }
        return false;
    }
    private function plugin_status_check($plug_slug){
        $status = '';
        if (!$this->is_plugin_installed($plug_slug)) {
            $status = 'not-installed';
        } elseif (!is_plugin_active($this->retrive_plugin_install_path($plug_slug))) {
            $status = 'not-active';
        } else {
            $status = 'active';
        }
        return $status;
    }
}