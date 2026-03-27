<?php
/* Register Sidebar */
if (!function_exists('freska_register_sidebar')) {
	function freska_register_sidebar()
	{
		register_sidebar(array(
			'name' => esc_html__('Main Sidebar', 'freska'),
			'id' => 'main-sidebar',
			'before_widget' => '<div id="%1$s" class="widget %2$s">',
			'after_widget' => '</div>',
			'before_title' => '<h4 class="wg-title">',
			'after_title' => '</h4>',
		));
	}
	add_action('widgets_init', 'freska_register_sidebar');
}

/* Get icon SVG HTML */
function freska_get_icon_svg_html( $icon_file_name ) {
    if ( empty( $icon_file_name ) ) {
        return 'Error: Invalid file name or file name is missing.'; 
    }

    $icon_file_name = sanitize_file_name( $icon_file_name );
    $file_path = get_template_directory() . '/assets/images/' . $icon_file_name . '.svg';

    if ( ! file_exists( $file_path ) ) {
        return 'Error: File does not exist.';
    }

    $svg = file_get_contents( $file_path );

    if ( false === $svg ) {
        return 'Error: Unable to read file.';
    }

    return $svg;
}

/* Enqueue Script */
if (!function_exists('freska_enqueue_scripts')) {
	function freska_enqueue_scripts()
	{
		wp_enqueue_style('freska-fonts', get_template_directory_uri() . '/assets/css/fonts.css',  array(), false);

		if (is_singular('post') && comments_open() || is_singular('product')) {
			wp_enqueue_script('jquery-validate', get_template_directory_uri() . '/assets/libs/jquery-validate/jquery.validate.min.js', array('jquery'), '', true);
		}
		wp_enqueue_script('select2', get_template_directory_uri() . '/assets/libs/select2/select2.min.js', array('jquery'), '', true);
		wp_enqueue_style('select2', get_template_directory_uri() . '/assets/libs/select2/select2.min.css', array(), false);

		wp_enqueue_style('freska-main', get_template_directory_uri() . '/assets/css/main.css',  array(), false);
		wp_enqueue_style('freska-style', get_template_directory_uri() . '/style.css',  array(), false);
		wp_enqueue_script('freska-main', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), '', true);

		if (function_exists('get_field')) {
			$dev_mode = get_field('dev_mode', 'options');
			/* Load custom style */
			$custom_style = '';

			$custom_style = get_field('custom_css_code', 'options');
			if ($dev_mode && !empty($custom_style)) {
				wp_add_inline_style('freska-style', $custom_style);
			}

			/* Custom script */
			$custom_script = '';
			$custom_script = get_field('custom_js_code', 'options');
			if ($dev_mode && !empty($custom_script)) {
				wp_add_inline_script('freska-main', $custom_script);
			}
		}

		/* Options to script */
		$js_options = array(
			'ajax_url' => admin_url('admin-ajax.php'),
			'user_info' => wp_get_current_user(),
		);

		wp_localize_script('freska-main', 'AJ_Options', $js_options);

		wp_enqueue_script('freska-main');
	}
	add_action('wp_enqueue_scripts', 'freska_enqueue_scripts');
}
/* Add Stylesheet And Script Backend */
if (!function_exists('freska_enqueue_admin_scripts')) {
	function freska_enqueue_admin_scripts($hook)
	{
		wp_enqueue_style('freska-fonts', get_template_directory_uri() . '/assets/css/fonts.css',  array(), false);
		wp_enqueue_script('freska-admin-main', get_template_directory_uri() . '/assets/js/admin-main.js', array('jquery'), '', true);
		wp_enqueue_style('freska-admin-main', get_template_directory_uri() . '/assets/css/admin-main.css', array(), false);

	}
	add_action('admin_enqueue_scripts', 'freska_enqueue_admin_scripts');
}

/* Template functions */
require_once get_template_directory() . '/framework/template-helper.php';

/* Post Functions */
require_once get_template_directory() . '/framework/templates/post-helper.php';

if ( class_exists( 'ACF' ) ) {
	/* ACF Options */
	require_once get_template_directory() . '/framework/acf-options.php';

	/* Block Load */
	require_once get_template_directory() . '/framework/block-load.php';
}

if ( did_action( 'elementor/loaded' ) ) {
    /* Widgets Load */
	require_once get_template_directory() . '/framework/widget-load.php';
}
