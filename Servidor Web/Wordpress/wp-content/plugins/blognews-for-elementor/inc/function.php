<?php
/**
 * Elementor Version Check
 */
if (!function_exists('blogfoel_elements_plugin_load')) {

    function blogfoel_elements_plugin_load() {
        
       if (!did_action('elementor/loaded')) {
            add_action('admin_notices', 'blogfoel_elements_widget_fail_load');
            return;
        }
        $elementor_version_required = '1.1.2';
        if (!version_compare(ELEMENTOR_VERSION, $elementor_version_required, '>=')) {
            add_action('admin_notices', 'blogfoel_elements_elementor_update_notice');
            return;
        }
    }

}
add_action('plugins_loaded', 'blogfoel_elements_plugin_load');

require_once BLOGFOEL_DIR_PATH . 'inc/widget-utils.php';
require_once BLOGFOEL_DIR_PATH . 'inc/widget-controls.php';
require_once BLOGFOEL_DIR_PATH . 'inc/instance.php';


/**
 *   Notice for Elementor is not installed or activated or both
 */
if (!function_exists('blogfoel_elements_widget_fail_load')) {

    function blogfoel_elements_widget_fail_load() {
        $screen = get_current_screen();
        if (isset($screen->parent_file) && 'plugins.php' === $screen->parent_file && 'update' === $screen->id) {
            return;
        }

        $plugin = 'elementor/elementor.php';

        if (blogfoel_elements_elementor_installed()) {
            if (!current_user_can('activate_plugins')) {
                return;
            }
            // Fixed: Removed &amp; entities
            $activation_url = wp_nonce_url('plugins.php?action=activate&plugin=' . $plugin . '&plugin_status=all&paged=1&s', 'activate-plugin_' . $plugin);

            $message = '<p><strong>' . __('BlogNews For Elementor', 'blognews-for-elementor') . '</strong>' . __(' Activate the Elementor for access Blog Elementor Plugin', 'blognews-for-elementor') . '</p>';
            $message .= '<p>' . sprintf('<a href="%s" class="button-primary">%s</a>', $activation_url, __('Activate Elementor Now', 'blognews-for-elementor')) . '</p>';
        } else {
            if (!current_user_can('install_plugins')) {
                return;
            }

            $install_url = wp_nonce_url(self_admin_url('update.php?action=install-plugin&plugin=elementor'), 'install-plugin_elementor');

            $message = '<p><strong>' . __('BlogNews For Elementor', 'blognews-for-elementor') . '</strong>' . __(' Plugin not working need to activate Elementor plugin', 'blognews-for-elementor') . '</p>';
            $message .= '<p>' . sprintf('<a href="%s" class="button-primary">%s</a>', $install_url, __('Install Elementor Now', 'blognews-for-elementor')) . '</p>';
        }

        // Fixed: Use modern notice class
        echo '<div class="notice notice-error"><p>' . wp_kses_post($message) . '</p></div>';
    }
}

/**
 * Admin Notice for Check Elementor Version
 */
if (!function_exists('blogfoel_elements_elementor_update_notice')) {

    function blogfoel_elements_elementor_update_notice() {
        if (!current_user_can('update_plugins')) {
            return;
        }

        $file_path = 'elementor/elementor.php';

        $upgrade_link = wp_nonce_url(self_admin_url('update.php?action=upgrade-plugin&plugin=') . $file_path, 'upgrade-plugin_' . $file_path);
        $message = '<p><strong>' . __('BlogNews For Elementor', 'blognews-for-elementor') . '</strong>' . __('Plugin not working because you are using an old version of Elementor.', 'blognews-for-elementor') . '</p>';
        $message .= '<p>' . sprintf('<a href="%s" class="button-primary">%s</a>', $upgrade_link, __('Update Elementor Now', 'blognews-for-elementor')) . '</p>';
        echo '<div class="error">' . wp_kses_post($message) . '</div>';
    }

}

/**
 * Action when plugin installed
 */
if (!function_exists('blogfoel_elements_elementor_installed')) {

    function blogfoel_elements_elementor_installed() {

        $file_path = 'elementor/elementor.php';
        $installed_plugins = get_plugins();

        return isset($installed_plugins[$file_path]);
    }
}

