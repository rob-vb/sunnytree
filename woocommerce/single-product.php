<?php
/**
 * The Template for displaying all single products
 *
 * This template overrides the default WooCommerce single-product.php
 *
 * @package SunnyTree
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

get_header('shop');

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action('woocommerce_before_main_content');

while (have_posts()) :
    the_post();

    global $product;

    if (! $product || ! $product instanceof WC_Product) {
        $product = wc_get_product(get_the_ID());
    }

    if (! $product) {
        continue;
    }
    ?>

    <div class="product">
        <div class="container">
            <div class="row">
                <div class="col-sm-6">
                    <?php wc_get_template_part('single-product/product-image-gallery'); ?>
                </div>

                <div class="col-sm-6">

                    <?php wc_get_template_part('single-product/product-title'); ?>

                    <?php if ($product->get_short_description()) : ?>
                        <div class="page__product__short__description">
                            <?php echo wp_kses_post(wpautop($product->get_short_description())); ?>
                        </div>
                    <?php endif; ?>

                    <?php wc_get_template_part('single-product/product-info'); ?>

                    <?php wc_get_template_part('single-product/product-cross-sells'); ?>

                    <div class="product-price">
                        <?php echo $product->get_price_html(); ?>
                    </div>

                    <div class="sunny-c--order-area">
                        <input type="number"
                               id="product__amount"
                               class="sunny-c--order-area__quantity"
                               value="1"
                               min="1"
                               max="99"
                               aria-label="<?php esc_attr_e('Aantal', 'sunnytree'); ?>" />

                        <button type="button"
                                class="hook__global__products-order-multiple"
                                data-add-to-cart
                                data-product-id="<?php echo esc_attr($product->get_id()); ?>"
                                data-cart-url="<?php echo esc_attr(wc_get_cart_url()); ?>">
                            <span class="one-product">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                                <?php esc_html_e('Bestellen', 'sunnytree'); ?>
                            </span>
                            <span class="more-products" style="display: none;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4Z"/><path d="M3 6h18"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
                                <span class="amount">1</span> <?php esc_html_e('Producten bestellen', 'sunnytree'); ?>
                            </span>
                        </button>
                    </div>
                </div>
            </div>

            <hr class="product-section-divider">

            <div class="row">
                <div class="col-sm-6">
                    <?php wc_get_template_part('single-product/product-description'); ?>
                </div>

                <div class="col-sm-6">
                    <?php wc_get_template_part('single-product/product-attributes'); ?>
                </div>
            </div>
        </div>
    </div>

    <?php
endwhile;

get_footer('shop');
