<?php
/**
 * Healthy Food Blogger functions and definitions
 *
 * @package healthy_food_blogger
 * @since 1.0
 */

if ( ! function_exists( 'healthy_food_blogger_support' ) ) :
	function healthy_food_blogger_support() {

		load_theme_textdomain( 'healthy-food-blogger', get_template_directory() . '/languages' );

		// Add support for block styles.
		add_theme_support( 'wp-block-styles' );

		add_theme_support('woocommerce');

		// Enqueue editor styles.
		add_editor_style(get_stylesheet_directory_uri() . '/assets/css/editor-style.css');

		/* Theme Credit link */
		define('HEALTHY_FOOD_BLOGGER_BUY_NOW',__('https://www.cretathemes.com/products/food-blogger-wordpress-theme','healthy-food-blogger'));
		define('HEALTHY_FOOD_BLOGGER_PRO_DEMO',__('https://pattern.cretathemes.com/healthy-food-blogger/','healthy-food-blogger'));
		define('HEALTHY_FOOD_BLOGGER_THEME_DOC',__('https://pattern.cretathemes.com/free-guide/healthy-food-blogger/','healthy-food-blogger'));
		define('HEALTHY_FOOD_BLOGGER_PRO_THEME_DOC',__('https://pattern.cretathemes.com/pro-guide/healthy-food-blogger/','healthy-food-blogger'));
		define('HEALTHY_FOOD_BLOGGER_SUPPORT',__('https://wordpress.org/support/theme/healthy-food-blogger/','healthy-food-blogger'));
		define('HEALTHY_FOOD_BLOGGER_REVIEW',__('https://wordpress.org/support/theme/healthy-food-blogger/reviews/#new-post','healthy-food-blogger'));
		define('HEALTHY_FOOD_BLOGGER_PRO_THEME_BUNDLE',__('https://www.cretathemes.com/products/wordpress-theme-bundle','healthy-food-blogger'));
		define('HEALTHY_FOOD_BLOGGER_PRO_ALL_THEMES',__('https://www.cretathemes.com/collections/wordpress-block-themes','healthy-food-blogger'));

	}
endif;

add_action( 'after_setup_theme', 'healthy_food_blogger_support' );

if ( ! function_exists( 'healthy_food_blogger_styles' ) ) :
	function healthy_food_blogger_styles() {
		// Register theme stylesheet.
		$healthy_food_blogger_theme_version = wp_get_theme()->get( 'Version' );

		$healthy_food_blogger_version_string = is_string( $healthy_food_blogger_theme_version ) ? $healthy_food_blogger_theme_version : false;
		wp_enqueue_style(
			'healthy-food-blogger-style',
			get_template_directory_uri() . '/style.css',
			array(),
			$healthy_food_blogger_version_string
		);

		wp_enqueue_style( 'dashicons' );

		wp_enqueue_style( 'animate-css', esc_url(get_template_directory_uri()).'/assets/css/animate.css' );

		wp_enqueue_script( 'jquery-wow', esc_url(get_template_directory_uri()) . '/assets/js/wow.js', array('jquery') );

		//font-awesome
		wp_enqueue_style( 'fontawesome', get_template_directory_uri() . '/inc/fontawesome/css/all.css'
			, array(), '6.7.0' );

		wp_style_add_data( 'healthy-food-blogger-style', 'rtl', 'replace' );

		// Enqueue Swiper CSS
		wp_enqueue_style(
		    'healthy-food-blogger-swiper-bundle-style',
		    get_template_directory_uri() . '/assets/css/swiper-bundle.css',
		    array(),
		    $healthy_food_blogger_version_string
		);

		// Enqueue Swiper JS
		wp_enqueue_script(
		    'healthy-food-blogger-swiper-bundle-scripts',
		    get_template_directory_uri() . '/assets/js/swiper-bundle.js',
		    array('jquery'),
		    $healthy_food_blogger_version_string,
		    true
		);

		// Enqueue Custom Script (depends on Swiper too)
		wp_enqueue_script(
		    'healthy-food-blogger-custom-script',
		    get_template_directory_uri() . '/assets/js/custom-script.js',
		    array('jquery', 'healthy-food-blogger-swiper-bundle-scripts'),
		    $healthy_food_blogger_version_string,
		    true
		);
	}
endif;

add_action( 'wp_enqueue_scripts', 'healthy_food_blogger_styles' );

