<?php
if ( ! function_exists( 'blognews_get_attachment_alt' ) ) {
	function blognews_get_attachment_alt( $attachment_id ) {
		if ( ! $attachment_id ) {
			return '';
		}

		$attachment = get_post( $attachment_id );
		if ( ! $attachment ) {
			return '';
		}

		$alt = get_post_meta( $attachment_id, '_wp_attachment_image_alt', true );
		if ( ! $alt ) {
			$alt = $attachment->post_excerpt;
			if ( ! $alt ) {
				$alt = $attachment->post_title;
			}
		}
		return trim( wp_strip_all_tags( $alt ) );
	}
}
function blogfoel_placeholder_image_src() {
	if ( did_action( 'elementor/loaded' ) ) { 
		$placeholder_image = ELEMENTOR_ASSETS_URL . 'images/placeholder.png';
		$placeholder_image = apply_filters( 'elementor/utils/get_placeholder_image_src', $placeholder_image );
	}else{
		$placeholder_image = '';
	}
	return $placeholder_image;
}
function blognews_get_categories( $demo = 0 ) {
	$categories = get_categories([
		"hide_empty" => 0,
		"type"      => "post",
		"orderby"   => "name",
		"order"     => "ASC"
		]
	);

	$cat = [];
	if($demo == 0){
		foreach( $categories as $category ) {
			$cat[$category->term_id] = $category->name;
		}
	}else {
		foreach( $categories as $category ) {
			$cat[$category->slug] = $category->name;
		}
	}

	return $cat;
}

function blognews_get_tags() {
	$tags = get_tags(array(
		'hide_empty' => false
	));

	$tgs = [];

	foreach( $tags as $tag ) {
		$tgs[$tag->slug] = $tag->name;
	}

	return $tgs;
}
function blognews_get_all_category() {
	$categories = get_categories(
		[
			'hide_empty' => 0,
			// phpcs:ignore Squiz.PHP.CommentedOutCode.Found
			//'exclude'  =>  1,
			'taxonomy'   => 'product_cat', // mention taxonomy here.
		]
	);

	if ( count( $categories ) > 0 ) {
		return $categories;
	}

	return false;
}
function blognews_get_all_authors( $demo = 0 ) {
    $args = array(
        'role__in'     => array('author', 'administrator', 'subscriber'),
        'orderby'      => 'display_name',
        'order'        => 'ASC',
        'number'       => null,
        'fields'       => 'all',
    );
    $authors = get_users( $args );
    $author_list = array();

	if($demo == 0){
		foreach ( $authors as $author ) {
			$author_list[$author->ID] = $author->display_name;
		}
	}else{
		foreach ( $authors as $author ) {
			$author_list[$author->display_name] = $author->display_name;
		}
	}

    return $author_list;
}
/**
 * Retrieve all post years
 */
function blognews_get_post_years() {
    $years = [];

    $posts = get_posts([
        'posts_per_page' => -1,  // Retrieve all posts
        'post_type'      => 'post',
        'orderby'        => 'date',
        'order'          => 'ASC',
        'fields'         => 'ids',  // Retrieve only post IDs to optimize performance
    ]);

    foreach ($posts as $post_id) {
        $post_date = get_post_field('post_date', $post_id);
        $year = date('Y', strtotime($post_date));

        if (!in_array($year, $years)) {
            $years[$year] = $year;
        }
    }

    return $years;
}
function get_placeholder_image_src() {
	$placeholder_image = ELEMENTOR_ASSETS_URL . 'images/placeholder.png';
	$placeholder_image = apply_filters( 'elementor/utils/get_placeholder_image_src', $placeholder_image );
	return $placeholder_image;
}
if (!function_exists('blogfoel_get_all_post_types')) {
	function blogfoel_get_all_post_types() {
		$post_types = get_post_types(['public' => true], 'objects');

		$filtered_post_types = [];

		foreach ($post_types as $post_type => $details) {
			$filtered_post_types[$post_type] = $details->label;
		}

		return $filtered_post_types;
	}
}
if ( ! function_exists( 'blogfoel_get_all_category' ) ) {
	function blogfoel_get_all_category( $icon = '', $class = '') {

		$categories = get_the_category();

		if ( ! empty( $categories ) ) {
			$cat_html = '<div class="blogfoel-category '. esc_html( $class ) .'">';
			foreach ( $categories as $category ) {
				// Get the saved color for obj category
				$bg_color = get_term_meta($category->term_id, 'category_color', true);
				$text_color = get_term_meta($category->term_id, 'category_text_color', true);
				
				// Set inline style if color exists
				$style = '';
				if ( ! empty( $bg_color ) ) {
					$style .= '--category-color:' . esc_attr( $bg_color ) . ';';
				}
				if ( ! empty( $text_color ) ) {
					$style .= '--category-text-color:' . esc_attr( $text_color ) . ';';
				}
				$style_attr = $style ? ' style="' . $style . '"' : '';

				$cat_html .= '<a href="' . esc_url(get_category_link($category->term_id)) . '" class="category-badge"' . $style_attr . '>'
				           . esc_html( $category->name ) . '</a>';
			}
			$cat_html .= '</div>';

			return $cat_html;
		}
	}
}

