<?php
$layout = !empty($args['layout']) ? $args['layout'] : 'layout-default';
?>
<article <?php post_class('bt-post'); ?>>
    <?php if ($layout != 'layout-02') : ?>
        <div class="bt-post--infor">
            <?php
            echo is_single() ? freska_single_post_title_render() : freska_post_title_render();
            echo freska_post_meta_category_render();
            ?>
        </div>
    <?php endif; ?>

    <?php
    if ($layout === 'layout-default') {
        echo freska_post_featured_render();
    }
    echo freska_post_content_render();
    ?>
</article>