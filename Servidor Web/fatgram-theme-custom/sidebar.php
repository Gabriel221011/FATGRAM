<div class="sidebar-section profile-header">
    <div class="profile-pic">
        <img src="<?php echo get_template_directory_uri(); ?>/img/default-avatar.png" alt="User">
    </div>
    <h3>Mi Perfil</h3>
    <p>@<?php $current_user = wp_get_current_user(); echo $current_user->display_name; ?></p>
</div>

<div class="sidebar-section">
    <h3>Notificaciones</h3>
    <div class="notification-item">🔔 Bienvenido de nuevo a Fatgram.</div>
    <div class="notification-item">🥘 Tienes 5 restaurantes nuevos cerca.</div>
</div>