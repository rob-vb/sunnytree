<?php
/**
 * Product Filters Module
 *
 * AJAX-based product filtering for WooCommerce shop pages.
 *
 * @package SunnyTree
 */

declare(strict_types=1);

namespace SunnyTree\Filters;

// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit;
}

/**
 * Initialize the filters module
 */
function init(): void
{
    add_action('wp_ajax_sunnytree_filter_products', __NAMESPACE__ . '\handle_filter_request');
    add_action('wp_ajax_nopriv_sunnytree_filter_products', __NAMESPACE__ . '\handle_filter_request');
    add_action('wp_enqueue_scripts', __NAMESPACE__ . '\localize_filter_script', 20);
}
add_action('init', __NAMESPACE__ . '\init');

/**
 * Localize script with filter configuration and translations
 */
function localize_filter_script(): void
{
    if (! is_shop() && ! is_product_category() && ! is_product_taxonomy()) {
        return;
    }

    $filter_data = [
        'ajaxUrl' => admin_url('admin-ajax.php'),
        'nonce'   => wp_create_nonce('sunnytree_filter_nonce'),
        'i18n'    => [
            'filters'    => __('Filters', 'sunnytree'),
            'clearAll'   => __('Wis alle filters', 'sunnytree'),
            'showResults' => __('Toon resultaten', 'sunnytree'),
            'showMore'   => __('Meer...', 'sunnytree'),
            'showLess'   => __('Minder', 'sunnytree'),
            'products'   => __('producten', 'sunnytree'),
            'product'    => __('product', 'sunnytree'),
            'loading'    => __('Laden...', 'sunnytree'),
            'noResults'  => __('Geen producten gevonden', 'sunnytree'),
            'unlimited'  => __('Onbeperkt', 'sunnytree'),
        ],
    ];

    wp_localize_script('sunnytree-main', 'sunnyTreeFilters', $filter_data);
}

/**
 * Handle AJAX filter request
 */
function handle_filter_request(): void
{
    // Verify nonce
    if (! isset($_POST['nonce']) || ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['nonce'])), 'sunnytree_filter_nonce')) {
        wp_send_json_error(['message' => 'Invalid security token'], 403);
    }

    $filters = sanitize_filter_params($_POST);
    $query_args = build_product_query($filters);

    // Determine which template to use based on context
    $is_category_context = ! empty($filters['current_category']);
    $category_template = \get_stylesheet_directory() . '/woocommerce/content-product-category.php';
    $use_category_template = $is_category_context && file_exists($category_template);

    $products = new \WP_Query($query_args);

    ob_start();

    if ($products->have_posts()) {
        while ($products->have_posts()) {
            $products->the_post();
            if ($use_category_template) {
                include $category_template;
            } else {
                wc_get_template_part('content', 'product');
            }
        }
    } else {
        wc_get_template('loop/no-products-found.php');
    }

    $html = ob_get_clean();

    wp_send_json_success([
        'html'         => $html,
        'total'        => $products->found_posts,
        'pages'        => $products->max_num_pages,
        'current_page' => max(1, (int) $products->query_vars['paged']),
    ]);

    wp_reset_postdata();
}

/**
 * Sanitize all filter parameters
 *
 * @param array<string, mixed> $params Raw parameters
 * @return array<string, mixed> Sanitized parameters
 */
function sanitize_filter_params(array $params): array
{
    $sanitized = [];

    $sanitized['page'] = isset($params['page']) ? absint($params['page']) : 1;
    $sanitized['min_price'] = isset($params['min_price']) && $params['min_price'] !== '' ? absint($params['min_price']) : null;
    $sanitized['max_price'] = isset($params['max_price']) && $params['max_price'] !== '' ? absint($params['max_price']) : null;

    $sanitized['category'] = isset($params['category'])
        ? array_map('sanitize_text_field', (array) $params['category'])
        : [];

    $sanitized['orderby'] = isset($params['orderby'])
        ? sanitize_text_field($params['orderby'])
        : 'menu_order';

    $sanitized['current_category'] = isset($params['current_category'])
        ? sanitize_text_field($params['current_category'])
        : '';

    // Dynamic attribute filters - collect all pa_* parameters
    $sanitized['attributes'] = [];
    foreach ($params as $key => $value) {
        if (strpos($key, 'pa_') === 0 && ! empty($value)) {
            $sanitized['attributes'][$key] = array_map('sanitize_text_field', (array) $value);
        }
    }

    return $sanitized;
}

/**
 * Build WooCommerce product query from filter parameters
 *
 * @param array<string, mixed> $filters Sanitized filter parameters
 * @return array<string, mixed> WP_Query arguments
 */
