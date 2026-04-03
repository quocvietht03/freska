<?php

/**
 * Mega Menu - Custom Post Type & Menu Item Options
 * Create CPT for Mega Menu blocks and add menu item options
 */
// Check if Elementor is active before running any code
if (!defined('ELEMENTOR_VERSION') && !class_exists('\Elementor\Plugin')) {
    return;
}

// Register Custom Post Type
function freska_register_megamenu_post_type()
{
    $labels = array(
        'name'               => __('Mega Menu', 'freska'),
        'singular_name'      => __('Mega Menu', 'freska'),
        'menu_name'          => __('Mega Menu', 'freska'),
        'add_new'            => __('Add New', 'freska'),
        'add_new_item'       => __('Add New Mega Menu', 'freska'),
        'edit_item'          => __('Edit Mega Menu', 'freska'),
        'new_item'           => __('New Mega Menu', 'freska'),
        'view_item'          => __('View Mega Menu', 'freska'),
        'search_items'       => __('Search Mega Menu', 'freska'),
        'not_found'          => __('No Mega Menu found', 'freska'),
        'not_found_in_trash' => __('No Mega Menu found in Trash', 'freska'),
    );

    $args = array(
        'labels'              => $labels,
        'public'              => false,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => true,
        'query_var'           => true,
        'rewrite'             => array('slug' => 'megamenu'),
        'capability_type'     => 'post',
        'has_archive'         => false,
        'hierarchical'        => false,
        'menu_position'       => 57,
        'menu_icon'           => 'dashicons-grid-view',
        'supports'            => array('title', 'editor', 'thumbnail', 'elementor'),
        'show_in_rest'        => true,
        'exclude_from_search' => true,
        'show_in_nav_menus'   => false,
    );

    register_post_type('megamenu', $args);
}
add_action('init', 'freska_register_megamenu_post_type');

// Enable Elementor support for megamenu
function freska_add_elementor_support_to_megamenu($post_types)
{
    $post_types[] = 'megamenu';
    return $post_types;
}
add_filter('elementor/documents/register/post_types', 'freska_add_elementor_support_to_megamenu');

