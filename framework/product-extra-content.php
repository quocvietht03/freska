<?php

/**
 * Extra Content Product - Custom Post Type & Meta Box
 * Create CPT for Extra Content and buttons in Product admin
 */

// Register Custom Post Type
function freska_register_extra_content_post_type()
{
    $labels = array(
        'name'               => __('Product Extra Content', 'freska'),
        'singular_name'      => __('Product Extra Content', 'freska'),
        'menu_name'          => __('Product Extra Content', 'freska'),
        'add_new'            => __('Add New', 'freska'),
        'add_new_item'       => __('Add New Extra Content', 'freska'),
        'edit_item'          => __('Edit Extra Content', 'freska'),
        'new_item'           => __('New Extra Content', 'freska'),
        'view_item'          => __('View Extra Content', 'freska'),
        'search_items'       => __('Search Extra Content', 'freska'),
        'not_found'          => __('No Extra Content found', 'freska'),
        'not_found_in_trash' => __('No Extra Content found in Trash', 'freska'),
    );

    $args = array(
        'labels'              => $labels,
        'public'              => false,
        'publicly_queryable'  => true,
        'show_ui'             => true,
        'show_in_menu'        => false,
        'query_var'           => true,
        'rewrite'             => array('slug' => 'extra-content-product'),
        'capability_type'     => 'post',
        'has_archive'         => false,
        'hierarchical'        => false,
        'menu_position'       => 56,
        'menu_icon'           => 'dashicons-editor-kitchensink',
        'supports'            => array('title', 'editor', 'thumbnail', 'elementor'),
        'show_in_rest'        => true, // CRITICAL: Required for Elementor REST API
        'exclude_from_search' => true,
    );

    register_post_type('extra_content_prod', $args);
}
add_action('init', 'freska_register_extra_content_post_type');

// Enable Elementor support for this post type
function freska_add_elementor_support_to_extra_content($post_types)
{
    $post_types[] = 'extra_content_prod';
    return $post_types;
}
add_filter('elementor/documents/register/post_types', 'freska_add_elementor_support_to_extra_content');

// Add Meta Box to Product Edit Screen (below short description)
function freska_add_extra_content_meta_box()
{
    add_meta_box(
        'freska_extra_content_box',
        __('Extra Content Settings', 'freska'),
        'freska_extra_content_meta_box_callback',
        'product',
        'normal',
        'default'
    );
}
add_action('add_meta_boxes', 'freska_add_extra_content_meta_box');

// Move Extra Content meta box below excerpt
function freska_renrder_extra_content_meta_box() {
    global $wp_meta_boxes;

    if (!isset($wp_meta_boxes['product']['normal']['default']['freska_extra_content_box'])) {
        return;
    }

    $extra_content_box = $wp_meta_boxes['product']['normal']['default']['freska_extra_content_box'];
    unset($wp_meta_boxes['product']['normal']['default']['freska_extra_content_box']);

    $new_order = array();
    $inserted  = false;

    foreach ($wp_meta_boxes['product']['normal']['default'] as $key => $box) {
        $new_order[$key] = $box;

        if ($key === 'postexcerpt') {
            $new_order['freska_extra_content_box'] = $extra_content_box;
            $inserted = true;
        }
    }

    // If postexcerpt not found, add to end of list
    if (!$inserted) {
        $new_order['freska_extra_content_box'] = $extra_content_box;
    }

    $wp_meta_boxes['product']['normal']['default'] = $new_order;
}
add_action('add_meta_boxes', 'freska_renrder_extra_content_meta_box', 100);

/**
 * Get Elementor Section templates for select dropdown
 * @return array [ ID => title ]
 */
function freska_get_elementor_sections()
{
    if (!post_type_exists('elementor_library')) {
        return array();
    }

    $sections = get_posts(array(
        'post_type'      => 'elementor_library',
        'posts_per_page' => -1,
        'post_status'    => 'publish',
        'orderby'        => 'title',
        'order'          => 'ASC',
        'tax_query'      => array(
            array(
                'taxonomy' => 'elementor_library_type',
                'field'    => 'slug',
                'terms'    => 'section',
            ),
        ),
    ));

    $result = array('' => __('— Select Section —', 'freska'));
    foreach ($sections as $section) {
        $result[$section->ID] = $section->post_title;
    }
    return $result;
}

