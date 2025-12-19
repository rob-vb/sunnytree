<?php
/**
 * Single Product Info Section
 *
 * Displays product variants (linked products in same family)
 *
 * @package SunnyTree
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

global $product;

if (! $product || ! $product instanceof WC_Product) {
    return;
}

$current_product_id = $product->get_id();

/**
 * Get linked product variants based on "product_type" attribute
 *
 * Products are linked by sharing the same "product_type" attribute value.
 * Each variant displays its "hoogte" (height) attribute as the label.
 */
$family_terms = wc_get_product_terms($current_product_id, 'pa_product_type', ['fields' => 'slugs']);

if (empty($family_terms)) {
    return;
}

$family_slug = $family_terms[0];

// Query all products in the same family
$variant_products = wc_get_products([
    'status'     => 'publish',
    'limit'      => 20,
    'orderby'    => 'menu_order',
    'order'      => 'ASC',
    'tax_query'  => [
        [
            'taxonomy' => 'pa_product_type',
            'field'    => 'slug',
            'terms'    => $family_slug,
        ],
    ],
]);

// Need at least 2 products to show variants
if (count($variant_products) < 2) {
    return;
}

// Build variant data with sorting by height
$variants = [];
foreach ($variant_products as $variant_product) {
    $variant_id = $variant_product->get_id();

    // Get height attribute for label (e.g., "100cm", "130cm")
    $height_terms = wc_get_product_terms($variant_id, 'pa_hoogte', ['fields' => 'names']);
    $height_label = ! empty($height_terms) ? $height_terms[0] : '';

    // Extract numeric value for sorting
    $sort_value = 0;
    if (preg_match('/(\d+)/', $height_label, $matches)) {
        $sort_value = (int) $matches[1];
    }

    $variants[] = [
        'id'         => $variant_id,
        'label'      => $height_label,
        'url'        => $variant_product->get_permalink(),
        'is_current' => $variant_id === $current_product_id,
        'sort_value' => $sort_value,
    ];
}

// Sort variants by height (numeric)
usort($variants, fn($a, $b) => $a['sort_value'] <=> $b['sort_value']);
?>

<div class="product-variants">
    <span class="product-variants__title"><?php esc_html_e('Varianten:', 'sunnytree'); ?></span>

    <div class="product-variants__grid">
        <?php foreach ($variants as $variant) : ?>
            <?php if ($variant['is_current']) : ?>
                <span class="grid__variant grid__variant--active" aria-current="true">
                    <?php echo esc_html($variant['label']); ?>
                </span>
            <?php else : ?>
                <a href="<?php echo esc_url($variant['url']); ?>"
                   class="grid__variant">
                    <?php echo esc_html($variant['label']); ?>
                </a>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>
