<?php 
// Exit if accessed directly
if ( !defined( 'ABSPATH' ) ) exit;

/**
 * After setup theme hook
 */
function crunchy_recipe_theme_setup(){
    /*
     * Make child theme available for translation.
     * Translations can be filed in the /languages/ directory.
     */
    load_child_theme_textdomain( 'crunchy-recipe', get_stylesheet_directory() . '/languages' );

    add_image_size( 'crunchy-recipe-slider-five', 360, 480 , true );
}
add_action( 'after_setup_theme', 'crunchy_recipe_theme_setup', 100 );

function crunchy_recipe_styles() {
    // Use minified libraries if SCRIPT_DEBUG is false
    $suffix   = ( defined( 'SCRIPT_DEBUG' ) && SCRIPT_DEBUG ) ? '' : '.min';
    $my_theme = wp_get_theme();
    $version  = $my_theme['Version'];

    wp_enqueue_style( 'yummy-bites-style', get_template_directory_uri()  . '/style' . $suffix . '.css', array(), array( ) );
    wp_enqueue_style( 'crunchy-recipe', get_stylesheet_directory_uri()  . '/style' . $suffix . '.css', array( 'yummy-bites-style' ), $version );

    wp_enqueue_script( 'crunchy-recipe', get_stylesheet_directory_uri() . '/js/custom.js', array( 'jquery'), $version, true );

}
add_action( 'wp_enqueue_scripts', 'crunchy_recipe_styles' );

/**
 * Typography Defaults
 * 
 * @return array
 */
function yummy_bites_get_typography_defaults(){
    $defaults = array(   
        'primary_font' => array(
            'family'         => 'Source Sans Pro',
            'variants'       => '',
            'category'       => '',
            'weight'         => '400',
            'transform'      => 'none',
            'desktop' => array(
                'font_size'      => 18,
                'line_height'    => 1.75,
                'letter_spacing' => 0,
            ),
            'tablet' => array(
                'font_size'      => 18,
                'line_height'    => 1.75,
                'letter_spacing' => 0,
            ),
            'mobile' => array(
                'font_size'      => 18,
                'line_height'    => 1.75,
                'letter_spacing' => 0,
            )
        ),
        'site_title' => array(
            'family'    => 'Default Family',
            'variants'  => '',
            'category'  => '',
            'weight'    => 'bold',
            'transform' => 'none',
            'desktop' => array(
                'font_size'      => 28,
                'line_height'    => 1.4,
                'letter_spacing' => 0
            ),
            'tablet' => array(
                'font_size'      => 28,
                'line_height'    => 1.4,
                'letter_spacing' => 0,
            ),
            'mobile' => array(
                'font_size'      => 28,
                'line_height'    => 1.4,
                'letter_spacing' => 0,
            )
        ),
        'button' => array(
            'family'         => 'Default Family',
            'variants'       => '',
            'category'       => '',
            'weight'         => '500',
            'transform'      => 'none',
            'desktop' => array(
                'font_size'      => 18,
                'line_height'    => 1.6,
                'letter_spacing' => 0,
            ),
            'tablet' => array(
                'font_size'      => 18,
                'line_height'    => 1.6,
                'letter_spacing' => 0,
            ),
            'mobile' => array(
                'font_size'      => 18,
                'line_height'    => 1.6,
                'letter_spacing' => 0,
            )
        ),
        'heading_one' => array(
            'family'      => 'Bree Serif',
            'variants'    => '',
            'category'    => '',
            'weight'      => '400',
            'transform'   => 'none',
            'desktop' => array(
                'font_size'      => 54,
                'line_height'    => 1.3,
                'letter_spacing' => 0,
            ),
            'tablet' => array(
                'font_size'      => 45,
                'line_height'    => 1.3,
                'letter_spacing' => 0,
            ),
            'mobile' => array(
                'font_size'      => 40.5,
                'line_height'    => 1.3,
                'letter_spacing' => 0,
            )
        ),
        'heading_two' => array(
            'family'      => 'Bree Serif',
            'variants'    => '',
            'category'    => '',
            'weight'      => '400',
            'transform'   => 'none',
            'desktop' => array(
                'font_size'      => 45,
                'line_height'    => 1.3,
                'letter_spacing' => 0,
            ),
            'tablet' => array(
                'font_size'      => 40.5,
                'line_height'    => 1.3,
                'letter_spacing' => 0,
            ),
            'mobile' => array(
                'font_size'      => 32.4,
                'line_height'    => 1.3,
                'letter_spacing' => 0,
            )
        ),
        'heading_three' => array(
            'family'      => 'Bree Serif',
            'variants'    => '',
            'category'    => '',
            'weight'      => '400',
            'transform'   => 'none',
            'desktop' => array(
                'font_size'      => 36,
                'line_height'    => 1.4,
                'letter_spacing' => 0,
            ),
            'tablet' => array(
                'font_size'      => 32.4,
                'line_height'    => 1.4,
                'letter_spacing' => 0,
            ),
            'mobile' => array(
                'font_size'      => 28.8,
                'line_height'    => 1.4,
                'letter_spacing' => 0,
            )
        ),
        'heading_four' => array(
            'family'      => 'Bree Serif',
            'variants'    => '',
            'category'    => '',
            'weight'      => '400',
            'transform'   => 'none',
            'desktop' => array(
                'font_size'      => 31.5,
                'line_height'    => 1.5,
                'letter_spacing' => 0,
            ),
            'tablet' => array(
                'font_size'      => 28.8,
                'line_height'    => 1.5,
                'letter_spacing' => 0,
            ),
            'mobile' => array(
                'font_size'      => 27,
                'line_height'    => 1.5,
                'letter_spacing' => 0,
            )
        ),
        'heading_five' => array(
            'family'      => 'Bree Serif',
            'variants'    => '',
            'category'    => '',
            'weight'      => '400',
            'transform'   => 'none',
            'desktop' => array(
                'font_size'      => 27,
                'line_height'    => 1.5,
                'letter_spacing' => 0,
            ),
            'tablet' => array(
                'font_size'      => 27,
                'line_height'    => 1.5,
                'letter_spacing' => 0,
            ),
            'mobile' => array(
                'font_size'      => 25.2,
                'line_height'    => 1.5,
                'letter_spacing' => 0,
            )
        ),
        'heading_six' => array(
            'family'      => 'Bree Serif',
            'variants'    => '',
            'category'    => '',
            'weight'      => '400',
            'transform'   => 'none',
            'desktop' => array(
                'font_size'      => 19.8,
                'line_height'    => 1.5,
                'letter_spacing' => 0,
            ),
            'tablet' => array(
                'font_size'      => 18,
                'line_height'    => 1.5,
                'letter_spacing' => 0,
            ),
            'mobile' => array(
                'font_size'      => 18,
                'line_height'    => 1.5,
                'letter_spacing' => 0,
            )
        )
    );

    return apply_filters( 'yummy_bites_typography_options_defaults', $defaults ); 
}