// Add Mega Menu options to menu item (available for all depths)
function freska_megamenu_nav_menu_item_custom_fields($item_id, $item, $depth, $args, $id = '')
{
    // Get icon menu data for all depths
    $icon_enabled = get_post_meta($item_id, '_freska_icon_enabled', true);
    $icon_svg_url = get_post_meta($item_id, '_freska_icon_svg_url', true);

    // Get label menu data for all depths
    $label_enabled = get_post_meta($item_id, '_freska_label_enabled', true);
    $label_text = get_post_meta($item_id, '_freska_label_text', true);
    $label_color = get_post_meta($item_id, '_freska_label_color', true);
    if (empty($label_color)) {
        $label_color = '#00706e';
    }

    // Mega menu options only for main menu items (depth 0)
    if ($depth === 0) {
        $megamenu_enabled = get_post_meta($item_id, '_freska_megamenu_enabled', true);
        $megamenu_id = get_post_meta($item_id, '_freska_megamenu_id', true);
        $megamenu_content_width = get_post_meta($item_id, '_freska_megamenu_content_width', true);
        if (empty($megamenu_content_width)) {
            $megamenu_content_width = 'full-width'; // Default value
        }
        $megamenu_horizontal_position = get_post_meta($item_id, '_freska_megamenu_horizontal_position', true);
        if (empty($megamenu_horizontal_position)) {
            $megamenu_horizontal_position = 'default'; // Default value
        }

        // Get all published megamenu blocks for dropdown
        $megamenus = get_posts(array(
            'post_type'      => 'megamenu',
            'posts_per_page' => -1,
            'post_status'    => 'publish',
            'orderby'        => 'title',
            'order'          => 'ASC',
        ));

        $has_selected_block = $megamenu_id && get_post_status($megamenu_id) !== false;
        $edit_block_url = $has_selected_block
            ? admin_url('post.php?post=' . intval($megamenu_id) . '&action=elementor')
            : '';
    }
?>
    <div class="freska-megamenu-fields description-wide">
        <!-- Icon Menu - Available for all depths -->
        <p class="field-icon-enable description">
            <label for="edit-icon-enable-<?php echo esc_attr($item_id); ?>">
                <input type="checkbox"
                    id="edit-icon-enable-<?php echo esc_attr($item_id); ?>"
                    name="menu-item[<?php echo esc_attr($item_id); ?>][_freska_icon_enabled]"
                    value="1"
                    <?php checked($icon_enabled, '1'); ?>
                    class="freska-icon-enable" />
                <?php esc_html_e('Enable Icon', 'freska'); ?>
            </label>
        </p>
        <div class="freska-icon-dependent-fields" style="<?php echo esc_attr($icon_enabled === '1' ? '' : 'display: none;'); ?>">
            <p class="field-icon-svg description description-wide">
                <label for="edit-icon-svg-<?php echo esc_attr($item_id); ?>">
                    <?php esc_html_e('SVG Icon', 'freska'); ?><br />
                    <input type="url"
                        id="edit-icon-svg-<?php echo esc_attr($item_id); ?>"
                        name="menu-item[<?php echo esc_attr($item_id); ?>][_freska_icon_svg_url]"
                        value="<?php echo esc_attr($icon_svg_url); ?>"
                        class="widefat freska-icon-svg-url"
                        placeholder="<?php esc_attr_e('SVG URL', 'freska'); ?>" />
                    <?php if (!empty($icon_svg_url)) : ?>
                        <div class="freska-icon-preview">
                            <img src="<?php echo esc_url($icon_svg_url); ?>" alt="Icon preview" />
                        </div>
                    <?php endif; ?>
                    <button type="button" class="button freska-upload-svg-button" data-item-id="<?php echo esc_attr($item_id); ?>">
                        <?php esc_html_e('Upload SVG', 'freska'); ?>
                    </button>
                    <button type="button" class="button freska-remove-svg-button" data-item-id="<?php echo esc_attr($item_id); ?>" style="<?php echo esc_attr(!empty($icon_svg_url) ? '' : 'display: none;'); ?>">
                        <?php esc_html_e('Remove Icon', 'freska'); ?>
                    </button>
                </label>
            </p>
        </div>
        
        <!-- Label Menu - Available for all depths -->
        <p class="field-label-enable description">
            <label for="edit-label-enable-<?php echo esc_attr($item_id); ?>">
                <input type="checkbox"
                    id="edit-label-enable-<?php echo esc_attr($item_id); ?>"
                    name="menu-item[<?php echo esc_attr($item_id); ?>][_freska_label_enabled]"
                    value="1"
                    <?php checked($label_enabled, '1'); ?>
                    class="freska-label-enable" />
                <?php esc_html_e('Enable Label', 'freska'); ?>
            </label>
        </p>
        <div class="freska-label-dependent-fields" style="<?php echo esc_attr($label_enabled === '1' ? '' : 'display: none;'); ?>">
            <p class="field-label-text description description-wide">
                <label for="edit-label-text-<?php echo esc_attr($item_id); ?>">
                    <?php esc_html_e('Label Text', 'freska'); ?><br />
                    <input type="text"
                        id="edit-label-text-<?php echo esc_attr($item_id); ?>"
                        name="menu-item[<?php echo esc_attr($item_id); ?>][_freska_label_text]"
                        value="<?php echo esc_attr($label_text); ?>"
                        class="widefat"
                        placeholder="<?php esc_attr_e('Enter label text (e.g., NEW, HOT)', 'freska'); ?>" />
                </label>
            </p>
            <p class="field-label-color description description-wide">
                <label for="edit-label-color-<?php echo esc_attr($item_id); ?>">
                    <?php esc_html_e('Label Color', 'freska'); ?><br />
                </label>
                <input type="text"
                        id="edit-label-color-<?php echo esc_attr($item_id); ?>"
                        name="menu-item[<?php echo esc_attr($item_id); ?>][_freska_label_color]"
                        value="<?php echo esc_attr($label_color); ?>"
                        class="freska-label-color wp-color-picker"
                        data-default-color="#00706e" />
            </p>
        </div>
        
        <?php if ($depth === 0) : ?>
        <!-- Mega Menu - Only for main menu items (depth 0) -->
        <p class="field-megamenu-enable description">
            <label for="edit-megamenu-enable-<?php echo esc_attr($item_id); ?>">
                <input type="checkbox"
                    id="edit-megamenu-enable-<?php echo esc_attr($item_id); ?>"
                    name="menu-item[<?php echo esc_attr($item_id); ?>][_freska_megamenu_enabled]"
                    value="1"
                    <?php checked($megamenu_enabled, '1'); ?>
                    class="freska-megamenu-enable" />
                <?php esc_html_e('Enable Mega Menu', 'freska'); ?>
            </label>
        </p>
        <div class="freska-megamenu-dependent-fields" style="<?php echo esc_attr($megamenu_enabled === '1' ? '' : 'display: none;'); ?>">
        <p class="field-megamenu description description-wide">
            <label for="edit-megamenu-<?php echo esc_attr($item_id); ?>">
                <?php esc_html_e('Select block', 'freska'); ?><br />
                <select id="edit-megamenu-<?php echo esc_attr($item_id); ?>"
                    name="menu-item[<?php echo esc_attr($item_id); ?>][_freska_megamenu_id]"
                    class="widefat freska-megamenu-select"
                    data-item-id="<?php echo esc_attr($item_id); ?>">
                    <option value=""><?php esc_html_e('— Select —', 'freska'); ?></option>
                    <?php foreach ($megamenus as $block) : ?>
                        <option value="<?php echo esc_attr($block->ID); ?>"
                            <?php selected($megamenu_id, $block->ID); ?>>
                            <?php echo esc_html($block->post_title); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </label>
        </p>
        <p class="field-megamenu-content-width description description-wide">
            <label for="edit-megamenu-content-width-<?php echo esc_attr($item_id); ?>">
                <?php esc_html_e('Content Width', 'freska'); ?><br />
                <select id="edit-megamenu-content-width-<?php echo esc_attr($item_id); ?>"
                    name="menu-item[<?php echo esc_attr($item_id); ?>][_freska_megamenu_content_width]"
                    class="widefat freska-megamenu-content-width-select"
                    data-item-id="<?php echo esc_attr($item_id); ?>">
                    <option value="full-width" <?php selected($megamenu_content_width, 'full-width'); ?>>
                        <?php esc_html_e('Full Width', 'freska'); ?>
                    </option>
                    <option value="fit-to-content" <?php selected($megamenu_content_width, 'fit-to-content'); ?>>
                        <?php esc_html_e('Fit to Content', 'freska'); ?>
                    </option>
                </select>
            </label>
        </p>
        <p class="field-megamenu-horizontal-position description description-wide freska-megamenu-horizontal-position-field"
            style="<?php echo esc_attr($megamenu_content_width === 'fit-to-content' ? '' : 'display: none;'); ?>">
            <label for="edit-megamenu-horizontal-position-<?php echo esc_attr($item_id); ?>">
                <?php esc_html_e('Content Horizontal Position', 'freska'); ?><br />
                <select id="edit-megamenu-horizontal-position-<?php echo esc_attr($item_id); ?>"
                    name="menu-item[<?php echo esc_attr($item_id); ?>][_freska_megamenu_horizontal_position]"
                    class="widefat freska-megamenu-horizontal-position-select">
                    <option value="default" <?php selected($megamenu_horizontal_position, 'default'); ?>>
                        <?php esc_html_e('Default', 'freska'); ?>
                    </option>
                    <option value="left" <?php selected($megamenu_horizontal_position, 'left'); ?>>
                        <?php esc_html_e('Left', 'freska'); ?>
                    </option>
                    <option value="center" <?php selected($megamenu_horizontal_position, 'center'); ?>>
                        <?php esc_html_e('Center', 'freska'); ?>
                    </option>
                    <option value="right" <?php selected($megamenu_horizontal_position, 'right'); ?>>
                        <?php esc_html_e('Right', 'freska'); ?>
                    </option>
                    <option value="center-to-item" <?php selected($megamenu_horizontal_position, 'center-to-item'); ?>>
                        <?php esc_html_e('Center to Menu Item', 'freska'); ?>
                    </option>
                </select>
            </label>
        </p>
        <p class="field-megamenu-links description">
            <a href="<?php echo esc_url($edit_block_url ?: '#'); ?>"
                class="freska-megamenu-edit-link"
                data-base-edit-url="<?php echo esc_attr(admin_url('post.php?post=%id%&action=elementor')); ?>"
                target="_blank"
                style="<?php echo esc_attr($has_selected_block ? '' : 'display: none;'); ?>">
                <?php esc_html_e('Edit megamenu', 'freska'); ?>
            </a>
            <span class="meta-sep" style="<?php echo esc_attr($has_selected_block ? '' : 'display: none;'); ?>"> | </span>
            <a href="#"
                class="freska-megamenu-add-link"
                data-item-id="<?php echo esc_attr($item_id); ?>">
                <?php esc_html_e('Add megamenu', 'freska'); ?>
            </a>
        </p>
        </div>
        <?php endif; ?>
    </div>
<?php
}
add_action('wp_nav_menu_item_custom_fields', 'freska_megamenu_nav_menu_item_custom_fields', 10, 5);