// Meta Box callback display
function freska_extra_content_meta_box_callback($post)
{
    wp_nonce_field('freska_extra_content_nonce', 'freska_extra_content_nonce');

    // Get linked Extra Content post ID
    $extra_content_id = get_post_meta($post->ID, '_extra_content_post_id', true);
    $global_extra_section_id = get_post_meta($post->ID, '_global_extra_content_section_id', true);
    $elementor_sections = freska_get_elementor_sections();

    // Mode: none | global | current
    $extra_mode = get_post_meta($post->ID, '_extra_content_mode', true);
    if (!in_array($extra_mode, array('none', 'global', 'current'), true)) {
        $extra_mode = (get_post_meta($post->ID, '_global_extra_content_enabled', true) === 'yes') ? 'global' : ($extra_content_id ? 'current' : 'none');
    }

?>
    <div class="freska-extra-content-box">
        <p class="freska-extra-mode-wrap">
            <select id="freska_extra_content_mode" name="freska_extra_content_mode" class="freska-extra-mode-select">
                <option value="none" <?php selected($extra_mode, 'none'); ?>><?php _e('- None -', 'freska'); ?></option>
                <option value="global" <?php selected($extra_mode, 'global'); ?>><?php _e('Use Global Content', 'freska'); ?></option>
                <option value="current" <?php selected($extra_mode, 'current'); ?>><?php _e('Use Product-Specific Content', 'freska'); ?></option>
            </select>
        </p>
        <div class="freska-global-extra-section-wrap" style="<?php echo esc_attr( $extra_mode === 'global' ? '' : 'display:none;' ); ?>">
            <div class="freska-global-extra-section-inner">
                <label for="freska_global_extra_content_section_id"><?php _e('Elementor Section', 'freska'); ?></label>
                <div class="freska-global-extra-section-row">
                    <select id="freska_global_extra_content_section_id" name="freska_global_extra_content_section_id">
                        <?php foreach ($elementor_sections as $sid => $title): ?>
                            <option value="<?php echo esc_attr($sid); ?>" <?php selected($global_extra_section_id, $sid); ?>>
                                <?php echo esc_html($title); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <?php
                    $edit_base = admin_url('post.php');
                    $create_section_url = admin_url('edit.php?post_type=elementor_library&elementor_library_type=section');
                    $default_sid = $global_extra_section_id ? (int) $global_extra_section_id : 0;
                    $default_edit_url = $default_sid ? add_query_arg(array('post' => $default_sid, 'action' => 'elementor'), $edit_base) : '#';
                    ?>
                    <a href="<?php echo esc_url($default_edit_url); ?>"
                        id="freska_global_edit_elementor"
                        class="button button-small freska-edit-elementor-link"
                        target="_blank"
                        data-base-url="<?php echo esc_attr($edit_base); ?>"
                        <?php if ( ! $default_sid ) : ?> style="display:none;"<?php endif; ?>>
                        <span class="dashicons dashicons-edit"></span>
                        <?php _e('Edit in Elementor', 'freska'); ?>
                    </a>
                    <a href="<?php echo esc_url($create_section_url); ?>"
                        id="freska_global_create_elementor"
                        class="button button-small freska-create-elementor-link"
                        target="_blank"
                        <?php if ( $default_sid ) : ?> style="display:none;"<?php endif; ?>>
                        <span class="dashicons dashicons-plus-alt"></span>
                        <?php _e('Create Elementor Section', 'freska'); ?>
                    </a>
                </div>
                <p class="freska-elementor-section-note">
                    <?php
                    printf(
                        __('To create a section: go to %s, click "Add New Section", design your content, then save. Refresh this page to see the new section in the dropdown.', 'freska'),
                        '<strong>Templates &rarr; Saved Templates &rarr; Section</strong>'
                    );
                    ?>
                </p>
            </div>
        </div>
        <div class="freska-extra-status-row" style="<?php echo esc_attr( $extra_mode === 'current' ? '' : 'display:none;' ); ?>">
        <?php if ($extra_content_id && get_post_status($extra_content_id) !== false):
            $edit_link = admin_url('post.php?post=' . $extra_content_id . '&action=elementor');
        ?>
            <p class="freska-extra-status">
                <span class="dashicons dashicons-yes-alt"></span>
                <?php _e('Extra Content Created', 'freska'); ?>
            </p>

            <p>
                <a href="<?php echo esc_url($edit_link); ?>"
                    class="button button-primary button-large freska-edit-extra-content"
                    target="_blank">
                    <span class="dashicons dashicons-edit"></span>
                    <?php _e('Edit Extra Content', 'freska'); ?>
                </a>
            </p>

            <p>
                <button type="button"
                    class="button button-link-delete freska-delete-extra-content"
                    data-product-id="<?php echo esc_attr($post->ID); ?>"
                    data-extra-id="<?php echo esc_attr($extra_content_id); ?>">
                    <span class="dashicons dashicons-trash"></span>
                    <?php _e('Delete Extra Content', 'freska'); ?>
                </button>
            </p>

        <?php else: ?>
            <p class="freska-extra-status">
                <span class="dashicons dashicons-info"></span>
                <?php _e('No Extra Content Yet', 'freska'); ?>
            </p>

            <p>
                <button type="button"
                    class="button button-primary button-large freska-create-extra-content"
                    data-product-id="<?php echo esc_attr($post->ID); ?>">
                    <span class="dashicons dashicons-plus-alt"></span>
                    <?php _e('Create Extra Content', 'freska'); ?>
                </button>
            </p>
        <?php endif; ?>
        </div>

        <input type="hidden"
            id="freska_extra_content_post_id"
            name="freska_extra_content_post_id"
            value="<?php echo esc_attr($extra_content_id); ?>" />

        <div class="freska-extra-loading" style="display:none;">
            <span class="spinner is-active"></span>
            <p><?php _e('Processing...', 'freska'); ?></p>
        </div>

        <p class="freska-extra-hook-note">
            <span class="dashicons dashicons-info"></span>
            <?php
            printf(
                __('Extra Content is added via hook %s or %s (depending on the layout type you select in the Advanced tab)', 'freska'),
                '<code>woocommerce_after_single_product_summary</code>',
                '<code>freska_woocommerce_single_product_after_summary</code>'
            );
            ?>
        </p>
    </div>
    <?php
}

