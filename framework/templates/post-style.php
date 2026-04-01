<?php
$post_id = get_the_ID();
$category = get_the_terms($post_id, 'category');
?>
<article <?php post_class('bt-post'); ?>>
  <div class="bt-post--inner">
    <?php echo freska_post_cover_featured_render($args['image-size']); ?>
    <div class="bt-post--content <?php echo !empty($args['read_more']) && $args['read_more'] === 'yes' ? 'bt-post--show-button' : ''; ?>">
      <?php
      echo freska_post_meta_render();
      echo freska_post_title_render();
      if (!empty($args['excerpt']) && $args['excerpt'] === 'yes') {
        echo freska_post_excerpt_render();
      }
      if (!empty($args['read_more']) && $args['read_more'] === 'yes') {
        $read_more_text = !empty($args['read_more_text']) ? $args['read_more_text'] : esc_html__('Read More', 'freska');
        echo freska_post_button_render($read_more_text);
      }
      ?>
    </div>
  </div>
</article>