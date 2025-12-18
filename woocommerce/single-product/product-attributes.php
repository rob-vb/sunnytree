<?php
/**
 * Single Product Attributes with Icons
 *
 * "Alles wat je wil weten" section using reference HTML structure
 *
 * @package SunnyTree
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

use function SunnyTree\AttributeIcons\get_attribute_icon;
use function SunnyTree\AttributeIcons\render_attribute_icon;

global $product;

$attributes = $product->get_attributes();

if (empty($attributes)) {
    return;
}

// Filter to only show visible attributes with values
$display_attributes = [];
foreach ($attributes as $attribute) {
    if (! $attribute->get_visible()) {
        continue;
    }

    $values = [];
    if ($attribute->is_taxonomy()) {
        $terms = wc_get_product_terms($product->get_id(), $attribute->get_name(), ['fields' => 'names']);
        $values = $terms;
        $taxonomy = $attribute->get_name();
        $label = wc_attribute_label($attribute->get_name());
    } else {
        $values = $attribute->get_options();
        $taxonomy = '';
        $label = $attribute->get_name();
    }

    if (! empty($values)) {
        $display_attributes[] = [
            'label' => $label,
            'taxonomy' => $taxonomy,
            'values' => $values,
        ];
    }
}

if (empty($display_attributes)) {
    return;
}
?>

<div class="product__grid">
    <span class="product-h2"><?php esc_html_e('Alles wat je wil weten', 'sunnytree'); ?></span>
    <div class="page__product-property">
        <table>
            <tbody>
                <?php foreach ($display_attributes as $attr) :
                    $icon = '';
                    if ($attr['taxonomy']) {
                        $icon = get_attribute_icon($attr['taxonomy']);
                    }
                ?>
                    <tr>
                        <td>
                            <span class="sunny-prop-wrapper">
                                <?php if ($icon) : ?>
                                    <?php render_attribute_icon($icon); ?>
                                <?php else : ?>
                                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="attribute-icon attribute-icon--default">
                                        <circle cx="12" cy="12" r="10"/>
                                        <path d="M12 16v-4"/>
                                        <path d="M12 8h.01"/>
                                    </svg>
                                <?php endif; ?>
                            </span>
                        </td>
                        <td><?php echo esc_html($attr['label']); ?></td>
                        <td>
                            <div class="product__property">
                                <span class="property__title"></span>
                                <span class="property__value">
                                    <?php echo esc_html(implode(', ', $attr['values'])); ?>
                                </span>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
