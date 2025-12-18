<?php
/**
 * Product Attribute Icons Admin
 *
 * Adds icon selection UI for product attributes in WooCommerce.
 *
 * @package SunnyTree
 */

declare(strict_types=1);

namespace SunnyTree\AttributeIcons;

// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit;
}

/**
 * Get available custom SVG icons for attributes
 *
 * @return array<string, string> Icon key => label
 */
function get_available_icons(): array
{
    return [
        ''                => __('Geen icoon', 'sunnytree'),
        'stamomvang'      => __('Stamomvang', 'sunnytree'),
        'winterhard'      => __('Winterhard', 'sunnytree'),
        'hoogte'          => __('Hoogte', 'sunnytree'),
        'winterhard_tot_c' => __('Winterhard tot °C', 'sunnytree'),
        'standplaats'     => __('Standplaats', 'sunnytree'),
    ];
}

/**
 * Get the SVG content for an icon
 *
 * @param string $icon_name Icon name without extension
 * @param array $attrs Optional attributes to add to SVG
 * @return string SVG content or empty string
 */
function get_icon_svg(string $icon_name, array $attrs = []): string
{
    if (empty($icon_name)) {
        return '';
    }

    $icon_path = SUNNYTREE_DIR . '/assets/icons/' . $icon_name . '.svg';

    if (! file_exists($icon_path)) {
        return '';
    }

    $svg = file_get_contents($icon_path);

    if ($svg === false) {
        return '';
    }

    // Add custom attributes if provided
    if (! empty($attrs)) {
        $attr_string = '';
        foreach ($attrs as $key => $value) {
            $attr_string .= sprintf(' %s="%s"', esc_attr($key), esc_attr($value));
        }
        // Insert attributes after opening svg tag
        $svg = preg_replace('/<svg/', '<svg' . $attr_string, $svg, 1);
    }

    return $svg;
}

/**
 * Render an attribute icon
 *
 * @param string $icon_name Icon name
 * @param array $attrs Optional attributes
 */
function render_attribute_icon(string $icon_name, array $attrs = []): void
{
    $default_attrs = [
        'width' => '28',
        'height' => '28',
        'class' => 'attribute-icon attribute-icon--' . esc_attr($icon_name),
        'aria-hidden' => 'true',
    ];

    $attrs = array_merge($default_attrs, $attrs);

    echo get_icon_svg($icon_name, $attrs);
}

/**
 * Get icon assigned to an attribute taxonomy
 *
 * @param string $taxonomy Taxonomy name (e.g., 'pa_standplaats')
 * @return string Icon name or empty string
 */
function get_attribute_icon(string $taxonomy): string
{
    $option_key = 'sunnytree_attr_icon_' . sanitize_key($taxonomy);
    $icon = get_option($option_key, '');

    return is_string($icon) ? $icon : '';
}

/**
 * Add settings page for attribute icons under WooCommerce menu
 */
function add_attribute_icon_settings(): void
{
    add_submenu_page(
        'woocommerce',
        __('Attribuut Iconen', 'sunnytree'),
        __('Attribuut Iconen', 'sunnytree'),
        'manage_woocommerce',
        'attribute-icons',
        __NAMESPACE__ . '\render_icon_settings_page'
    );
}
add_action('admin_menu', __NAMESPACE__ . '\add_attribute_icon_settings');

/**
 * Enqueue admin styles for the settings page
 */