function yummy_bites_get_color_defaults(){
    $defaults = array(
		'primary_color'             => '#b65871',
		'secondary_color'           => '#034f4f',
		'body_font_color'           => '#39433F',
		'heading_color'             => '#07120D',
		'site_bg_color'             => '#FFFFFF',
        'accent_color_one'          => '#FBEDFF',
		'accent_color_two'          => '#F6F4F0',
		'site_title_color'          => '#000000',
		'site_tagline_color'        => '#333333',
		'btn_text_color_initial'    => '#ffffff',
		'btn_text_color_hover'      => '#ffffff',
		'btn_bg_color_initial'      => '#EDA602',
		'btn_bg_color_hover'        => '#227755',
		'btn_border_color_initial'  => '#EDA602',
		'btn_border_color_hover'    => '#227755',
		'foot_text_color'           => 'rgba(255,255,255,0.9)',
		'foot_bg_color'             => '#0D0C0C',
		'foot_widget_heading_color' => '#FFFFFF',
		'abt_bg_color'              => '#F4F6F5',
		'abt_title_color'           => '#07120D',
		'abt_desc_color'            => '#39433F',
    );

    return apply_filters( 'yummy_bites_color_options_defaults', $defaults );
}

/**
 * Header Start
*/
function yummy_bites_header(){ 
    $defaults               = yummy_bites_get_general_defaults();
    $siteDefaults           = yummy_bites_get_site_defaults();
    
    $ed_social_media        = get_theme_mod( 'ed_social_links', $defaults['ed_social_links'] );
    $social_media_order     = get_theme_mod( 'social_media_order', $defaults['social_media_order']  );
    $ed_social_media_newtab = get_theme_mod( 'ed_social_links_new_tab', $defaults['ed_social_links_new_tab'] );
    $ed_cart                = get_theme_mod( 'ed_woo_cart', $defaults['ed_woo_cart'] );
    $ed_search              = get_theme_mod( 'ed_header_search', $defaults['ed_header_search'] );
    $blogname               = get_option('blogname');
    $hideblogname           = get_theme_mod('hide_title', $siteDefaults['hide_title']);
    $blogdesc               = get_option('blogdescription');
    $hideblogdesc           = get_theme_mod('hide_tagline', $siteDefaults['hide_tagline']);
    ?>
    <header id="masthead" class="site-header style-seven" itemscope itemtype="http://schema.org/WPHeader">
        <?php if( $ed_social_media || $ed_search || has_nav_menu( 'secondary' ) ){ ?>
            <div class="header-top">
                <div class="container">
                    <div class="header-left">
                        <?php yummy_bites_secondary_navigation(); ?>
                    </div>
                    <?php if ( $ed_search || $ed_social_media ){ 
                        echo '<div class="header-right">';
                            $social_icons = new Yummy_Bites_Social_Lists;
                            $social_icons->yummy_bites_social_links( $ed_social_media, $ed_social_media_newtab, $social_media_order );
                            if( $ed_search ) get_search_form();
                        echo '</div>';
                    } ?>
                </div>
            </div>
        <?php } 
        if( has_nav_menu( 'primary') || has_custom_logo() || (!empty($blogname) && !$hideblogname) || ( !empty($blogdesc) && !$hideblogdesc) || $ed_cart ) { ?>
            <div class="header-main"> 
                <div class="container">
                    <?php 
                    yummy_bites_site_branding(); 
                    yummy_bites_primary_navigation();
                    
                    if ( yummy_bites_is_woocommerce_activated() && $ed_cart ) yummy_bites_wc_cart_count();
                    ?>
                </div>
            </div>
        <?php }
        yummy_bites_mobile_navigation(); ?>
    </header>
    <?php 
}


