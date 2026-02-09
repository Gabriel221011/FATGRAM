<?php
?>
<div id="post-<?php the_ID(); ?>" <?php post_class('blogfoel-post-item overlay '.$layout_style . $image_class); ?>>
    <div class="blogfoel-post-item-box">
        <?php if ($thumbnail_id && $show_image == 'yes') { ?>
            <div class="blogfoel-img-wraper">
                <?php 
                $image_src = \Elementor\Group_Control_Image_Size::get_attachment_image_src($thumbnail_id, 'thumbnail_size', $settings);
                echo sprintf('<img src="%s" title="%s" alt="%s" class="blogfoel-image back-img" />',
                    esc_attr($image_src),
                    esc_attr(get_the_title($thumbnail_id)),
                    esc_attr(blognews_get_attachment_alt($thumbnail_id))
                );
            echo '</div>';
        } ?>
        <div class="blogfoel-inner">
            <?php
            if ( $settings['show_category'] === 'yes') {
                echo wp_kses_post(blogfoel_get_all_category());
            }
            if ( $settings['show_title'] === 'yes') {
                echo '<' . esc_html($title_html_tag) . ' class="blogfoel-title '.esc_attr($title_animation).'">
                    <a href="' . esc_url(get_permalink()) . '" title="' . esc_attr(get_the_title()) . '">
                        ' . esc_html(get_the_title()) . '
                    </a>
                </' . esc_html($title_html_tag) . '>';
            }
            echo'<div class="blogfoel-meta">';
                if ($settings['show_author'] === 'yes') {
                    echo wp_kses_post(blogfoel_get_author($settings['show_avatar'], $settings['author_icon']));
                } if ($settings['show_date'] === 'yes') {
                    echo wp_kses_post(blogfoel_get_date('', $settings['date_icon']));
                } if ($settings['show_edit'] === 'yes') {
                    echo wp_kses_post(blogfoel_get_edit_post_link($settings['edit_icon'], $settings['edit_title']));
                }
            echo '</div>';
            ?>
            <a href="<?php echo esc_url(get_permalink())?>" class="img-link"></a>
        </div>
    </div>
</div>
<?php