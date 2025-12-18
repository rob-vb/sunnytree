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

<section class="cross-sells">
    <h2 class="cross-sells__title"><?php esc_html_e('Maak je bestelling compleet', 'sunnytree'); ?></h2>

    <div class="cross-sells__grid">
        <?php foreach ($cross_sells as $cross_product) :
            $cross_id = $cross_product->get_id();
            $cross_image = $cross_product->get_image('woocommerce_thumbnail', ['class' => 'cross-sells__image']);
            $cross_name = $cross_product->get_name();
            $cross_price = $cross_product->get_price_html();
            $cross_link = $cross_product->get_permalink();
            $is_simple = $cross_product->is_type('simple');
        ?>
            <article class="cross-sells__item">
                <a href="<?php echo esc_url($cross_link); ?>" class="cross-sells__link">
                    <div class="cross-sells__img-wrap">
                        <?php echo $cross_image; ?>
                    </div>
                    <div class="cross-sells__content">
                        <h3 class="cross-sells__name"><?php echo esc_html($cross_name); ?></h3>
                        <span class="cross-sells__price"><?php echo $cross_price; ?></span>
                    </div>
                </a>
                <?php if ($is_simple && $cross_product->is_in_stock()) : ?>
                    <button type="button"
                            class="cross-sells__add"
                            data-add-to-cart="<?php echo esc_attr($cross_id); ?>"
                            data-product-name="<?php echo esc_attr($cross_name); ?>"
                            aria-label="<?php echo esc_attr(sprintf(__('Voeg %s toe aan winkelwagen', 'sunnytree'), $cross_name)); ?>">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M12 5v14"/>
                            <path d="M5 12h14"/>
                        </svg>
                    </button>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
    </div>
</section>
