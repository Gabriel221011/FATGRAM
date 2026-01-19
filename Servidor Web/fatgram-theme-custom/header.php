<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <nav>
        <div class="logo-wrapper">
            <?php
            // Comprobamos si el usuario ha subido un logo personalizado en el personalizador
            if (has_custom_logo()) {
                // Si existe, WordPress genera automáticamente la etiqueta <img> con enlace
                the_custom_logo();
            } else {
                // FALLBACK: Si NO han subido imagen, mostramos el texto y emoji antiguo
                ?>
                 <a href="<?php echo esc_url(home_url('/')); ?>" class="logo-container-text">
                    <span class="logo-icon">🍔</span>
                    <span class="logo-text"><?php bloginfo('name'); ?></span>
                </a>
                <?php
            }
            ?>
        </div>
         <div class="search-bar">
            <form role="search" method="get" action="<?php echo home_url('/'); ?>">
                <input type="text" placeholder="Busca comida..." name="s">
            </form>
        </div>
        <div class="nav-icons">
            <a href="<?php echo esc_url(home_url('/')); ?>"><i class="fas fa-home"></i></a>
            <i class="fas fa-heart"></i>
            <i class="fas fa-plus-square"></i>
            
            <?php if ( is_user_logged_in() ) : 
                $current_user = wp_get_current_user(); ?>
                
        <div class="profile-dropdown">
            <button class="dropdown-trigger" aria-haspopup="true">
                <i class="fas fa-user-circle"></i>
            </button>
            <div class="dropdown-content">
                <div class="dropdown-card card">
                    <div class="dropdown-header">
                        <strong><?php echo $current_user->display_name; ?></strong>
                        <span>@<?php echo $current_user->user_nicename; ?></span>
                    </div>
                    <hr>
                    <a href="<?php echo get_author_posts_url($current_user->ID); ?>">
                        <i class="fas fa-user"></i> Ver Perfil
                    </a>
                    <a href="<?php echo home_url('/editar-perfil/'); ?>">
                        <i class="fas fa-cog"></i> Ajustes
                    </a>
                    <hr>
                    <a href="<?php echo wp_logout_url(home_url()); ?>" class="logout-link">
                        <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                    </a>
                </div>
            </div>
        </div>

            <?php else : ?>
                <a href="<?php echo home_url('/acceso/'); ?>"><i class="fas fa-user-circle"></i></a>
            <?php endif; ?>
        </div>
    </nav>