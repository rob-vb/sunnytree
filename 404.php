<?php
/**
 * 404 Error Template
 *
 * @package SunnyTree
 */

declare(strict_types=1);

get_header();
?>

<div class="content-area content-area--404">
    <div class="error-404">
        <h1 class="error-404__title"><?php esc_html_e('404', 'sunnytree'); ?></h1>

        <p class="error-404__message">
            <?php esc_html_e("The page you're looking for doesn't exist or has been moved.", 'sunnytree'); ?>
        </p>

        <div class="error-404__actions">
            <a href="<?php echo esc_url(home_url('/')); ?>" class="button">
                <?php esc_html_e('Go Home', 'sunnytree'); ?>
            </a>
        </div>

        <div class="error-404__search">
            <?php get_search_form(); ?>
        </div>
    </div>
</div>

<?php
get_footer();
