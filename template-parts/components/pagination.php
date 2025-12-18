<?php
/**
 * Template Part: Pagination
 *
 * @package SunnyTree
 */

declare(strict_types=1);

$pagination = paginate_links([
    'type'      => 'array',
    'prev_text' => __('&laquo; Previous', 'sunnytree'),
    'next_text' => __('Next &raquo;', 'sunnytree'),
]);

if (! $pagination) {
    return;
}
?>

<nav class="pagination" aria-label="<?php esc_attr_e('Posts navigation', 'sunnytree'); ?>">
    <ul class="pagination__list">
        <?php foreach ($pagination as $page_link) : ?>
            <li class="pagination__item">
                <?php echo $page_link; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
            </li>
        <?php endforeach; ?>
    </ul>
</nav>
