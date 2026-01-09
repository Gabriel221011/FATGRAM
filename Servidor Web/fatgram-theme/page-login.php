<?php
/* Template Name: Fatgram Login & Registro */

// Proceso de Registro
if (isset($_POST['fatgram_register'])) {
    $username = sanitize_user($_POST['user_name']);
    $first_name = sanitize_text_field($_POST['first_name']);
    $last_name = sanitize_text_field($_POST['last_name']);
    $password = $_POST['password'];

    if (!username_exists($username)) {
        $user_id = wp_create_user($username, $password);
        if (!is_wp_error($user_id)) {
            wp_update_user(array(
                'ID' => $user_id,
                'first_name' => $first_name,
                'last_name' => $last_name,
                'display_name' => $first_name . ' ' . $last_name
            ));
            // Autologin tras registro
            wp_set_auth_cookie($user_id);
            wp_redirect(home_url());
            exit;
        }
    } else {
        $error = "El nombre de usuario ya existe.";
    }
}

// Proceso de Login
if (isset($_POST['fatgram_login'])) {
    $creds = array(
        'user_login'    => $_POST['log_user'],
        'user_password' => $_POST['log_pass'],
        'remember'      => true
    );
    $user = wp_signon($creds, false);
    if (!is_wp_error($user)) {
        wp_redirect(home_url());
        exit;
    } else {
        $error = "Usuario o contraseña incorrectos.";
    }
}

get_header(); ?>

<div class="auth-container">
<div class="auth-box card">
        <div class="auth-logo">
            <?php 
            if (has_custom_logo()) {
                // Logo personalizado
                the_custom_logo();
            } else {
                // Fallback si no hay logo subido
                echo '<div class="logo-fallback">
                        <span class="logo-icon">🍔</span>
                        <h1 class="logo-text">Fatgram</h1>
                      </div>';
            }
            ?>
        </div>

        <?php if(isset($error)) {
            echo "<p class='auth-error'>$error</p>"; 
        } elseif ( isset($_GET['login']) && $_GET['login'] == 'failed' ) {
            echo "<p class='auth-error'>Usuario o contraseña incorrectos. Inténtalo de nuevo.</p>";
        } elseif ( isset($_GET['loggedout']) && $_GET['loggedout'] == 'true' ) {
            echo "<p class='auth-success' style='color:green; font-size:14px; margin-bottom:15px;'>Has cerrado sesión correctamente.</p>";
        } ?>

        <div id="login-form">
            <form method="post">
                <input type="text" name="log_user" placeholder="Nombre de usuario" required>
                <input type="password" name="log_pass" placeholder="Contraseña" required>
                <button type="submit" name="fatgram_login" class="btn-primary">Iniciar Sesión</button>
            </form>
            <p class="toggle-text">¿No tienes cuenta? <a href="#" onclick="toggleAuth()">Regístrate</a></p>
        </div>

        <div id="register-form" style="display:none;">
            <form method="post">
                <input type="text" name="first_name" placeholder="Nombre" required>
                <input type="text" name="last_name" placeholder="Apellidos" required>
                <input type="text" name="user_name" placeholder="Nombre de usuario" required>
                <input type="password" name="password" placeholder="Contraseña" required>
                <button type="submit" name="fatgram_register" class="btn-primary">Crear Cuenta</button>
            </form>
            <p class="toggle-text">¿Ya tienes cuenta? <a href="#" onclick="toggleAuth()">Inicia sesión</a></p>
        </div>
    </div>
</div>

<script>
function toggleAuth() {
    const l = document.getElementById('login-form');
    const r = document.getElementById('register-form');
    l.style.display = l.style.display === 'none' ? 'block' : 'none';
    r.style.display = r.style.display === 'none' ? 'block' : 'none';
}
</script>

<?php get_footer(); ?>