/**
 * Banner
*/
function yummy_bites_slider_meta_contents(){
    $defaults  = yummy_bites_get_banner_defaults();
    $read_more = get_theme_mod('slider_readmore', $defaults['slider_readmore']);

    if( yummy_bites_pro_is_activated() ){
        $prodefaults   = yummy_bites_pro_get_customizer_layouts_defaults();
        $slider_layout = get_theme_mod( 'slider_layouts', $prodefaults['slider_layouts'] );
    }

    if ( yummy_bites_is_delicious_recipe_activated() && DELICIOUS_RECIPE_POST_TYPE == get_post_type() ) {
        if ( ( yummy_bites_pro_is_activated() && ( $slider_layout == 'one' ||  $slider_layout == 'three' ) )  ){
            echo '<div class="cat-links-wrap">';
                yummy_bites_recipe_category();
            echo '</div>';
        }
        the_title('<h2 class="item-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');
        if ( ( yummy_bites_pro_is_activated() &&  $slider_layout == 'one') ){
            echo '<div class="item-content">';
                the_excerpt();
            echo '</div>';
        }
        echo '<footer class="item-footer">';
            if ( ( yummy_bites_pro_is_activated() &&  $slider_layout == 'one') ){
                if ( $read_more ) echo '<div class="btn-wrapper"> <a href="' . esc_url( get_the_permalink()) . '" class="btn-primary">' . esc_html( $read_more ) . '</a></div>';
            }
            echo '<div class="recipe-item-meta">';
                yummy_bites_prep_time();
                yummy_bites_difficulty_level();
                yummy_bites_recipe_rating();
            echo '</div>';
        echo '</footer>';
    }elseif( 'post' == get_post_type() ){
        if ( ( yummy_bites_pro_is_activated() && ( $slider_layout == 'one' ||  $slider_layout == 'three' ) )  || ! yummy_bites_pro_is_activated() ){
            echo '<div class="cat-links-wrap">';
                yummy_bites_category();
            echo '</div>';
        }
        the_title('<h2 class="item-title"><a href="' . esc_url(get_permalink()) . '" rel="bookmark">', '</a></h2>');

        if ( ( yummy_bites_pro_is_activated() &&  $slider_layout == 'one') ){
            echo '<div class="item-content">';
                the_excerpt();
            echo '</div>';
        }

        if ( ( yummy_bites_pro_is_activated() &&  $slider_layout == 'one') ){
            echo '<footer class="item-footer">';
                if ( $read_more ) echo '<div class="btn-wrapper"> <a href="' . esc_url( get_the_permalink()) . '" class="btn-primary">' . esc_html( $read_more ) . '</a></div>';
            echo '</footer>';
        }
    }
}

/**
 * Demo Importer
 */
function yummy_bites_demo_importer_checked() {
    if (function_exists('DEMO_IMPORTER_PLUS_setup')) {
        add_filter(
            'demo_importer_plus_api_id',
            function () {
                return  array( '201', '394', '398', '405', '97','168','176','180','181', '182', '148', '155', '158', '161', '166' );
            }
        );
    }
}

/**
 * Footer Bottom
*/
function yummy_bites_footer_bottom(){ ?>
    <div class="footer-b">
		<div class="container">
            <div class="footer-bottom-t">
                <div class="site-info">            
                    <?php
                        yummy_bites_get_footer_copyright();
                        if( yummy_bites_pro_is_activated() ){
                            $partials = new Yummy_Bites_Partials;
                            $partials->yummy_bites_pro_ed_author_link();
                            $partials->yummy_bites_pro_ed_wp_link();
                        }else {
                            echo esc_html__( ' Crunchy Recipe | Developed By ', 'crunchy-recipe' ); 
                            echo '<a href="' . esc_url( 'https://wpdelicious.com/' ) .'" rel="nofollow" target="_blank">' . esc_html__( 'WP Delicious', 'crunchy-recipe' ) . '</a>.';                
                            printf( esc_html__( ' Powered by %s. ', 'crunchy-recipe' ), '<a href="'. esc_url( 'https://wordpress.org/', 'yummy-bites' ) .'" rel="nofollow" target="_blank">WordPress</a>' );
                        }
                        if( function_exists( 'the_privacy_policy_link' ) ){
                            the_privacy_policy_link();
                        }
                    ?> 
                </div>
                <?php yummy_bites_footer_navigation(); ?>
            </div>
		</div>
	</div>
    <?php
}