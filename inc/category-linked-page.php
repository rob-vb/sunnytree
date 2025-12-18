<?php
/**
 * Category Linked Page Feature
 *
 * Allows linking WordPress pages to WooCommerce product categories
 * to display custom Gutenberg content on category archive pages.
 *
 * @package SunnyTree
 */

declare(strict_types=1);

namespace SunnyTree\CategoryLinkedPage;

// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit;
}

/**
 * Register term meta for linked page
 */
function register_term_meta(): void
{
    \register_term_meta('product_cat', '_linked_page_id', [
        'type'              => 'integer',
        'description'       => __('Linked page ID for category content', 'sunnytree'),
        'single'            => true,
        'show_in_rest'      => true,
        'sanitize_callback' => 'absint',
    ]);
}
add_action('init', __NAMESPACE__ . '\register_term_meta');

/**
 * Add linked page field to "Add New Category" form
 */
function add_category_form_field(): void
{
    if (! class_exists('WooCommerce')) {
        return;
    }

    $pages = get_pages([
        'post_status' => 'publish',
        'sort_column' => 'post_title',
        'sort_order'  => 'ASC',
    ]);
    ?>
    <div class="form-field">
        <label for="linked_page_id"><?php esc_html_e('Linked Page', 'sunnytree'); ?></label>
        <select name="linked_page_id" id="linked_page_id">
            <option value=""><?php esc_html_e('— None —', 'sunnytree'); ?></option>
            <?php foreach ($pages as $page) : ?>
                <option value="<?php echo esc_attr((string) $page->ID); ?>">
                    <?php echo esc_html($page->post_title); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <p class="description">
            <?php esc_html_e('Select a page to display its content at the bottom of this category page.', 'sunnytree'); ?>
        </p>
    </div>
    <?php
}
add_action('product_cat_add_form_fields', __NAMESPACE__ . '\add_category_form_field');

/**
 * Add linked page field to "Edit Category" form
 *
 * @param \WP_Term $term Current taxonomy term object
 */
function edit_category_form_field(\WP_Term $term): void
{
    if (! class_exists('WooCommerce')) {
        return;
    }

    $linked_page_id = (int) get_term_meta($term->term_id, '_linked_page_id', true);

    $pages = get_pages([
        'post_status' => 'publish',
        'sort_column' => 'post_title',
        'sort_order'  => 'ASC',
    ]);
    ?>
    <tr class="form-field">
        <th scope="row">
            <label for="linked_page_id"><?php esc_html_e('Linked Page', 'sunnytree'); ?></label>
        </th>
        <td>
            <select name="linked_page_id" id="linked_page_id" class="postform">
                <option value=""><?php esc_html_e('— None —', 'sunnytree'); ?></option>
                <?php foreach ($pages as $page) : ?>
                    <option value="<?php echo esc_attr((string) $page->ID); ?>" <?php selected($linked_page_id, $page->ID); ?>>
                        <?php echo esc_html($page->post_title); ?>
                    </option>
                <?php endforeach; ?>
            </select>
            <p class="description">
                <?php esc_html_e('Select a page to display its content at the bottom of this category page.', 'sunnytree'); ?>
            </p>
            <?php if ($linked_page_id > 0) : ?>
                <p class="description">
                    <a href="<?php echo esc_url((string) get_edit_post_link($linked_page_id)); ?>" target="_blank">
                        <?php esc_html_e('Edit linked page', 'sunnytree'); ?> &rarr;
                    </a>
                </p>
            <?php endif; ?>
        </td>
    </tr>
    <?php
}
add_action('product_cat_edit_form_fields', __NAMESPACE__ . '\edit_category_form_field');

/**
 * Save linked page meta when category is created
 *
 * @param int $term_id Term ID
 */
function save_category_created(int $term_id): void
{
    if (! isset($_POST['_wpnonce_add-tag']) ||
        ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce_add-tag'])), 'add-tag')) {
        return;
    }

    if (! current_user_can('manage_product_terms')) {
        return;
    }

    save_linked_page_meta($term_id);
}
add_action('created_product_cat', __NAMESPACE__ . '\save_category_created');

/**
 * Save linked page meta when category is updated
 *
 * @param int $term_id Term ID
 */
function save_category_updated(int $term_id): void
{
    if (! isset($_POST['_wpnonce']) ||
        ! wp_verify_nonce(sanitize_text_field(wp_unslash($_POST['_wpnonce'])), 'update-tag_' . $term_id)) {
        return;
    }

    if (! current_user_can('manage_product_terms')) {
        return;
    }

    save_linked_page_meta($term_id);
}
add_action('edited_product_cat', __NAMESPACE__ . '\save_category_updated');

/**
 * Common function to save linked page meta
 *
 * @param int $term_id Term ID
 */
function save_linked_page_meta(int $term_id): void
{
    if (! isset($_POST['linked_page_id'])) {
        return;
    }

    $linked_page_id = absint($_POST['linked_page_id']);

    if ($linked_page_id > 0) {
        $page = get_post($linked_page_id);
        if ($page && $page->post_type === 'page' && $page->post_status === 'publish') {
            update_term_meta($term_id, '_linked_page_id', $linked_page_id);
        }
    } else {
        delete_term_meta($term_id, '_linked_page_id');
    }
}

/**
 * Get the linked page ID for a product category
 *
 * @param int|null $term_id Term ID (defaults to current queried object)
 * @return int|null Page ID or null if not set
 */
