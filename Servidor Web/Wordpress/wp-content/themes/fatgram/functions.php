<?php
function fatgram_setup() {
// Soporte para imágenes destacadas en posts
    add_theme_support('post-thumbnails');
    // Título dinámico en la pestaña del navegador
    add_theme_support('title-tag');

    // --- NUEVO: ACTIVAR SOPORTE PARA LOGO PERSONALIZADO ---
    add_theme_support('custom-logo', array(
        'height'      => 100,    // Altura sugerida
        'width'       => 300,    // Ancho sugerido
        'flex-height' => true,   // Permitir que sea flexible
        'flex-width'  => true,   // Permitir que sea flexible
        'header-text' => array('site-title', 'site-description'), // Ocultar el texto si hay imagen
    ));
}
add_action('after_setup_theme', 'fatgram_setup');

function fatgram_scripts() {
    // Cargar CSS principal
    wp_enqueue_style('fatgram-style', get_stylesheet_uri());
    // Font Awesome
    wp_enqueue_style('font-awesome', 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css');
}
add_action('wp_enqueue_scripts', 'fatgram_scripts');
// 1. Obtener número de seguidores
function get_fatgram_follower_count($user_id) {
    $followers = get_user_meta($user_id, '_fatgram_followers', true);
    return is_array($followers) ? count($followers) : 0;
}

// 2. Comprobar si ya sigo a este usuario
function is_user_following($me, $them) {
    $followers = get_user_meta($them, '_fatgram_followers', true);
    return is_array($followers) && in_array($me, $followers);
}

// 3. Procesar el clic (AJAX)
add_action('wp_ajax_toggle_follow', 'fatgram_toggle_follow_handler');
function fatgram_toggle_follow_handler() {
    $them_id = intval($_POST['author_id']);
    $me_id = get_current_user_id();

    if (!$me_id || !$them_id) wp_send_json_error();

    $followers = get_user_meta($them_id, '_fatgram_followers', true);
    if (!is_array($followers)) $followers = array();

    if (in_array($me_id, $followers)) {
        // Dejar de seguir
        $followers = array_diff($followers, array($me_id));
        $status = 'unfollowed';
    } else {
        // Seguir
        $followers[] = $me_id;
        $status = 'followed';
    }

    update_user_meta($them_id, '_fatgram_followers', $followers);
    
    wp_send_json_success(array(
        'count' => count($followers),
        'status' => $status
    ));
}
// 1. Redirigir el login de WordPress a tu página personalizada
function fatgram_redirect_login() {
    // Definimos la URL de tu página personalizada. 
    // Asegúrate de que el slug coincida con el que pusiste al crear la página (ej: 'acceso')
    $login_page  = home_url( '/acceso/' ); 
    $page_viewed = basename($_SERVER['REQUEST_URI']);

    // Si intentan entrar a wp-login.php y no es para salir (logout) o procesar el formulario
    if( $page_viewed == "wp-login.php" && $_SERVER['REQUEST_METHOD'] == 'GET') {
        wp_redirect($login_page);
        exit;
    }
}
add_action('init','fatgram_redirect_login');

// 2. Redirigir si hay un error de inicio de sesión
function fatgram_login_failed() {
    $login_page  = home_url( '/acceso/' );
    wp_redirect( $login_page . '?login=failed' );
    exit;
}
add_action( 'wp_login_failed', 'fatgram_login_failed' );

// 3. Redirigir tras cerrar sesión
function fatgram_logout_redirect() {
    $login_page  = home_url( '/acceso/' );
    wp_redirect( $login_page . '?loggedout=true' );
    exit;
}
add_action('wp_logout','fatgram_logout_redirect');
// Procesar la edición del perfil vía AJAX
add_action('wp_ajax_edit_user_profile', 'fatgram_edit_profile_handler');

function fatgram_edit_profile_handler() {
    $current_user_id = get_current_user_id();
    
    if (!$current_user_id) wp_send_json_error('No autorizado');

    $first_name = sanitize_text_field($_POST['first_name']);
    $last_name  = sanitize_text_field($_POST['last_name']);
    $description = sanitize_textarea_field($_POST['description']);

    $user_data = array(
        'ID'           => $current_user_id,
        'first_name'   => $first_name,
        'last_name'    => $last_name,
        'display_name' => $first_name . ' ' . $last_name,
        'description'  => $description
    );

    $user_id = wp_update_user($user_data);

    if (!is_wp_error($user_id)) {
        wp_send_json_success(array(
            'new_name' => $first_name . ' ' . $last_name,
            'new_bio'  => $description
        ));
    } else {
        wp_send_json_error('Error al actualizar');
    }
}
function fatgram_cargar_scripts() {
    // 1. Registrar y cargar tu archivo JS principal
    wp_enqueue_script(
        'fatgram-js', 
        get_template_directory_uri() . '/js/app.js', 
        array(), // Dependencias (si usas jQuery, pondrías array('jquery'))
        '1.0.0', 
        true // TRUE significa que se cargará al final del <body> (mejor para velocidad)
    );

    // 2. Pasar variables de PHP a JS (MUY IMPORTANTE)
    // Esto permite que tu archivo JS sepa cuál es la URL para AJAX
    wp_localize_script('fatgram-main-js', 'fatgram_data', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'home_url' => home_url()
    ));
}
add_action('wp_enqueue_scripts', 'fatgram_cargar_scripts');
// 1. Procesar la subida de la foto de perfil
add_action('init', 'fatgram_handle_profile_photo_upload');
function fatgram_handle_profile_photo_upload() {
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['profile_photo']) && is_user_logged_in()) {
        
        require_once(ABSPATH . 'wp-admin/includes/image.php');
        require_once(ABSPATH . 'wp-admin/includes/file.php');
        require_once(ABSPATH . 'wp-admin/includes/media.php');

        $attachment_id = media_handle_upload('profile_photo', 0); // 0 significa que no está asociado a un post

        if (!is_wp_error($attachment_id)) {
            // Guardamos el ID de la imagen en los metadatos del usuario
            update_user_meta(get_current_user_id(), 'fatgram_custom_avatar', $attachment_id);
        }
    }
}

