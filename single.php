<?php

/**
 * Single Post Template
 */

get_header();

// Get layout and banner settings
$layout = 'layout-default';
$banner = '';

if (function_exists('get_field')) {
	$banner = get_field('banner_post', get_the_ID()) ?: '';
	$layout = get_field('layout_post', get_the_ID()) ?: 'layout-default';
}

?>

<main id="bt_main" class="bt-site-main <?php echo esc_attr($layout); ?>">
	<?php if (did_action('elementor/loaded') && \Elementor\Plugin::$instance->preview->is_preview_mode()): ?>
		<?php while (have_posts()): the_post(); ?>
			<?php the_content(); ?>
		<?php endwhile; ?>
	<?php else: ?>

		<?php if ($layout == 'layout-01'): ?>
			<div class="bt-main-image-full">
				<?php if (!empty($banner)): ?>
					<div class="bt-post--featured">
						<div class="bt-cover-image">
							<?php echo wp_get_attachment_image($banner['id'], 'full'); ?>
						</div>
					</div>
				<?php else: ?>
					<?php echo freska_post_featured_render('full'); ?>
				<?php endif; ?>
			</div>

			<div class="bt-container-single">
				<?php while (have_posts()): the_post(); ?>
					<div class="bt-main-post">
						<?php get_template_part('framework/templates/post', null, array('layout' => $layout)); ?>
					</div>
					<div class="bt-main-actions">
						<?php
						echo freska_tags_render();
						echo freska_share_render();
						?>
					</div>
					<?php
					echo freska_author_render();
					freska_post_nav();
					if (comments_open() || get_comments_number()) comments_template();
					?>
				<?php endwhile; ?>
			</div>

		<?php elseif ($layout == 'layout-02'): ?>
			<!-- Layout 02: Image with overlay info -->
			<div class="bt-main-image-full">
				<?php if (!empty($banner)):
				?>
					<div class="bt-post--featured">
						<div class="bt-cover-image">
							<?php echo wp_get_attachment_image($banner['id'], 'full'); ?>
						</div>
					</div>
				<?php else: ?>
					<?php echo freska_post_featured_render('full'); ?>
				<?php endif; ?>

				<div class="bt-row-breadcrumb-single-post">
					<div class="bt-container">
						<div class="bt-breadcrumb">
							<?php
							$home_text = esc_html__('Homepage', 'freska');
							$delimiter = '<svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
    <g clip-path="url(#clip0_4145_9660)">
        <path d="M3.125 10H16.875" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
        <path d="M11.25 4.375L16.875 10L11.25 15.625" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"/>
    </g>
    <defs>
        <clipPath id="clip0_4145_9660">
        <rect width="20" height="20" fill="white"/>
        </clipPath>
    </defs>
</svg>';
							echo freska_page_breadcrumb($home_text, $delimiter);
							?>
						</div>
					</div>
				</div>

				<div class="bt-single-information">
					<div class="bt-container">
						<?php
						echo freska_post_meta_category_render();
						echo freska_single_post_title_render();
						?>
					</div>
				</div>
			</div>

			<div class="bt-main-content-ss bt-main-content-sidebar">
				<div class="bt-container">
					<div class="bt-main-post-row">
						<div class="bt-main-post-col">
							<?php while (have_posts()): the_post(); ?>
								<div class="bt-main-post bt-post-sidebar">
									<?php get_template_part('framework/templates/post', null, array('layout' => $layout)); ?>
								</div>
								<div class="bt-main-actions">
									<?php
									echo freska_tags_render();
									echo freska_share_render();
									?>
								</div>
								<?php
								freska_post_nav();
								if (comments_open() || get_comments_number()) comments_template();
								?>
							<?php endwhile; ?>
						</div>
						<div class="bt-sidebar-col">
							<div class="bt-sidebar">
								<?php if (is_active_sidebar('main-sidebar')) echo get_sidebar('main-sidebar'); ?>
							</div>
						</div>
					</div>
				</div>
			</div>

		<?php else: ?>
			<!-- Default layout: Breadcrumb + Sidebar -->
			<div class="bt-single-post-breadcrumb">
				<div class="bt-container">
					<div class="bt-row-breadcrumb-single-post">
						<div class="bt-breadcrumb">
							<?php
							$delimiter = '<svg xmlns="http://www.w3.org/2000/svg" width="12" height="12" viewBox="0 0 12 12" fill="none"><path d="M8.51552 6.26552L4.76552 10.0155C4.73068 10.0504 4.68932 10.078 4.64379 10.0969C4.59827 10.1157 4.54948 10.1254 4.50021 10.1254C4.45094 10.1254 4.40214 10.1157 4.35662 10.0969C4.3111 10.078 4.26974 10.0504 4.2349 10.0155C4.20005 9.98068 4.17242 9.93932 4.15356 9.89379C4.1347 9.84827 4.125 9.79948 4.125 9.75021C4.125 9.70094 4.1347 9.65214 4.15356 9.60662C4.17242 9.5611 4.20005 9.51974 4.2349 9.4849L7.72005 6.00021L4.2349 2.51552C4.16453 2.44516 4.125 2.34972 4.125 2.25021C4.125 2.1507 4.16453 2.05526 4.2349 1.9849C4.30526 1.91453 4.4007 1.875 4.50021 1.875C4.59972 1.875 4.69516 1.91453 4.76552 1.9849L8.51552 5.7349C8.55039 5.76972 8.57805 5.81108 8.59692 5.85661C8.61579 5.90213 8.6255 5.95093 8.6255 6.00021C8.6255 6.04949 8.61579 6.09829 8.59692 6.14381C8.57805 6.18933 8.55039 6.23069 8.51552 6.26552Z" fill="#A0A0A0"/></svg>';
							echo freska_page_breadcrumb('Home', $delimiter);
							?>
						</div>
					</div>
				</div>
			</div>

			<div class="bt-main-content-ss bt-main-content-sidebar">
				<div class="bt-container">
					<div class="bt-main-post-row">
						<div class="bt-main-post-col">
							<?php while (have_posts()): the_post(); ?>
								<div class="bt-main-post bt-post-sidebar">
									<?php get_template_part('framework/templates/post', null, array('layout' => $layout)); ?>
								</div>
								<div class="bt-main-actions">
									<?php
									echo freska_tags_render();
									echo freska_share_render();
									?>
								</div>
								<?php
								freska_post_nav();
								if (comments_open() || get_comments_number()) comments_template();
								?>
							<?php endwhile; ?>
						</div>
						<div class="bt-sidebar-col">
							<div class="bt-sidebar">
								<?php if (is_active_sidebar('main-sidebar')) echo get_sidebar('main-sidebar'); ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?php endif; ?>

		<?php echo freska_related_posts(); ?>
	<?php endif; ?>
</main>

<?php get_footer(); ?>