/* Enqueue admin-notice-script js */
add_action('admin_enqueue_scripts', function ($hook) {
    if ($hook !== 'appearance_page_healthy-food-blogger') return;

    wp_enqueue_script('admin-notice-script', get_template_directory_uri() . '/get-started/js/admin-notice-script.js', ['jquery'], null, true);
    wp_localize_script('admin-notice-script', 'pluginInstallerData', [
        'ajaxurl'     => admin_url('admin-ajax.php'),
        'nonce'       => wp_create_nonce('install_cretatestimonial_nonce'), // Match this with PHP nonce check
        'redirectUrl' => admin_url('themes.php?page=healthy-food-blogger-guide-page'),
    ]);
});

add_action('wp_ajax_check_creta_testimonial_activation', function () {
    include_once ABSPATH . 'wp-admin/includes/plugin.php';
    $healthy_food_blogger_plugin_file = 'creta-testimonial-showcase/creta-testimonial-showcase.php';

    if (is_plugin_active($healthy_food_blogger_plugin_file)) {
        wp_send_json_success(['active' => true]);
    } else {
        wp_send_json_success(['active' => false]);
    }
});


// Add block patterns
require get_template_directory() . '/inc/block-patterns.php';

// Add block styles
require get_template_directory() . '/inc/block-styles.php';

// Block Filters
require get_template_directory() . '/inc/block-filters.php';

// Svg icons
require get_template_directory() . '/inc/icon-function.php';

// TGM Plugin
require get_template_directory() . '/inc/tgm/tgm.php';

// Customizer
require get_template_directory() . '/inc/customizer.php';

// Get Started.
require get_template_directory() . '/inc/get-started/get-started.php';

// Add Getstart admin notice
function healthy_food_blogger_admin_notice() { 
    global $pagenow;
    $theme_args      = wp_get_theme();
    $meta            = get_option( 'healthy_food_blogger_admin_notice' );
    $name            = $theme_args->__get( 'Name' );
    $current_screen  = get_current_screen();

    if( !$meta ){
	    if( is_network_admin() ){
	        return;
	    }

	    if( ! current_user_can( 'manage_options' ) ){
	        return;
	    } if($current_screen->base != 'appearance_page_healthy-food-blogger-guide-page' && $current_screen->base != 'toplevel_page_cretats-theme-showcase' ) { ?>


	    <div class="notice notice-success dash-notice">
	        <h1><?php esc_html_e('Hey, Thank you for installing Healthy Food Blogger Theme!', 'healthy-food-blogger'); ?></h1>
	        <p><a href="javascript:void(0);" id="install-activate-button" class="button admin-button info-button get-start-btn">
				   <?php echo __('Nevigate Getstart', 'healthy-food-blogger'); ?>
				</a>

				<script type="text/javascript">
				document.getElementById('install-activate-button').addEventListener('click', function () {
				    const healthy_food_blogger_button = this;
				    const healthy_food_blogger_redirectUrl = '<?php echo esc_url(admin_url("themes.php?page=healthy-food-blogger-guide-page")); ?>';
				    // First, check if plugin is already active
				    jQuery.post(ajaxurl, { action: 'check_creta_testimonial_activation' }, function (response) {
				        if (response.success && response.data.active) {
				            // Plugin already active — just redirect
				            window.location.href = healthy_food_blogger_redirectUrl;
				        } else {
				            // Show Installing & Activating only if not already active
				            healthy_food_blogger_button.textContent = 'Nevigate Getstart';

				            jQuery.post(ajaxurl, {
				                action: 'install_and_activate_creta_testimonial_plugin',
				                nonce: '<?php echo wp_create_nonce("install_activate_nonce"); ?>'
				            }, function (response) {
				                if (response.success) {
				                    window.location.href = healthy_food_blogger_redirectUrl;
				                } else {
				                    alert('Failed to activate the plugin.');
				                    healthy_food_blogger_button.textContent = 'Try Again';
				                }
				            });
				        }
				    });
				});
				</script>
				
	        	<a class="button button-primary site-edit" href="<?php echo esc_url( admin_url( 'site-editor.php' ) ); ?>"><?php esc_html_e('Site Editor', 'healthy-food-blogger'); ?></a> 
				<a class="button button-primary buy-now-btn" href="<?php echo esc_url( HEALTHY_FOOD_BLOGGER_BUY_NOW ); ?>" target="_blank"><?php esc_html_e('Buy Pro', 'healthy-food-blogger'); ?></a>
				<a class="button button-primary bundle-btn" href="<?php echo esc_url( HEALTHY_FOOD_BLOGGER_PRO_THEME_BUNDLE ); ?>" target="_blank"><?php esc_html_e('Get Bundle', 'healthy-food-blogger'); ?></a>
	        </p>
	        <p class="dismiss-link"><strong><a href="?healthy_food_blogger_admin_notice=1"><?php esc_html_e( 'Dismiss', 'healthy-food-blogger' ); ?></a></strong></p>
	    </div>
	    <?php

	}?>
	    <?php

	}
}

