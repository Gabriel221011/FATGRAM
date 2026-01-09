<?php
/* Template Name: Fatgram Editar Perfil */

// Seguridad: Redirigir si no está logueado
if (!is_user_logged_in()) {
    wp_redirect(home_url('/acceso/'));
    exit;
}

$current_user = wp_get_current_user();
$error_message = '';
$success_message = '';

// Procesar el formulario
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['fatgram_update_profile'])) {
    
    if (isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'update_user_profile')) {
        
        $user_id = $current_user->ID;
        $first_name = sanitize_text_field($_POST['first_name']);
        $last_name = sanitize_text_field($_POST['last_name']);
        $description = sanitize_textarea_field($_POST['description']);
        $pass1 = $_POST['pass1'];
        $pass2 = $_POST['pass2'];

        // Datos básicos a actualizar
        $userdata = array(
            'ID'           => $user_id,
            'first_name'   => $first_name,
            'last_name'    => $last_name,
            'display_name' => $first_name . ' ' . $last_name,
            'description'  => $description
        );

        // Lógica de cambio de contraseña
        if (!empty($pass1) || !empty($pass2)) {
            if ($pass1 === $pass2) {
                if (strlen($pass1) >= 6) {
                    $userdata['user_pass'] = $pass1; // WordPress la cifrará automáticamente
                } else {
                    $error_message = "La contraseña debe tener al menos 6 caracteres.";
                }
            } else {
                $error_message = "Las contraseñas no coinciden.";
            }
        }

        // Si no hay errores previos, actualizamos
        if (empty($error_message)) {
            $updated = wp_update_user($userdata);

            if (!is_wp_error($updated)) {
                $success_message = "¡Perfil actualizado con éxito!";
                
                // Si cambió la contraseña, WordPress cerrará la sesión por seguridad, 
                // así que la reiniciamos para que el usuario no tenga que loguearse de nuevo.
                if (!empty($pass1)) {
                    wp_set_auth_cookie($user_id);
                }
                
                $current_user = wp_get_current_user();
            } else {
                $error_message = "Error al actualizar en la base de datos.";
            }
        }
    }
}

get_header(); ?>

<div class="main-container">
    <div class="edit-profile-container card">
        <h2 class="edit-title">Configuración de Perfil</h2>

        <?php if ($success_message): ?>
            <p class="auth-message success"><?php echo $success_message; ?></p>
        <?php endif; ?>

        <?php if ($error_message): ?>
            <p class="auth-message error"><?php echo $error_message; ?></p>
        <?php endif; ?>

        <form method="post" action="" class="fatgram-form" enctype="multipart/form-data">
            <?php wp_nonce_field('update_user_profile'); ?>

            <div class="form-section">
                <h3>Datos Personales</h3>
                <div class="form-group">
                    <label>Nombre</label>
                    <input type="text" name="first_name" value="<?php echo esc_attr($current_user->first_name); ?>" required>
                </div>
                <div class="form-group">
                    <label>Apellidos</label>
                    <input type="text" name="last_name" value="<?php echo esc_attr($current_user->last_name); ?>" required>
                </div>
                <div class="form-group">
                    <label>Biografía</label>
                    <textarea name="description" rows="3"><?php echo esc_textarea($current_user->description); ?></textarea>
                </div>
            </div>

            <div class="form-section" style="margin-top: 30px; border-top: 1px solid #eee; padding-top: 20px;">
                <h3>Cambiar Contraseña</h3>
                <p style="font-size: 0.8rem; color: #888; margin-bottom: 15px;">Deja estos campos en blanco si no deseas cambiarla.</p>
                
                <div class="form-group">
                    <label>Nueva Contraseña</label>
                    <input type="password" name="pass1" placeholder="Mínimo 6 caracteres">
                </div>
                <div class="form-group">
                    <label>Confirmar Nueva Contraseña</label>
                    <input type="password" name="pass2" placeholder="Repite la contraseña">
                </div>
            </div>
            
            <div class="form-section">
                    <h3>Foto de Perfil</h3>
                <div class="profile-upload-container">
                    <div class="current-avatar">
                        <?php echo get_avatar($current_user->ID, 100); ?>
                    </div>
                        <div class="upload-controls">
                            <input type="file" name="profile_photo" id="profile_photo" accept="image/*">
                            <p class="help-text">JPG, PNG o GIF. Recomendado: Cuadrada.</p>
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" name="fatgram_update_profile" class="btn-primary">Guardar Cambios</button>
                <a href="<?php echo get_author_posts_url($current_user->ID); ?>" class="btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>
</div>

<?php get_footer(); ?>
<?php
/* Template Name: Fatgram Editar Perfil */

if (!is_user_logged_in()) {
    wp_redirect(home_url('/acceso/'));
    exit;
}

$current_user = wp_get_current_user();
$error_message = '';
$success_message = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['fatgram_update_profile'])) {
    
    if (isset($_POST['_wpnonce']) && wp_verify_nonce($_POST['_wpnonce'], 'update_user_profile')) {
        
        $user_id = $current_user->ID;

        // --- 1. PROCESAR SUBIDA DE IMAGEN ---
        if (!empty($_FILES['profile_photo']['name'])) {
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/media.php');

            // Subir el archivo a la biblioteca de medios
            $attachment_id = media_handle_upload('profile_photo', 0);

            if (is_wp_error($attachment_id)) {
                $error_message = "Error al subir la imagen: " . $attachment_id->get_error_message();
            } else {
                // Guardar el ID de la imagen en los metadatos del usuario
                update_user_meta($user_id, 'fatgram_custom_avatar', $attachment_id);
            }
        }

        // --- 2. ACTUALIZAR RESTO DE DATOS SI NO HAY ERROR ---
        if (empty($error_message)) {
            $userdata = array(
                'ID'           => $user_id,
                'first_name'   => sanitize_text_field($_POST['first_name']),
                'last_name'    => sanitize_text_field($_POST['last_name']),
                'display_name' => sanitize_text_field($_POST['first_name']) . ' ' . sanitize_text_field($_POST['last_name']),
                'description'  => sanitize_textarea_field($_POST['description'])
            );

            // Solo actualizar contraseña si se rellenó
            if (!empty($_POST['pass1'])) {
                if ($_POST['pass1'] === $_POST['pass2']) {
                    $userdata['user_pass'] = $_POST['pass1'];
                } else {
                    $error_message = "Las contraseñas no coinciden.";
                }
            }

            if (empty($error_message)) {
                wp_update_user($userdata);
                $success_message = "¡Perfil actualizado con éxito!";
                $current_user = wp_get_current_user(); // Refrescar datos
            }
        }
    }
}

get_header(); ?>