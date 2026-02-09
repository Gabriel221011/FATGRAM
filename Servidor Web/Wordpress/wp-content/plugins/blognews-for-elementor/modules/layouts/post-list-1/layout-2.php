<?php
?>
<div id="post-<?php the_ID(); ?>" <?php post_class('blogfoel-post-item '.$layout_style . $image_class); ?>>
    <div class="blogfoel-post-item-box">    
    <?php
    if (!empty($categories) && $show_category === 'yes'){
        $cat_html = blogfoel_get_all_category();
    }
    if ($thumbnail_id && $show_image == 'yes') { ?>
        <div class="blogfoel-img-wraper">
            <?php echo wp_kses_post($cat_html);
            $image_src = \Elementor\Group_Control_Image_Size::get_attachment_image_src($thumbnail_id, 'thumbnail_size', $settings);
            echo sprintf('<img src="%s" title="%s" alt="%s" class="blogfoel-image back-img" />',
                esc_attr($image_src),
                esc_attr(get_the_title($thumbnail_id)),
                esc_attr(blognews_get_attachment_alt($thumbnail_id))
            );
            ?>
            <a href="<?php echo esc_url(get_permalink())?>" class="img-link"></a>
        </div>
        <div class="blogfoel-inner">
    <?php } else { ?>

    <div class="blogfoel-inner">
        <?php echo wp_kses_post($cat_html);
        }

            $button_icon_html = '';
            if ( ! empty( $settings['btn_icon'] ) ) {
                ob_start(); // Start output buffering
                \Elementor\Icons_Manager::render_icon( $settings['btn_icon'], [ 'aria-hidden' => 'true' ] );
                $button_icon_html = ob_get_clean(); // Get the buffered content
            }

            foreach ($active_repeater as $post_item) :
                $content_type = $post_item['post_content'];
                $show_content = $post_item['show_content'];
                $show_item    = $show_content == 'yes';

                $meta_html = '';

                if ($content_type === 'meta' && !empty($post_meta_repeater)) {
                    $meta_html .= '<div class="blogfoel-meta">';
                
                    foreach ($post_meta_repeater as $meta_item) {
                        $meta_type            = $meta_item['post_meta'];
                        $avatar               = $meta_item['show_avatar'];
                        $edit_title           = $meta_item['edit_title'];
                        $icon                 = $meta_item['meta_icon'];
                        if ($meta_type === 'author') {
                            $meta_html .= blogfoel_get_author($avatar, $icon);
                        } elseif ($meta_type === 'date') {
                            $meta_html .= blogfoel_get_date('', $icon);
                        } elseif ($meta_type === 'edit') {
                            $meta_html .=  blogfoel_get_edit_post_link($icon, $edit_title);
                        }
                    }
                
                    $meta_html .= '</div>';
                }
                
                $content_templates = [
                    'title'     => $show_item ? '<' . esc_html($title_html_tag) . ' class="blogfoel-title '.esc_attr($title_animation).'">
                                    <a href="' . esc_url(get_permalink()) . '" title="' . esc_attr(get_the_title()) . '">
                                        ' . esc_html(get_the_title()) . '
                                    </a>
                                </' . esc_html($title_html_tag) . '>' :'',

                    'meta'      => $show_item ? $meta_html : '',

                    'excerpt'   => $show_item ? '<div class="blogfoel-excerpt">
                                    ' . wp_kses_post(wp_trim_words(get_the_excerpt(), $excerpt_length)) . '
                                </div>':'',

                    'button'    => $show_item ? '<div class="blogfoel-button">
                                    <a class="blogfoel-more-link" href="' . esc_url(get_permalink()) . '">'
                                        . esc_html($btn_title) .
                                $button_icon_html .'
                                    </a>
                                </div>':'',
                ];

                if (isset($content_templates[$content_type])) {
                    echo wp_kses_post($content_templates[$content_type]);
                }
            endforeach;
            ?>
        </div>
    </div>
</div>
<?php