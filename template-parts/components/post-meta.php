<?php
/**
 * Template Part: Post Meta
 *
 * @package SunnyTree
 */

declare(strict_types=1);
?>

<div class="post-meta">
    <time class="post-meta__date" datetime="<?php echo esc_attr(get_the_date('c')); ?>">
        <?php echo esc_html(get_the_date()); ?>
    </time>

    <span class="post-meta__separator">&middot;</span>

    <span class="post-meta__author">
        <?php
        printf(
            /* translators: %s: Author name */
            esc_html__('By %s', 'sunnytree'),
            '<a href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a>'
        );
        ?>
    </span>
</div>
