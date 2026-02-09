<?php
/*
Plugin Name: Crear Rol Personalizado
Description: Crea un rol personalizado en WordPress
*/

add_action('init', function () {
    if (!get_role('editor_personalizado')) {
        add_role(
            'editor_personalizado',
            'Editor Personalizado',
            array(
                'read' => true,
                'edit_posts' => true,
                'edit_pages' => true,
                'publish_posts' => true,
                'delete_posts' => false
            )
        );
    }
});
