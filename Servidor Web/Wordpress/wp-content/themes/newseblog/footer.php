<?php

/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after.
 *
 * @package NewseBlog
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

?>
<footer class="newseblog-footer">
  <div class="copyright">
  <?php
    $theme_data	= wp_get_theme();
    
    printf( __( '<p> <span class="copyright-text">Copyright © All rights reserved</span> | %1$s by  <a href="%2$s">%3$s.</a></p>', 'newseblog' ), esc_html( $theme_data->Name ), esc_url( 'https://themeansar.com/' ), $theme_data->Author );
    ?>
  </div>
</footer>
<?php wp_footer(); ?>
</body>

</html>