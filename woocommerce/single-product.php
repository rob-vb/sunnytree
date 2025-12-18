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

    <div class="container single-product-container">
        <div class="row">
            <div class="col-sm-6">
                <?php wc_get_template_part('single-product/product-image-gallery'); ?>
            </div>

            <div class="col-sm-6">
                <?php wc_get_template_part('single-product/product-title'); ?>

                <?php if ($product->get_short_description()) : ?>
                    <div class="product__short-description">
                        <?php echo wp_kses_post(wpautop($product->get_short_description())); ?>
                    </div>
                <?php endif; ?>

                <?php wc_get_template_part('single-product/product-info'); ?>
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

    <?php
endwhile;

get_footer('shop');
