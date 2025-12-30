<?php
/**
 * Sunnytree Template Tags
 *
 * Helper functions for template parts.
 *
 * @package SunnyTree
 */

declare(strict_types=1);

namespace SunnyTree\TemplateTags;

// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit;
}

/**
 * Get theme settings with defaults
 */
function get_settings(): array
{
    return \SunnyTree\Settings\get_settings();
}

/**
 * Get enabled USPs for display
 */
function get_usps(): array
{
    $settings = get_settings();
    $usps = $settings['usps'] ?? [];

    return array_filter($usps, fn($usp) => ! empty($usp['enabled']));
}

/**
 * Get WooCommerce cart item count
 */
function get_cart_count(): int
{
    if (! function_exists('WC') || ! WC()->cart) {
        return 0;
    }

    return (int) WC()->cart->get_cart_contents_count();
}

/**
 * Check if WooCommerce is active
 */
function is_woocommerce_active(): bool
{
    return class_exists('WooCommerce');
}

/**
 * Get product categories with children for navigation
 */
function get_product_categories(): array
{
    if (! is_woocommerce_active()) {
        return [];
    }

    $categories = get_terms([
        'taxonomy' => 'product_cat',
        'hide_empty' => true,
        'parent' => 0,
        'orderby' => 'menu_order',
        'order' => 'ASC',
    ]);

    if (is_wp_error($categories)) {
        return [];
    }

    $result = [];

    foreach ($categories as $category) {
        $children = get_terms([
            'taxonomy' => 'product_cat',
            'hide_empty' => true,
            'parent' => $category->term_id,
            'orderby' => 'menu_order',
            'order' => 'ASC',
        ]);

        $thumbnail_id = get_term_meta($category->term_id, 'thumbnail_id', true);

        $result[] = [
            'id' => $category->term_id,
            'name' => $category->name,
            'slug' => $category->slug,
            'url' => get_term_link($category),
            'count' => $category->count,
            'image' => $thumbnail_id ? wp_get_attachment_image_url((int) $thumbnail_id, 'medium_large') : '',
            'children' => is_wp_error($children) ? [] : array_map(function ($child) {
                return [
                    'id' => $child->term_id,
                    'name' => $child->name,
                    'slug' => $child->slug,
                    'url' => get_term_link($child),
                    'count' => $child->count,
                ];
            }, $children),
        ];
    }

    return $result;
}

/**
 * Render a Lucide icon as inline SVG
 *
 * @param string $name Icon name (e.g., 'truck', 'clock')
 * @param array $attrs Additional attributes
 */
function render_icon(string $name, array $attrs = []): void
{
    $default_attrs = [
        'width' => '24',
        'height' => '24',
        'stroke' => 'currentColor',
        'stroke-width' => '2',
        'stroke-linecap' => 'round',
        'stroke-linejoin' => 'round',
        'fill' => 'none',
        'class' => 'icon icon-' . esc_attr($name),
    ];

    $attrs = array_merge($default_attrs, $attrs);

    $icons = get_icon_paths();

    if (! isset($icons[$name])) {
        return;
    }

    $attr_string = '';
    foreach ($attrs as $key => $value) {
        $attr_string .= sprintf(' %s="%s"', esc_attr($key), esc_attr($value));
    }

    printf(
        '<svg%s viewBox="0 0 24 24">%s</svg>',
        $attr_string,
        $icons[$name]
    );
}

/**
 * Get icon as string
 */
function get_icon(string $name, array $attrs = []): string
{
    ob_start();
    render_icon($name, $attrs);
    return ob_get_clean();
}

/**
 * Get Lucide icon SVG paths
 */
