<?php
/**
 * Archive Template
 *
 * @package SunnyTree
 */

declare(strict_types=1);

get_header();
?>

<div class="content-area content-area--archive">
    <header class="archive-header">
        <?php the_archive_title('<h1 class="archive-header__title">', '</h1>'); ?>
        <?php the_archive_description('<div class="archive-header__description">', '</div>'); ?>
    </header>

    <?php if (have_posts()) : ?>

        <div class="posts-list">
            <?php
            while (have_posts()) :
                the_post();
                get_template_part('template-parts/content/content');
            endwhile;
            ?>
        </div>

        <?php get_template_part('template-parts/components/pagination'); ?>

    <?php else : ?>

        <?php get_template_part('template-parts/content/content', 'none'); ?>

    <?php endif; ?>
</div>

<?php
get_footer();