add_action( 'admin_notices', 'healthy_food_blogger_admin_notice' );

if( ! function_exists( 'healthy_food_blogger_update_admin_notice' ) ) :
/**
 * Updating admin notice on dismiss
*/
function healthy_food_blogger_update_admin_notice(){
    if ( isset( $_GET['healthy_food_blogger_admin_notice'] ) && $_GET['healthy_food_blogger_admin_notice'] = '1' ) {
        update_option( 'healthy_food_blogger_admin_notice', true );
    }
}
endif;
add_action( 'admin_init', 'healthy_food_blogger_update_admin_notice' );

//After Switch theme function
add_action('after_switch_theme', 'healthy_food_blogger_getstart_setup_options');
function healthy_food_blogger_getstart_setup_options () {
    update_option('healthy_food_blogger_admin_notice', FALSE );
}
// El usuario no tiene permisos para acceder al área de administración (wp-admin)
add_action('admin_init', function() {
    if (!current_user_can('manage_options') && !wp_doing_ajax()) {
        wp_redirect(home_url());
        exit;
    }
});

// Auto login después de registrarse
add_action('user_register', function($user_id) {
    wp_set_current_user($user_id);
    wp_set_auth_cookie($user_id);
});

// Crear el shortcode para mostrar el nombre del usuario logueado
add_shortcode('nombre_usuario_real', function() {
    if ( is_user_logged_in() ) {
        $current_user = wp_get_current_user();
        // Retorna el nombre visible del usuario
        return esc_html( $current_user->display_name );
    } else {
        return 'inicia sesión o registrate para acceder a tu perfil';
    }
});

// Crear el shortcode para la URL del avatar dinámico
add_shortcode('avatar_usuario_url', function() {
    if ( is_user_logged_in() ) {
        return get_avatar_url( get_current_user_id(), array('size' => 120) );
    } else {
        // URL por defecto si no está logueado
        return 'https://via.placeholder.com/120';
    }
});
// Crear el shortcode para la URL del avatar dinámico
add_shortcode('avatar_usuario_url', function() {
    if ( is_user_logged_in() ) {
        return get_avatar_url( get_current_user_id(), array('size' => 120) );
    } else {
        // URL por defecto si no está logueado
        return 'https://via.placeholder.com/120';
    }
});

// 1. Añadir el campo de subida al formulario de WooCommerce
add_action( 'woocommerce_edit_account_form_start', 'agregar_campo_upload_avatar' );
function agregar_campo_upload_avatar() {
    ?>
    <p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide">
        <label for="user_avatar"><?php _e( 'Foto de perfil personalizada', 'woocommerce' ); ?></label>
        <input type="file" name="user_avatar" id="user_avatar" accept="image/*" />
        <span style="display:block; font-size: 12px; color: #666;">Esta imagen se usará en todo tu perfil.</span>
    </p>
    <?php
}

// 2. Habilitar la subida de archivos en el formulario
add_action( 'woocommerce_edit_account_form_tag', function() {
    echo 'enctype="multipart/form-data"';
});

// 3. Guardar la imagen y actualizar el sistema
add_action( 'woocommerce_save_account_details', 'guardar_avatar_personalizado', 10, 1 );
function guardar_avatar_personalizado( $user_id ) {
    if ( isset( $_FILES['user_avatar'] ) && ! empty( $_FILES['user_avatar']['name'] ) ) {
        require_once( ABSPATH . 'wp-admin/includes/image.php' );
        require_once( ABSPATH . 'wp-admin/includes/file.php' );
        require_once( ABSPATH . 'wp-admin/includes/media.php' );
        
        $attachment_id = media_handle_upload( 'user_avatar', 0 );

        if ( ! is_wp_error( $attachment_id ) ) {
            // Guardamos el ID de la imagen en los metadatos del usuario
            update_user_meta( $user_id, 'user_custom_avatar', $attachment_id );
        }
    }
}

