<?php
/**
 * Recommended plugins.
 */
	
require get_template_directory() . '/inc/tgm/class-tgm-plugin-activation.php';

function healthy_food_blogger_register_recommended_plugins() {
	$plugins = array(
		array(
			'name'             => __( 'Current Date Shortcode For WordPess', 'healthy-food-blogger' ),
			'slug'             => 'current-date',
			'required'         => false,
			'force_activation' => false,
		),
		array(
			'name'             => __( 'WooCommerce', 'healthy-food-blogger' ),
			'slug'             => 'woocommerce',
			'required'         => false,
			'force_activation' => false,
		),
		array(
			'name'             => __( 'Creta Testimonial Showcase', 'healthy-food-blogger' ),
			'slug'             => 'creta-testimonial-showcase',
			'source'           => '',
			'required'         => false,
			'force_activation' => false,
		)
	);
	$config = array();
	healthy_food_blogger_tgmpa( $plugins, $config );
}
add_action( 'tgmpa_register', 'healthy_food_blogger_register_recommended_plugins' );

//Creta Testimonial Showcase plugin activation
add_action('wp_ajax_install_and_activate_creta_testimonial_plugin', 'install_and_activate_creta_testimonial_plugin');

function install_and_activate_creta_testimonial_plugin() {
    // Verify nonce for security
    if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'install_activate_nonce')) {
        wp_send_json_error(['message' => 'Nonce verification failed.']);
    }

    // Include necessary WordPress files
    include_once ABSPATH . 'wp-admin/includes/plugin.php';
    include_once ABSPATH . 'wp-admin/includes/file.php';
    include_once ABSPATH . 'wp-admin/includes/misc.php';
    include_once ABSPATH . 'wp-admin/includes/class-wp-upgrader.php';
    include_once ABSPATH . 'wp-admin/includes/plugin-install.php';

    $healthy_food_blogger_upgrader = new Plugin_Upgrader(new Automatic_Upgrader_Skin());

    // Define required plugins
    $plugins = [
        [
            'slug'     => 'creta-testimonial-showcase',
            'file'     => 'creta-testimonial-showcase/creta-testimonial-showcase.php',
            'url'      => 'https://downloads.wordpress.org/plugin/creta-testimonial-showcase.latest-stable.zip',
        ]
    ];

    $healthy_food_blogger_installed_plugins = get_plugins();

    foreach ($plugins as $plugin) {
        // Install if not present
        if (!isset($healthy_food_blogger_installed_plugins[$plugin['file']])) {
            $healthy_food_blogger_install_result = $healthy_food_blogger_upgrader->install($plugin['url']);
            if (is_wp_error($healthy_food_blogger_install_result)) {
                wp_send_json_error(['message' => "Failed to install {$plugin['slug']}"]);
            }
        }

        // Activate if not active
        if (!is_plugin_active($plugin['file'])) {
            $healthy_food_blogger_activate_result = activate_plugin($plugin['file']);
            if (is_wp_error($healthy_food_blogger_activate_result)) {
                wp_send_json_error([
                    'message' => "Failed to activate {$plugin['slug']}",
                    'error'   => $healthy_food_blogger_activate_result->get_error_message(),
                ]);
            }
        }
    }

    // Success response
    wp_send_json_success(['message' => 'Creta Testimonial Showcase plugins are activated successfully.']);
}

