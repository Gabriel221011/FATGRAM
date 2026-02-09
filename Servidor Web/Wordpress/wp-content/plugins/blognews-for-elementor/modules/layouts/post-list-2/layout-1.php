<?php
if ( $post_count <= 0 ) { ?>
<div class="blogfoel-post-item">
    <div class="blogfoel-post-item-box">    
        <?php 
        $cat_html = '';
        if ( $show_category === 'yes'){
            $cat_html .= blogfoel_get_all_category();
        }
        if ($thumbnail_id && $show_image == 'yes') { ?>
        <div class="blogfoel-img-wraper">
            <?php 
            $image_src = \Elementor\Group_Control_Image_Size::get_attachment_image_src($thumbnail_id, 'thumbnail_size', $settings);
            echo sprintf('<img src="%s" title="%s" alt="%s" class="blogfoel-image back-img" />',
                esc_attr($image_src),
                esc_attr(get_the_title($thumbnail_id)),
                esc_attr(blognews_get_attachment_alt($thumbnail_id))
            ); ?>
            <a href="<?php echo esc_url(get_permalink())?>" class="img-link"></a>
        </div>
        <?php } ?>
        <div class="blogfoel-inner <?php echo esc_attr($this->blog_inner_class);?>">
           <?php echo wp_kses_post($cat_html);
          
            echo '<' . esc_html($title_html_tag) . ' class="blogfoel-title '.esc_attr($title_animation).'">
                    <a href="' . esc_url(get_permalink()) . '" title="' . esc_attr(get_the_title()) . '">
                        ' . esc_html(get_the_title()) . '
                    </a>
                </' . esc_html($title_html_tag) . '>';
            if ($settings['show_author'] === 'yes' || $settings['show_date'] === 'yes'){ ?>
            <div class="blogfoel-meta">
                <?php if ($settings['show_author'] === 'yes') {
                    echo wp_kses_post(blogfoel_get_author($settings['show_avatar'], $settings['author_icon']));
                } if ($settings['show_date'] === 'yes') {
                    echo wp_kses_post(blogfoel_get_date('', $settings['date_icon']));
                } ?>
            </div>
            <?php } if ( $excerpt_length > 0 ) {
                echo '<div class="blogfoel-excerpt">';
                echo wp_kses_post(wp_trim_words(get_the_excerpt(), $excerpt_length));
                echo '</div>';
            }
            ?>
        </div>
    </div>
</div>
<?php } else { ?>
<div class="blogfoel-small-post">
    <?php if ($thumbnail_id && $show_small_image == 'yes') { ?>
        <div class="blogfoel-back-img">
            <?php 
            $image_src = \Elementor\Group_Control_Image_Size::get_attachment_image_src($thumbnail_id, 'thumbnail_small_size', $settings);
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
        <?php if ( $show_small_category === 'yes'){ echo wp_kses_post(blogfoel_get_all_category()); }
        echo '<' . esc_html($title_html_tag) . ' class="blogfoel-title '.esc_attr($title_animation).'">
                <a href="' . esc_url(get_permalink()) . '" title="' . esc_attr(get_the_title()) . '">
                    ' . esc_html(get_the_title()) . '
                </a>
            </' . esc_html($title_html_tag) . '>'; 
            if ($settings['show_small_author'] === 'yes' || $settings['show_small_date'] === 'yes'){ ?>
            <div class="blogfoel-meta">
                <?php if ($settings['show_small_author'] === 'yes') {
                    echo wp_kses_post(blogfoel_get_author($settings['show_small_avatar'], $settings['small_author_icon']));
                } if ($settings['show_small_date'] === 'yes') {
                    echo wp_kses_post(blogfoel_get_date('', $settings['small_date_icon']));
                } ?>
            </div>
            <?php } ?>
    </div>
</div>
<?php } ?>