<?php
/**
 * Full Page 
 *
 * Handle Full Page.
 *
 * @package blognews_Full_Page_Template
 *
 */
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly 

get_header(); ?>
	<div id="blognews-full-page" class="blognews-full-page-site">
		<?php do_action( '_blognews_full_page_' ); ?>
	</div>     
<?php get_footer(); ?>
