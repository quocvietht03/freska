<article <?php post_class('bt-post'); ?>>
	<div class="bt-post--infor">
		<?php
		echo freska_post_category_render();
		if (is_single()) {
			echo freska_single_post_title_render();
		} else {
			echo freska_post_title_render();
		}
		echo freska_post_meta_single_render();
		?>
	</div>
	<?php
		echo freska_post_featured_render();
		echo freska_post_content_render();
	?>
</article>