if (!function_exists('blogfoel_get_author')) {
	function blogfoel_get_author($avatar = 'no', $icon = '', $by_author = 'no' ) {
		$author_id   = get_the_author_meta('ID');
		$author_url  = esc_url(get_author_posts_url($author_id));
		$author_name = esc_html(get_the_author());
		$by_title    = esc_html__('By','blognews-for-elementor');

		$author_html = '<span class="blogfoel-author">';

		if ($avatar === 'yes') {
			$author_html .= '<a href="' . $author_url . '"><span class="blogfoel-avatar">' . get_avatar($author_id,150) . '</span></a>';
		} else {
			if ($icon === 'yes') {
				$author_html .= '<span class="blogfoel-meta-icon"><i class="fas fa-user"></i></span>';
			}
		}
		if($by_author == 'yes'){
			$author_html .= $by_title;
		}
		$author_html .= '<a href="' . $author_url . '">';    

		$author_html .= $author_name . '</a></span>';

		return $author_html;
	}
}
if (!function_exists('blogfoel_get_date')) {
	function blogfoel_get_date($date_format = 'default', $icon = '' ) {
		$date_url  = esc_url(get_day_link(get_post_time('Y'), get_post_time('m'), get_post_time('j')));
		$default_format =  esc_html(get_the_date('F j, Y'));
		$wordpress_format =  esc_html(get_the_date());
		$date_type = $date_format === 'default' ? $default_format : $wordpress_format;
		
		$date_html = '<span class="blogfoel-date">';

			if ($icon === 'yes') {
				$date_html .= '<span class="blogfoel-meta-icon"><i class="fas fa-clock"></i></span>';
			} else { }
			
			$date_html .= '<a href="' . $date_url . '" class="entry-date">' . $date_type . '</a></span>';
			
		return $date_html;
	}
}
if (!function_exists('blogfoel_get_comments')) {
	function blogfoel_get_comments($icon = '' , $no_comments = '', $one_comment = '', $multi_comments = '') {
		$comments_count = get_comments_number();
		$comment_number = $comments_count == 0 ? '': $comments_count;
		$comment_url    = get_comments_link();
		$comment_html   = '<span class="blogfoel-comments">';

		if ($icon === 'yes') {
			$comment_html .= '<span class="blogfoel-meta-icon"><i class="fas fa-comment"></i></span>';
		} else {
		}
	
		if ($comments_count == 0){
			$comment_label = $no_comments;
		} elseif ($comments_count == 1){
			$comment_label = $one_comment;
		} else {
			$comment_label = $multi_comments;
		}
		$comment_type = esc_html($comment_number) . ' ' . ($comment_label); 
		
		$comment_html .= '<a href="' . $comment_url . '">' . $comment_type . '</a></span>';

		return $comment_html;
	}
}
if (!function_exists('blogfoel_get_views')) {
	function blogfoel_get_views($icon = '', $views_format = 'numeric', $one_view = '', $multi_views = '') {
		if (!get_the_ID()) {
			return '';
		}
		$views_count = get_post_meta(get_the_ID(), 'post_views_count', true) ?: '0';
		if ($views_format === 'short' && $views >= 1000) {
			$views_count = round($views / 1000, 1) . 'K';
		}
		$view_label = $views_count == 1 ? $one_view : $multi_views;
		$views_html = '<span class="blogfoel-views">';
		if (!empty($icon)) {
			$views_html .= '<span class="blogfoel-meta-icon">';
			ob_start();
			\Elementor\Icons_Manager::render_icon($icon, [ 'aria-hidden' => 'true' ]);
			$views_html .= ob_get_clean();
			$views_html .= '</span>'; 
		} else {
			$views_html .= '<span class="blogfoel-meta-icon"><i class="fas fa-eye"></i></span>';
		}
		$views_html .= '<span class="entry-views">' . esc_html($views_count) . ' ' . ($view_label).'</span>';
		$views_html .= '</span>';
		return $views_html;
	}
}
function blogfoel_increment_post_views() {
    if (is_single()) {
        $post_id = get_the_ID();
        $views = get_post_meta($post_id, 'post_views_count', true);
        $views = $views ? (int)$views : 0;
        $views++;
        update_post_meta($post_id, 'post_views_count', $views);
    }
}
add_action('wp_head', 'blogfoel_increment_post_views');
function blogfoel_get_read_time($icon = '', $read_format = 'short', $wpm = 200) {
    if (!get_the_ID()) {
        return '';
    }

    $content = get_post_field('post_content', get_the_ID());
    $word_count = str_word_count(strip_tags($content));
    $read_time = ceil($word_count / $wpm);
    $read_time_text = $read_format === 'short' ? esc_html($read_time . ' min') : esc_html($read_time . ' minutes');

    $read_time_html = '<span class="blogfoel-read-time">';

    if (!empty($icon)) {
		$read_time_html .= '<span class="blogfoel-meta-icon">';
        ob_start();
        \Elementor\Icons_Manager::render_icon($icon, [ 'aria-hidden' => 'true' ]);
        $read_time_html .= ob_get_clean();
		$read_time_html .= '</span>'; // .blogfoel-meta-icon
    } else {
		$read_time_html .= '<span class="blogfoel-meta-icon">';
        $read_time_html .= '<i class="fas fa-book-reader"></i>';
		$read_time_html .= '</span>'; // .blogfoel-meta-icon
    }

    $read_time_html .= '<span class="entry-read-time">' . $read_time_text . '</span>';
    $read_time_html .= '</span>'; // .blogfoel-read-time

    return $read_time_html;
}
function blogfoel_get_publish_time($icon = '', $format = 'default') {
    if (!get_the_ID()) {
        return '';
    }
    $time_format = $format === 'default' ? get_option('time_format') : $format;
    $publish_time = esc_html(date_i18n($time_format, get_post_time('U')));
    $time_html = '<span class="blogfoel-publish-time">';

	if (!empty($icon)) {
		$time_html .= '<span class="blogfoel-meta-icon">';
		ob_start();
        \Elementor\Icons_Manager::render_icon($icon, [ 'aria-hidden' => 'true' ]);
        $time_html .= ob_get_clean();
		$time_html .= '</span>'; // .blogfoel-meta-icon
	} else {
		$time_html .= '<span class="blogfoel-meta-icon"><i class="fas fa-clock"></i></span>';
	}

    $time_html .= '<span class="entry-publish-time">' . $publish_time . '</span>';
    $time_html .= '</span>';
    return $time_html;
}
function blogfoel_get_edit_post_link($icon = '', $link_text = 'Edit') {
    if (!is_user_logged_in()) {
        return '';
    }

    $post_id = get_the_ID();
    if (!$post_id || !current_user_can('edit_post', $post_id)) {
        return '';
    }

    $edit_link = get_edit_post_link($post_id);
    if (!$edit_link) {
        return '';
    }

    $output = '<span class="blogfoel-edit-post-link">';

    $output .= '<span class="blogfoel-meta-icon">';
    if ($icon === 'yes') {
        $output .= '<i class="fas fa-edit"></i>';
    } else {
    }
    $output .= '</span>'; // .blogfoel-meta-icon

    $output .= '<a href="' . esc_url($edit_link) . '" class="entry-edit-link">' . esc_html($link_text) . '</a>';
    $output .= '</span>'; // .blogfoel-edit-post-link

    return $output;
}
function blognews_numeric_posts_pagination($wp_query, $pagination = true) {
	$big = 999999999; // A large number unlikely to appear in a URL

	// Only show pagination if there's more than one page
	if ( ! $wp_query instanceof WP_Query || $wp_query->max_num_pages <= 1 ) {
		return;
	}

	echo '<div class="blogfoel-navigation navigation">' . "\n";

	echo wp_kses_post( paginate_links( [
		'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
		'format'    => '?paged=%#%',
		'current'   => max( 1, get_query_var( 'paged', get_query_var( 'page' ) ) ),
		'total'     => $wp_query->max_num_pages,
		'show_all'  => $pagination,
		'type'      => 'list',
		'mid_size'  => 2,
		'prev_next' => false,
	] ) );

	echo '</div>' . "\n";
}
use Elementor\Controls_Manager;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;

