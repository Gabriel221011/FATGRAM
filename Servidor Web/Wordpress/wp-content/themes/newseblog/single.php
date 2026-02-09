<!-- =========================
     Page Breadcrumb   
============================== -->
<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
get_header(); ?>
<!--==================== main content section ====================-->
<!-- =========================
     Page Content Section      
============================== -->
<main id="content" class="main-section single-section">  
  <?php if(have_posts())
  {
    while(have_posts()) { the_post(); ?>
    <div class="newseblog-blog-post-box"> 
      <article class="newseblogog-post-inner">
        <div class="newseblog-blog-category"> 
          <?php $cat_list = get_the_category_list();
            if(!empty($cat_list)) {  
              the_category('&nbsp'); 
            } ?>
        </div>
        <h1 class="newseblog-title single">
          <?php the_title(); ?>
        </h1>
        <div class="post-meta">
          <a href="<?php echo esc_url(get_day_link(get_post_time('Y'), get_post_time('m'), get_post_time('j')));  ?>">
              <span class="post-date"><?php the_time( get_option( 'date_format' ) ); ?></span>
          </a>
          <span class="meta-sep">/</span>
          <?php comments_popup_link( esc_html__( 'No Comments', 'newseblog' ), esc_html__( '1 Comment', 'newseblog' ), '% '. esc_html__( 'Comments', 'newseblog' ), 'post-comments'); ?>
        </div>
        <?php if (has_tag()) {
          the_tags(); 
        }
        ?>
      </article>
      <?php
      if(has_post_thumbnail()){
        echo the_post_thumbnail( '', array( 'class'=>'img-responsive' ) );
      } ?>
      <article class="newseblog-blog-post-inner single">
        <?php the_content(); ?>
      </article>
    </div>
  <?php } } ?>
  <div class="newseblog-blog-comment"> 
    <?php comments_template('',true); ?> 
  </div>
</main>
<?php get_footer(); ?>