// Save meta when saving product
function freska_save_extra_content_meta($post_id)
{
    // Check nonce
    if (
        !isset($_POST['freska_extra_content_nonce']) ||
        !wp_verify_nonce($_POST['freska_extra_content_nonce'], 'freska_extra_content_nonce')
    ) {
        return;
    }

    // Check autosave
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
        return;
    }

    // Check permissions
    if (!current_user_can('edit_post', $post_id)) {
        return;
    }

    // Save extra content post ID if exists
    if (isset($_POST['freska_extra_content_post_id'])) {
        $extra_id = absint($_POST['freska_extra_content_post_id']);
        update_post_meta($post_id, '_extra_content_post_id', $extra_id);

        // Ensure one-to-one relation between Product and Extra Content.
        // If this Extra Content is already linked to another Product, do not
        // allow the duplicated Product to reuse it.
        if ($extra_id) {
            $existing_parent = absint(get_post_meta($extra_id, '_parent_product_id', true));

            if ($existing_parent && $existing_parent !== $post_id) {
                // This Extra Content belongs to a different product (likely original one).
                // Detach it from the current product so duplicates don't inherit it.
                delete_post_meta($post_id, '_extra_content_post_id');

                $mode = get_post_meta($post_id, '_extra_content_mode', true);
                if ($mode === 'current') {
                    update_post_meta($post_id, '_extra_content_mode', 'none');
                }
            } else {
                // If not yet assigned, or assigned to this product, make sure
                // the back-reference is up to date.
                update_post_meta($extra_id, '_parent_product_id', $post_id);
            }
        }
    }

    // Save Extra Content mode
    if (isset($_POST['freska_extra_content_mode']) && in_array($_POST['freska_extra_content_mode'], array('none', 'global', 'current'), true)) {
        update_post_meta($post_id, '_extra_content_mode', $_POST['freska_extra_content_mode']);
    }

    if (isset($_POST['freska_global_extra_content_section_id'])) {
        $section_id = absint($_POST['freska_global_extra_content_section_id']);
        update_post_meta($post_id, '_global_extra_content_section_id', $section_id);
    }
}
add_action('save_post_product', 'freska_save_extra_content_meta');

