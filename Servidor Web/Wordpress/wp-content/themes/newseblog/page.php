<?php
/**
 * The template for displaying all pages.
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages
 * and that other 'pages' on your WordPress site may use a
 * different template.
 *
 * @package NewseBlog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header(); 
?>
<!--==================== page section ====================-->
<main id="content" class="page-section main-section">
	<!-- Blog Area -->
	<?php 
	if ( have_posts() ) :
		while ( have_posts() ) : the_post();

			// Output page/post content
			the_content();

			// Pagination inside a post/page
			wp_link_pages(array(
				'before' => '<div class="link btn-theme">' . esc_html__('Pages:', 'newseblog'),
				'after'  => '</div>',
			));

			// Show comments if enabled
			if ( comments_open() || get_comments_number() ) :
				comments_template();
			endif;

		endwhile;
	endif;
	?>
</main>
<?php
get_footer();