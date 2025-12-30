<?php
/**
 * Category Filter Settings
 *
 * Allows configuring which product filters are available per category.
 *
 * @package SunnyTree
 */

declare(strict_types=1);

namespace SunnyTree\CategoryFilterSettings;

// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit;
}

/**
 * Get all available WooCommerce product attributes
 *
 * @return array<int, array{slug: string, name: string}> Available attributes
 */
function get_available_filter_attributes(): array
{
    if (! function_exists('wc_get_attribute_taxonomies')) {
        return [];
    }

    $attributes = wc_get_attribute_taxonomies();

    if (empty($attributes)) {
        return [];
    }

    return array_map(function ($attr) {
        return [
            'slug' => 'pa_' . $attr->attribute_name,
            'name' => $attr->attribute_label,
        ];
    }, array_values($attributes));
}

/**
 * Register term meta for filter settings
 */
function register_term_meta(): void
{
    \register_term_meta('product_cat', '_enabled_filters', [
        'type'              => 'array',
        'description'       => __('Enabled filter attributes for this category', 'sunnytree'),
        'single'            => true,
        'show_in_rest'      => false,
        'sanitize_callback' => function ($value) {
            if (! is_array($value)) {
                return [];
            }
            return array_map('sanitize_text_field', $value);
        },
    ]);
}
add_action('init', __NAMESPACE__ . '\register_term_meta');

/**
 * Add filter settings fields to "Add New Category" form
 */
function add_category_form_field(): void
{
    if (! class_exists('WooCommerce')) {
        return;
    }

    $attributes = get_available_filter_attributes();
    ?>
    <div class="form-field">
        <label><?php esc_html_e('Product Filters', 'sunnytree'); ?></label>
        <input type="hidden" name="sunnytree_filter_settings_submitted" value="1">

        <?php if (! empty($attributes)) : ?>
            <p style="margin-top: 10px; margin-bottom: 5px;">
                <strong><?php esc_html_e('Attribute filters:', 'sunnytree'); ?></strong>
            </p>
            <?php foreach ($attributes as $attr) : ?>
                <p>
                    <label>
                        <input type="checkbox" name="enabled_filters[]" value="<?php echo esc_attr($attr['slug']); ?>">
                        <?php echo esc_html($attr['name']); ?>
                    </label>
                </p>
            <?php endforeach; ?>
        <?php else : ?>
            <p class="description">
                <?php esc_html_e('No product attributes found.', 'sunnytree'); ?>
            </p>
        <?php endif; ?>

        <p class="description">
            <?php esc_html_e('Select which filters to show on this category page.', 'sunnytree'); ?>
        </p>
    </div>
    <?php
}
add_action('product_cat_add_form_fields', __NAMESPACE__ . '\add_category_form_field');

/**
 * Add filter settings fields to "Edit Category" form
 *
 * @param \WP_Term $term Current taxonomy term object
 */
function edit_category_form_field(\WP_Term $term): void
{
    if (! class_exists('WooCommerce')) {
        return;
    }

    $attributes = get_available_filter_attributes();
    $enabled_filters = get_enabled_filters($term->term_id);
    ?>
    <tr class="form-field">
        <th scope="row">
            <label><?php esc_html_e('Product Filters', 'sunnytree'); ?></label>
        </th>
        <td>
            <input type="hidden" name="sunnytree_filter_settings_submitted" value="1">

            <?php if (! empty($attributes)) : ?>
                <p style="margin-top: 10px; margin-bottom: 5px;">
                    <strong><?php esc_html_e('Attribute filters:', 'sunnytree'); ?></strong>
                </p>
                <?php foreach ($attributes as $attr) : ?>
                    <p>
                        <label>
                            <input type="checkbox" name="enabled_filters[]" value="<?php echo esc_attr($attr['slug']); ?>" <?php checked(in_array($attr['slug'], $enabled_filters, true)); ?>>
                            <?php echo esc_html($attr['name']); ?>
                        </label>
                    </p>
                <?php endforeach; ?>
            <?php else : ?>
                <p class="description">
                    <?php esc_html_e('No product attributes found.', 'sunnytree'); ?>
                </p>
            <?php endif; ?>

            <p class="description">
                <?php esc_html_e('Select which filters to show on this category page.', 'sunnytree'); ?>
            </p>
        </td>
    </tr>
    <?php
}
add_action('product_cat_edit_form_fields', __NAMESPACE__ . '\edit_category_form_field');

/**
 * Save filter settings when category is created or updated
 *
 * @param int $term_id Term ID
 */
function save_category_filters(int $term_id): void
{
    // Only process if our form was submitted (hidden field always present)
    if (! isset($_POST['sunnytree_filter_settings_submitted'])) {
        return;
    }

    if (! current_user_can('manage_product_terms')) {
        return;
    }

    save_filter_settings($term_id);
}
add_action('created_product_cat', __NAMESPACE__ . '\save_category_filters');
add_action('edited_product_cat', __NAMESPACE__ . '\save_category_filters');

/**
 * Common function to save filter settings
 *
 * @param int $term_id Term ID
 */
function save_filter_settings(int $term_id): void
{
    // Save enabled attribute filters
    if (isset($_POST['enabled_filters']) && is_array($_POST['enabled_filters'])) {
        $enabled = array_map('sanitize_text_field', $_POST['enabled_filters']);
        update_term_meta($term_id, '_enabled_filters', $enabled);
    } else {
        delete_term_meta($term_id, '_enabled_filters');
    }
}

/**
 * Get enabled filter attributes for a category
 *
 * @param int|null $term_id Term ID (defaults to current queried object)
 * @return array<string> Array of enabled attribute slugs
 */
function get_enabled_filters(?int $term_id = null): array
{
    if ($term_id === null) {
        $queried_object = get_queried_object();

        if (! $queried_object instanceof \WP_Term || $queried_object->taxonomy !== 'product_cat') {
            return [];
        }

        $term_id = $queried_object->term_id;
    }

    $filters = get_term_meta($term_id, '_enabled_filters', true);

    return is_array($filters) ? $filters : [];
}

/**
 * Add filters column to categories list
 *
 * @param array<string, string> $columns Existing columns
 * @return array<string, string> Modified columns
 */
function add_admin_column(array $columns): array
{
    $columns['enabled_filters'] = __('Filters', 'sunnytree');
    return $columns;
}
add_filter('manage_edit-product_cat_columns', __NAMESPACE__ . '\add_admin_column');

/**
 * Render filters column content
 *
 * @param string $content Column content
 * @param string $column_name Column name
 * @param int    $term_id Term ID
 * @return string Modified content
 */
function render_admin_column(string $content, string $column_name, int $term_id): string
{
    if ($column_name !== 'enabled_filters') {
        return $content;
    }

    $enabled_filters = get_enabled_filters($term_id);
    $count = count($enabled_filters);

    if ($count > 0) {
        $attributes = get_available_filter_attributes();
        $attr_map = [];
        foreach ($attributes as $attr) {
            $attr_map[$attr['slug']] = $attr['name'];
        }

        $parts = [];
        foreach ($enabled_filters as $slug) {
            if (isset($attr_map[$slug])) {
                $parts[] = $attr_map[$slug];
            }
        }

        return sprintf(
            '<span title="%s">%d %s</span>',
            esc_attr(implode(', ', $parts)),
            $count,
            _n('filter', 'filters', $count, 'sunnytree')
        );
    }

    return '<span aria-hidden="true">—</span>';
}
add_filter('manage_product_cat_custom_column', __NAMESPACE__ . '\render_admin_column', 10, 3);