// Save mega menu and label options when menu is saved
function freska_megamenu_save_nav_menu_item($menu_id, $menu_item_db_id, $args)
{
    if (!isset($_POST['menu-item'][$menu_item_db_id])) {
        return;
    }

    $menu_item_data = $_POST['menu-item'][$menu_item_db_id];

    // Save icon menu options (available for all depths)
    $icon_enabled = isset($menu_item_data['_freska_icon_enabled']) && $menu_item_data['_freska_icon_enabled'] === '1' ? '1' : '0';
    update_post_meta($menu_item_db_id, '_freska_icon_enabled', $icon_enabled);

    $icon_svg_url = isset($menu_item_data['_freska_icon_svg_url']) ? esc_url_raw($menu_item_data['_freska_icon_svg_url']) : '';
    update_post_meta($menu_item_db_id, '_freska_icon_svg_url', $icon_svg_url);

    // Save label menu options (available for all depths)
    $label_enabled = isset($menu_item_data['_freska_label_enabled']) && $menu_item_data['_freska_label_enabled'] === '1' ? '1' : '0';
    update_post_meta($menu_item_db_id, '_freska_label_enabled', $label_enabled);

    $label_text = isset($menu_item_data['_freska_label_text']) ? sanitize_text_field($menu_item_data['_freska_label_text']) : '';
    update_post_meta($menu_item_db_id, '_freska_label_text', $label_text);

    $label_color = isset($menu_item_data['_freska_label_color']) ? sanitize_hex_color($menu_item_data['_freska_label_color']) : '';
    if (empty($label_color)) {
        $label_color = '#00706e';
    }
    update_post_meta($menu_item_db_id, '_freska_label_color', $label_color);

    // Save mega menu enabled
    $megamenu_enabled = isset($menu_item_data['_freska_megamenu_enabled']) && $menu_item_data['_freska_megamenu_enabled'] === '1' ? '1' : '0';
    update_post_meta($menu_item_db_id, '_freska_megamenu_enabled', $megamenu_enabled);

    // Save megamenu block ID
    $megamenu_id = isset($menu_item_data['_freska_megamenu_id']) ? absint($menu_item_data['_freska_megamenu_id']) : 0;
    if ($megamenu_id && get_post_type($megamenu_id) === 'megamenu') {
        update_post_meta($menu_item_db_id, '_freska_megamenu_id', $megamenu_id);
    } else {
        update_post_meta($menu_item_db_id, '_freska_megamenu_id', '');
    }

    // Save megamenu content width
    $megamenu_content_width = isset($menu_item_data['_freska_megamenu_content_width']) 
        ? sanitize_text_field($menu_item_data['_freska_megamenu_content_width']) 
        : 'full-width';
    if (in_array($megamenu_content_width, array('full-width', 'fit-to-content'), true)) {
        update_post_meta($menu_item_db_id, '_freska_megamenu_content_width', $megamenu_content_width);
    } else {
        update_post_meta($menu_item_db_id, '_freska_megamenu_content_width', 'full-width');
    }

    // Save megamenu horizontal position
    $megamenu_horizontal_position = isset($menu_item_data['_freska_megamenu_horizontal_position']) 
        ? sanitize_text_field($menu_item_data['_freska_megamenu_horizontal_position']) 
        : 'default';
    if (in_array($megamenu_horizontal_position, array('default', 'left', 'center', 'center-to-item', 'right'), true)) {
        update_post_meta($menu_item_db_id, '_freska_megamenu_horizontal_position', $megamenu_horizontal_position);
    } else {
        update_post_meta($menu_item_db_id, '_freska_megamenu_horizontal_position', 'default');
    }
}
add_action('wp_update_nav_menu_item', 'freska_megamenu_save_nav_menu_item', 10, 3);

