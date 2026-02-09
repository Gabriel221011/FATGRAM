<?php
/* Template Name: Notificaciones */
get_header();

if (!is_user_logged_in()) {
    echo "<p>Debes iniciar sesión para ver tus notificaciones.</p>";
    get_footer();
    exit;
}

$current_user = wp_get_current_user();
$notificaciones = obtener_notificaciones_usuario($current_user->ID);

echo "<h2>Tus notificaciones</h2>";

if ($notificaciones) {
    echo "<ul class='lista-notificaciones'>";
    foreach ($notificaciones as $n) {
        $leida = get_post_meta($n->ID, 'leida', true);
        $clase = $leida ? 'leida' : 'no-leida';

        echo "<li class='$clase'>{$n->post_title}</li>";
    }
    echo "</ul>";
} else {
    echo "<p>No tienes notificaciones.</p>";
}

get_footer();
?>