function enqueue_admin_styles(string $hook): void
{
    if ($hook !== 'woocommerce_page_attribute-icons') {
        return;
    }

    wp_add_inline_style('woocommerce_admin_styles', '
        .sunnytree-icon-settings {
            max-width: 800px;
        }
        .sunnytree-icon-settings .form-table th {
            width: 200px;
        }
        .sunnytree-icon-row {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .sunnytree-icon-preview {
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f0f0f0;
            border-radius: 8px;
            padding: 8px;
        }
        .sunnytree-icon-preview svg {
            width: 32px;
            height: 32px;
            fill: currentColor;
        }
        .sunnytree-icon-select {
            min-width: 200px;
        }
    ');
}
add_action('admin_enqueue_scripts', __NAMESPACE__ . '\enqueue_admin_styles');

/**
 * Render the attribute icons settings page
 */
function render_icon_settings_page(): void
{
    if (! current_user_can('manage_woocommerce')) {
        return;
    }

    // Handle form submission
    if (isset($_POST['sunnytree_save_attr_icons'])) {
        if (! wp_verify_nonce($_POST['sunnytree_attr_icons_nonce'] ?? '', 'sunnytree_attr_icons_nonce')) {
            wp_die(__('Beveiligingscontrole mislukt.', 'sunnytree'));
        }

        $attributes = wc_get_attribute_taxonomies();
        foreach ($attributes as $attribute) {
            $option_key = 'sunnytree_attr_icon_pa_' . $attribute->attribute_name;
            $field_name = 'icon_' . $attribute->attribute_name;
            $icon = isset($_POST[$field_name])
                ? sanitize_text_field(wp_unslash($_POST[$field_name]))
                : '';
            update_option($option_key, $icon);
        }

        echo '<div class="notice notice-success is-dismissible"><p>' . esc_html__('Iconen opgeslagen!', 'sunnytree') . '</p></div>';
    }

    $attributes = wc_get_attribute_taxonomies();
    $icons = get_available_icons();

    ?>
    <div class="wrap sunnytree-icon-settings">
        <h1><?php esc_html_e('Product Attribuut Iconen', 'sunnytree'); ?></h1>
        <p><?php esc_html_e('Wijs een icoon toe aan elk product attribuut. Deze iconen worden weergegeven op de productpagina in de "Alles wat je wil weten" sectie.', 'sunnytree'); ?></p>

        <?php if (empty($attributes)) : ?>
            <div class="notice notice-warning">
                <p><?php esc_html_e('Geen product attributen gevonden. Maak eerst attributen aan in WooCommerce > Attributen.', 'sunnytree'); ?></p>
            </div>
        <?php else : ?>
            <form method="post">
                <?php wp_nonce_field('sunnytree_attr_icons_nonce', 'sunnytree_attr_icons_nonce'); ?>
                <table class="form-table">
                    <tbody>
                    <?php foreach ($attributes as $attribute) :
                        $option_key = 'sunnytree_attr_icon_pa_' . $attribute->attribute_name;
                        $current = get_option($option_key, '');
                        $field_id = 'icon_' . $attribute->attribute_name;
                    ?>
                        <tr>
                            <th scope="row">
                                <label for="<?php echo esc_attr($field_id); ?>">
                                    <?php echo esc_html($attribute->attribute_label); ?>
                                </label>
                            </th>
                            <td>
                                <div class="sunnytree-icon-row">
                                    <div class="sunnytree-icon-preview" id="preview_<?php echo esc_attr($field_id); ?>">
                                        <?php
                                        if ($current) {
                                            render_attribute_icon($current, ['width' => '32', 'height' => '32']);
                                        } else {
                                            echo '<span style="color: #999;">—</span>';
                                        }
                                        ?>
                                    </div>
                                    <select name="<?php echo esc_attr($field_id); ?>"
                                            id="<?php echo esc_attr($field_id); ?>"
                                            class="sunnytree-icon-select"
                                            onchange="updateIconPreview(this)">
                                        <?php foreach ($icons as $value => $label) : ?>
                                            <option value="<?php echo esc_attr($value); ?>"
                                                    <?php selected($current, $value); ?>
                                                    data-icon="<?php echo esc_attr($value); ?>">
                                                <?php echo esc_html($label); ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
                <p class="submit">
                    <input type="submit" name="sunnytree_save_attr_icons" class="button-primary"
                           value="<?php esc_attr_e('Opslaan', 'sunnytree'); ?>">
                </p>
            </form>

            <script>
            // Store icon SVGs for preview updates
            const iconSvgs = <?php
                $svg_data = [];
                foreach ($icons as $key => $label) {
                    if ($key) {
                        $svg_data[$key] = get_icon_svg($key, ['width' => '32', 'height' => '32']);
                    }
                }
                echo wp_json_encode($svg_data);
            ?>;

            function updateIconPreview(select) {
                const previewId = 'preview_' + select.id.replace('icon_', '');
                const preview = document.getElementById(previewId);
                const selectedIcon = select.value;

                if (selectedIcon && iconSvgs[selectedIcon]) {
                    preview.innerHTML = iconSvgs[selectedIcon];
                } else {
                    preview.innerHTML = '<span style="color: #999;">—</span>';
                }
            }
            </script>
        <?php endif; ?>
    </div>
    <?php
}