// AJAX: Create Mega Menu 
function freska_ajax_create_megamenu()
{
    check_ajax_referer('freska-megamenu-nonce', 'nonce');

    if (!current_user_can('edit_theme_options')) {
        wp_send_json_error(array('message' => __('Permission denied', 'freska')));
        return;
    }

    // Get next sequential number for block title
    $existing_blocks = get_posts(array(
        'post_type'      => 'megamenu',
        'posts_per_page' => -1,
        'post_status'    => 'any',
        'fields'         => 'ids',
    ));
    $next_number = count($existing_blocks) + 1;

    // Create Mega Menu Post
    $block_post = array(
        'post_title'   => sprintf(__('Mega Menu %d', 'freska'), $next_number),
        'post_content' => '',
        'post_status'  => 'publish',
        'post_type'    => 'megamenu',
        'post_author'  => get_current_user_id(),
    );

    $block_id = wp_insert_post($block_post);

    if (is_wp_error($block_id)) {
        wp_send_json_error(array('message' => __('Failed to create Mega Menu', 'freska')));
        return;
    }

    // Enable Elementor for this post with full width template
    update_post_meta($block_id, '_elementor_edit_mode', 'builder');
    update_post_meta($block_id, '_elementor_template_type', 'wp-post');

    // Initialize empty Elementor data
    update_post_meta($block_id, '_elementor_data', '[]');
    
    if (defined('ELEMENTOR_VERSION')) {
        update_post_meta($block_id, '_elementor_version', ELEMENTOR_VERSION);
    }
    update_post_meta($block_id, '_elementor_css', '');

    $page_settings = array(
        'post_status' => 'publish',
        'template' => 'elementor_canvas',
        'hide_title' => 'yes',
    );
    update_post_meta($block_id, '_elementor_page_settings', $page_settings);
    update_post_meta($block_id, '_wp_page_template', 'elementor_canvas');

    $edit_link = admin_url('post.php?post=' . $block_id . '&action=elementor');
    $block_title = get_the_title($block_id);

    wp_send_json_success(array(
        'message' => __('Mega Menu created successfully!', 'freska'),
        'block_id' => $block_id,
        'block_title' => $block_title,
        'edit_link' => $edit_link,
    ));
}
add_action('wp_ajax_freska_create_megamenu', 'freska_ajax_create_megamenu');

