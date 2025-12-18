<?php
/**
 * Product Subtitle Custom Field
 *
 * Adds a subtitle field to WooCommerce products for display on category pages.
 *
 * @package SunnyTree
 */

declare(strict_types=1);

namespace SunnyTree\ProductSubtitle;

// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit;
}

/**
 * Add subtitle field to product General tab
 *
 * Uses show_if_* classes to ensure field appears for all product types
 */
function add_subtitle_field(): void
{
    echo '<div class="options_group show_if_simple show_if_variable show_if_grouped show_if_external">';
    woocommerce_wp_text_input([
        'id'          => '_product_subtitle',
        'label'       => __('Subtitle', 'sunnytree'),
        'description' => __('Optional subtitle displayed below product name (e.g., Latin name)', 'sunnytree'),
        'desc_tip'    => true,
        'placeholder' => __('e.g., Olea Europaea', 'sunnytree'),
    ]);
    echo '</div>';
}
add_action('woocommerce_product_options_general_product_data', __NAMESPACE__ . '\add_subtitle_field');

/**
 * Save subtitle field
 *
 * @param int $post_id Product ID
 */
function save_subtitle_field(int $post_id): void
{
    if (! isset($_POST['_product_subtitle'])) {
        return;
    }

    $subtitle = sanitize_text_field(wp_unslash($_POST['_product_subtitle']));
    update_post_meta($post_id, '_product_subtitle', $subtitle);
}
add_action('woocommerce_process_product_meta', __NAMESPACE__ . '\save_subtitle_field');

/**
 * Get product subtitle
 *
 * @param int $product_id Product ID
 * @return string Product subtitle or empty string
 */
function get_product_subtitle(int $product_id): string
{
    $subtitle = get_post_meta($product_id, '_product_subtitle', true);
    return is_string($subtitle) ? $subtitle : '';
}
