<?php
/**
 * Product Filter Sidebar Template
 *
 * @package SunnyTree
 */

declare(strict_types=1);

use function SunnyTree\Filters\get_filter_options;

$options = get_filter_options();
$current_category = '';

if (is_product_category()) {
    $cat = get_queried_object();
    $current_category = $cat->slug ?? '';
}
?>

<!-- Mobile backdrop -->
<div class="product-filters-backdrop" data-filter-backdrop></div>

<!-- Filter sidebar -->
<aside class="product-filters" id="product-filters" data-filter-sidebar>
    <div class="product-filters__header">
        <h3 class="product-filters__title"><?php esc_html_e('Filters', 'sunnytree'); ?></h3>
        <button type="button" class="product-filters__close" data-filter-close aria-label="<?php esc_attr_e('Sluit filters', 'sunnytree'); ?>">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M18 6 6 18M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- Active filter chips -->
    <div class="product-filters__active" data-filter-chips></div>

    <div class="product-filters__body">
        <form class="product-filters__form" data-filter-form>
            <!-- Hidden field for current category context -->
            <?php if ($current_category) : ?>
                <input type="hidden" name="current_category" value="<?php echo esc_attr($current_category); ?>">
            <?php endif; ?>

            <!-- Price Range -->
            <?php if (! empty($options['price_range'])) : ?>
                <?php
                get_template_part('template-parts/woocommerce/filter-price-range', null, [
                    'min' => $options['price_range']['min'],
                    'max' => $options['price_range']['max'],
                ]);
                ?>
            <?php endif; ?>

            <!-- Standplaats -->
            <?php if (! empty($options['standplaats'])) : ?>
                <?php
                get_template_part('template-parts/woocommerce/filter-group', null, [
                    'title'               => __('Standplaats', 'sunnytree'),
                    'subtitle'            => __('De beste standplaats is:', 'sunnytree'),
                    'name'                => 'standplaats',
                    'items'               => $options['standplaats'],
                    'show_more_threshold' => 5,
                ]);
                ?>
            <?php endif; ?>

            <!-- Winter Hardy (Winterhard) -->
            <?php if (! empty($options['winterhard'])) : ?>
                <?php
                get_template_part('template-parts/woocommerce/filter-group', null, [
                    'title'               => __('Winterhard', 'sunnytree'),
                    'subtitle'            => __('Is de boom winterhard', 'sunnytree'),
                    'name'                => 'winterhard',
                    'items'               => $options['winterhard'],
                    'show_more_threshold' => 5,
                ]);
                ?>
            <?php endif; ?>

            <!-- Hoogte -->
            <?php if (! empty($options['hoogte'])) : ?>
                <?php
                get_template_part('template-parts/woocommerce/filter-group', null, [
                    'title'               => __('Hoogte', 'sunnytree'),
                    'subtitle'            => __('De hoogte ligt tussen de:', 'sunnytree'),
                    'name'                => 'hoogte',
                    'items'               => $options['hoogte'],
                    'show_more_threshold' => 5,
                ]);
                ?>
            <?php endif; ?>

            <!-- Subcategories -->
            <?php if (! empty($options['categories'])) : ?>
                <?php
                get_template_part('template-parts/woocommerce/filter-group', null, [
                    'title'               => __('Subcategorie', 'sunnytree'),
                    'name'                => 'category',
                    'items'               => $options['categories'],
                    'show_more_threshold' => 5,
                ]);
                ?>
            <?php endif; ?>
        </form>
    </div>

    <!-- Mobile footer with apply button -->
    <div class="product-filters__footer">
        <button type="button" class="product-filters__clear-btn" data-filter-clear-all>
            <?php esc_html_e('Wis alle filters', 'sunnytree'); ?>
        </button>
        <button type="button" class="product-filters__apply" data-filter-apply>
            <?php esc_html_e('Toon resultaten', 'sunnytree'); ?>
            <span class="product-filters__count" data-filter-count></span>
        </button>
    </div>
</aside>
