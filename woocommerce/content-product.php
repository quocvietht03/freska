<?php

/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.4.0
 */

defined('ABSPATH') || exit;

global $product, $is_ajax_filter_product;
if (empty($product) || ! $product->is_visible()) {
	return;
}
?>
<div <?php wc_product_class('woocommerce-loop-product', $product); ?>>
	<div class="woocommerce-loop-product__thumbnail">
		<?php
		do_action('freska_woocommerce_template_loop_product_link_open');
		do_action('freska_woocommerce_template_loop_product_thumbnail');
		do_action('freska_woocommerce_template_loop_product_link_close');
		echo '<div class="woocommerce-product-sale-label">';
		do_action('freska_woocommerce_show_product_loop_sale_flash');
		do_action('freska_woocommerce_shop_loop_item_label');
		echo '</div>';
		if (!$product->is_type('variable')) {
			echo wc_get_stock_html($product); // WPCS: XSS ok. 
		}
		echo '<div class="bt-add-to-cart">';
		if (!$product->is_type('variable')) {
			do_action('freska_woocommerce_template_loop_add_to_cart');
		} else {
			do_action('freska_woocommerce_template_loop_add_to_cart_variable');
		}
		echo '</div>';
		do_action('freska_woocommerce_template_loop_list_cta_button');
		do_action('freska_template_loop_product_countdown_and_sale');
		?>

	</div>

	<div class="woocommerce-loop-product__infor">
		<?php
		do_action('freska_woocommerce_template_loop_product_category');
		do_action('freska_woocommerce_template_loop_product_link_open');
		// Display product title (pass parent_id for variations in cross-sells, etc.)
		do_action('freska_woocommerce_template_loop_product_title', isset($parent_id) ? $parent_id : null);
		do_action('freska_woocommerce_template_loop_product_link_close');

		do_action('freska_woocommerce_template_loop_rating');
		// Display default attributes as "attribute_label : value" only for variable products (passed from product tooltip hotspot widget)
		do_action('freska_woocommerce_template_loop_product_default_attributes', isset($attributes_default) ? $attributes_default : null);

		// Display variation formatted for variation products (passed from cart/cross-sells.php, up-sells, etc.)
		do_action('freska_woocommerce_template_loop_product_variation', isset($parent_id) ? $parent_id : null);

		do_action('freska_woocommerce_template_loop_price');

		// Display stock bar if enable_process_stock is enabled
		if (!empty($enable_process_stock) && $enable_process_stock === 'yes') {
			do_action('freska_woocommerce_template_loop_process_stock');
		}

		do_action('freska_woocommerce_template_loop_product_short_description');
		echo '<div class="woocommerce-loop-product__actions">';
		if (!$product->is_type('variable')) {
			do_action('freska_woocommerce_template_loop_add_to_cart');
		} else {
			do_action('freska_woocommerce_template_loop_add_to_cart_variable');
		}
		do_action('freska_woocommerce_template_loop_list_cta_button');
		echo '</div>';
		?>

	</div>
</div>