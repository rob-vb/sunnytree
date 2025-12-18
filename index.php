<?php
/**
 * Main Template (Blog Archive)
 *
 * @package SunnyTree
 */

declare(strict_types=1);

get_header();
?>

<div class="content-area">
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
