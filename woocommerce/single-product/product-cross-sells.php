<?php
/**
 * Single Product Cross-sells Section
 *
 * "Maak je bestelling compleet" section with cross-sell products
 *
 * @package SunnyTree
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

global $product;

$cross_sell_ids = $product->get_cross_sell_ids();

if (empty($cross_sell_ids)) {
    return;
}

$cross_sells = wc_get_products([
    'include' => $cross_sell_ids,
    'limit'   => 4,
    'status'  => 'publish',
]);

if (empty($cross_sells)) {
    return;
}
?>

<section class="sunny-c--complete-order">
    <span class="sunny-c--complete-order__title"><?php esc_html_e('Maak je bestelling compleet', 'sunnytree'); ?></span>

    <div class="border">
        <?php foreach ($cross_sells as $cross_product) :
            $cross_id = $cross_product->get_id();
            $cross_image = wp_get_attachment_image_src($cross_product->get_image_id(), 'thumbnail');
            $cross_image_url = $cross_image ? $cross_image[0] : wc_placeholder_img_src('thumbnail');
            $cross_name = $cross_product->get_name();
            $cross_link = $cross_product->get_permalink();
            $is_simple = $cross_product->is_type('simple');
            $is_in_stock = $cross_product->is_in_stock();

            // Get price display
            $regular_price = $cross_product->get_regular_price();
            $sale_price = $cross_product->get_sale_price();
            $price = $cross_product->get_price();
        ?>
            <div class="sunny-c--product-line">
                <div class="sunny-c--product-line__wrapper flex align-items--center justify-content--space-between">
                    <a href="<?php echo esc_url($cross_link); ?>" class="flex align-items--center sunny-c--product-line__link">
                        <div class="sunny-c--product-line__image-wrapper">
                            <img src="<?php echo esc_url($cross_image_url); ?>"
                                 alt="<?php echo esc_attr($cross_name); ?>"
                                 loading="lazy" />
                        </div>
                        <div class="flex flex-direction--column">
                            <span class="block-link"><?php echo esc_html($cross_name); ?></span>
                            <span class="sunny-c--product-line__price">
                                <?php if ($sale_price && $sale_price < $regular_price) : ?>
                                    <span class="sunny-c--product-line__price--current"><?php echo wc_price($sale_price); ?></span>
                                    <span class="sunny-c--product-line__price--old"><?php echo wc_price($regular_price); ?></span>
                                <?php else : ?>
                                    <?php echo wc_price($price); ?>
                                <?php endif; ?>
                            </span>
                        </div>
                    </a>

                    <?php if ($is_simple && $is_in_stock) : ?>
                        <div class="sunny-c--product-line__actions">
                            <input type="hidden" class="product-id" value="<?php echo esc_attr($cross_id); ?>" />
                            <button type="button"
                                    class="btn-complete-order"
                                    data-cross-sell-add
                                    data-product-id="<?php echo esc_attr($cross_id); ?>"
                                    aria-label="<?php echo esc_attr(sprintf(__('Voeg %s toe', 'sunnytree'), $cross_name)); ?>">
                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                    <line x1="12" y1="5" x2="12" y2="19"></line>
                                    <line x1="5" y1="12" x2="19" y2="12"></line>
                                </svg>
                            </button>
                            <input type="number"
                                   class="product-amount product-card__input"
                                   value="1"
                                   min="0"
                                   max="99"
                                   style="display: none;"
                                   aria-label="<?php echo esc_attr(sprintf(__('Aantal %s', 'sunnytree'), $cross_name)); ?>" />
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>
