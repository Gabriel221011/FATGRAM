<?php // Main Builder Class
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class BlogNews_Header_Footer_Elementor {

    private static $instance = null;
	public static function instance() {
		if ( is_null( self::$instance) ){
		    self::$instance = new self();
		}
		return self::$instance;
	}

    private function __construct() {
        
        // Initialize the post type.
        add_action('init', [ $this,'blogfoel_post_type']);
        add_action('add_meta_boxes', [ $this,'blogfoel_hf_meta_box']);
        add_action('save_post', [ $this,'blogfoel_hf_meta_cb_save']);
        add_action('admin_print_styles', [ $this,'blogfoel_main_plug_styles']);
        add_action('admin_enqueue_scripts', [ $this,'blogfoel_main_plug_enqueue_script'], 999);

        //Setup Elementor header and footer builder
        add_filter('template_include', [$this, 'show_full_page'], 9999 );
        add_action( 'get_header', [$this, 'show_header'] );
        add_action( 'get_footer', [$this, 'show_footer'] );
        add_action( 'wp_enqueue_scripts', [ $this,'blogfoel_enqueue_scripts']);
        add_action( '_blognews_full_page_', [ $this,'blogfoel_full_page_template'], 10);
        add_action( '_blognews_head_', [ $this,'blogfoel_head_template'], 10);
        add_action( '_blognews_foot_', [ $this,'blogfoel_foot_template'], 10);
        add_action( 'wp_ajax_blognews_pt_update', [ $this,'blogfoel_pt_input']);

	}

    function blogfoel_post_type(){
        $labels = array(
            'name'                       => esc_html__('BlogNews Theme Builder',  'blognews-for-elementor'),
            'singular_name'              => esc_html__('Item', 'blognews-for-elementor'),
            'menu_name'                  => esc_html__('BlogNews Theme Builder', 'blognews-for-elementor'),
            'name_admin_bar'             => esc_html__('BlogNews Theme Builder item', 'blognews-for-elementor'),
            'parent_item_colon'          => esc_html__( 'Parent Item', 'blognews-for-elementor' ),
            'all_items'                  => esc_html__( 'All Items', 'blognews-for-elementor' ),
            'view_item'                  => esc_html__( 'View Item', 'blognews-for-elementor' ),
            'add_new_item'               => esc_html__( 'Add New Item', 'blognews-for-elementor' ),
            'add_new'                    => esc_html__( 'Add New', 'blognews-for-elementor' ),
            'edit_item'                  => esc_html__( 'Edit Template', 'blognews-for-elementor' ),
            'update_item'                => esc_html__( 'Update Item', 'blognews-for-elementor' ),
            'search_items'               => esc_html__( 'Search Item', 'blognews-for-elementor' ),
            'not_found'                  => esc_html__( 'Not Found', 'blognews-for-elementor' ),
            'not_found_in_trash'         => esc_html__( 'Not found in Trash', 'blognews-for-elementor' ),
        );
    
        $args = array(
            'label'               => esc_html__( 'BlogNews Theme Builder', 'blognews-for-elementor' ),
            'description'         => esc_html__( 'BlogNews Theme Builder', 'blognews-for-elementor' ),
            'labels'              => $labels,
            'supports'            => array( 'title', 'elementor', 'permalink' ),
            'hierarchical'        => false,
            'public'              => true,
            'register_meta_box_cb' => array($this, "blogfoel_hf_meta_box"),
            'show_ui'             => true,
            'show_in_menu'        => false,
            'show_in_nav_menus'   => false,
            'show_in_admin_bar'   => false,
            'has_archive'         => true,
            'menu_icon'           => 'dashicons-editor-kitchensink'
      
        );

        register_post_type('blogfoel-hf-builder', $args);
    }

    function blogfoel_hf_meta_box() {
        global $post;
        $template_type    = get_post_meta($post->ID, 'template_type', true); 
        if($template_type !== 'header' && $template_type !== 'footer'){
          return;  
        } 
        add_meta_box(
            'head-foot-metabox',
            esc_html__( 'BlogNews Theme Builder Metabox', 'blognews-for-elementor' ),
            array($this, "blognews_hf_meta_cb"),
            'blogfoel-hf-builder'
        );
    }

    function blognews_hf_meta_cb( $post ){

        $post_types       = get_post_types();
        $post_types_unset = array(
            'attachment'              => 'attachment',
            'revision'                => 'revision',
            'nav_menu_item'           => 'nav_menu_item',
            'custom_css'              => 'custom_css',
            'customize_changeset'     => 'customize_changeset',
            'oembed_cache'            => 'oembed_cache',
            'user_request'            => 'user_request',
            'wp_block'                => 'wp_block',
            'elementor_library'       => 'elementor_library',
            'elespare_builder'        => 'elespare_builder',
            'elementor-hf'            => 'elementor-hf',
            'elementor_font'          => 'elementor_font',
            'elementor_icons'         => 'elementor_icons',
            'wpforms'                 => 'wpforms',
            'wpforms_log'             => 'wpforms_log',
            'acf-field-group'         => 'acf-field-group',
            'acf-field'               => 'acf-field',
            'booked_appointments'     => 'booked_appointments',
            'wpcf7_contact_form'      => 'wpcf7_contact_form',
            'scheduled-action'        => 'scheduled-action',
            'shop_order'              => 'shop_order',
            'shop_order_refund'       => 'shop_order_refund',
            'shop_coupon'             => 'shop_coupon',
            'blogfoel-hf-builder'     => 'blogfoel-hf-builder',
            'blogfoel-hf-builder-pro' => 'blogfoel-hf-builder-pro', 
            'blogfoel_mega_menu'      => 'blogfoel_mega_menu',
            'wp_navigation'           => 'wp_navigation',
            'product_variation'       => 'product_variation',
            'shop_order_placehold'    => 'shop_order_placehold',
            'product'                 => 'product',
            'wp_global_styles'        => 'wp_global_styles',
            'wp_template_part'        => 'wp_template_part',
            'wp_template'             => 'wp_template',
            'e-landing-page'          => 'e-landing-page',
        );
        $options = array_diff( $post_types, $post_types_unset );
        
        $template_type    = get_post_meta($post->ID, 'template_type', true);    
        
        $current_template = get_post_meta( $post->ID, 'blogfoel_display_condition', true );
        
        $post_id          = get_post_meta( $post->ID, 'post_type_posts', true );
        
        $post_type        = get_post_meta( $post->ID, 'post_type_template', true );
        
        if(get_post_meta( $post->ID, 'blogfoel_display_condition', true ) == ''){
            $current_template =  array('');
        }elseif(in_array( 'all', $current_template , true )){
            $current_template = array('','all');
        }else{

            $current_template = get_post_meta( $post->ID, 'blogfoel_display_condition', true );
        }

        ?>
        <div class = "main_cls">
            <div class="template-type-main">
                <div class="temp-label">
                <label><strong><?php esc_html_e( 'Type of Template', 'blognews-for-elementor' ) ?></strong></label>
                </div>
                    <div class="template-type">
                    <select name="type_of_template" class="form-control selectpicker">
                        <option value="header" <?php selected($template_type, 'header' ); ?>><?php esc_html_e('Header', 'blognews-for-elementor'); ?></option>
                        <option value="footer" <?php selected($template_type, 'footer' ); ?>><?php esc_html_e('Footer', 'blognews-for-elementor'); ?></option>
                        <option value="body" <?php selected($template_type, 'body' ); ?>><?php esc_html_e('Full Page', 'blognews-for-elementor'); ?></option>
                    </select>
                    </div>
                </div>
        
            <div class="display--on">
                <div class="dis-label">
                    <label><strong><?php esc_html_e( 'Display On ', 'blognews-for-elementor' ) ?></strong></label>
                    <i class="bsf-target-rules-heading-help dashicons dashicons-editor-help"></i>
                </div>
                    <div class="custome-dropdown-wrapper">
                        <select name="blogfoel_display_condition[]" data-placeholder="Please Select Page to Display" class="custome-dropdown opt-display-on" multiple="multiple"  >
                                <option value="all" <?php selected( in_array( 'all', $current_template, true ) ); ?>><?php esc_html_e( 'Entire Site', 'blognews-for-elementor' ) ?></option>
                                <option value="home" disabled <?php selected( in_array( 'home', $current_template, true ) ); ?>><?php esc_html_e( 'Front Page (Pro)', 'blognews-for-elementor' ) ?></option>
                                <option value="blogArchive" disabled <?php selected( in_array( 'blogArchive', $current_template, true ) ); ?>><?php esc_html_e( 'Post Archive (Pro)', 'blognews-for-elementor' ) ?></option>
                                <option value="singlePost" disabled <?php selected( in_array( 'singlePost', $current_template, true ) ); ?>><?php esc_html_e( 'Single Post (Pro)', 'blognews-for-elementor' ) ?></option>
                                <?php foreach($options as $option){ ?>
                                <option value="<?php echo esc_attr( $option ); ?>" <?php selected( in_array( $option, $current_template, true ) ); ?> style = " text-transform: capitalize;">
                                <?php echo esc_html( $option ); ?></option>
                            <?php } ?>
                        </select>
                    </div>
        
            </div>
            <div class="posttype_val">
                <input type="hidden" name="post_type_posts" value="<?php echo esc_attr( $post_id ); ?>">
                <input type="hidden" name="post_type_template" value="<?php echo esc_attr( $post_type ); ?>" class="post-type-template">
            </div>					
            <div class="display-on-post"></div>
        </div>
        <?php
    }

        function blogfoel_hf_meta_cb_save( $post_id ){

            if ( isset( $_REQUEST['blogfoel_display_condition'] ) ) {
                $array = array_map( 'sanitize_text_field', wp_unslash( $_POST['blogfoel_display_condition'] ) );
                update_post_meta( $post_id, 'blogfoel_display_condition',  $array );
            }
            
            if ( isset( $_REQUEST['type_of_template'] ) ) {
                update_post_meta( $post_id, 'template_type',  sanitize_text_field( $_POST[ 'type_of_template' ] ) );
            }
            
            if ( isset( $_REQUEST['post_type_template'] ) ) {
                update_post_meta( $post_id, 'post_type_template',  sanitize_text_field( $_POST[ 'post_type_template' ] ) );
            }
            
            if ( isset( $_REQUEST['post_type_posts'] ) ) {
                update_post_meta( $post_id, 'post_type_posts',  sanitize_text_field( $_POST[ 'post_type_posts' ] ) );
            }
            
        }
        
        function blogfoel_main_plug_styles() {
            wp_enqueue_style( 'style',  BLOGFOEL_DIR_URL . "assets/css/meta-box.css", array(), BLOGFOEL_VERSION);
            wp_enqueue_style( 'select2-min-css', BLOGFOEL_DIR_URL . "assets/css/select2.min.css", array(), BLOGFOEL_VERSION);
        }
            
        function blogfoel_main_plug_enqueue_script() {   
            wp_enqueue_script( 'main-js', BLOGFOEL_DIR_URL . 'assets/js/main.js',array( 'jquery', 'suggest' ), 0.1 , true );
        
            $localize = array(
                'url'   => admin_url( 'admin-ajax.php' ),
                'nonce' => wp_create_nonce( 'blognews_pt_nonce' ),
                'edit'  => admin_url( 'edit.php?post_type=blogfoel-hf-builder' ),
            );
        
            wp_localize_script(
                'main-js',
                'admin',
                $localize
            );
        
            wp_enqueue_script( 'select2-min-js', BLOGFOEL_DIR_URL . 'assets/js/select2.min.js',array( 'jquery'), '4.0.13' , true);
        }

        function show_full_page($template) {
            $full_page_template_id = $this->full_page_template_id();
            if ($full_page_template_id) {
                
                get_header(); ?>
                    <div id="blognews-full-page" class="blognews-full-page-site">
                        <?php $full_page_template = \Elementor\Plugin::instance()->frontend->get_builder_content( $full_page_template_id, false );

                        if ( '' === $full_page_template ) { return $template; }

                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
                        echo ''. $full_page_template; ?>
                    </div>     
                <?php get_footer(); 
            }else{
                return $template;
            }
        }

        function show_header() {
            $head_template_id = $this->head_template_id();
            if ( $head_template_id) {
                require plugin_dir_path( __FILE__ ) . 'elementor/template/header.php';
                $template   = array();
                $template[] = 'header.php';
                remove_all_actions( 'wp_head' );
                ob_start();
                locate_template( $template, true );
                ob_get_clean();
            }
        }
            
        function show_footer() {  
            $foot_template_id = $this->foot_template_id();
            if ( $foot_template_id) {
                require plugin_dir_path( __FILE__ ) . 'elementor/template/footer.php';
                $template   = array();
                $template[] = 'footer.php';
                remove_all_actions( 'wp_footer' );
                ob_start();
                locate_template( $template, true );
                ob_get_clean();
            }
        }
        
        function blogfoel_enqueue_scripts() {

            if($this->full_page_template_id()){
        
                if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
                    $css_file = new \Elementor\Core\Files\CSS\Post( $this->full_page_template_id() );
                } elseif ( class_exists( '\Elementor\Post_CSS_File' ) ) {
                    $css_file = new \Elementor\Post_CSS_File( $this->full_page_template_id() );
                }
        
                $css_file->enqueue();
            }

            if($this->head_template_id()){

                if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
                    $css_file = new \Elementor\Core\Files\CSS\Post( $this->head_template_id() );
                } elseif ( class_exists( '\Elementor\Post_CSS_File' ) ) {
                    $css_file = new \Elementor\Post_CSS_File( $this->head_template_id() );
                }

                $css_file->enqueue();
            }
        
            if($this->foot_template_id()){
                
                if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {
                    $css_file = new \Elementor\Core\Files\CSS\Post( $this->foot_template_id() );
                } elseif ( class_exists( '\Elementor\Post_CSS_File' ) ) {
                    $css_file = new \Elementor\Post_CSS_File( $this->foot_template_id() );
                }
        
                $css_file->enqueue();
            }
        }

        function blogfoel_full_page_template() {
            global $post;
            
            if ( ! empty( $post ) ) {
                $id   = $post->ID;
                $post_type = get_post_type( $post->ID );
            }
        
            $path      = plugin_dir_path( __FILE__ ) . 'elementor/content/full-page-content.php';
            $template_type = $this->template_type();
            
            if ( ! empty( $template_type ) ) {
                
                $full_page = $this->show_template( $template_type, 'body' );
                if ( ! $full_page ) {
                    $full_page = $this->show_all('body');
                }
            }
        
            if ( is_singular() ) {
                $full_page = $this->present_single( $id, $post_type, 'body' );
        
                if ( ! $full_page ) {
                    $full_page = $this->total_single( $id, $post_type, 'body' );
                    if ( ! $full_page ) {
                        $full_page = $this->show_all('body');
                    }
                }
            }

            if($full_page == false){
        
            }else{
                $this->generate( $full_page, $path );
            }
        }
        
        function blogfoel_head_template() {
            global $post;
            
            if ( ! empty( $post ) ) {
                $id   = $post->ID;
                $post_type = get_post_type( $post->ID );
            }
        
            $path      = plugin_dir_path( __FILE__ ) . 'elementor/content/content-header.php';
            $template_type = $this->template_type();
            
            if ( ! empty( $template_type ) ) {
                
                $header = $this->show_template( $template_type, 'header' );
                if ( ! $header ) {
                    $header = $this->show_all('header');
                }

                if($header == false){
                    return;
                }else{
                    $this->generate( $header, $path );
                }
            }
        
            if ( is_singular() ) {
                $header = $this->present_single( $id, $post_type, 'header' );
        
                if ( ! $header ) {
                    $header = $this->total_single( $id, $post_type, 'header' );
                    if ( ! $header ) {
                        $header = $this->show_all('header');
                    }
                }

                if($header == false){
                    return;
                }else{
                    $this->generate( $header, $path );
                }
            }
        }
            
        function blogfoel_foot_template() {
            global $post;
            
            if ( ! empty( $post ) ) {
                $id        = $post->ID;
                $post_type = get_post_type( $post->ID );
            }
        
            $path      = plugin_dir_path( __FILE__ ) . 'elementor/content/content-footer.php';
            $template_type = $this->template_type();
            
            if ( ! empty( $template_type ) ) {
                $footer = $this->show_template( $template_type, 'footer' );
                if ( ! $footer ) {
                    $footer = $this->show_all( 'footer' );
                }

                if($footer == false){
                    return;
                }else{
                    $this->generate( $footer, $path );
                }
            }
        
            if ( is_singular() ) {
                $footer = $this->present_single( $id, $post_type, 'footer' );
                if ( ! $footer ) {
                    $footer = $this->total_single( $id, $post_type, 'footer' );
                    if ( ! $footer ) {
                        $footer = $this->show_all( 'footer' );
                    }
                }

                if($footer == false){
                    return;
                }else{
                    $this->generate( $footer, $path );
                }
            }
        }
        
        function show_all( $type ) {    
            $args = array(
                'post_type'           => 'blogfoel-hf-builder',
                'orderby'             => 'id',
                'order'               => 'DESC',
                'posts_per_page'      => 1,
                'ignore_sticky_posts' => 1,
                'meta_query'          => array( 
                    array(
                        'key'     => 'template_type',
                        'compare' => 'LIKE',
                        'value'   => $type,
                    ),
                    array(
                        'key'     => 'blogfoel_display_condition',
                        'compare' => 'LIKE',
                        'value'   => 'all',
                    ),
                ),
            );
        
            $header = new \WP_Query( $args );
        
            if ( $header->have_posts() ) {
                return $header;
            } else {
                return false;
            }
        }
        
        function show_template( $template_type, $type ) {
            if ( empty( $template_type ) ) {
                return false;
            }
            $args   = array(
                'post_type'           => 'blogfoel-hf-builder',
                'orderby'             => 'id',
                'order'               => 'DESC',
                'posts_per_page'      => 1,
                'ignore_sticky_posts' => 1,
                'meta_query'          => array( 
                    array(
                        'key'     => 'template_type',
                        'compare' => 'LIKE',
                        'value'   => $type,
                    ),
                    array(
                        'key'     => 'blogfoel_display_condition',
                        'compare' => 'LIKE',
                        'value'   => $template_type,
                    ),
                ),
            );
            $header = new \WP_Query( $args );
        
            if ( $header->have_posts() ) {
                return $header;
            } else {
                return false;
            }
        }
        
        function present_single( $id, $post_type, $type ) {
            if ( ! is_singular()  ) {
                return false;
            }
        
            $args = array(
                'post_type'           => 'blogfoel-hf-builder',
                'orderby'             => 'id',
                'order'               => 'DESC',
                'posts_per_page'      => -1,
                'ignore_sticky_posts' => 1,
                'meta_query'          => array(
                    array(
                        'key'     => 'template_type',
                        'compare' => 'LIKE',
                        'value'   => $type,
                    ),
                    array(
                        'key'     => 'post_type_template',
                        'compare' => 'LIKE',
                        'value'   => $post_type,
                    ),
                ),
            );
            $header = new \WP_Query( $args );
                
            if ( $header->have_posts() ) {
                
                $list_header = $header->posts;
                $current     = array();   
        
                foreach ( $list_header as $key => $post ) {
                    
                    $list_id = get_post_meta( $post->ID, 'post_type_posts', true );
                    if ( ! empty( $list_id ) || 'all' != $list_id ) { 
                        $post_id = explode( ',', $list_id );
                        if ( in_array( $id, $post_id ) ) { 
                            $current[0] = $post;
                        }
                    }
                }
                wp_reset_postdata();
        
                if ( empty( $current ) ) {
        
                    return false;
                } else {
                    $header->posts      = $current;
                    $header->post_count = 1;
                    return $header;
                }
            } else {
                return false;
            }
        }    
            
        function total_single( $post_id, $post_type, $type) {
            if ( ! is_singular() ) {
                return false;
            }
            $args   = array(
                'post_type'           => 'blogfoel-hf-builder',
                'orderby'             => 'id',
                'order'               => 'DESC',
                'posts_per_page'      => 1,
                'ignore_sticky_posts' => 1,
                'meta_query'          => array(
                    array(
                        'key'     => 'template_type',
                        'compare' => 'LIKE',
                        'value'   => $type,
                    ),
                    array(
                        'key'     => 'post_type_template',
                        'compare' => 'LIKE',
                        'value'   => $post_type,
                    ),
                    array(
                        'key'     => 'post_type_posts',
                        'compare' => 'LIKE',
                        'value'   => 'all',
                    ),
                ),
            );
            $header = new \WP_Query( $args );
        
            if ( $header->have_posts() ) {
                return $header;
            } else {
                return false;
            }
        }       
        
        function template_type() {
            $template_type = '';

            if ( class_exists( 'woocommerce' ) ) {
                if( is_front_page() || is_home() ) {
                    $template_type = 'home';
                }elseif ( is_archive() && ! is_shop() && get_post_type() === 'post' ) {
                    $template_type = 'blogArchive';
                }elseif ( is_single() && is_singular('post') ) {
                    $template_type = 'singlePost';
                }elseif ( is_search() ) {
                    $template_type = 'search';
                }elseif ( is_404() ) {
                    $template_type = 'not_found';
                }elseif ( is_shop() ) {
                    $template_type = 'mainShop';
                }elseif ( is_archive() && get_post_type() === 'product' && ! is_shop() ) {
                    $template_type = 'wooArchive';
                }elseif ( is_product() && is_singular('product') ) {
                    $template_type = 'currentProduct';
                }elseif ( is_checkout() ) {
                    $template_type = 'woocheckout';  
                }
            }else{
                if( is_front_page() || is_home() ) {
                    $template_type = 'home';
                }elseif ( is_single() && is_singular('post') ) {
                    $template_type = 'singlePost';
                }elseif ( is_archive() && get_post_type() === 'post' ) {
                    $template_type = 'blogArchive';
                }elseif ( is_search() ) {
                    $template_type = 'search';
                }elseif ( is_404() ) {
                    $template_type = 'not_found';
                }
            }
            return $template_type;
        }
        
        function generate( $content, $path ) {
            if ( $content->have_posts() ) {
                while ( $content->have_posts() ) {
                    $content->the_post();
                    load_template( $path );
                }
                wp_reset_postdata();
            }
        }

        function elementor_maintenance_check(){
            $maintain_mode     = get_option( 'elementor_maintenance_mode_mode' );
            $maintain_template = get_option( 'elementor_maintenance_mode_template_id' );
            if ( 'coming_soon' == $maintain_mode && $maintain_template == $post_id ) {
                return false;
            }
        }

        function full_page_template_id() { 
            global $post;
            
            if ( ! empty( $post ) ) {
                $post_id   = $post->ID;
                $post_type = get_post_type( $post->ID );
            }else{
                $post_id   = NULL;
                $post_type = NULL;
            }
            $this->elementor_maintenance_check();

            $template_type = $this->template_type();
            $id        = '';
        
            if ( $this->show_all('body') || $this->show_template( $template_type , 'body' ) || $this->total_single( $post_id, $post_type, 'body' ) || $this->present_single( $post_id, $post_type, 'body' ) ) {
                
                if ( $this->show_all('body') ) {
                    $full_page = $this->show_all('body');
                }
                if ( $this->show_template( $template_type , 'body' ) ) {
                    $full_page = $this->show_template( $template_type , 'body' );
                }
                if ( $this->total_single( $post_id, $post_type, 'body' ) ) {
                    $full_page = $this->total_single( $post_id, $post_type, 'body' );
                }
                if ( $this->present_single( $post_id, $post_type, 'body' ) ) {
                    $full_page = $this->present_single( $post_id, $post_type, 'body' );
                }
        
                while ( $full_page->have_posts() ) {
                    $full_page->the_post();
                    $id = get_the_ID();
                }
                wp_reset_postdata();
                return $id;
        
            } else {
                return false;
            }
        }
        
        function head_template_id() { 
            global $post;
            
            if ( ! empty( $post ) ) {
                $post_id   = $post->ID;
                $post_type = get_post_type( $post->ID );
            }else{
                $post_id   = NULL;
                $post_type = NULL;
            }
            $this->elementor_maintenance_check();

            $template_type = $this->template_type();
            $id        = '';
        
            if ( $this->show_all('header') || $this->show_template( $template_type , 'header' ) || $this->total_single( $post_id, $post_type, 'header' ) || $this->present_single( $post_id, $post_type, 'header' ) ) {
                
                if ( $this->show_all('header') ) {
                    $header = $this->show_all('header');
                }
                if ( $this->show_template( $template_type , 'header' ) ) {
                    $header = $this->show_template( $template_type , 'header' );
                }
                if ( $this->total_single( $post_id, $post_type, 'header' ) ) {
                    $header = $this->total_single( $post_id, $post_type, 'header' );
                }
                if ( $this->present_single( $post_id, $post_type, 'header' ) ) {
                    $header = $this->present_single( $post_id, $post_type, 'header' );
                }
        
                while ( $header->have_posts() ) {
                    $header->the_post();
                    $id = get_the_ID();
                }
                wp_reset_postdata();
                return $id;
        
            } else {
                return false;
            }
        }
        
        function foot_template_id() {
            global $post;
        
            if ( ! empty( $post ) ) {
                $post_id   = $post->ID;
                $post_type = get_post_type( $post->ID );
            }else{
                $post_id   = NULL;
                $post_type = NULL;
            }
        
            $this->elementor_maintenance_check();

            $template_type = $this->template_type();
            $id        = '';
        
            if ( $this->show_all( 'footer' ) || $this->show_template( $template_type, 'footer' ) || $this->total_single( $post_id, $post_type, 'footer' ) || $this->present_single( $post_id, $post_type, 'footer' ) ) {
                
                if ( $this->show_all( 'footer' ) ) {
                    $header = $this->show_all( 'footer' );
                }
                if ( $this->show_template( $template_type, 'footer' ) ) {
                    $header = $this->show_template( $template_type, 'footer' );
                }
                if ( $this->total_single( $post_id, $post_type, 'footer' ) ) {
                    $header = $this->total_single( $post_id, $post_type, 'footer' );
                }
                if ( $this->present_single( $post_id, $post_type, 'footer' ) ) {
                    $header = $this->present_single( $post_id, $post_type, 'footer' );
                }
        
                while ( $header->have_posts() ) {
                    $header->the_post();
                    $id = get_the_ID();
                }
                wp_reset_postdata();
        
                return $id;
        
            } else {
                return false;
            }
        }

        function blogfoel_pt_input() {

            if ( isset($_POST) ) {
                $post_type = $_POST['post_type1'];
                $post_type =  implode(",",$post_type);
            }
                
            if ( 'all' !== $post_type && 'blogArchive' !== $post_type && 'search' !== $post_type && 'home' !== $post_type && 'not_found' !== $post_type ) : ?>
        
            <input type="hidden" name="post_type_posts" value="all">
            <input type="hidden" name="post_type_template" value="<?php echo esc_attr( $post_type ); ?>" class="post-type-template">
        
            <?php endif; die();
        }          
}