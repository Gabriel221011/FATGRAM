<?php get_header(); ?>

<div class="main-container profile-layout">
    <main style="grid-column: 1 / -1;"> <?php
        // Obtenemos los datos del autor de esta página
        $curauth = (isset($_GET['author_name'])) ? get_user_by('slug', $author_name) : get_userdata(intval($author));
        ?>

        <header class="profile-header-dedicated card">
            <div class="profile-info-top">
                <div class="profile-avatar-large">
                    <?php echo get_avatar($curauth->ID, 150); ?>
                </div>
                <div class="profile-text-data">
                <h2><?php echo $curauth->display_name; ?></h2>
                <p class="username">@<?php echo $curauth->user_nicename; ?></p>
                
                <div class="profile-stats">
                    <span><strong><?php echo count_user_posts($curauth->ID); ?></strong> posts</span>
                    <span><strong id="follower-count"><?php echo get_fatgram_follower_count($curauth->ID); ?></strong> seguidores</span>
                </div>

                <?php if ( is_user_logged_in() && get_current_user_id() !== $curauth->ID ) : 
                    $is_following = is_user_following(get_current_user_id(), $curauth->ID); ?>
                    <button id="follow-btn" 
                            data-author="<?php echo $curauth->ID; ?>" 
                            class="btn-fatgram <?php echo $is_following ? 'is-following' : ''; ?>">
                        <?php echo $is_following ? 'Siguiendo' : 'Seguir'; ?>
                    </button>
                <?php endif; ?>
                
               <?php if ( is_user_logged_in() && get_current_user_id() === $curauth->ID ) : ?>
                    <a href="<?php echo home_url('/editar-perfil/'); ?>" class="btn-edit">
                        <i class="fas fa-user-edit"></i> Editar Perfil
                    </a>
                <?php endif; ?>
                <p class="description" style="margin-top:10px;"><?php echo $curauth->description; ?></p>
            </div>
            <div class="profile-actions">
                <?php 
                // 1. Obtenemos el ID del usuario logueado
                $current_user_id = get_current_user_id();
                
                // 2. Obtenemos el ID del perfil que estamos viendo
                $viewed_user_id = get_queried_object_id();

                // 3. CONDICIÓN: Si el usuario está logueado Y NO es su propio perfil
                if ( is_user_logged_in() && $current_user_id !== $viewed_user_id ) : 
                    
                    // Comprobamos si ya lo sigue (usando la lógica de meta-datos anterior)
                    $followers = get_user_meta($viewed_user_id, '_fatgram_followers', true);
                    $is_following = is_array($followers) && in_array($current_user_id, $followers);
                ?>
                    
                    <button id="follow-btn" 
                            data-author="<?php echo $viewed_user_id; ?>" 
                            class="btn-follow <?php echo $is_following ? 'active' : ''; ?>">
                        <?php echo $is_following ? 'Siguiendo' : 'Seguir'; ?>
                    </button>

                <?php endif;  ?>
            </div>
            </div>
        </header>

        <h3 style="margin: 2rem 0 1rem;">Publicaciones de <?php echo $curauth->first_name; ?></h3>
        
        <div class="food-grid-profile">
            <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
                <div class="grid-item">
                    <a href="<?php the_permalink(); ?>">
                        <?php if (has_post_thumbnail()) : ?>
                            <?php the_post_thumbnail('medium_large'); ?>
                        <?php endif; ?>
                        <div class="grid-overlay">
                            <span><i class="fas fa-heart"></i> 24</span>
                        </div>
                    </a>
                </div>
            <?php endwhile; else: ?>
                <p>Este foodie aún no ha compartido platos.</p>
            <?php endif; ?>
        </div>

    </main>
</div>


<?php get_footer(); ?>