function blogfoel_navigation_style( $obj, $params ) {
	$conditions = array_key_exists( 'conditions', $params ) ? $params['conditions'] : [];
	//  Navigation styles
	$obj->start_controls_section(
		'section_navi_arrow_style',
		[
			'label'     => __( 'Navigation Style', 'blognews-for-elementor' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => $conditions,
		],
	);
	$obj->start_controls_tabs( 'navi_arrow_btn_tabs' );

	$obj->start_controls_tab(
		'navi_arrow_btn_normal_style',
		[
			'label' => __( 'Normal', 'blognews-for-elementor' ),

		]
	);
	$obj->add_control(
		'navi_arrow_color',
		[
			'label' => esc_html__( 'Color', 'blognews-for-elementor' ),
			'type' => \Elementor\Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .swiper-button-prev' => 'color: {{VALUE}};background-image: none;',
				'{{WRAPPER}} .swiper-button-next' => 'color: {{VALUE}};background-image: none;',
			],
		]
	);

	$obj->add_control(
		'navi_arrow_bg_color',
		[
			'label' => esc_html__( 'Background Color', 'blognews-for-elementor' ),
			'type' => \Elementor\Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .swiper-button-prev' => 'background-color: {{VALUE}};background-image: none;',
				'{{WRAPPER}} .swiper-button-next' => 'background-color: {{VALUE}};background-image: none;',
			],
		]
	);

	$obj->add_responsive_control(
		'navi_arrow_opacity',
		[
			'label' => esc_html__( 'Opacity', 'blognews-for-elementor' ),
			'type' => \Elementor\Controls_Manager::SLIDER,
			'size_units' => [ 'px','custom' ],
			'range'  => [ 
				'px' => [ 'min' => 0, 'max' => 1, 'step' => 0.01 ],
			],
			'default_desktop' => ['size' => '', 'unit' => 'px'],
			'tablet_default'  => ['size' => '', 'unit' => 'px'],
			'mobile_default'  => ['size' => '', 'unit' => 'px'],
			'separator'       => 'after',
			'selectors'       => [
				'{{WRAPPER}} .swiper-button-prev' => 'opacity: {{SIZE}};',
				'{{WRAPPER}} .swiper-button-next' => 'opacity: {{SIZE}};',
			],
		]
	);

	$obj->end_controls_tab();

	$obj->start_controls_tab(
		'navi_arrow_btn_style_hover',
		[
			'label' => __( 'Hover', 'blognews-for-elementor' ),
		]
	);

	$obj->add_control(
		'navi_arrow_color_hover',
		[
			'label' => esc_html__( 'Color', 'blognews-for-elementor' ),
			'type' => \Elementor\Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .swiper-button-prev:hover' => 'color: {{VALUE}};background-image: none;',
				'{{WRAPPER}} .swiper-button-next:hover' => 'color: {{VALUE}};background-image: none;',
			],
		]
	);

	$obj->add_control(
		'navi_arrow_bg_color_hover',
		[
			'label' => esc_html__( 'Background Color', 'blognews-for-elementor' ),
			'type' => \Elementor\Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .swiper-button-prev:hover' => 'background-color: {{VALUE}};background-image: none;',
				'{{WRAPPER}} .swiper-button-next:hover' => 'background-color: {{VALUE}};background-image: none;',
			],
		]
	);

	$obj->add_responsive_control(
		'navi_arrow_opacity_hover',
		[
			'label' => esc_html__( 'Arrow Opacity', 'blognews-for-elementor' ),
			'type' => \Elementor\Controls_Manager::SLIDER,
			'size_units' => [ 'px','custom' ],
			'range'  => [ 
				'px' => [ 'min' => 0, 'max' => 1, 'step' => 0.01],
			],
			'default_desktop' => ['size' => '', 'unit' => 'px'],
			'tablet_default'  => ['size' => '', 'unit' => 'px'],
			'mobile_default'  => ['size' => '', 'unit' => 'px'],
			'selectors'       => [
				'{{WRAPPER}} .swiper-button-prev:hover' => 'opacity: {{SIZE}};',
				'{{WRAPPER}} .swiper-button-next:hover' => 'opacity: {{SIZE}};',
			],
		]
	);

	$obj->end_controls_tab();
	$obj->end_controls_tabs();

	$obj->add_responsive_control(
		'navi_arrow_size',
		[
			'label' => esc_html__( 'Size', 'blognews-for-elementor' ),
			'type' => \Elementor\Controls_Manager::SLIDER,
			'size_units' => [ 'px', 'em', 'rem', 'custom' ],
			'range'      => [
				'px'  => ['min' => 0, 'max' => 100],
				'em'  => ['min' => 0, 'max' => 100],
				'rem' => ['min' => 0, 'max' => 100],
			],
			'default_desktop' => ['size' => '', 'unit' => 'px'],
			'tablet_default'  => ['size' => '', 'unit' => 'px'],
			'mobile_default'  => ['size' => '', 'unit' => 'px'],
			'separator'       => 'before',
			'selectors'       => [
				'{{WRAPPER}} .swiper-button-prev::after' => 'font-size: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .swiper-button-next::after' => 'font-size: {{SIZE}}{{UNIT}};',
			],
		]
	);

	$obj->add_responsive_control(
		'navi_arrow_width',
		[
			'label' => esc_html__( 'Width', 'blognews-for-elementor' ),
			'type' => \Elementor\Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
			'range'      => [
				'px'  => ['min' => 0, 'max' => 120],
				'em'  => ['min' => 0, 'max' => 100],
				'rem' => ['min' => 0, 'max' => 100],
			],
			'default_desktop' => ['size' => '', 'unit' => 'px'],
			'tablet_default'  => ['size' => '', 'unit' => 'px'],
			'mobile_default'  => ['size' => '', 'unit' => 'px'],
			'selectors'   => [
				'{{WRAPPER}} .swiper-button-prev' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
				'{{WRAPPER}} .swiper-button-next' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}};',
			],
		]
	);
	
	$obj->add_responsive_control(
		'navi_arrow_border_radius',
		[
			'label' => esc_html__( 'Border Radius', 'blognews-for-elementor' ),
			'type' => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ], 
			'selectors' => [
				'{{WRAPPER}} .swiper-button-prev' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				'{{WRAPPER}} .swiper-button-next' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);

	$obj->end_controls_section();
	//  Navigation styles ends
}
function blogfoel_pagination_style( $obj, $params ) {
	$condition = array_key_exists( 'condition', $params ) ? $params['condition'] : [];
	//  Pagination styles
	$obj->start_controls_section(
		'section_pagination_arrow_style',
		[
			'label'     => __( 'Pagination Style', 'blognews-for-elementor' ),
			'tab'       => Controls_Manager::TAB_STYLE,
			'condition' => $condition,
		]
	);

	$obj->start_controls_tabs( 'pagi_dot_tabs' );

	$obj->start_controls_tab(
		'pagi_dot_normal_style',
		[
			'label' => __( 'Normal', 'blognews-for-elementor' ),
		]
	);

	$obj->add_control(
		'pagi_dot_color',
		[
			'label' => esc_html__( 'Color', 'blognews-for-elementor' ),
			'type' => \Elementor\Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .swiper .swiper-pagination-bullet' => 'background-color: {{VALUE}}; background-image: none;',
			],
		]
	);

	$obj->add_responsive_control(
		'pagi_dot_width',
		[
			'label' => esc_html__( 'Width', 'blognews-for-elementor' ),
			'type' => \Elementor\Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%', 'em', 'rem','vw', 'custom' ],
			'range'      => [
				'px'  => ['min' => 0, 'max' => 100],
				'em'  => ['min' => 0, 'max' => 100],
				'rem' => ['min' => 0, 'max' => 100],
				'vw'  => ['min' => 0, 'max' => 100],
			],
			'default_desktop' => ['size' => '', 'unit' => 'px'],
			'tablet_default'  => ['size' => '', 'unit' => 'px'],
			'mobile_default'  => ['size' => '', 'unit' => 'px'],
			'selectors'   => [
				'{{WRAPPER}} .swiper .swiper-pagination-bullet' => 'width: {{SIZE}}{{UNIT}};',
			],
		]
	);

	$obj->add_responsive_control(
		'pagi_dot_height',
		[
			'label' => esc_html__( 'Height', 'blognews-for-elementor' ),
			'type' => \Elementor\Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%', 'em', 'rem','vh', 'custom' ],
			'range'      => [
				'px'  => ['min' => 0, 'max' => 100],
				'em'  => ['min' => 0, 'max' => 100],
				'rem' => ['min' => 0, 'max' => 100],
				'vh'  => ['min' => 0, 'max' => 100],
			],
			'default_desktop' => ['size' => '', 'unit' => 'px'],
			'tablet_default'  => ['size' => '', 'unit' => 'px'],
			'mobile_default'  => ['size' => '', 'unit' => 'px'],
			'selectors'   => [
				'{{WRAPPER}} .swiper .swiper-pagination-bullet' => 'height: {{SIZE}}{{UNIT}};',
			],
		]
	);

	$obj->add_responsive_control(
		'pagination_dot_opacity',
		[
			'label' => esc_html__( 'Opacity', 'blognews-for-elementor' ),
			'type' => Controls_Manager::SLIDER,
			'range' => [
				'px' => [
					'max' => 1,
					'step' => 0.01,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .swiper-pagination.swiper-pagination-bullets .swiper-pagination-bullet' => 'opacity: {{SIZE}};',
			],
		]
	);

	$obj->end_controls_tab();

	$obj->start_controls_tab(
		'pagi_dot_style_active',
		[
			'label' => __( 'Active', 'blognews-for-elementor' ),
		]
	);

	$obj->add_control(
		'pagi_dot_active_color',
		[
			'label' => esc_html__( 'Color', 'blognews-for-elementor' ),
			'type' => \Elementor\Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .swiper .swiper-pagination-bullet-active' => 'background-color: {{VALUE}}; background-image: none;',
			],
		]
	);

	$obj->add_responsive_control(
		'pagi_dot_active_width',
		[
			'label' => esc_html__( 'Width', 'blognews-for-elementor' ),
			'type' => \Elementor\Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%', 'em', 'rem','vw', 'custom' ],
			'range'      => [
				'px'  => ['min' => 0, 'max' => 100],
				'em'  => ['min' => 0, 'max' => 100],
				'rem' => ['min' => 0, 'max' => 100],
				'vw'  => ['min' => 0, 'max' => 100],
			],
			'default_desktop' => ['size' => '', 'unit' => 'px'],
			'tablet_default'  => ['size' => '', 'unit' => 'px'],
			'mobile_default'  => ['size' => '', 'unit' => 'px'],
			'selectors'   => [
				'{{WRAPPER}} .swiper .swiper-pagination-bullet-active' => 'width: {{SIZE}}{{UNIT}};',
			],
		]
	);

	$obj->add_responsive_control(
		'pagi_dot_active_height',
		[
			'label' => esc_html__( 'Height', 'blognews-for-elementor' ),
			'type' => \Elementor\Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%', 'em', 'rem','vh', 'custom' ],
			'range'      => [
				'px'  => ['min' => 0, 'max' => 100],
				'em'  => ['min' => 0, 'max' => 100],
				'rem' => ['min' => 0, 'max' => 100],
				'vh'  => ['min' => 0, 'max' => 100],
			],
			'default_desktop' => ['size' => '', 'unit' => 'px'],
			'tablet_default'  => ['size' => '', 'unit' => 'px'],
			'mobile_default'  => ['size' => '', 'unit' => 'px'],
			'selectors'   => [
				'{{WRAPPER}} .swiper .swiper-pagination-bullet-active' => 'height: {{SIZE}}{{UNIT}};',
			],
		]
	);

	$obj->add_responsive_control(
		'pagination_dot_active_opacity',
		[
			'label' => esc_html__( 'Opacity', 'blognews-for-elementor' ),
			'type' => Controls_Manager::SLIDER,
			'range' => [
				'px' => [
					'max' => 1,
					'step' => 0.01,
				],
			],
			'selectors'  => [
				'{{WRAPPER}} .blogfoel-slider .swiper-pagination.swiper-pagination-bullets .swiper-pagination-bullet.swiper-pagination-bullet-active, {{WRAPPER}} .blogfoel-carousel .swiper-pagination.swiper-pagination-bullets .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'opacity: {{SIZE}};',
			],
		]
	);

	$obj->end_controls_tab();
	$obj->end_controls_tabs();

	$obj->add_control(
		'hr',
		[
			'type' => \Elementor\Controls_Manager::DIVIDER,
		]
	);

	$obj->add_responsive_control(
		'pagi_dot_space',
		[
			'label' => esc_html__( 'Space', 'blognews-for-elementor' ),
			'type' => \Elementor\Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%', 'em', 'rem','vh', 'custom' ],
			'range'      => [
				'px'  => ['min' => 0, 'max' => 100],
				'em'  => ['min' => 0, 'max' => 100],
				'rem' => ['min' => 0, 'max' => 100],
				'vh'  => ['min' => 0, 'max' => 100],
			],
			'default_desktop' => ['size' => '', 'unit' => 'px'],
			'tablet_default'  => ['size' => '', 'unit' => 'px'],
			'mobile_default'  => ['size' => '', 'unit' => 'px'],
			'selectors'   => [
				'{{WRAPPER}} .swiper .swiper-pagination-bullet' => 'margin: 0 {{SIZE}}{{UNIT}};',
			],
		]
	);

	$obj->add_responsive_control(
		'pagi_dot_border_radius',
		[
			'label' => esc_html__( 'Border Radius', 'blognews-for-elementor' ),
			'type' => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ], 
			'selectors' => [
				'{{WRAPPER}} .swiper .swiper-pagination-bullet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);

	$obj->end_controls_section();
	//  Pagination styles ends
}
function blogfoel_post_pagination_style( $obj, $params ) {

	$obj->start_controls_section(
		'pagination_style',
		[
			'label' => __( 'Pagination', 'blognews-for-elementor' ),
			'tab'   => Controls_Manager::TAB_STYLE,
		]
	);

	$slug = 'pagination';
	$obj->start_controls_tabs( $slug.'_style_tabs' );

	$obj->start_controls_tab(
		$slug.'_normal_style',
		[
			'label' => __( 'Normal', 'blognews-for-elementor' ),
		]
	);

	$obj->add_control(
		$slug.'_color',
		[
			'label' => esc_html__( 'Color', 'blognews-for-elementor' ),
			'type' => \Elementor\Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .blogfoel-navigation li .page-numbers' => 'color: {{VALUE}};',
			],
		]
	);
	$obj->add_group_control(
		\Elementor\Group_Control_Background::get_type(),
		[
			'name'      => $slug.'_bg_color',
			'types'          => [ 'classic', 'gradient' ],
			'exclude'        => [ 'image' ],
			'fields_options' => [
				'background' => [
					'label' => esc_html__( 'Background Color', 'blognews-for-elementor' ),
					'default' => 'classic',
				],
			],
			'selector'  => '{{WRAPPER}} .blogfoel-navigation li .page-numbers',
		]
	);

	$obj->end_controls_tab();

	$obj->start_controls_tab(
		$slug.'_hover_style',
		[
			'label' => __( 'Hover', 'blognews-for-elementor' ),
		]
	);
	$obj->add_control(
		$slug.'_color_hover',
		[
			'label' => esc_html__( 'Color', 'blognews-for-elementor' ),
			'type' => \Elementor\Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .blogfoel-navigation li .page-numbers:hover' => 'color: {{VALUE}};',
			],
		]
	);
	$obj->add_group_control(
		\Elementor\Group_Control_Background::get_type(),
		[
			'name'      => $slug.'_bg_color_hover',
			'types'          => [ 'classic', 'gradient' ],
			'exclude'        => [ 'image' ],
			'fields_options' => [
				'background' => [
					'label' => esc_html__( 'Background Color', 'blognews-for-elementor' ),
					'default' => 'classic',
				],
			],
			'selector'  => '{{WRAPPER}} .blogfoel-navigation li .page-numbers:hover',
		]
	);

	$obj->end_controls_tab();

	$obj->start_controls_tab(
		$slug.'_active_style',
		[
			'label' => __( 'Active', 'blognews-for-elementor' ),
		]
	);
	$obj->add_control(
		$slug.'_color_active',
		[
			'label' => esc_html__( 'Color', 'blognews-for-elementor' ),
			'type' => \Elementor\Controls_Manager::COLOR,
			'selectors' => [
				'{{WRAPPER}} .blogfoel-navigation li .current.page-numbers' => 'color: {{VALUE}};',
			],
		]
	);
	$obj->add_group_control(
		\Elementor\Group_Control_Background::get_type(),
		[
			'name'      => $slug.'_bg_color_active',
			'types'          => [ 'classic', 'gradient' ],
			'exclude'        => [ 'image' ],
			'fields_options' => [
				'background' => [
					'label' => esc_html__( 'Background Color', 'blognews-for-elementor' ),
					'default' => 'classic',
				],
			],
			'selector'  => '{{WRAPPER}} .blogfoel-navigation li .current.page-numbers',
		]
	);
	
	$obj->end_controls_tab();
	$obj->end_controls_tabs();

	$obj->add_control(
		$slug.'_hr',
		[
			'type' => \Elementor\Controls_Manager::DIVIDER,
		]
	);

	$obj->add_group_control(
		\Elementor\Group_Control_Typography::get_type(),
		[
			'name'     =>  $slug.'_typography',
			'label'     => __( 'Typography', 'blognews-for-elementor' ),
			'selector' => '{{WRAPPER}} .blogfoel-navigation li .page-numbers',
		]
	);
	$obj->add_responsive_control(
		$slug.'_width',
		[
			'label' => esc_html__( 'Width', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
			'type' => \Elementor\Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
			'range' => [
				'px'  => ['min' => 0, 'max' => 200],
				'em'  => ['min' => 0, 'max' => 100],
				'rem' => ['min' => 0, 'max' => 100],
				'vw'  => ['min' => 0, 'max' => 100],
			],
			'devices'         => [ 'desktop', 'tablet', 'mobile' ],
			'default'         => ['size' => '', 'unit' => 'px'],
			'tablet_default'  => ['size' => '', 'unit' => 'px'],
			'mobile_default'  => ['size' => '', 'unit' => 'px'],
			'classes' => 'blogfoel-pro-popup-notice',
		]
	);
	$obj->add_responsive_control(
		$slug.'_height',
		[
			'label' => esc_html__( 'Height', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
			'type' => \Elementor\Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%', 'em', 'rem', 'vh', 'custom' ],
			'range' => [
				'px'  => ['min' => 0, 'max' => 200],
				'em'  => ['min' => 0, 'max' => 100],
				'rem' => ['min' => 0, 'max' => 100],
				'vh'  => ['min' => 0, 'max' => 100],
			],
			'devices'         => [ 'desktop', 'tablet', 'mobile' ],
			'default'         => ['size' => '', 'unit' => 'px'],
			'tablet_default'  => ['size' => '', 'unit' => 'px'],
			'mobile_default'  => ['size' => '', 'unit' => 'px'],
			'classes' => 'blogfoel-pro-popup-notice',
		]
	);

	$obj->add_responsive_control(
		$slug.'_gap',
		[
			'label' => esc_html__( 'Gap', 'blognews-for-elementor' ),
			'type' => \Elementor\Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
			'range' => [
				'px'  => ['min' => 0, 'max' => 100],
				'em'  => ['min' => 0, 'max' => 100],
				'rem' => ['min' => 0, 'max' => 100],
			],
			'devices'         => [ 'desktop', 'tablet', 'mobile' ],
			'default'         => ['size' => '', 'unit' => 'px'],
			'tablet_default'  => ['size' => '', 'unit' => 'px'],
			'mobile_default'  => ['size' => '', 'unit' => 'px'],
			'selectors'       => [
				'{{WRAPPER}} .blogfoel-navigation .page-numbers' => 'gap: {{SIZE}}{{UNIT}};',
			],
		]
	);
	$obj->add_group_control(
		Group_Control_Border::get_type(),
		[
			'name'     => $slug.'_border_type',
			'label'    => 'Border Type',
			'selector' => '{{WRAPPER}} .blogfoel-navigation li .page-numbers',
		]
	);

	$obj->add_responsive_control(
		$slug.'_border_radius',
		[
			'label' => esc_html__( 'Border Radius', 'blognews-for-elementor' ),
			'type' => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
			'selectors' => [
				'{{WRAPPER}} .blogfoel-navigation li .page-numbers' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
			],
		]
	);
	$obj->add_responsive_control(
		$slug.'_padding',
		[
			'label' => esc_html__( 'Padding', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
			'type' => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
			'classes' => 'blogfoel-pro-popup-notice',
		]
	);
	$obj->add_responsive_control(
		$slug.'_margin',
		[
			'label' => esc_html__( 'Margin', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
			'type' => \Elementor\Controls_Manager::DIMENSIONS,
			'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
			'classes' => 'blogfoel-pro-popup-notice',
		]
	);

	$obj->add_control(
		$slug . '_box_shadow',
		[
			'label' => esc_html__( 'Box Shadow', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
			'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
			'label_off' => esc_html__( 'Default', 'blognews-for-elementor' ),
			'label_on' => esc_html__( 'Custom', 'blognews-for-elementor' ),
			'return_value' => 'yes',
			'default' => 'no',
			'classes' => 'blogfoel-pro-popup-notice',
		]
	);
	$obj->start_popover();
	$obj->end_popover();

	$obj->add_control(
		$slug.'_bottom_hr',
		[
			'type' => \Elementor\Controls_Manager::DIVIDER,
		]
	);
	$obj->add_responsive_control(
		$slug.'_top_margin',
		[
			'label' => esc_html__( 'Top Gap', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
			'type' => \Elementor\Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
			'range' => [
				'px'  => ['min' => 0, 'max' => 200],
				'em'  => ['min' => 0, 'max' => 100],
				'rem' => ['min' => 0, 'max' => 100],
				'vw'  => ['min' => 0, 'max' => 100],
			],
			'devices'         => [ 'desktop', 'tablet', 'mobile' ],
			'default'         => ['size' => '', 'unit' => 'px'],
			'tablet_default'  => ['size' => '', 'unit' => 'px'],
			'mobile_default'  => ['size' => '', 'unit' => 'px'],
			'classes' => 'blogfoel-pro-popup-notice',
		]
	);

	$obj->add_responsive_control(
		$slug.'_bottom_margin',
		[
			'label' => esc_html__( 'Bottom Gap', 'blognews-for-elementor' ) . BLOGFOEL_PRO_ICON,
			'type' => \Elementor\Controls_Manager::SLIDER,
			'size_units' => [ 'px', '%', 'em', 'rem', 'vw', 'custom' ],
			'range' => [
				'px'  => ['min' => 0, 'max' => 200],
				'em'  => ['min' => 0, 'max' => 100],
				'rem' => ['min' => 0, 'max' => 100],
				'vw'  => ['min' => 0, 'max' => 100],
			],
			'devices'         => [ 'desktop', 'tablet', 'mobile' ],
			'default'         => ['size' => '', 'unit' => 'px'],
			'tablet_default'  => ['size' => '', 'unit' => 'px'],
			'mobile_default'  => ['size' => '', 'unit' => 'px'],
			'classes' => 'blogfoel-pro-popup-notice',
		]
	);
	
	$obj->end_controls_section();
}