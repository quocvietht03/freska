<?php
/*
 * Single Post
 */

get_header();

?>

<main id="bt_main" class="bt-site-main">
	<div class="bt-single-post-breadcrumb">
		<div class="bt-container">
			<div class="bt-row-breadcrumb-single-post">
				<?php
				$home_text = 'Home';
				$delimiter = '/';
				echo '<div class="bt-breadcrumb">';
				echo freska_page_breadcrumb($home_text, $delimiter);
				echo '</div>';
				?>
			</div>
		</div>
	</div>
	<div class="bt-main-content-ss bt-main-content-sidebar">
		<div class="bt-container">
			<div class="bt-main-post-row">
				<div class="bt-main-post-col">
					<?php 
					while (have_posts()) : the_post();
					?>
						<div class="bt-main-post bt-post-sidebar">
							
							<?php get_template_part('framework/templates/post', null); ?>
						</div>
						<div class="bt-main-actions">
							<?php
							echo freska_tags_render();
							echo freska_share_render();
							?>
						</div>
					<?php
						freska_post_nav();

						// If comments are open or we have at least one comment, load up the comment template.
						if (comments_open() || get_comments_number()) comments_template();
					endwhile;

					?>
				</div>
				<div class="bt-sidebar-col">
					<div class="bt-sidebar">
						<?php if (is_active_sidebar('main-sidebar')) echo get_sidebar('main-sidebar'); ?>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php echo freska_related_posts(); ?>
</main><!-- #main -->

<?php get_footer(); ?>