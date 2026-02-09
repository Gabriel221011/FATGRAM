<?php if ( ! defined( 'ABSPATH' ) ) { exit; } // Exit if accessed directly

if (!function_exists('blogfoel_get_post_title')) {
	function blogfoel_get_post_title() {
		$args = array(
			'post_type' => 'post',
			'post_status' => 'publish',
			'posts_per_page' => -1,
		);

		$posts = new WP_Query($args);

		$post_list = [];

		if ($posts->have_posts()) {
			while ($posts->have_posts()) {
				$posts->the_post();
				$post_list[get_the_ID()] = get_the_title();
			}
			wp_reset_postdata();
		}

		return $post_list;
	}
}