/**
 * Ensure duplicated products do not inherit Extra Content links.
 *
 * When WooCommerce duplicates a product, it copies all post meta by default.
 * This hook clears Extra Content related meta on the new product immediately,
 * so previews of the draft do not show the original product's Extra Content.
 *
 * @param WC_Product $duplicate New (duplicated) product object.
 * @param WC_Product $product   Original product object.
 */
function freska_reset_extra_content_on_duplicate($duplicate, $product)
{
    if (!$duplicate instanceof WC_Product) {
        return;
    }

    $duplicate_id = $duplicate->get_id();

    // Remove any extra content linkage on the duplicated product.
    delete_post_meta($duplicate_id, '_extra_content_post_id');
}
add_action('woocommerce_product_duplicate', 'freska_reset_extra_content_on_duplicate', 10, 2);

// AJAX: Create Extra Content Post
function freska_ajax_create_extra_content()
{
    check_ajax_referer('freska-extra-content-nonce', 'nonce');

    if (!current_user_can('edit_products')) {
        wp_send_json_error(array('message' => __('Permission denied', 'freska')));
        return;
    }

    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;

    if (!$product_id || get_post_type($product_id) !== 'product') {
        wp_send_json_error(array('message' => __('Invalid Product ID', 'freska')));
        return;
    }

    // Check if Extra Content already exists
    $existing_extra = get_post_meta($product_id, '_extra_content_post_id', true);
    if ($existing_extra && get_post_status($existing_extra) !== false) {
        wp_send_json_error(array('message' => __('Extra Content already exists for this product', 'freska')));
        return;
    }

    $product = wc_get_product($product_id);
    $product_title = $product ? $product->get_name() : 'Product #' . $product_id;

    // Create Extra Content Post
    $extra_post = array(
        'post_title'   => sprintf(__('Extra Content - %s', 'freska'), $product_title),
        'post_content' => '',
        'post_status'  => 'publish',
        'post_type'    => 'extra_content_prod',
        'post_author'  => get_current_user_id(),
    );

    $extra_content_id = wp_insert_post($extra_post);

    if (is_wp_error($extra_content_id)) {
        wp_send_json_error(array('message' => __('Failed to create Extra Content', 'freska')));
        return;
    }

    // Save relationship between Product and Extra Content
    update_post_meta($product_id, '_extra_content_post_id', $extra_content_id);
    update_post_meta($extra_content_id, '_parent_product_id', $product_id);

    // Enable Elementor for this post with full width template
    update_post_meta($extra_content_id, '_elementor_edit_mode', 'builder');
    update_post_meta($extra_content_id, '_elementor_template_type', 'wp-post');

    // Initialize empty Elementor data FIRST
    update_post_meta($extra_content_id, '_elementor_data', '[]');
    
    // Check if Elementor constant exists
    if (defined('ELEMENTOR_VERSION')) {
        update_post_meta($extra_content_id, '_elementor_version', ELEMENTOR_VERSION);
    }
    update_post_meta($extra_content_id, '_elementor_css', '');

    // IMPORTANT: _elementor_page_settings must be an array, not JSON string
    $page_settings = array(
        'post_status' => 'publish',
        'template' => 'elementor_canvas', // Full width template
        'hide_title' => 'yes',
    );
    update_post_meta($extra_content_id, '_elementor_page_settings', $page_settings);

    // Ensure WordPress template is set
    update_post_meta($extra_content_id, '_wp_page_template', 'elementor_canvas');

    $edit_link = admin_url('post.php?post=' . $extra_content_id . '&action=elementor');

    wp_send_json_success(array(
        'message' => __('Extra Content created successfully!', 'freska'),
        'extra_content_id' => $extra_content_id,
        'edit_link' => $edit_link,
    ));
}
add_action('wp_ajax_freska_create_extra_content', 'freska_ajax_create_extra_content');

