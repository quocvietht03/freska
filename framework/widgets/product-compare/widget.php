<?php

namespace FreskaElementorWidgets\Widgets\ProductCompare;

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class Widget_ProductCompare extends Widget_Base
{

	public function get_name()
	{
		return 'bt-product-compare';
	}

	public function get_title()
	{
		return __('Product Compare', 'freska');
	}

	public function get_icon()
	{
		return 'bt-bears-icon eicon-sync';
	}

	public function get_categories()
	{
		return ['freska'];
	}

	protected function register_layout_section_controls()
	{
		$this->start_controls_section(
			'section_layout',
			[
				'label' => __('Layout', 'freska'),
			]
		);

		$this->end_controls_section();
	}


	protected function register_style_section_controls()
	{
		$this->start_controls_section(
			'section_style_content',
			[
				'label' => esc_html__('Content', 'freska'),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->end_controls_section();
	}

	protected function register_controls()
	{

		$this->register_layout_section_controls();
		$this->register_style_section_controls();
	}

	public function post_social_share()
	{

		$social_item = array();
		$social_item[] = '<li>
                        <a target="_blank" data-btIcon="fa fa-facebook" data-toggle="tooltip" title="' . esc_attr__('Facebook', 'freska') . '" href="https://www.facebook.com/sharer/sharer.php?u=' . get_the_permalink() . '">
                          <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 320 512">
                            <path d="M279.14 288l14.22-92.66h-88.91v-60.13c0-25.35 12.42-50.06 52.24-50.06h40.42V6.26S260.43 0 225.36 0c-73.22 0-121.08 44.38-121.08 124.72v70.62H22.89V288h81.39v224h100.17V288z"/>
                          </svg>
                        </a>
                      </li>';
		$social_item[] = '<li>
                        <a target="_blank" data-btIcon="fa fa-twitter" data-toggle="tooltip" title="' . esc_attr__('Twitter', 'freska') . '" href="https://twitter.com/share?url=' . get_the_permalink() . '">
                          <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 512 512">
                            <path d="M389.2 48h70.6L305.6 224.2 487 464H345L233.7 318.6 106.5 464H35.8L200.7 275.5 26.8 48H172.4L272.9 180.9 389.2 48zM364.4 421.8h39.1L151.1 88h-42L364.4 421.8z"/>
                          </svg>
                        </a>
                      </li>';
		$social_item[] = '<li>
                        <a target="_blank" data-btIcon="fa fa-google-plus" data-toggle="tooltip" title="' . esc_attr__('Google Plus', 'freska') . '" href="https://plus.google.com/share?url=' . get_the_permalink() . '">
                          <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 488 512">
                            <path d="M488 261.8C488 403.3 391.1 504 248 504 110.8 504 0 393.2 0 256S110.8 8 248 8c66.8 0 123 24.5 166.3 64.9l-67.5 64.9C258.5 52.6 94.3 116.6 94.3 256c0 86.5 69.1 156.6 153.7 156.6 98.2 0 135-70.4 140.8-106.9H248v-85.3h236.1c2.3 12.7 3.9 24.9 3.9 41.4z"/>
                          </svg>
                        </a>
                      </li>';
		$social_item[] = '<li>
                        <a target="_blank" data-btIcon="fa fa-linkedin" data-toggle="tooltip" title="' . esc_attr__('Linkedin', 'freska') . '" href="https://www.linkedin.com/shareArticle?url=' . get_the_permalink() . '">
                          <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 448 512">
                            <path d="M100.28 448H7.4V148.9h92.88zM53.79 108.1C24.09 108.1 0 83.5 0 53.8a53.79 53.79 0 0 1 107.58 0c0 29.7-24.1 54.3-53.79 54.3zM447.9 448h-92.68V302.4c0-34.7-.7-79.2-48.29-79.2-48.29 0-55.69 37.7-55.69 76.7V448h-92.78V148.9h89.08v40.8h1.3c12.4-23.5 42.69-48.3 87.88-48.3 94 0 111.28 61.9 111.28 142.3V448z"/>
                          </svg>
                        </a>
                      </li>';
		$social_item[] = '<li>
                        <a target="_blank" data-btIcon="fa fa-pinterest" data-toggle="tooltip" title="' . esc_attr__('Pinterest', 'freska') . '" href="https://pinterest.com/pin/create/button/?url=' . get_the_permalink() . '">
                          <svg xmlns="http://www.w3.org/2000/svg" height="1em" viewBox="0 0 496 512">
                            <path d="M496 256c0 137-111 248-248 248-25.6 0-50.2-3.9-73.4-11.1 10.1-16.5 25.2-43.5 30.8-65 3-11.6 15.4-59 15.4-59 8.1 15.4 31.7 28.5 56.8 28.5 74.8 0 128.7-68.8 128.7-154.3 0-81.9-66.9-143.2-152.9-143.2-107 0-163.9 71.8-163.9 150.1 0 36.4 19.4 81.7 50.3 96.1 4.7 2.2 7.2 1.2 8.3-3.3.8-3.4 5-20.3 6.9-28.1.6-2.5.3-4.7-1.7-7.1-10.1-12.5-18.3-35.3-18.3-56.6 0-54.7 41.4-107.6 112-107.6 60.9 0 103.6 41.5 103.6 100.9 0 67.1-33.9 113.6-78 113.6-24.3 0-42.6-20.1-36.7-44.8 7-29.5 20.5-61.3 20.5-82.6 0-19-10.2-34.9-31.4-34.9-24.9 0-44.9 25.7-44.9 60.2 0 22 7.4 36.8 7.4 36.8s-24.5 103.8-29 123.2c-5 21.4-3 51.6-.9 71.2C65.4 450.9 0 361.1 0 256 0 119 111 8 248 8s248 111 248 248z"/>
                          </svg>
                        </a>
                      </li>';

		ob_start();
?>
		<div class="bt-post-share">
			<?php
			if (!empty($social_item)) {
				echo '<span>' . esc_html__('Share: ', 'freska') . '</span><ul>' . implode(' ', $social_item) . '</ul>';
			}
			?>
		</div>
	<?php
		return ob_get_clean();
	}

	protected function render()
	{
		if (!class_exists('WooCommerce')) {
			return;
		}

		$settings = $this->get_settings_for_display();

		// Check if compare should be shown
		$archive_shop = function_exists('get_field') ? get_field('archive_shop', 'options') : array();
		$show_compare = isset($archive_shop['show_compare']) ? $archive_shop['show_compare'] : true;

		if (!$show_compare) {
			return;
		}

		$productcompare = '';
		if (isset($_GET['datashare']) && !empty($_GET['datashare'])) {
			$compare_data = sanitize_text_field($_GET['datashare']);
			$product_ids = explode(',', $compare_data);
			$product_ids = array_map('intval', $product_ids);
			$product_ids = array_filter($product_ids);
		} else {
			$product_ids = array();
		}
		$default_fields = array('short_desc', 'price', 'rating', 'brand', 'stock_status', 'sku', 'color');
		$compare_settings = get_field('compare', 'options');
		$fields_show_compare = !empty($compare_settings['fields_to_show_compare']) && is_array($compare_settings['fields_to_show_compare'])
			? $compare_settings['fields_to_show_compare']
			: $default_fields;

		$field_labels = array(
			'short_desc' => __('Short Description', 'freska'),
			'price' => __('Price', 'freska'),
			'rating' => __('Rating', 'freska'),
			'brand' => __('Brand', 'freska'),
			'stock_status' => __('Availability', 'freska'),
			'sku' => __('SKU', 'freska'),
			'color' => __('Color', 'freska'),
		);
		$valid_fields_show_compare = array();
		foreach ((array) $fields_show_compare as $field_key) {
			if (in_array($field_key, array('short_desc', 'price', 'rating', 'brand', 'stock_status', 'sku'), true)) {
				$valid_fields_show_compare[] = $field_key;
				continue;
			}
	
			if (taxonomy_exists($field_key)) {
				$valid_fields_show_compare[] = $field_key;
				continue;
			}
		}
		$fields_show_compare = $valid_fields_show_compare;
		foreach ((array) wc_get_attribute_taxonomies() as $attribute) {
			$taxonomy_name = wc_attribute_taxonomy_name($attribute->attribute_name);
			$field_labels[$taxonomy_name] = !empty($attribute->attribute_label) ? $attribute->attribute_label : $taxonomy_name;
		}
		$ex_items = count($product_ids) < 3 ? 3 - count($product_ids) : 0;
	?>
		<div class="bt-elwg-products-compare--default">
			<div class="bt-popup-compare bt-compare-elwwg">
				<div class="bt-compare-body woocommerce">
					<div class="bt-loading-wave"></div>
					<div class="bt-compare-load">
						<div class="bt-table-title">
							<h2><?php esc_html_e('Compare products', 'freska') ?></h2>
						</div>
						<div class="bt-table-compare">
							<div class="bt-table--head">
								<div class="bt-table--col"><?php esc_html_e('Thumbnail', 'freska'); ?></div>
								<div class="bt-table--col"><?php esc_html_e('Product Name', 'freska'); ?></div>
								<?php foreach ($fields_show_compare as $field_key) : ?>
									<div class="bt-table--col<?php echo $field_key === 'color' ? ' bt-head-color' : ''; ?>"><?php echo esc_html($field_labels[$field_key] ?? $field_key); ?></div>
								<?php endforeach; ?>
								<div class="bt-table--col"></div>
							</div>
							<div class="bt-table--body">
								<?php if (!empty($product_ids)) : ?>
									<?php foreach ($product_ids as $id) : ?>
										<?php $product = wc_get_product($id);
										if (!$product) {
											continue;
										} ?>
										<?php
										$product_url = get_permalink($id);
										$product_name = $product->get_name();
										$product_image = wp_get_attachment_image_src($product->get_image_id(), 'large');
										$product_image_url = $product_image ? $product_image[0] : wc_placeholder_img_src();
										$product_price = $product->get_price_html();
										$stock_status_custom = $product->is_on_backorder(1)
											? '<p class="stock on-backorder">' . esc_html__('On Backorder', 'freska') . '</p>'
											: ($product->is_in_stock()
												? '<p class="stock in-stock">' . esc_html__('In Stock', 'freska') . '</p>'
												: '<p class="stock out-of-stock">' . esc_html__('Out of Stock', 'freska') . '</p>');
										$brand_terms = wp_get_post_terms($id, 'product_brand', array('fields' => 'all'));
										$brand_links = array();
										foreach ($brand_terms as $brand_term) {
											$term_link = get_term_link($brand_term);
											if (!is_wp_error($term_link)) {
												$brand_links[] = '<a href="' . esc_url($term_link) . '">' . esc_html($brand_term->name) . '</a>';
											}
										}
										$brand_list = implode(', ', $brand_links);
										?>
										<div class="bt-table--row">
											<div class="bt-table--col bt-thumb">
												<div class="bt-remove-item" data-id="<?php echo esc_attr($id); ?>">
													<div class="bt-icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16" fill="none">
															<path d="M9.41183 8L15.6952 1.71665C15.7905 1.62455 15.8666 1.51437 15.9189 1.39255C15.9713 1.27074 15.9988 1.13972 16 1.00714C16.0011 0.874567 15.9759 0.743089 15.9256 0.620381C15.8754 0.497673 15.8013 0.386193 15.7076 0.292444C15.6138 0.198695 15.5023 0.124556 15.3796 0.0743523C15.2569 0.0241486 15.1254 -0.00111435 14.9929 3.76988e-05C14.8603 0.00118975 14.7293 0.0287337 14.6074 0.0810623C14.4856 0.133391 14.3755 0.209456 14.2833 0.30482L8 6.58817L1.71665 0.30482C1.52834 0.122941 1.27612 0.0223015 1.01433 0.0245764C0.752534 0.0268514 0.502106 0.131859 0.316983 0.316983C0.131859 0.502107 0.0268514 0.752534 0.0245764 1.01433C0.0223015 1.27612 0.122941 1.52834 0.30482 1.71665L6.58817 8L0.30482 14.2833C0.209456 14.3755 0.133391 14.4856 0.0810623 14.6074C0.0287337 14.7293 0.00118975 14.8603 3.76988e-05 14.9929C-0.00111435 15.1254 0.0241486 15.2569 0.0743523 15.3796C0.124556 15.5023 0.198695 15.6138 0.292444 15.7076C0.386193 15.8013 0.497673 15.8754 0.620381 15.9256C0.743089 15.9759 0.874567 16.0011 1.00714 16C1.13972 15.9988 1.27074 15.9713 1.39255 15.9189C1.51437 15.8666 1.62455 15.7905 1.71665 15.6952L8 9.41183L14.2833 15.6952C14.4226 15.8358 14.6006 15.9317 14.7945 15.9708C14.9885 16.0098 15.1898 15.9902 15.3726 15.9145C15.5554 15.8388 15.7115 15.7104 15.8211 15.5456C15.9306 15.3808 15.9886 15.1871 15.9877 14.9893C15.9878 14.8581 15.9619 14.7283 15.9117 14.6072C15.8615 14.4861 15.7879 14.376 15.6952 14.2833L9.41183 8Z" fill="#0C2C48" />
														</svg></div>
												</div>
												<a href="<?php echo esc_url($product_url); ?>"><img src="<?php echo esc_url($product_image_url); ?>" alt="<?php echo esc_attr($product_name); ?>"></a>
											</div>
											<div class="bt-table--col bt-name">
												<h3><a href="<?php echo esc_url($product_url); ?>"><?php echo esc_html($product_name); ?></a></h3>
											</div>
											<?php foreach ($fields_show_compare as $field_key) : ?>
												<?php if ($field_key === 'short_desc') : ?>
													<div class="bt-table--col bt-description">
														<p><?php echo esc_html(wp_trim_words(wp_strip_all_tags($product->get_short_description()), 20)); ?></p>
													</div>
												<?php elseif ($field_key === 'price') : ?>
													<div class="bt-table--col bt-price">
														<p><?php echo wp_kses_post($product_price); ?></p>
													</div>
												<?php elseif ($field_key === 'rating') : ?>
													<div class="bt-table--col bt-rating woocommerce">
														<div class="bt-product-rating"><?php echo wc_get_rating_html($product->get_average_rating()); ?><?php if ($product->get_rating_count()) : ?><div class="bt-product-rating--count">(<?php echo esc_html($product->get_rating_count()); ?>)</div><?php endif; ?></div>
													</div>
												<?php elseif ($field_key === 'brand') : ?>
													<div class="bt-table--col bt-brand">
														<p><?php echo wp_kses_post($brand_list); ?></p>
													</div>
												<?php elseif ($field_key === 'stock_status') : ?>
													<div class="bt-table--col bt-stock"><?php echo wp_kses_post($stock_status_custom); ?></div>
												<?php elseif ($field_key === 'sku') : ?>
													<div class="bt-table--col bt-sku">
														<p><?php echo esc_html($product->get_sku()); ?></p>
													</div>
												<?php elseif ($field_key === 'color') : ?>
													<div class="bt-table--col bt-color">
														<?php
														$color_taxonomy = freska_get_color_taxonomy();
														if ($color_taxonomy) {
															$colors = wp_get_post_terms($id, $color_taxonomy, array('fields' => 'ids'));
															$count = 0;
															foreach ($colors as $color_id) {
																if ($count >= 6) {
																	break;
																}
																$color_value = get_term_meta($color_id, 'freska_term_color', true);
																$color = get_term($color_id, $color_taxonomy);
																if ($color && !is_wp_error($color)) {
																	if (!$color_value) {
																		$color_value = $color->slug;
																	}
																	echo '<div class="bt-item-color"><span style="background-color: ' . esc_attr($color_value) . ';"></span>' . esc_html($color->name) . '</div>';
																	$count++;
																}
															}
														}
														?>
													</div>
												<?php elseif (taxonomy_exists($field_key)) : ?>
													<div class="bt-table--col bt-attribute bt-<?php echo esc_attr(sanitize_html_class(str_replace('pa_', '', $field_key))); ?>">
														<?php
														$attribute_terms = wp_get_post_terms($id, $field_key, array('fields' => 'all'));
														$attribute_values = array();
														foreach ($attribute_terms as $attribute_term) {
															if ($attribute_term && !is_wp_error($attribute_term)) {
																$attribute_values[] = $attribute_term->name;
															}
														}
														echo !empty($attribute_values) ? '<p>' . esc_html(implode(', ', array_unique($attribute_values))) . '</p>' : '';
														?>
													</div>
												<?php endif; ?>
											<?php endforeach; ?>
											<div class="bt-table--col bt-add-to-cart"><?php if ($product->is_type('simple')) : ?><a href="?add-to-cart=<?php echo esc_attr($id); ?>" class="bt-button product_type_simple add_to_cart_button ajax_add_to_cart" data-quantity="1" data-product_id="<?php echo esc_attr($id); ?>" rel="nofollow"><?php echo esc_html__('Add to Cart', 'freska'); ?></a><?php else : ?><a href="<?php echo esc_url(get_permalink($id)); ?>" class="bt-button"><?php echo esc_html__('View Product', 'freska'); ?></a><?php endif; ?></div>
										</div>
									<?php endforeach; ?>
								<?php endif; ?>
								<?php 	if ($ex_items > 0) {
									for ($i = 0; $i < $ex_items; $i++) { ?>
									<div class="bt-table--row bt-load-before bt-product-add-compare">
										<div class="bt-table--col bt-thumb">
											<div class="bt-cover-image"><svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" width="512" height="512" x="0" y="0" viewBox="0 0 512 512" fill="currentColor">
													<path d="M256 512a25 25 0 0 1-25-25V25a25 25 0 0 1 50 0v462a25 25 0 0 1-25 25z"></path>
													<path d="M487 281H25a25 25 0 0 1 0-50h462a25 25 0 0 1 0 50z"></path>
												</svg><span><?php echo esc_html__('Add Product To Compare', 'freska'); ?></span></div>
										</div>
										<div class="bt-table--col bt-name"></div>
										<?php foreach ($fields_show_compare as $field_key) : ?>
											<div class="bt-table--col bt-<?php echo esc_attr($field_key === 'short_desc' ? 'description' : $field_key); ?>"></div>
										<?php endforeach; ?>
										<div class="bt-table--col"></div>
									</div>
								<?php }
								} ?>
							</div>
						</div>
					</div>
					<?php echo '<div class="bt-compare-share bt-social-share">' . $this->post_social_share() . '</div>'; ?>
				</div>
			</div>
		</div>
<?php
	}

	protected function content_template() {}
}
