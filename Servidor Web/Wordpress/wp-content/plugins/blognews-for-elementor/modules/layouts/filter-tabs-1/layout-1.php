<?php
if (empty($post_content_repeater) || !is_array($post_content_repeater)) {
    return;
}
?>
<div id="post-<?php the_ID(); ?>" <?php post_class('blogfoel-small-post' . $image_class); ?>>
    <?php if ($thumbnail_id && $show_image == 'yes') { ?>
        <div class="blogfoel-back-img">
            <?php 
            $image_src = \Elementor\Group_Control_Image_Size::get_attachment_image_src($thumbnail_id, 'thumbnail_size', $settings);
            echo sprintf('<img src="%s" title="%s" alt="%s" class="blogfoel-image back-img" />',
                esc_attr($image_src),
                esc_attr(get_the_title($thumbnail_id)),
                esc_attr(blognews_get_attachment_alt($thumbnail_id))
            );
            ?>
            <a href="<?php echo esc_url(get_permalink())?>" class="img-link"></a>
        </div>
    <?php } ?>

    <div class="blogfoel-inner">
        <?php        
            foreach ($post_content_repeater as $post_item) :
                $content_type = $post_item['post_content'];
                $show_content = $post_item['show_content'];
                $show_item    = $show_content == 'yes';

                $meta_html = '';

                if ($content_type === 'meta' && !empty($post_meta_repeater)) {
                    $meta_html .= '<div class="blogfoel-meta">';
                
                    foreach ($post_meta_repeater as $meta_item) {
                        $meta_type            = $meta_item['post_meta'];
                        $avatar               = $meta_item['show_avatar'];
                        $icon                 = $meta_item['meta_icon'];
                        if ($meta_type === 'author') {
                            $meta_html .= blogfoel_get_author($avatar, $icon);
                        } elseif ($meta_type === 'date') {
                            $meta_html .= blogfoel_get_date('', $icon);
                        }
                    }
                
                    $meta_html .= '</div>';
                }
                
                $content_templates = [
                    'category'      => $show_item ? blogfoel_get_all_category() : '',
                    'title'     => $show_item ? '<' . esc_html($title_html_tag) . ' class="blogfoel-title '.esc_attr($title_animation).'">
                                    <a href="' . esc_url(get_permalink()) . '" title="' . esc_attr(get_the_title()) . '">
                                        ' . esc_html(get_the_title()) . '
                                    </a>
                                </' . esc_html($title_html_tag) . '>' :'',

                    'meta'      => $show_item ? $meta_html : '',
                ];

                if (isset($content_templates[$content_type])) {
                    echo wp_kses_post($content_templates[$content_type]);
                }
            endforeach;
            ?>
        </div>
</div>
<?php