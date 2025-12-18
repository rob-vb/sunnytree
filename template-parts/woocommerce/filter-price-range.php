<?php
/**
 * Price Range Filter Template
 *
 * Displays a dual-thumb range slider for price filtering.
 *
 * @package SunnyTree
 *
 * @var array $args {
 *     @type int $min Minimum price
 *     @type int $max Maximum price
 * }
 */

declare(strict_types=1);

$min = $args['min'] ?? 0;
$max = $args['max'] ?? 1000;

// Get current values from URL
$current_min = isset($_GET['min_price']) ? absint($_GET['min_price']) : $min;
$current_max = isset($_GET['max_price']) ? absint($_GET['max_price']) : $max;

// Ensure values are within range
$current_min = max($min, min($current_min, $max));
$current_max = max($min, min($current_max, $max));
?>

<div class="filter-group filter-group--price" data-filter-group="price">
    <button type="button" class="filter-group__header" aria-expanded="true" data-filter-toggle>
        <span class="filter-group__title"><?php esc_html_e('Prijs', 'sunnytree'); ?></span>
        <span class="filter-group__icon">
            <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="m6 9 6 6 6-6"/>
            </svg>
        </span>
    </button>

    <div class="filter-group__content">
        <div class="price-range" data-price-range data-min="<?php echo esc_attr($min); ?>" data-max="<?php echo esc_attr($max); ?>">
            <div class="price-range__slider">
                <div class="price-range__track"></div>
                <div class="price-range__selected" data-price-selected></div>
                <input
                    type="range"
                    class="price-range__input price-range__input--min"
                    min="<?php echo esc_attr($min); ?>"
                    max="<?php echo esc_attr($max); ?>"
                    value="<?php echo esc_attr($current_min); ?>"
                    step="1"
                    data-price-min-input
                    aria-label="<?php esc_attr_e('Minimum prijs', 'sunnytree'); ?>"
                >
                <input
                    type="range"
                    class="price-range__input price-range__input--max"
                    min="<?php echo esc_attr($min); ?>"
                    max="<?php echo esc_attr($max); ?>"
                    value="<?php echo esc_attr($current_max); ?>"
                    step="1"
                    data-price-max-input
                    aria-label="<?php esc_attr_e('Maximum prijs', 'sunnytree'); ?>"
                >
            </div>
            <div class="price-range__values">
                <span class="price-range__value price-range__value--min">
                    <span class="price-range__currency">&euro;</span>
                    <span data-price-min-display><?php echo esc_html($current_min); ?></span>
                </span>
                <span class="price-range__value price-range__value--max">
                    <span class="price-range__currency">&euro;</span>
                    <span data-price-max-display><?php echo esc_html($current_max); ?></span>
                </span>
            </div>

            <!-- Hidden inputs for form submission -->
            <input type="hidden" name="min_price" value="<?php echo esc_attr($current_min); ?>" data-filter-min-price>
            <input type="hidden" name="max_price" value="<?php echo esc_attr($current_max); ?>" data-filter-max-price>
        </div>
    </div>
</div>
