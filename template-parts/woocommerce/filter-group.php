<?php
/**
 * Filter Group Template
 *
 * Displays a collapsible group of checkbox filters.
 *
 * @package SunnyTree
 *
 * @var array $args {
 *     @type string $title               Filter group title
 *     @type string $subtitle            Optional subtitle/description
 *     @type string $name                Form field name
 *     @type array  $items               Filter items with id, name, slug, count
 *     @type int    $show_more_threshold Number of items to show before "More..." button
 * }
 */

declare(strict_types=1);

$title = $args['title'] ?? '';
$subtitle = $args['subtitle'] ?? '';
$name = $args['name'] ?? '';
$items = $args['items'] ?? [];
$threshold = $args['show_more_threshold'] ?? 5;
$has_more = count($items) > $threshold;

if (empty($items) || empty($name)) {
    return;
}
?>

<div class="filter-group" data-filter-group="<?php echo esc_attr($name); ?>">
    <button type="button" class="filter-group__header" aria-expanded="true" data-filter-toggle>
        <span class="filter-group__title">
            <?php echo esc_html($title); ?>
        </span>
        <span class="filter-group__icon">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m6 9 6 6 6-6"/>
            </svg>
        </span>
    </button>

    <div class="filter-group__content">
        <?php if ($subtitle) : ?>
            <p class="filter-group__subtitle"><?php echo esc_html($subtitle); ?></p>
        <?php endif; ?>

        <ul class="filter-group__list">
            <?php foreach ($items as $index => $item) : ?>
                <li class="filter-group__item<?php echo $index >= $threshold && $has_more ? ' filter-group__item--hidden' : ''; ?>">
                    <label class="filter-checkbox">
                        <input
                            type="checkbox"
                            name="<?php echo esc_attr($name); ?>[]"
                            value="<?php echo esc_attr($item['slug']); ?>"
                            class="filter-checkbox__input"
                            data-filter-input
                            data-label="<?php echo esc_attr($item['name']); ?>"
                        >
                        <span class="filter-checkbox__box"></span>
                        <span class="filter-checkbox__label"><?php echo esc_html($item['name']); ?></span>
                    </label>
                </li>
            <?php endforeach; ?>
        </ul>

        <?php if ($has_more) : ?>
            <button type="button" class="filter-group__more" data-filter-show-more>
                <svg class="filter-group__more-icon" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="m6 9 6 6 6-6"/>
                </svg>
                <span data-more-text><?php esc_html_e('Meer...', 'sunnytree'); ?></span>
                <span data-less-text style="display: none;"><?php esc_html_e('Minder', 'sunnytree'); ?></span>
            </button>
        <?php endif; ?>
    </div>
</div>
