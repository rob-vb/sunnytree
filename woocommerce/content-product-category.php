<?php
/**
 * Product template for category pages
 *
 * Uses the sunny-* class structure for category archive display.
 *
 * @package SunnyTree
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

global $product;

// Check if the product is a valid WooCommerce product and ensure its visibility before proceeding.
if (! is_a($product, WC_Product::class) || ! $product->is_visible()) {
    return;
}

$product_id = $product->get_id();
$permalink = $product->get_permalink();
$product_name = $product->get_name();
$is_on_sale = $product->is_on_sale();
$is_in_stock = $product->is_in_stock();

// Get the deepest (most specific) category for this product
$terms = wc_get_product_terms($product_id, 'product_cat', ['orderby' => 'parent', 'order' => 'DESC']);
$category = null;
$max_depth = -1;

foreach ($terms as $term) {
    $depth = count(get_ancestors($term->term_id, 'product_cat', 'taxonomy'));
    if ($depth > $max_depth) {
        $max_depth = $depth;
        $category = $term;
    }
}

// Calculate sale percentage
$sale_percentage = '';
if ($is_on_sale) {
    $regular_price = (float) $product->get_regular_price();
    $sale_price = (float) $product->get_sale_price();

    if ($regular_price > 0 && $sale_price > 0) {
        $sale_percentage = round((($regular_price - $sale_price) / $regular_price) * 100);
    }
}

// Get product image
$image_id = $product->get_image_id();
$image_url = $image_id ? wp_get_attachment_image_url($image_id, 'woocommerce_thumbnail') : wc_placeholder_img_src('woocommerce_thumbnail');
$image_alt = $image_id ? get_post_meta($image_id, '_wp_attachment_image_alt', true) : $product_name;

// Get prices
$regular_price = $product->get_regular_price();
$current_price = $product->get_price();
?>
<div class="col-xs-12 col-sm-4 col-md-4">
    <div class="sunny-product-cat">
        <a class="sunny-product-link" href="<?php echo esc_url($permalink); ?>" aria-label="<?php echo esc_attr($product_name); ?>"></a>

        <?php if ($is_on_sale && $sale_percentage) : ?>
            <div class="sale-label">
                <span>-<?php echo esc_html($sale_percentage); ?>%</span>
            </div>
        <?php endif; ?>

        <a class="sunny-product-img-cat" href="<?php echo esc_url($permalink); ?>">
            <?php echo $product->get_image('woocommerce_thumbnail', ['class' => 'lazyloaded', 'alt' => esc_attr($image_alt), 'title' => esc_attr($product_name)]); ?>
        </a>

        <div class="sunny-product-content-cat">
            <a href="<?php echo esc_url($permalink); ?>" class="sunny-product-title-cat">
                <?php if ($category) : ?>
                    <?php echo esc_html($category->name); ?>
                <?php endif; ?>
                <span><?php echo esc_html($product_name); ?></span>
            </a>

            <div class="sunny-special-content">
                <?php
                /**
                 * Hook for additional product content (e.g., short description, attributes)
                 */
                do_action('sunnytree_product_special_content', $product);
                ?>
            </div>

            <div class="sunny-flex-container">
                <div class="sunny-product-prices-cat">
                    <div class="sunny-price">
                        <?php if ($is_on_sale && $regular_price) : ?>
                            <p class="sunny-from-price"><?php echo wc_price($regular_price); ?></p>
                        <?php endif; ?>
                        <p class="sunny-current-price"><?php echo wc_price($current_price); ?></p>
                    </div>
                </div>

                <div class="sunny-cat-btn-wrapper">
                    <a class="sunny-product-info-btn" href="<?php echo esc_url($permalink); ?>"><?php esc_html_e('Bekijk', 'sunnytree'); ?></a>
                    <?php if ($is_in_stock && $product->is_purchasable() && $product->is_type('simple')) : ?>
                        <a href="<?php echo esc_url($product->add_to_cart_url()); ?>"
                           data-quantity="1"
                           class="hook_AddProductToCart add_to_cart_button ajax_add_to_cart"
                           data-product_id="<?php echo esc_attr($product_id); ?>"
                           data-product_sku="<?php echo esc_attr($product->get_sku()); ?>"
                           aria-label="<?php echo esc_attr(sprintf(__('Add %s to cart', 'sunnytree'), $product_name)); ?>"
                           rel="nofollow">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"/>
                            </svg>
                        </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