function build_product_query(array $filters): array
{
    $per_page = apply_filters('loop_shop_per_page', wc_get_default_products_per_row() * wc_get_default_product_rows_per_page());

    $args = [
        'post_type'      => 'product',
        'post_status'    => 'publish',
        'posts_per_page' => $per_page,
        'paged'          => $filters['page'] ?? 1,
        'tax_query'      => ['relation' => 'AND'],
        'meta_query'     => ['relation' => 'AND'],
    ];

    // Exclude hidden products
    $args['tax_query'][] = [
        'taxonomy' => 'product_visibility',
        'field'    => 'name',
        'terms'    => ['exclude-from-catalog'],
        'operator' => 'NOT IN',
    ];

    // Current category context (when on a category page)
    if (! empty($filters['current_category'])) {
        $args['tax_query'][] = [
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => $filters['current_category'],
        ];
    }

    // Subcategory filter
    if (! empty($filters['category'])) {
        $args['tax_query'][] = [
            'taxonomy' => 'product_cat',
            'field'    => 'slug',
            'terms'    => $filters['category'],
        ];
    }

    // Price range filter
    if ($filters['min_price'] !== null || $filters['max_price'] !== null) {
        $price_query = [
            'key'     => '_price',
            'type'    => 'NUMERIC',
            'compare' => 'BETWEEN',
            'value'   => [
                $filters['min_price'] ?? 0,
                $filters['max_price'] ?? PHP_INT_MAX,
            ],
        ];

        // Handle open-ended ranges
        if ($filters['min_price'] !== null && $filters['max_price'] === null) {
            $price_query['compare'] = '>=';
            $price_query['value'] = $filters['min_price'];
        } elseif ($filters['min_price'] === null && $filters['max_price'] !== null) {
            $price_query['compare'] = '<=';
            $price_query['value'] = $filters['max_price'];
        }

        $args['meta_query'][] = $price_query;
    }

    // Dynamic attribute filters from request
    if (! empty($filters['attributes'])) {
        foreach ($filters['attributes'] as $taxonomy => $terms) {
            if (! empty($terms) && taxonomy_exists($taxonomy)) {
                $args['tax_query'][] = [
                    'taxonomy' => $taxonomy,
                    'field'    => 'slug',
                    'terms'    => $terms,
                    'operator' => 'IN',
                ];
            }
        }
    }

    // Ordering
    $orderby = $filters['orderby'] ?? 'menu_order';

    switch ($orderby) {
        case 'popularity':
            $args['meta_key'] = 'total_sales';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
            break;
        case 'rating':
            $args['meta_key'] = '_wc_average_rating';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
            break;
        case 'date':
            $args['orderby'] = 'date';
            $args['order'] = 'DESC';
            break;
        case 'price':
            $args['meta_key'] = '_price';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'ASC';
            break;
        case 'price-desc':
            $args['meta_key'] = '_price';
            $args['orderby'] = 'meta_value_num';
            $args['order'] = 'DESC';
            break;
        default:
            $args['orderby'] = 'menu_order title';
            $args['order'] = 'ASC';
    }

    return apply_filters('sunnytree_product_filter_query_args', $args, $filters);
}

/**
 * Get all available filter options for the current context
 *
 * @return array<string, mixed> Filter options
 */
function get_filter_options(): array
{
    $options = [
        'categories' => get_subcategories(),
        'attributes' => [],
    ];

    // Get category context
    $term_id = null;
    if (is_product_category()) {
        $queried_object = get_queried_object();
        if ($queried_object instanceof \WP_Term) {
            $term_id = $queried_object->term_id;
        }
    }

    // Price filter - always enabled
    $options['price_range'] = get_price_range();

    // Get enabled attribute filters for this category
    if ($term_id !== null) {
        $enabled_filters = \SunnyTree\CategoryFilterSettings\get_enabled_filters($term_id);

        foreach ($enabled_filters as $taxonomy) {
            $terms = get_attribute_terms($taxonomy);
            if (! empty($terms)) {
                // Get the attribute label
                $attr_name = str_replace('pa_', '', $taxonomy);
                $attribute = wc_get_attribute(wc_attribute_taxonomy_id_by_name($attr_name));
                $label = $attribute ? $attribute->name : ucfirst($attr_name);

                $options['attributes'][] = [
                    'slug'  => $taxonomy,
                    'name'  => $label,
                    'terms' => $terms,
                ];
            }
        }
    }

    return apply_filters('sunnytree_filter_options', $options);
}

/**
 * Get min/max price range for products
 *
 * @return array{min: int, max: int} Price range
 */