// Ensure Elementor settings on publish (like extra_content_prod)
function freska_megamenu_ensure_elementor_settings_on_publish($new_status, $old_status, $post)
{
    if ($post->post_type !== 'megamenu') {
        return;
    }

    if ($new_status === 'publish' && $old_status !== 'publish') {
        $page_settings = get_post_meta($post->ID, '_elementor_page_settings', true);

        if (empty($page_settings) || !is_array($page_settings)) {
            $page_settings = array(
                'post_status' => 'publish',
                'template' => 'elementor_canvas',
                'hide_title' => 'yes',
            );
            update_post_meta($post->ID, '_elementor_page_settings', $page_settings);
        }

        $template = get_post_meta($post->ID, '_wp_page_template', true);
        if (empty($template)) {
            update_post_meta($post->ID, '_wp_page_template', 'elementor_canvas');
        }

        $edit_mode = get_post_meta($post->ID, '_elementor_edit_mode', true);
        if (empty($edit_mode)) {
            update_post_meta($post->ID, '_elementor_edit_mode', 'builder');
        }

        if (class_exists('\Elementor\Plugin')) {
            \Elementor\Plugin::$instance->files_manager->clear_cache();
        }
    }
}
add_action('transition_post_status', 'freska_megamenu_ensure_elementor_settings_on_publish', 10, 3);