// AJAX: Delete Extra Content Post
function freska_ajax_delete_extra_content()
{
    check_ajax_referer('freska-extra-content-nonce', 'nonce');

    if (!current_user_can('delete_products')) {
        wp_send_json_error(array('message' => __('Permission denied', 'freska')));
        return;
    }

    $product_id = isset($_POST['product_id']) ? intval($_POST['product_id']) : 0;
    $extra_id = isset($_POST['extra_id']) ? intval($_POST['extra_id']) : 0;

    if (!$product_id || !$extra_id) {
        wp_send_json_error(array('message' => __('Invalid ID', 'freska')));
        return;
    }

    // Verify Extra Content belongs to Product
    $linked_product = get_post_meta($extra_id, '_parent_product_id', true);
    if ($linked_product != $product_id) {
        wp_send_json_error(array('message' => __('Extra Content does not belong to this Product', 'freska')));
        return;
    }

    // Verify the extra content post exists
    if (get_post_type($extra_id) !== 'extra_content_prod') {
        wp_send_json_error(array('message' => __('Invalid Extra Content', 'freska')));
        return;
    }

    // Delete Extra Content Post permanently
    $deleted = wp_delete_post($extra_id, true);

    if (!$deleted) {
        wp_send_json_error(array('message' => __('Failed to delete Extra Content', 'freska')));
        return;
    }

    // Remove meta from Product
    delete_post_meta($product_id, '_extra_content_post_id');

    wp_send_json_success(array(
        'message' => __('Extra Content deleted successfully!', 'freska'),
    ));
}
add_action('wp_ajax_freska_delete_extra_content', 'freska_ajax_delete_extra_content');

// Add column in Product list table
function freska_add_extra_content_column($columns)
{
    $new_columns = array();
    foreach ($columns as $key => $value) {
        $new_columns[$key] = $value;
        if ($key === 'product_tag') {
            $new_columns['extra_content'] = __('Extra Content', 'freska');
        }
    }
    return $new_columns;
}
add_filter('manage_product_posts_columns', 'freska_add_extra_content_column');

// Display column content
function freska_extra_content_column_content($column, $post_id)
{
    if ($column === 'extra_content') {
        $extra_content_id = get_post_meta($post_id, '_extra_content_post_id', true);
        if ($extra_content_id && get_post_status($extra_content_id) !== false) {
            $edit_link = admin_url('post.php?post=' . intval($extra_content_id) . '&action=elementor');
            echo '<a href="' . esc_url($edit_link) . '" target="_blank" class="button button-small">';
            echo '<span class="dashicons dashicons-yes-alt" style="color: green;"></span> ';
            echo esc_html__('Edit', 'freska');
            echo '</a>';
        } else {
            echo '<span class="dashicons dashicons-minus" style="color: #ccc;"></span>';
        }
    }
}
add_action('manage_product_posts_custom_column', 'freska_extra_content_column_content', 10, 2);

/**
 * Helper function: Display Extra Content in single product
 * Use in template to simplify code
 * Priority: Global Extra Content (if enabled) > Product-specific Extra Content
 *
 * @param int $product_id Product ID (optional, defaults to current post ID)
 * @return void
 */
