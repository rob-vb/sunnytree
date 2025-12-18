<?php
/**
 * Template Part: No Results
 *
 * @package SunnyTree
 */

declare(strict_types=1);
?>

<section class="no-results">
    <header class="no-results__header">
        <h1 class="no-results__title"><?php esc_html_e('Nothing Found', 'sunnytree'); ?></h1>
    </header>

    <div class="no-results__content">
        <?php if (is_search()) : ?>
            <p><?php esc_html_e('Sorry, no results were found for your search. Please try again with different keywords.', 'sunnytree'); ?></p>
            <?php get_search_form(); ?>
        <?php else : ?>
            <p><?php esc_html_e('No posts found.', 'sunnytree'); ?></p>
        <?php endif; ?>
    </div>
</section>
