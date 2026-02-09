<?php
/**
 * The template for displaying 404 pages (not found).
 *
 * @package NewseBlog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

get_header(); ?>
  <!--==================== main content section ====================-->
  <!--container-->
  <div class="container">
    <!--row-->
        <!--mg-error-404-->
        <div class="mg-error-404">
          <h4><?php esc_html_e('Page Not Found','newseblog'); ?></h4>
          <p><?php esc_html_e('We are sorry, but the page you are looking for does not exist.','newseblog'); ?></p>
          <a href="<?php echo esc_url(home_url());?>" onClick="history.back();" class="btn btn-theme"><?php esc_html_e('Go Back','newseblog'); ?></a> 
        </div>
        <!--/mg-error-404--> 
      <!--/col-md-12--> 
  </div>
  <!--/container-->
<?php
get_footer();