function freska_display_product_extra_content($product_id = null)
{
    if (!$product_id) {
        $product_id = get_the_ID();
    }

    $extra_content_id = get_post_meta($product_id, '_extra_content_post_id', true);
    $extra_mode = get_post_meta($product_id, '_extra_content_mode', true);
    if (!in_array($extra_mode, array('global', 'current'), true)) {
        $extra_mode = (get_post_meta($product_id, '_global_extra_content_enabled', true) === 'yes') ? 'global' : ($extra_content_id ? 'current' : 'none');
    }

    // Global Extra Content: Elementor section
    $global_section_id = absint(get_post_meta($product_id, '_global_extra_content_section_id', true));
    if ($extra_mode === 'global' && $global_section_id && get_post_status($global_section_id) === 'publish') {
        if (class_exists('\Elementor\Plugin')) {
            echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($global_section_id);
        } else {
            echo do_shortcode('[elementor-template id="' . $global_section_id . '"]');
        }
        return;
    }

    // Product-specific Extra Content
    if ($extra_mode !== 'current') {
        return;
    }
    if (!$extra_content_id || get_post_status($extra_content_id) !== 'publish') {
        return;
    }

    if (class_exists('\Elementor\Plugin')) {
        echo \Elementor\Plugin::instance()->frontend->get_builder_content_for_display($extra_content_id);
    } else {
        $extra_post = get_post($extra_content_id);
        if ($extra_post) {
            echo apply_filters('the_content', $extra_post->post_content);
        }
    }
}

/**
 * Output Extra Content before Related Products via hook
 * Hooked into woocommerce_after_single_product_summary (priority 18)
 * and freska_woocommerce_single_product_after_summary (priority 18)
 */
function freska_output_product_extra_content()
{
    if (!is_product()) {
        return;
    }

    $product_id = get_the_ID();
    $extra_mode = get_post_meta($product_id, '_extra_content_mode', true);
    if (!in_array($extra_mode, array('global', 'current'), true)) {
        $extra_mode = (get_post_meta($product_id, '_global_extra_content_enabled', true) === 'yes') ? 'global' : 'none';
    }
    $global_section_id = absint(get_post_meta($product_id, '_global_extra_content_section_id', true));
    $extra_content_id = get_post_meta($product_id, '_extra_content_post_id', true);

    $has_global = $extra_mode === 'global' && $global_section_id && get_post_status($global_section_id) === 'publish';
    $has_product = $extra_mode === 'current' && $extra_content_id && get_post_status($extra_content_id) === 'publish';

    if (!$has_global && !$has_product) {
        return;
    }

    echo '<div class="bt-product-extra-content">';
    freska_display_product_extra_content();
    echo '</div>';
}
add_action('woocommerce_after_single_product_summary', 'freska_output_product_extra_content', 18);
add_action('freska_woocommerce_single_product_after_summary', 'freska_output_product_extra_content', 18);

// Display notice in Extra Content editor about linked product
function freska_extra_content_admin_notice()
{
    $screen = get_current_screen();

    if ($screen && $screen->post_type === 'extra_content_prod' && $screen->base === 'post') {
        global $post;
        if ($post) {
            $parent_product_id = get_post_meta($post->ID, '_parent_product_id', true);
            if ($parent_product_id && get_post_status($parent_product_id) !== false) {
                $product = wc_get_product($parent_product_id);
                $product_title = $product ? $product->get_name() : 'Product #' . $parent_product_id;
                $product_edit_link = admin_url('post.php?post=' . intval($parent_product_id) . '&action=edit');
                $product_view_link = get_permalink($parent_product_id);

    ?>
                <div class="notice notice-info extra_content_prod-info-notice">
                    <p><strong><?php esc_html_e('📦 Linked to Product:', 'freska'); ?></strong></p>
                    <p>
                        <?php esc_html_e('This Extra Content will be displayed in:', 'freska'); ?>
                        <a href="<?php echo esc_url($product_edit_link); ?>" target="_blank">
                            <strong><?php echo esc_html($product_title); ?></strong>
                        </a>
                    </p>
                    <p>
                        <a href="<?php echo esc_url($product_view_link); ?>" class="button button-small" target="_blank">
                            <?php esc_html_e('View Product', 'freska'); ?>
                        </a>
                        <a href="<?php echo esc_url($product_edit_link); ?>" class="button button-small" target="_blank">
                            <?php esc_html_e('Edit Product', 'freska'); ?>
                        </a>
                    </p>
                </div>
            <?php
            }
        }
    }
}
add_action('admin_notices', 'freska_extra_content_admin_notice');