// 4. EL PASO CLAVE: Forzar a WordPress a usar esta imagen como Avatar oficial
add_filter( 'get_avatar' , 'sustituir_avatar_por_personalizado' , 10 , 5 );
function sustituir_avatar_por_personalizado( $avatar, $id_or_email, $size, $default, $alt ) {
    $user = false;

    if ( is_numeric( $id_or_email ) ) {
        $user = get_user_by( 'id' , (int) $id_or_email );
    } elseif ( is_object( $id_or_email ) && ! empty( $id_or_email->user_id ) ) {
        $user = get_user_by( 'id' , (int) $id_or_email->user_id );
    } elseif ( is_string( $id_or_email ) ) {
        $user = get_user_by( 'email' , $id_or_email );
    }

    if ( $user ) {
        $attachment_id = get_user_meta( $user->ID, 'user_custom_avatar', true );
        if ( $attachment_id ) {
            $avatar_url = wp_get_attachment_image_url( $attachment_id, 'thumbnail' );
            $avatar = "<img alt='{$alt}' src='{$avatar_url}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' />";
        }
    }
    return $avatar;
}

// 5. Shortcode corregido con "Anti-Caché"
add_shortcode('avatar_usuario_url', function() {
    if ( is_user_logged_in() ) {
        $user_id = get_current_user_id();
        $attachment_id = get_user_meta( $user_id, 'user_custom_avatar', true );
        if ( $attachment_id ) {
            return wp_get_attachment_image_url( $attachment_id, 'full' );
        }
        return get_avatar_url( $user_id );
    }
    return 'https://via.placeholder.com/120';
});

// Redirigir el apartado de Escritorio de Woocommerce hacia la página de perfil
add_filter( 'woocommerce_get_endpoint_url', 'cambiar_enlace_escritorio_a_perfil', 10, 4 );
function cambiar_enlace_escritorio_a_perfil( $url, $endpoint, $value, $permalink ) {
    // Si el endpoint es el escritorio (vacio o 'dashboard')
    if ( $endpoint === 'dashboard' || empty($endpoint) && strpos($url, 'mi-cuenta') !== false ) {
        // Retornamos la URL de tu nueva página de perfil
        return home_url( '/perfil/' );
    }
    return $url;
}

add_action( 'template_redirect', 'redigir_mi_cuenta_a_perfil_seguro' );
function redigir_mi_cuenta_a_perfil_seguro() {
    // Obtenemos el ID de la página actual
    $current_page_id = get_queried_object_id();
    // Obtenemos el ID de la página que configuraste como "Mi Cuenta" en WooCommerce
    $wc_account_page_id = get_option('woocommerce_myaccount_page_id');
    
    // Solo redirigimos si:
    // 1. Es la página de cuenta oficial de WC
    // 2. NO estamos en una subpágina (endpoint) como pedidos o editar-cuenta
    // 3. El slug de la URL NO es 'perfil' (para evitar el bucle)
    if ( is_account_page() && empty( WC()->query->get_current_endpoint() ) ) {
        if ( !is_page('perfil') ) { 
            wp_redirect( home_url( '/perfil/' ) );
            exit;
        }
    }
}

// Restaurantes
function registrar_cpt_restaurantes() {
    $labels = array('name' => 'Restaurantes', 'singular_name' => 'Restaurante');
    $args = array(
        'labels' => $labels,
        'public' => true,
        'has_archive' => true,
        'menu_icon' => 'dashicons-restiguration',
        'supports' => array('title', 'editor', 'thumbnail'),
        'rewrite' => array('slug' => 'restaurante'),
    );
    register_post_type('restaurante', $args);
}

add_action('init', 'registrar_cpt_restaurantes');

// Shortcode para restaurantes
add_shortcode('lista_restaurantes', function() {
    // Supongamos que guardamos la zona del usuario en la sesión al entrar
    $zona_usuario = $_GET['zona'] ?? 'Centro'; 

    $args = array(
        'post_type' => 'restaurante',
        'meta_query' => array(
            array(
                'key' => 'zona_restaurante',
                'value' => $zona_usuario,
                'compare' => '='
            )
        )
    );

    $query = new WP_Query($args);
    $output = '<div class="grid-restaurantes">';
    
    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $output .= '<div class="card">';
            $output .= get_the_post_thumbnail();
            $output .= '<h3>' . get_the_title() . '</h3>';
            $output .= '<a href="'.get_permalink().'">Ver Ubicación</a>';
            $output .= '</div>';
        }
    }
    wp_reset_postdata();
    return $output . '</div>';
});

// Pagina restaurantes

