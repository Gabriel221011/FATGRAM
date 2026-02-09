<?php 
/**
 * AJAX handler to store the state of dismissible notices.
 */
function newseblog_ajax_notice_handler() {
    if ( isset( $_POST['type'] ) ) {
        // Pick up the notice "type" - passed via jQuery (the "data-notice" attribute on the notice)
        $type = sanitize_text_field( wp_unslash( $_POST['type'] ) );
        // Store it in the options table
        update_option( 'dismissed-' . $type, TRUE );
    }
}

add_action( 'wp_ajax_newseblog_dismissed_notice_handler', 'newseblog_ajax_notice_handler' );

function newseblog_deprecated_hook_admin_notice() {
    // Check if it's been dismissed...
    if ( ! get_option('dismissed-get_started', FALSE ) ) {
        // Added the class "notice-get-started-class" so jQuery pick it up and pass via AJAX,
        // and added "data-notice" attribute in order to track multiple / different notices
        // multiple dismissible notice states ?>
        <div class="newseblog-notice-started updated notice notice-get-started-class is-dismissible" data-notice="get_started">
            <div class="newseblog-notice clearfix">
                <div class="newseblog-notice-content">
                    <div class="newseblog-notice_text">
                        <div class="newseblog-hello">
                            <?php esc_html_e( 'Hello, ', 'newseblog' ); 
                            $current_user = wp_get_current_user();
                            echo esc_html( $current_user->display_name );
                            ?>
                            <img draggable="false" role="img" class="emoji" alt="👋🏻" src="https://s.w.org/images/core/emoji/14.0.0/svg/1f44b-1f3fb.svg">                
                        </div>
                        <h1>
                            <?php $theme_info = wp_get_theme();
                            printf( esc_html__('Welcome to %1$s', 'newseblog'), esc_html( $theme_info->Name ), esc_html( $theme_info->Version ) ); ?>
                        </h1>
                        
                       <p>
                        <?php
                            echo wp_kses_post( sprintf(
                                __(
                                    'Thank you for choosing %1$s theme. To take full advantage of the complete features of the theme, click Get Started and install and activate the %2$s plugin, then use the demo importer and install the %3$s demo according to your need.',
                                    'newseblog'
                                ),
                                esc_html($theme_info->Name),
                                '<a href="https://wordpress.org/plugins/ansar-import" target="_blank">' . esc_html__('Ansar Import', 'newseblog') . '</a>',
                                esc_html($theme_info->Name)
                            ) );
                            ?>
                        </p>

                        <div class="panel-column-6">
                            <div class="newseblog-notice-buttons">
                                <a class="newseblog-btn-get-started button button-primary button-hero newseblog-button-padding" href="#" data-name="" data-slug=""><span aria-hidden="true" class="dashicons dashicons-external"></span><?php esc_html_e( 'Get Started', 'newseblog' ) ?></a>
                                <a class="newseblog-btn-demos button button-secondary button-hero newseblog-button-padding" href="<?php echo esc_url('https://blognews.themeansar.com/starter-sites/')?>" data-name="" data-slug=""><span aria-hidden="true" class="dashicons dashicons-images-alt"></span><?php esc_html_e( 'View Demos', 'newseblog' ) ?></a>
                            </div>
                            <div class="newseblog-notice-links">
                                <div class="newseblog-documentation newseblog-notice-link">
                                    <span aria-hidden="true" class="dashicons dashicons-list-view"></span>
                                    <a class="newseblog-documentation" href="<?php echo esc_url('https://docs.themeansar.com/docs/blognews-for-elementor/')?>" data-name="" data-slug=""><?php esc_html_e( 'View Documentation', 'newseblog' ) ?></a>
                                </div>
                                <div class="newseblog-support newseblog-notice-link">
                                    <span aria-hidden="true" class="dashicons dashicons-format-chat"></span>
                                    <a class="newseblog-support" href="<?php echo esc_url('https://themeansar.ticksy.com/')?>" data-name="" data-slug=""><?php esc_html_e( 'Support', 'newseblog' ) ?></a>
                                </div>
                                <div class="newseblog-videos newseblog-notice-link">
                                    <span aria-hidden="true" class="dashicons dashicons-video-alt3"></span>
                                    <a class="newseblog-videos" href="<?php echo esc_url('https://www.youtube.com/watch?v=73AgZuKzHSY&list=PLWpTqYqS4j-yHASHJ9cVdrjBxigIX02Mg')?>" data-name="" data-slug=""><?php esc_html_e( 'Video Tutorials', 'newseblog' ) ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="newseblog-notice_image">
                    <?php 
                    $image_url = get_theme_file_uri( '/images/notice-img.png' );
                    // Check if the file exists
                    if ( file_exists( get_theme_file_path( '/images/notice-img.png' ) ) ) { ?>
                        <img class="newseblog-screenshot" src="<?php echo esc_url( $image_url ); ?>" alt="<?php esc_attr_e( 'NewseBlog', 'newseblog' ); ?>" />
                    <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    <?php }
}

add_action( 'admin_notices', 'newseblog_deprecated_hook_admin_notice' );

/* Plugin Install */
add_action( 'wp_ajax_install_act_plugin', 'newseblog_admin_info_install_plugin' );

function newseblog_admin_info_install_plugin() {

    // Check user capability
    if ( ! current_user_can( 'install_plugins' ) ) {
        wp_send_json_error( array( 'message' => __( 'Sorry, you are not allowed to access this page.', 'newseblog' ) ), 403 );
    }

    // Security Nonce verification
    check_ajax_referer( 'newseblog_install_plugin_nonce', 'security' );

    include_once ABSPATH . 'wp-admin/includes/file.php';
    include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
    include_once ABSPATH . 'wp-admin/includes/plugin-install.php';
    include_once ABSPATH . 'wp-admin/includes/plugin.php';

    // List of plugins to install and activate
    $plugins = array(
        'elementor' => 'elementor/elementor.php',
        'blognews-for-elementor' => 'blognews-for-elementor/blognews-for-elementor.php',
        'ansar-import' => 'ansar-import/ansar-import.php',
    );

    foreach ( $plugins as $slug => $main_file ) {

        // Install plugin if it does not exist
        if ( ! file_exists( WP_PLUGIN_DIR . '/' . $slug ) ) {
            $api = plugins_api( 'plugin_information', array(
                'slug'   => $slug,
                'fields' => array( 'sections' => false ),
            ) );

            if ( is_wp_error( $api ) ) {
                wp_send_json_error( array( 'message' => sprintf( __( 'Failed to fetch plugin information.', 'newseblog' ), $slug ) ) );
            }

            $skin     = new WP_Ajax_Upgrader_Skin();
            $upgrader = new Plugin_Upgrader( $skin );
            $result   = $upgrader->install( $api->download_link );

            if ( is_wp_error( $result ) ) {
                wp_send_json_error( array( 'message' => sprintf( __( 'Plugin installation failed.', 'newseblog' ), $slug ) ) );
            }
        }
        
        // Activate plugin
        if ( current_user_can( 'activate_plugins' ) ) {
            activate_plugin( $main_file );

            // Elementor redirect skip (only once)
            if ( 'elementor' === $slug ) {

                if ( get_option( 'elementor_onboarded' ) ) {
                    delete_option( 'elementor_onboarded' );
                }

                if ( ! get_option( 'newseblog_elementor_skip_done', false ) ) {
                    delete_transient( 'elementor_activation_redirect' );
                    update_option( 'newseblog_elementor_skip_done', true );
                }
            }
        }
    }

    wp_send_json_success( array( 'message' => __( 'All plugins installed and activated successfully.', 'newseblog' ) ) );
}