/**
 * Helper: Get mega menu block ID for a menu item
 *
 * @param int $menu_item_id Menu item post ID
 * @return int|false Mega menu block ID or false
 */
function freska_get_megamenu_id($menu_item_id)
{
    $enabled = get_post_meta($menu_item_id, '_freska_megamenu_enabled', true);
    if ($enabled !== '1') {
        return false;
    }

    $block_id = get_post_meta($menu_item_id, '_freska_megamenu_id', true);
    if (!$block_id || get_post_status($block_id) !== 'publish' || get_post_type($block_id) !== 'megamenu') {
        return false;
    }

    return (int) $block_id;
}

/**
 * Helper: Display mega menu block content (for use in walker or template)
 *
 * @param int $block_id Mega menu block post ID
 * @return void
 */
function freska_display_megamenu($block_id)
{
    if (!$block_id || get_post_status($block_id) !== 'publish') {
        return;
    }

    if (class_exists('\Elementor\Plugin')) {
        echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($block_id);
    } else {
        $post = get_post($block_id);
        if ($post) {
            echo apply_filters('the_content', $post->post_content);
        }
    }
}

// Add icon and label to menu items in regular wp_nav_menu (not for widget megamenu)
function freska_add_label_to_nav_menu_items($title, $item, $args, $depth)
{
    // Skip if this is being called from widget megamenu
    if (isset($args->menu_class) && strpos($args->menu_class, 'bt-megamenu') !== false) {
        return $title;
    }
    
    // Skip if title already contains our elements (avoid duplication)
    if (strpos($title, 'bt-menu-') !== false) {
        return $title;
    }
    
    $icon_html = $label_html = '';
    
    // Get icon
    if (get_post_meta($item->ID, '_freska_icon_enabled', true) === '1') {
        $icon_svg_url = get_post_meta($item->ID, '_freska_icon_svg_url', true);
        if (!empty($icon_svg_url)) {
            $is_svg = false;
            
            // Check if the image is SVG
            if (pathinfo($icon_svg_url, PATHINFO_EXTENSION) === 'svg') {
                $is_svg = true;
            }
            
            if ($is_svg) {
                // Render SVG content directly
                $response = wp_safe_remote_get($icon_svg_url, array(
                    'timeout' => 20,
                    'headers' => array(
                        'User-Agent' => 'Mozilla/5.0 (compatible; WordPress)',
                    ),
                ));
                if (!is_wp_error($response)) {
                    $icon_html = '<span class="bt-menu-icon">' . wp_remote_retrieve_body($response) . '</span>';
                }
            } 
        }
    }

    // Get label
    if (get_post_meta($item->ID, '_freska_label_enabled', true) === '1') {
        $label_text = get_post_meta($item->ID, '_freska_label_text', true);
        if (!empty($label_text)) {
            $label_color = get_post_meta($item->ID, '_freska_label_color', true) ?: '#00706e';
            $label_html = '<span class="bt-menu-label" style="--background-color: ' . esc_attr($label_color) . ';">' . esc_html($label_text) . '</span>';
        }
    }

    // Combine if we have icon or label
    if ($icon_html || $label_html) {
        $title = '<span class="bt-menu-title">' . $icon_html . $title . $label_html . '</span>';
    }

    return $title;
}
add_filter('nav_menu_item_title', 'freska_add_label_to_nav_menu_items', 10, 4);