function get_icon_paths(): array
{
    return [
        'menu' => '<line x1="4" x2="20" y1="12" y2="12"/><line x1="4" x2="20" y1="6" y2="6"/><line x1="4" x2="20" y1="18" y2="18"/>',
        'x' => '<path d="M18 6 6 18"/><path d="m6 6 12 12"/>',
        'search' => '<circle cx="11" cy="11" r="8"/><path d="m21 21-4.3-4.3"/>',
        'user' => '<path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/>',
        'heart' => '<path d="M19 14c1.49-1.46 3-3.21 3-5.5A5.5 5.5 0 0 0 16.5 3c-1.76 0-3 .5-4.5 2-1.5-1.5-2.74-2-4.5-2A5.5 5.5 0 0 0 2 8.5c0 2.3 1.5 4.05 3 5.5l7 7Z"/>',
        'shopping-cart' => '<circle cx="8" cy="21" r="1"/><circle cx="19" cy="21" r="1"/><path d="M2.05 2.05h2l2.66 12.42a2 2 0 0 0 2 1.58h9.78a2 2 0 0 0 1.95-1.57l1.65-7.43H5.12"/>',
        'clock' => '<circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>',
        'truck' => '<path d="M14 18V6a2 2 0 0 0-2-2H4a2 2 0 0 0-2 2v11a1 1 0 0 0 1 1h2"/><path d="M15 18H9"/><path d="M19 18h2a1 1 0 0 0 1-1v-3.65a1 1 0 0 0-.22-.624l-3.48-4.35A1 1 0 0 0 17.52 8H14"/><circle cx="17" cy="18" r="2"/><circle cx="7" cy="18" r="2"/>',
        'tree-palm' => '<path d="M13 8c0-2.76-2.46-5-5.5-5S2 5.24 2 8h2l1-1 1 1h4"/><path d="M13 7.14A5.82 5.82 0 0 1 16.5 6c3.04 0 5.5 2.24 5.5 5h-3l-1-1-1 1h-3"/><path d="M5.89 9.71c-2.15 2.15-2.3 5.47-.35 7.43l4.24-4.25.7-.7.71-.71 2.12-2.12c-1.95-1.96-5.27-1.8-7.42.35"/><path d="M11 15.5c.5 2.5-.17 4.5-1 6.5h4c2-5.5-.5-12-1-14"/>',
        'shield-check' => '<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/>',
        'star' => '<polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>',
        'phone' => '<path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z"/>',
        'mail' => '<rect width="20" height="16" x="2" y="4" rx="2"/><path d="m22 7-8.97 5.7a1.94 1.94 0 0 1-2.06 0L2 7"/>',
        'map-pin' => '<path d="M20 10c0 4.993-5.539 10.193-7.399 11.799a1 1 0 0 1-1.202 0C9.539 20.193 4 14.993 4 10a8 8 0 0 1 16 0"/><circle cx="12" cy="10" r="3"/>',
        'award' => '<path d="m15.477 12.89 1.515 8.526a.5.5 0 0 1-.81.47l-3.58-2.687a1 1 0 0 0-1.197 0l-3.586 2.686a.5.5 0 0 1-.81-.469l1.514-8.526"/><circle cx="12" cy="8" r="6"/>',
        'check-circle' => '<path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"/><path d="m9 11 3 3L22 4"/>',
        'package' => '<path d="m7.5 4.27 9 5.15"/><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><path d="m3.3 7 8.7 5 8.7-5"/><path d="M12 22V12"/>',
        'credit-card' => '<rect width="20" height="14" x="2" y="5" rx="2"/><line x1="2" x2="22" y1="10" y2="10"/>',
        'lock' => '<rect width="18" height="11" x="3" y="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0 1 10 0v4"/>',
        'thumbs-up' => '<path d="M7 10v12"/><path d="M15 5.88 14 10h5.83a2 2 0 0 1 1.92 2.56l-2.33 8A2 2 0 0 1 17.5 22H4a2 2 0 0 1-2-2v-8a2 2 0 0 1 2-2h2.76a2 2 0 0 0 1.79-1.11L12 2a3.13 3.13 0 0 1 3 3.88Z"/>',
        'leaf' => '<path d="M11 20A7 7 0 0 1 9.8 6.1C15.5 5 17 4.48 19 2c1 2 2 4.18 2 8 0 5.5-4.78 10-10 10Z"/><path d="M2 21c0-3 1.85-5.36 5.08-6C9.5 14.52 12 13 13 12"/>',
        'sun' => '<circle cx="12" cy="12" r="4"/><path d="M12 2v2"/><path d="M12 20v2"/><path d="m4.93 4.93 1.41 1.41"/><path d="m17.66 17.66 1.41 1.41"/><path d="M2 12h2"/><path d="M20 12h2"/><path d="m6.34 17.66-1.41 1.41"/><path d="m19.07 4.93-1.41 1.41"/>',
        'droplets' => '<path d="M7 16.3c2.2 0 4-1.83 4-4.05 0-1.16-.57-2.26-1.71-3.19S7.29 6.75 7 5.3c-.29 1.45-1.14 2.84-2.29 3.76S3 11.1 3 12.25c0 2.22 1.8 4.05 4 4.05z"/><path d="M12.56 6.6A10.97 10.97 0 0 0 14 3.02c.5 2.5 2 4.9 4 6.5s3 3.5 3 5.5a6.98 6.98 0 0 1-11.91 4.97"/>',
        'home' => '<path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>',
        'users' => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
        'chevron-right' => '<path d="m9 18 6-6-6-6"/>',
        'chevron-left' => '<path d="m15 18-6-6 6-6"/>',
        'chevron-down' => '<path d="m6 9 6 6 6-6"/>',
    ];
}

