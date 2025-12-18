<?php
/**
 * Template Part: Single Post Content
 *
 * @package SunnyTree
 */

declare(strict_types=1);
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('single-post'); ?>>
    <header class="single-post__header">
        <?php the_title('<h1 class="single-post__title">', '</h1>'); ?>

        <?php get_template_part('template-parts/components/post-meta'); ?>
    </header>

    <?php if (has_post_thumbnail()) : ?>
        <figure class="single-post__thumbnail">
            <?php the_post_thumbnail('large'); ?>
        </figure>
    <?php endif; ?>

    <div class="single-post__content">
        <?php the_content(); ?>
    </div>

    <footer class="single-post__footer">
        <?php
        $categories = get_the_category();
        if ($categories) :
        ?>
            <div class="single-post__categories">
                <span class="single-post__label"><?php esc_html_e('Categories:', 'sunnytree'); ?></span>
                <?php
                foreach ($categories as $category) {
                    printf(
                        '<a href="%s" class="single-post__term">%s</a>',
                        esc_url(get_category_link($category->term_id)),
                        esc_html($category->name)
                    );
                }
                ?>
            </div>
        <?php endif; ?>

        <?php
        $tags = get_the_tags();
        if ($tags) :
        ?>
            <div class="single-post__tags">
                <span class="single-post__label"><?php esc_html_e('Tags:', 'sunnytree'); ?></span>
                <?php
                foreach ($tags as $tag) {
                    printf(
                        '<a href="%s" class="single-post__term">%s</a>',
                        esc_url(get_tag_link($tag->term_id)),
                        esc_html($tag->name)
                    );
                }
                ?>
            </div>
        <?php endif; ?>
    </footer>
</article>