// Add color field to "Add Category" form
if (!class_exists('BlogfoelCatColorPicker')) :
    /**
     * Class BlogfoelCatColorPicker
     *
     * Handles category color picker integration in WordPress admin.
     */
    class BlogfoelCatColorPicker {

        /**
         * Constructor
         */
        public function __construct() {
            // Initialize hooks
            $this->init();
        }

        /**
         * Initialize hooks
         */
        public function init() {
            // Add category color field
            add_action('category_add_form_fields', array($this, 'add_category_color_field'));
            add_action('category_edit_form_fields', array($this, 'edit_category_color_field'));

            // Enqueue assets
            add_action('admin_enqueue_scripts', array($this, 'enqueue_assets'));

            // Save color on create/edit
            add_action('created_term', array($this, 'save_category_color'));
            add_action('edited_term', array($this, 'save_edit_category_color'));
        }

        /**
         * Enqueue color picker scripts and styles
         */
        public function enqueue_assets() {
            wp_enqueue_style('wp-color-picker');
            wp_enqueue_script('wp-color-picker');

            // Inline script for initializing color picker in footer
            add_action('admin_footer', array($this, 'inline_color_picker_style'));
            add_action('admin_footer', array($this, 'inline_color_picker_script'));
        }

        /**
         * Add color field to "Add Category" form
         */
        public function add_category_color_field() {
            ?>
            <div class="form-field">
                <div class="category-field-title">
                    <label for="category_color"><?php esc_html_e('Background Color', 'blognews-for-elementor'); ?></label>
                    <img src="<?php echo esc_url( BLOGFOEL_DIR_URL .'/assets/images/siteicon.png'); ?>" alt="site-icon">
                </div>
                <input type="text" name="category_color" id="category_color" class="color-picker" value="" />
                <p class="description"><?php esc_html_e('Select a color for this category.', 'blognews-for-elementor'); ?></p>
                
                <div class="category-field-title">
                    <label for="category_text_color"><?php esc_html_e('Text Color', 'blognews-for-elementor'); ?></label>
                    <img src="<?php echo esc_url( BLOGFOEL_DIR_URL .'/assets/images/siteicon.png'); ?>" alt="site-icon">
                </div>
                <input type="text" name="category_text_color" id="category_text_color" class="color-picker" value="" />
                <p class="description"><?php esc_html_e('Select a color for this category.', 'blognews-for-elementor'); ?></p>
            </div>
            <?php
        }

        /**
         * Add color field to "Edit Category" form
         *
         * @param object $term Term object
         */
        public function edit_category_color_field($term) {
            $color = get_term_meta($term->term_id, 'category_color', true);
            $text_color = get_term_meta($term->term_id, 'category_text_color', true);
            ?>
            <tr class="form-field">
                <th scope="row">
                    <div class="category-field-title">
                        <label for="category_color"><?php esc_html_e('Background Color', 'blognews-for-elementor'); ?></label>
                        <img src="<?php echo esc_url( BLOGFOEL_DIR_URL .'/assets/images/siteicon.png'); ?>" alt="site-icon">
                    </div>
                </th>
                <td>
                    <input type="text" name="category_color" id="category_color" class="color-picker" value="<?php echo esc_attr($color); ?>" />
                    <p class="description"><?php esc_html_e('Select a color for this category.', 'blognews-for-elementor'); ?></p>
                </td>
            </tr>
            <tr class="form-field">
                <th scope="row">
                    <div class="category-field-title">
                        <label for="category_text_color"><?php esc_html_e('Text Color', 'blognews-for-elementor'); ?></label>
                        <img src="<?php echo esc_url( BLOGFOEL_DIR_URL .'/assets/images/siteicon.png'); ?>" alt="site-icon">
                    </div>
                </th>
                <td>
                    <input type="text" name="category_text_color" id="category_text_color" class="color-picker" value="<?php echo esc_attr($text_color); ?>" />
                    <p class="description"><?php esc_html_e('Select a color for this category.', 'blognews-for-elementor'); ?></p>
                </td>
            </tr>
            <?php
        }

        /**
         * CSS color picker
         */
        public function inline_color_picker_style() {
            ?>
            <style>
               .category-field-title {
                    display: flex;
                    gap: 8px;
                    margin-bottom: 8px;
                }
            </style>
            <?php
        }
        /**
         * Inline JavaScript to initialize color picker
         */
        public function inline_color_picker_script() {
            ?>
            <script>
                jQuery(document).ready(function ($) {
                    $('.color-picker').wpColorPicker();
                });
            </script>
            <?php
        }

        /**
         * Save color when category is created
         *
         * @param int $term_id Term ID
         */
        public function save_category_color($term_id) {
            if (isset($_POST['category_color'])) {
                update_term_meta($term_id, 'category_color', sanitize_hex_color($_POST['category_color']));
            }
            if (isset($_POST['category_text_color'])) {
                update_term_meta($term_id, 'category_text_color', sanitize_hex_color($_POST['category_text_color']));
            }
        }

        /**
         * Save color when category is edited
         *
         * @param int $term_id Term ID
         */
        public function save_edit_category_color($term_id) {
            if (isset($_POST['category_color'])) {
                update_term_meta($term_id, 'category_color', sanitize_hex_color($_POST['category_color']));
            }
            if (isset($_POST['category_text_color'])) {
                update_term_meta($term_id, 'category_text_color', sanitize_hex_color($_POST['category_text_color']));
            }
        }
    }
    // Instantiate the class
    $blogfoel_cat_color_picker = new BlogfoelCatColorPicker();

endif;