/**
 * Render stars based on rating
 */
function render_stars(float $rating = 5.0, int $total = 5): void
{
    $full_stars = floor($rating);
    $half_star = ($rating - $full_stars) >= 0.5;
    $empty_stars = $total - $full_stars - ($half_star ? 1 : 0);

    echo '<span class="stars" aria-label="' . esc_attr(sprintf(__('%s out of %s stars', 'sunnytree'), $rating, $total)) . '">';

    for ($i = 0; $i < $full_stars; $i++) {
        echo '<span class="star star--full">&#9733;</span>';
    }

    if ($half_star) {
        echo '<span class="star star--half">&#9733;</span>';
    }

    for ($i = 0; $i < $empty_stars; $i++) {
        echo '<span class="star star--empty">&#9734;</span>';
    }

    echo '</span>';
}

/**
 * Get my account page URL
 */
function get_account_url(): string
{
    if (is_woocommerce_active() && function_exists('wc_get_page_id')) {
        $account_page_id = wc_get_page_id('myaccount');
        if ($account_page_id > 0) {
            return get_permalink($account_page_id);
        }
    }

    return wp_login_url();
}

/**
 * Get cart page URL
 */
function get_cart_url(): string
{
    if (is_woocommerce_active() && function_exists('wc_get_cart_url')) {
        return wc_get_cart_url();
    }

    return home_url('/cart');
}

/**
 * Get wishlist page URL
 */
function get_wishlist_url(): string
{
    // Check for YITH WooCommerce Wishlist
    if (function_exists('YITH_WCWL')) {
        return YITH_WCWL()->get_wishlist_url();
    }

    // Check for TI WooCommerce Wishlist
    if (function_exists('tinv_url_wishlist_default')) {
        return tinv_url_wishlist_default();
    }

    return home_url('/wishlist');
}

/**
 * Get wishlist count
 */
function get_wishlist_count(): int
{
    // YITH WooCommerce Wishlist
    if (function_exists('YITH_WCWL') && method_exists(YITH_WCWL(), 'count_products')) {
        return YITH_WCWL()->count_products();
    }

    // TI WooCommerce Wishlist
    if (function_exists('tinv_wishlist_get')) {
        $wishlist = tinv_wishlist_get();
        return is_array($wishlist) ? count($wishlist) : 0;
    }

    return 0;
}