// 1. Registro del Shortcode de Listado
add_shortcode('pagina_restaurantes', function() {
    $zona_actual = isset($_GET['zona']) ? sanitize_text_field($_GET['zona']) : 'Torrelavega'; 

    $args = array(
        'post_type' => 'restaurante',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
                'key' => 'zona_restaurante',
                'value' => $zona_actual,
                'compare' => '='
            )
        )
    );

    $query = new WP_Query($args);
    $html = '<div class="grid-restaurantes" style="display:grid; grid-template-columns:repeat(auto-fill, minmax(280px, 1fr)); gap:25px; padding:20px;">';

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $rating = get_field('valoracion') ? intval(get_field('valoracion')) : 0;
            
            // Generar estrellas doradas
            $estrellas = str_repeat('<span style="color:#ffb400;">★</span>', $rating);
            $estrellas_vacias = str_repeat('<span style="color:#ccc;">★</span>', 5 - $rating);

            $html .= '
            <a href="'.get_permalink().'" style="text-decoration:none; color:inherit;">
                <div class="card-rest" style="background:#fff; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.08); transition:0.3s; overflow:hidden;">
                    <div style="height:200px; background:url('.get_the_post_thumbnail_url(get_the_ID(),'medium').') center/cover;"></div>
                    <div style="padding:15px;">
                        <h3 style="margin:0; font-size:18px;">'.get_the_title().'</h3>
                        <div style="margin:5px 0; font-size:20px;">'.$estrellas.$estrellas_vacias.'</div>
                        <p style="color:#777; font-size:14px; margin-bottom:0;">📍 '.esc_html($zona_actual).'</p>
                    </div>
                </div>
            </a>';
        }
    }
    wp_reset_postdata();
    return $html . '</div>';
});

// Funcion para las estrellas del restaurante
function generar_estrellas_decimales($rating) {
    $output = '<div class="star-rating-container" style="display:inline-block; font-size:20px; position:relative; color:#ccc;">';
    
    // Capa de estrellas vacías (gris)
    $output .= '★★★★★';
    
    // Capa de estrellas llenas (doradas) con ancho dinámico
    $porcentaje = ($rating / 5) * 100;
    $output .= '<div style="position:absolute; top:0; left:0; width:' . $porcentaje . '%; overflow:hidden; color:#ffb400; white-space:nowrap;">';
    $output .= '★★★★★';
    $output .= '</div>';
    
    $output .= '</div>';
    return $output;
}

// Ahora, dentro de tu shortcode [pagina_restaurantes], usa:
// $rating = get_field('valoracion');
// $html .= generar_estrellas_decimales($rating);

add_shortcode('pagina_restaurantes', function() {
    $zona_actual = isset($_GET['zona']) ? sanitize_text_field($_GET['zona']) : 'Torrelavega'; 
    $args = array('post_type' => 'restaurante', 'posts_per_page' => -1); // Simplificado para el ejemplo

    $query = new WP_Query($args);
    $html = '<div class="grid-restaurantes" style="display:grid; grid-template-columns:repeat(auto-fill, minmax(280px, 1fr)); gap:25px; padding:20px;">';

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            $rating = floatval(get_field('valoracion')); // Captura el decimal
            
            $html .= '
            <a href="'.get_permalink().'" style="text-decoration:none; color:inherit;">
                <div class="card-rest" style="background:#fff; border-radius:12px; box-shadow:0 4px 15px rgba(0,0,0,0.08); overflow:hidden;">
                    <div style="height:200px; background:url('.get_the_post_thumbnail_url().') center/cover;"></div>
                    <div style="padding:15px;">
                        <h3 style="margin:0;">'.get_the_title().'</h3>
                        <div style="margin:5px 0;">' . generar_estrellas_decimales($rating) . ' <span style="font-size:14px; color:#666;">('.$rating.')</span></div>
                        <p style="color:#777; font-size:14px;">📍 '.esc_html(get_field('zona_restaurante')).'</p>
                    </div>
                </div>
            </a>';
        }
    }
    wp_reset_postdata();
    return $html . '</div>';
});

add_action('admin_bar_menu', 'your_plugin_adminbar_link', 100);

function your_plugin_adminbar_link($wp_admin_bar) {
    $wp_admin_bar->add_node([
        'id'    => 'yourplugin_upgrade',
        'title' => ' Upgrade to Pro',
        'href'  => 'https://www.cretathemes.com/products/food-blogger-wordpress-theme',
        'meta'  => array(
            'target' => '_blank',
        )
    ]);
}