// Hide Extra Content from main menu (optional - uncomment to hide)
function freska_hide_extra_content_from_menu()
{
    // Uncomment the line below to hide from menu
    // remove_menu_page('edit.php?post_type=extra_content_prod');
}
add_action('admin_menu', 'freska_hide_extra_content_from_menu', 999);

// Ensure Elementor settings are preserved when publishing
function freska_ensure_elementor_settings_on_publish($new_status, $old_status, $post)
{
    // Only for extra_content_prod post type
    if ($post->post_type !== 'extra_content_prod') {
        return;
    }

    // When transitioning to publish
    if ($new_status === 'publish' && $old_status !== 'publish') {
        // Ensure page settings is an array
        $page_settings = get_post_meta($post->ID, '_elementor_page_settings', true);

        if (empty($page_settings) || !is_array($page_settings)) {
            $page_settings = array(
                'post_status' => 'publish',
                'template' => 'elementor_canvas',
                'hide_title' => 'yes',
            );
            update_post_meta($post->ID, '_elementor_page_settings', $page_settings);
        }

        // Ensure template is set
        $template = get_post_meta($post->ID, '_wp_page_template', true);
        if (empty($template)) {
            update_post_meta($post->ID, '_wp_page_template', 'elementor_canvas');
        }

        // Ensure edit mode is builder
        $edit_mode = get_post_meta($post->ID, '_elementor_edit_mode', true);
        if (empty($edit_mode)) {
            update_post_meta($post->ID, '_elementor_edit_mode', 'builder');
        }

        // Clear Elementor cache
        if (class_exists('\Elementor\Plugin')) {
            \Elementor\Plugin::$instance->files_manager->clear_cache();
        }
    }
}
add_action('transition_post_status', 'freska_ensure_elementor_settings_on_publish', 10, 3);

// Admin action to flush rewrite rules manually
function freska_extra_content_flush_rules_action()
{
    if (isset($_GET['freska_flush_extra_content']) && current_user_can('manage_options')) {
        check_admin_referer('freska_flush_extra_content');

        flush_rewrite_rules();

        wp_redirect(admin_url('edit.php?post_type=extra_content_prod&flushed=1'));
        exit;
    }
}
add_action('admin_init', 'freska_extra_content_flush_rules_action');

// Show admin notice with flush button if needed
function freska_extra_content_flush_notice()
{
    $screen = get_current_screen();

    if ($screen && $screen->post_type === 'extra_content_prod') {
        if (isset($_GET['flushed']) && $_GET['flushed'] === '1') {
            ?>
            <div class="notice notice-success is-dismissible">
                <p><strong><?php esc_html_e('Rewrite rules flushed successfully!', 'freska'); ?></strong></p>
            </div>
        <?php
        } else {
            $flush_url = wp_nonce_url(
                admin_url('edit.php?post_type=extra_content_prod&freska_flush_extra_content=1'),
                'freska_flush_extra_content'
            );
        ?>
            <div class="notice notice-info">
                <p>
                    <?php esc_html_e('If you experience issues with Extra Content posts:', 'freska'); ?>
                    <a href="<?php echo esc_url($flush_url); ?>" class="button button-small" style="margin-left: 10px;">
                        <?php esc_html_e('Flush Rewrite Rules', 'freska'); ?>
                    </a>
                </p>
            </div>
<?php
        }
    }
}
add_action('admin_notices', 'freska_extra_content_flush_notice');