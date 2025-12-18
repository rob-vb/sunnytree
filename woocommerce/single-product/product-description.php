<?php
/**
 * Single Product Description
 *
 * "Productomschrijving" section using reference HTML structure
 *
 * @package SunnyTree
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

global $product;

$description = $product->get_description();

if (empty($description)) {
    return;
}
?>

<div class="product__grid">
    <div class="page__product__description">
        <div class="product__block product__block--description" id="product__description">
            <div class="block__wrapper">
                <span class="product-h2"><?php esc_html_e('Productomschrijving', 'sunnytree'); ?></span>

                <div class="product__description">
                    <?php echo wp_kses_post(wpautop($description)); ?>
                </div>
            </div>
        </div>
    </div>
    <span class="app-codeblock-htmlhook" id="app-codeblock-htmlhook-product-description"></span>
</div>
