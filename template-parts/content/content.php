<?php
/**
 * Template Part: Post Excerpt (Archives)
 *
 * @package SunnyTree
 */

declare(strict_types=1);
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('post-card'); ?>>
    <header class="post-card__header">
        <?php the_title(sprintf('<h2 class="post-card__title"><a href="%s">', esc_url(get_permalink())), '</a></h2>'); ?>

        <?php get_template_part('template-parts/components/post-meta'); ?>
    </header>

    <div class="post-card__excerpt">
        <?php the_excerpt(); ?>
    </div>
</article>