function get_price_range(): array
{
    global $wpdb;

    // Check for category context
    $category_id = 0;
    if (is_product_category()) {
        $category = get_queried_object();
        $category_id = $category->term_id ?? 0;
    }

    $transient_key = 'sunnytree_price_range_' . $category_id;
    $cached = get_transient($transient_key);

    if ($cached !== false) {
        return $cached;
    }

    // Build query based on context
    if ($category_id > 0) {
        $result = $wpdb->get_row($wpdb->prepare(
            "SELECT MIN(CAST(pm.meta_value AS DECIMAL(10,2))) as min_price,
                    MAX(CAST(pm.meta_value AS DECIMAL(10,2))) as max_price
             FROM {$wpdb->postmeta} pm
             INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
             INNER JOIN {$wpdb->term_relationships} tr ON p.ID = tr.object_id
             INNER JOIN {$wpdb->term_taxonomy} tt ON tr.term_taxonomy_id = tt.term_taxonomy_id
             WHERE pm.meta_key = %s
             AND p.post_type = %s
             AND p.post_status = %s
             AND tt.term_id = %d
             AND pm.meta_value > 0",
            '_price',
            'product',
            'publish',
            $category_id
        ));
    } else {
        $result = $wpdb->get_row($wpdb->prepare(
            "SELECT MIN(CAST(meta_value AS DECIMAL(10,2))) as min_price,
                    MAX(CAST(meta_value AS DECIMAL(10,2))) as max_price
             FROM {$wpdb->postmeta} pm
             INNER JOIN {$wpdb->posts} p ON pm.post_id = p.ID
             WHERE pm.meta_key = %s
             AND p.post_type = %s
             AND p.post_status = %s
             AND pm.meta_value > 0",
            '_price',
            'product',
            'publish'
        ));
    }

    $range = [
        'min' => (int) floor((float) ($result->min_price ?? 0)),
        'max' => (int) ceil((float) ($result->max_price ?? 1000)),
    ];

    set_transient($transient_key, $range, HOUR_IN_SECONDS);

    return $range;
}

/**
 * Get attribute terms with product counts
 *
 * @param string $taxonomy Attribute taxonomy name
 * @return array<int, array{id: int, name: string, slug: string, count: int}> Terms
 */
function get_attribute_terms(string $taxonomy): array
{
    // Check if taxonomy exists
    if (! taxonomy_exists($taxonomy)) {
        return [];
    }

    $terms = get_terms([
        'taxonomy'   => $taxonomy,
        'hide_empty' => true,
        'orderby'    => 'name',
        'order'      => 'ASC',
    ]);

    if (is_wp_error($terms) || empty($terms)) {
        return [];
    }

    return array_map(function ($term) {
        return [
            'id'    => $term->term_id,
            'name'  => $term->name,
            'slug'  => $term->slug,
            'count' => $term->count,
        ];
    }, $terms);
}

/**
 * Get subcategories for current category context
 *
 * @return array<int, array{id: int, name: string, slug: string, count: int}> Categories
 */
function get_subcategories(): array
{
    $parent_id = 0;

    if (is_product_category()) {
        $current_cat = get_queried_object();
        $parent_id = $current_cat->term_id ?? 0;
    }

    $categories = get_terms([
        'taxonomy'   => 'product_cat',
        'hide_empty' => true,
        'parent'     => $parent_id,
        'orderby'    => 'menu_order',
        'order'      => 'ASC',
    ]);

    if (is_wp_error($categories) || empty($categories)) {
        return [];
    }

    return array_map(function ($cat) {
        return [
            'id'    => $cat->term_id,
            'name'  => $cat->name,
            'slug'  => $cat->slug,
            'count' => $cat->count,
        ];
    }, $categories);
}

/**
 * Clear price range transients when product prices change
 *
 * @param int|\WC_Product $product Product ID or object
 */
function clear_price_cache($product): void
{
    // Handle both product objects and post IDs
    if (is_object($product) && method_exists($product, 'get_id')) {
        $post_id = $product->get_id();
    } else {
        $post_id = (int) $product;
    }

    if (get_post_type($post_id) !== 'product') {
        return;
    }

    // Clear all price range transients
    global $wpdb;
    $wpdb->query(
        "DELETE FROM {$wpdb->options}
         WHERE option_name LIKE '_transient_sunnytree_price_range_%'
         OR option_name LIKE '_transient_timeout_sunnytree_price_range_%'"
    );
}
add_action('woocommerce_product_set_stock', __NAMESPACE__ . '\clear_price_cache');
add_action('save_post_product', __NAMESPACE__ . '\clear_price_cache');
