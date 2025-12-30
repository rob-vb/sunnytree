<?php
/**
 * SunnyTree Theme Functions
 *
 * @package SunnyTree
 */

declare(strict_types=1);

namespace SunnyTree;

// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit;
}

/**
 * Theme constants
 */
define('SUNNYTREE_VERSION', '1.0.0');
define('SUNNYTREE_DIR', get_template_directory());
define('SUNNYTREE_URI', get_template_directory_uri());

/**
 * Include theme files
 */
require_once SUNNYTREE_DIR . '/inc/theme-settings.php';
require_once SUNNYTREE_DIR . '/inc/template-tags.php';
require_once SUNNYTREE_DIR . '/inc/category-linked-page.php';
require_once SUNNYTREE_DIR . '/inc/product-filters.php';
require_once SUNNYTREE_DIR . '/inc/category-filter-settings.php';
require_once SUNNYTREE_DIR . '/inc/product-attribute-icons.php';
require_once SUNNYTREE_DIR . '/blocks/index.php';
require_once SUNNYTREE_DIR . '/inc/class-sunny-mega-walker.php';

/**
 * Theme setup
 */
function setup(): void
{
    // Add support for automatic title tag
    add_theme_support('title-tag');

    // Add support for block styles
    add_theme_support('wp-block-styles');

    // Add support for editor styles
    add_theme_support('editor-styles');

    // Add support for responsive embeds
    add_theme_support('responsive-embeds');

    // Add support for custom logo
    add_theme_support('custom-logo', [
        'height'      => 100,
        'width'       => 400,
        'flex-width'  => true,
        'flex-height' => true,
    ]);

    // Add support for post thumbnails
    add_theme_support('post-thumbnails');

    // Add WooCommerce support
    add_theme_support('woocommerce');

    // Register navigation menus
    register_nav_menus([
        'sunny-main-menu' => __('Categories', 'sunnytree'),
        'footer-menu'  => __('Footer Menu', 'sunnytree'),
        'footer-categories'  => __('Footer Categories', 'sunnytree'),
    ]);
}
add_action('after_setup_theme', __NAMESPACE__ . '\setup');

/**
 * Check if Vite dev server is running
 */
function is_vite_dev(): bool
{
    if (defined('WP_DEBUG') && WP_DEBUG) {
        $dev_server = @file_get_contents('http://localhost:5173');
        return $dev_server !== false;
    }
    return false;
}

/**
 * Get Vite asset URL
 */
function vite_asset(string $entry): string
{
    if (is_vite_dev()) {
        return 'http://localhost:5173/' . $entry;
    }

    $manifest_path = SUNNYTREE_DIR . '/dist/.vite/manifest.json';

    if (! file_exists($manifest_path)) {
        return '';
    }

    $manifest = json_decode(file_get_contents($manifest_path), true);

    if (! isset($manifest[$entry])) {
        return '';
    }

    return SUNNYTREE_URI . '/dist/' . $manifest[$entry]['file'];
}

/**
 * Enqueue theme assets
 */
function enqueue_assets(): void
{
    // Dev mode: load Vite client and module
    if (is_vite_dev()) {
        // Vite client for HMR
        wp_enqueue_script(
            'vite-client',
            'http://localhost:5173/@vite/client',
            [],
            null,
            false
        );

        // Main entry point as module
        wp_enqueue_script(
            'sunnytree-main',
            'http://localhost:5173/js/main.js',
            ['jquery'],
            null,
            true
        );

        // Add module type attribute
        add_filter('script_loader_tag', function (string $tag, string $handle): string {
            if (in_array($handle, ['vite-client', 'sunnytree-main'], true)) {
                return str_replace(' src', ' type="module" src', $tag);
            }
            return $tag;
        }, 10, 2);

        return;
    }

    // Production: load compiled assets
    $js_url = vite_asset('js/main.js');

    if ($js_url) {
        // Get CSS file from manifest
        $manifest_path = SUNNYTREE_DIR . '/dist/.vite/manifest.json';
        if (file_exists($manifest_path)) {
            $manifest = json_decode(file_get_contents($manifest_path), true);

            // Check for CSS in main.js entry (Vite default)
            if (isset($manifest['js/main.js']['css'])) {
                foreach ($manifest['js/main.js']['css'] as $css_file) {
                    wp_enqueue_style(
                        'sunnytree-style',
                        SUNNYTREE_URI . '/dist/' . $css_file,
                        [],
                        SUNNYTREE_VERSION
                    );
                }
            }
            // Check for separate style.css entry (IIFE build)
            elseif (isset($manifest['style.css']['file'])) {
                wp_enqueue_style(
                    'sunnytree-style',
                    SUNNYTREE_URI . '/dist/' . $manifest['style.css']['file'],
                    [],
                    SUNNYTREE_VERSION
                );
            }
        }

        // Enqueue JS
        wp_enqueue_script(
            'sunnytree-main',
            $js_url,
            ['jquery'],
            SUNNYTREE_VERSION,
            true
        );
    }
}
add_action('wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_assets');

/**
 * Enqueue editor assets
 */
function enqueue_editor_assets(): void
{
    // Only load compiled CSS in editor (not HMR)
    $manifest_path = SUNNYTREE_DIR . '/dist/.vite/manifest.json';

    if (file_exists($manifest_path)) {
        $manifest = json_decode(file_get_contents($manifest_path), true);
        if (isset($manifest['js/main.js']['css'])) {
            foreach ($manifest['js/main.js']['css'] as $css_file) {
                add_editor_style('dist/' . $css_file);
            }
        } elseif (isset($manifest['style.css']['file'])) {
            add_editor_style('dist/' . $manifest['style.css']['file']);
        }
    }
}
add_action('after_setup_theme', __NAMESPACE__ . '\enqueue_editor_assets');