function get_linked_page_id(?int $term_id = null): ?int
{
    if ($term_id === null) {
        $queried_object = get_queried_object();

        if (! $queried_object instanceof \WP_Term || $queried_object->taxonomy !== 'product_cat') {
            return null;
        }

        $term_id = $queried_object->term_id;
    }

    $page_id = (int) get_term_meta($term_id, '_linked_page_id', true);

    return $page_id > 0 ? $page_id : null;
}

/**
 * Get the rendered content of a linked page
 *
 * @param int $page_id Page ID
 * @return string Rendered page content
 */
function get_linked_page_content(int $page_id): string
{
    $page = get_post($page_id);

    if (! $page || $page->post_type !== 'page' || $page->post_status !== 'publish') {
        return '';
    }

    $content = apply_filters('the_content', $page->post_content);

    return $content;
}

/**
 * Render linked page content for current category
 */
function render_linked_page_content(): void
{
    $page_id = get_linked_page_id();

    if ($page_id === null) {
        return;
    }

    $content = get_linked_page_content($page_id);

    if (empty($content)) {
        return;
    }

    ?>
    <section class="category-linked-content">
        <?php
        // Content is already escaped/processed by the_content filter
        echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
        ?>
    </section>
    <?php
}

/**
 * Track whether linked content has been rendered (shared between classic and block hooks)
 */
function has_rendered_linked_content(bool $set = false): bool
{
    static $rendered = false;

    if ($set) {
        $rendered = true;
    }

    return $rendered;
}

/**
 * Hook into WooCommerce to display linked page content after products
 * Using multiple hooks to ensure compatibility with both classic and block templates
 */
function maybe_display_linked_content(): void
{
    if (has_rendered_linked_content()) {
        return;
    }

    if (! is_product_category()) {
        return;
    }

    render_linked_page_content();
    has_rendered_linked_content(true);
}

// Classic template hooks
add_action('woocommerce_after_main_content', __NAMESPACE__ . '\maybe_display_linked_content', 5);
add_action('woocommerce_after_shop_loop', __NAMESPACE__ . '\maybe_display_linked_content', 99);

// Block template hook - fires at the end of the main content area
add_action('wp_footer', __NAMESPACE__ . '\maybe_render_linked_content_for_blocks', 1);

/**
 * Render linked content for block templates via wp_footer
 * This approach injects the content via JavaScript for block-based templates
 */
function maybe_render_linked_content_for_blocks(): void
{
    // Skip if already rendered by classic template hooks
    if (has_rendered_linked_content()) {
        return;
    }

    if (! is_product_category()) {
        return;
    }

    $page_id = get_linked_page_id();
    if ($page_id === null) {
        return;
    }

    $content = get_linked_page_content($page_id);
    if (empty($content)) {
        return;
    }

    // Mark as rendered so it won't be duplicated
    has_rendered_linked_content(true);

    // Output content in footer with JS to move it to the right place
    ?>
    <div id="category-linked-content-placeholder" style="display:none;">
        <section class="category-linked-content">
            <?php echo $content; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?>
        </section>
    </div>
    <script>
    (function() {
        var placeholder = document.getElementById('category-linked-content-placeholder');
        if (!placeholder) return;

        var content = placeholder.innerHTML;
        placeholder.remove();

        // Try to find the WooCommerce product grid/loop container
        var targets = [
            '.woocommerce-products-header',
            '.wp-block-woocommerce-product-collection',
            '.wp-block-woocommerce-product-template',
            '.woocommerce-pagination',
            '.products.columns-4',
            '.products.columns-3',
            '.products',
            '.woocommerce'
        ];

        var inserted = false;
        for (var i = 0; i < targets.length && !inserted; i++) {
            var target = document.querySelector(targets[i]);
            if (target) {
                // Find the outermost woocommerce container and insert after it
                var container = target.closest('.woocommerce') || target.closest('.wp-block-woocommerce-product-collection') || target;
                if (container && container.parentNode) {
                    container.insertAdjacentHTML('afterend', content);
                    inserted = true;
                }
            }
        }

        // Fallback: insert before footer
        if (!inserted) {
            var main = document.querySelector('main') || document.querySelector('.site-main') || document.querySelector('#main');
            if (main) {
                main.insertAdjacentHTML('beforeend', content);
            }
        }
    })();
    </script>
    <?php
}

/**
 * Add linked page column to categories list
 *
 * @param array<string, string> $columns Existing columns
 * @return array<string, string> Modified columns
 */
function add_admin_column(array $columns): array
{
    $columns['linked_page'] = __('Linked Page', 'sunnytree');
    return $columns;
}
add_filter('manage_edit-product_cat_columns', __NAMESPACE__ . '\add_admin_column');

/**
 * Render linked page column content
 *
 * @param string $content Column content
 * @param string $column_name Column name
 * @param int    $term_id Term ID
 * @return string Modified content
 */
function render_admin_column(string $content, string $column_name, int $term_id): string
{
    if ($column_name !== 'linked_page') {
        return $content;
    }

    $page_id = (int) get_term_meta($term_id, '_linked_page_id', true);

    if ($page_id > 0) {
        $page = get_post($page_id);
        if ($page) {
            return sprintf(
                '<a href="%s">%s</a>',
                esc_url((string) get_edit_post_link($page_id)),
                esc_html($page->post_title)
            );
        }
    }

    return '<span aria-hidden="true">—</span>';
}
add_filter('manage_product_cat_custom_column', __NAMESPACE__ . '\render_admin_column', 10, 3);