// 2. Filtrar el avatar para que WordPress use nuestra imagen en lugar de Gravatar
add_filter('get_avatar', 'fatgram_custom_avatar_filter', 10, 5);
function fatgram_custom_avatar_filter($avatar, $id_or_email, $size, $default, $alt) {
    $user_id = 0;

    if (is_numeric($id_or_email)) {
        $user_id = (int) $id_or_email;
    } elseif (is_object($id_or_email) && isset($id_or_email->user_id)) {
        $user_id = (int) $id_or_email->user_id;
    } elseif (is_string($id_or_email) && ($user = get_user_by('email', $id_or_email))) {
        $user_id = $user->ID;
    }

    if ($user_id) {
        $custom_avatar_id = get_user_meta($user_id, 'fatgram_custom_avatar', true);
        if ($custom_avatar_id) {
            $img_url = wp_get_attachment_image_url($custom_avatar_id, array($size, $size));
            if ($img_url) {
                $avatar = "<img alt='{$alt}' src='{$img_url}' class='avatar avatar-{$size} photo' height='{$size}' width='{$size}' style='border-radius:50%; object-fit:cover;' />";
            }
        }
    }
    return $avatar;
}
add_filter('get_avatar', 'fatgram_show_custom_avatar', 10, 5);
// Notificaciones
function crear_cpt_notificaciones() {
    register_post_type('notificacion', array(
        'labels' => array(
            'name' => 'Notificaciones',
            'singular_name' => 'Notificación'
        ),
        'public' => false,
        'show_ui' => true,
        'supports' => array('title', 'custom-fields'),
    ));
}
add_action('init', 'crear_cpt_notificaciones');
function crear_notificacion($user_id, $mensaje) {
    $notificacion_id = wp_insert_post(array(
        'post_type' => 'notificacion',
        'post_title' => $mensaje,
        'post_status' => 'publish',
        'meta_input' => array(
            'user_id' => $user_id,
            'leida' => 0
        )
    ));
    return $notificacion_id;
}
// Obtener notificaciones de un usuario
function obtener_notificaciones_usuario($user_id) {
    $args = array(
        'post_type' => 'notificacion',
        'meta_key' => 'user_id',
        'meta_value' => $user_id,
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'DESC'
    );

    return get_posts($args);
}

// Sistema para mostrar una foto de perfil personalizado
function fatgram_show_custom_avatar($avatar, $id_or_email, $size, $default, $alt) {
    $user_id = 0;
    if (is_numeric($id_or_email)) {
        $user_id = (int) $id_or_email;
    } elseif (is_object($id_or_email) && !empty($id_or_email->user_id)) {
        $user_id = (int) $id_or_email->user_id;
    }

    if ($user_id) {
        $custom_avatar_id = get_user_meta($user_id, 'fatgram_custom_avatar', true);
        if ($custom_avatar_id) {
            $img_url = wp_get_attachment_image_url($custom_avatar_id, 'thumbnail');
            if ($img_url) {
                return '<img alt="'.$alt.'" src="'.$img_url.'" class="avatar photo" height="'.$size.'" width="'.$size.'" style="border-radius:50%; object-fit:cover;">';
            }
        }
    }
    return $avatar;
}
?>
