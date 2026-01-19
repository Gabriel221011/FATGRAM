<?php get_header(); ?>
<main style="max-width:1000px; margin:40px auto; padding:20px; font-family:'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
    <?php while ( have_posts() ) : the_post(); 
        $direccion = get_field('direccion_restaurante');
        $rating = get_field('valoracion');
        $menu = get_field('menu_archivo');
        $map_url = "https://maps.google.com/maps?q=" . urlencode($direccion) . "&t=&z=16&ie=UTF8&iwloc=&output=embed";
    ?>
        
        <div style="display:grid; grid-template-columns: 1fr 350px; gap:40px;">
            
            <div>
                <?php the_post_thumbnail('large', ['style' => 'width:100%; border-radius:15px; margin-bottom:20px;']); ?>
                <h1><?php the_title(); ?></h1>
                
                <div class="descripcion" style="font-size:17px; line-height:1.6; color:#333; margin-bottom:30px;">
                    <?php the_content(); ?>
                </div>

                <?php if($menu): ?>
                <div class="menu-seccion" style="background:#f9f9f9; padding:20px; border-radius:10px; border-left:5px solid #0073aa;">
                    <h3>📖 Nuestra Carta / Menú</h3>
                    <p>Echa un vistazo a nuestras especialidades.</p>
                    <a href="<?php echo $menu['url']; ?>" target="_blank" style="background:#0073aa; color:white; padding:10px 20px; border-radius:5px; text-decoration:none; display:inline-block;">Ver Menú Completo</a>
                </div>
                <?php endif; ?>
            </div>

            <aside>
                <div style="position:sticky; top:20px;">
                    <div style="background:#fff; padding:20px; border-radius:15px; box-shadow:0 10px 30px rgba(0,0,0,0.1);">
                        <h4 style="margin-top:0;">Ubicación</h4>
                        <div style="border-radius:10px; overflow:hidden; margin-bottom:15px;">
                            <iframe width="100%" height="250" frameborder="0" src="<?php echo $map_url; ?>"></iframe>
                        </div>
                        <p style="font-size:14px; color:#555;">📍 <?php echo esc_html($direccion); ?></p>
                        <hr>
                        <p style="font-weight:bold; margin-bottom:5px;">Valoración media:</p>
                        <div style="font-size:24px;">
                            <?php 
                            $rating = floatval(get_field('valoracion'));
                            echo generar_estrellas_decimales($rating); 
                            ?>
                            <span style="font-size:18px; color:#444;"> <?php echo $rating; ?> / 5</span>
                        </div>
                    </div>
                </div>
            </aside>

        </div>
    <?php endwhile; ?>
</main>
<?php get_footer(); ?>