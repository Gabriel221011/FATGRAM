<?php get_header(); ?>

<div class="main-container">
    <main>
        <h2 style="margin-bottom: 1rem;">Explora Restaurantes</h2>
        
        <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
            <article class="card">
                <?php if (has_post_thumbnail()) : ?>
                    <?php the_post_thumbnail('large'); ?>
                <?php endif; ?>
                
                <div class="card-content">
                    <div class="restaurant-info">
                        <h3><?php the_title(); ?></h3>
                        <span class="rating">★ <?php echo get_post_meta(get_the_ID(), 'rating', true); ?></span>
                    </div>
                    <div class="excerpt"><?php the_excerpt(); ?></div>
                    
                    <div class="reviews-section">
                        <strong>Comentarios:</strong>
                        <?php comments_template(); ?>
                    </div>
                </div>
            </article>
        <?php endwhile; endif; ?>
    </main>

    <aside>
        <?php get_sidebar(); ?>
    </aside>
</div>

<?php get_footer(); ?>