/**
 * Add WooCommerce cart fragments for header cart count
 */
function cart_count_fragments(array $fragments): array
{
    if (! function_exists('WC') || ! WC()->cart) {
        return $fragments;
    }

    $count = WC()->cart->get_cart_contents_count();

    $fragments['.sunny-counter'] = sprintf(
        '<span class="sunny-counter">%d</span>',
        $count
    );

    return $fragments;
}
add_filter('woocommerce_add_to_cart_fragments', __NAMESPACE__ . '\cart_count_fragments');

/**
 * Check if current page should use category-style product layout
 *
 * @return bool True if category layout should be used
 */
function should_use_category_layout(): bool
{
    // Product category pages
    if (\is_product_category()) {
        return true;
    }

    // Product search results
    if (\is_search() && isset($_GET['post_type']) && $_GET['post_type'] === 'product') {
        return true;
    }

    return false;
}

/**
 * Use category-specific product template on product category pages and search
 *
 * @param string $template  Template path
 * @param string $slug      Template slug
 * @param string $name      Template name
 * @return string Modified template path
 */
function category_product_template(string $template, string $slug, string $name): string
{
    if ($slug === 'content' && $name === 'product' && should_use_category_layout()) {
        $category_template = \get_stylesheet_directory() . '/woocommerce/content-product-category.php';
        if (file_exists($category_template)) {
            return $category_template;
        }
    }
    return $template;
}
add_filter('wc_get_template_part', __NAMESPACE__ . '\category_product_template', 10, 3);

/**
 * Use category-specific loop templates on product category pages and search
 *
 * @param string $template      Template path
 * @param string $template_name Template name
 * @return string Modified template path
 */
function category_loop_templates(string $template, string $template_name): string
{
    if (! should_use_category_layout()) {
        return $template;
    }

    $category_templates = [
        'loop/loop-start.php' => '/woocommerce/loop/loop-start-category.php',
        'loop/loop-end.php'   => '/woocommerce/loop/loop-end-category.php',
    ];

    if (isset($category_templates[$template_name])) {
        $category_template = \get_stylesheet_directory() . $category_templates[$template_name];
        if (file_exists($category_template)) {
            return $category_template;
        }
    }

    return $template;
}
add_filter('wc_get_template', __NAMESPACE__ . '\category_loop_templates', 10, 2);

/**
 * Replace WooCommerce content wrapper to avoid duplicate <main> elements
 * The theme's header.php already outputs <main>, so we only need simple divs
 */
function custom_woocommerce_wrapper_start(): void
{
    echo '<div id="primary" class="content-area">';
}

function custom_woocommerce_wrapper_end(): void
{
    echo '</div><!-- #primary -->';
}

// Remove default WooCommerce wrappers and add custom ones
remove_action('woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action('woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);
add_action('woocommerce_before_main_content', __NAMESPACE__ . '\custom_woocommerce_wrapper_start', 10);
add_action('woocommerce_after_main_content', __NAMESPACE__ . '\custom_woocommerce_wrapper_end', 10);

/**
 * Customize WooCommerce breadcrumb:
 * - Wrap in container div
 * - Remove category title from breadcrumb display
 */
function custom_breadcrumb_defaults(array $defaults): array
{
    $defaults['wrap_before'] = '<nav class="woocommerce-breadcrumb" aria-label="' . esc_attr__('Breadcrumb', 'sunnytree') . '"><div class="container">';
    $defaults['wrap_after'] = '</div></nav>';

    return $defaults;
}
add_filter('woocommerce_breadcrumb_defaults', __NAMESPACE__ . '\custom_breadcrumb_defaults');

/**
 * Remove "Shop" from breadcrumb trail on search results
 */
function remove_shop_from_search_breadcrumb(array $crumbs): array
{
    if (is_search()) {
        foreach ($crumbs as $key => $crumb) {
            if (isset($crumb[1]) && $crumb[1] === wc_get_page_permalink('shop')) {
                unset($crumbs[$key]);
                $crumbs = array_values($crumbs);
                break;
            }
        }
    }
    return $crumbs;
}
add_filter('woocommerce_get_breadcrumb', __NAMESPACE__ . '\remove_shop_from_search_breadcrumb');

/**
 * Remove the WooCommerce archive header (h1 title) from product archives
 * We display our own title in archive-product.php
 */
remove_action('woocommerce_shop_loop_header', 'woocommerce_product_taxonomy_archive_header', 10);

/**
 * Wrap result count and ordering in a custom div
 */
remove_action('woocommerce_before_shop_loop', 'woocommerce_result_count', 20);
remove_action('woocommerce_before_shop_loop', 'woocommerce_catalog_ordering', 30);

function category_actions_wrapper(): void
{
    echo '<div class="category-actions">';
    \woocommerce_result_count();
    \woocommerce_catalog_ordering();
    echo '</div>';
}
add_action('woocommerce_before_shop_loop', __NAMESPACE__ . '\category_actions_wrapper', 20);

// TODO: Custom product page gallery hooks (disabled for now)
// function disable_woocommerce_gallery_features(): void { ... }
// function product_gallery_scripts(): void { ... }
