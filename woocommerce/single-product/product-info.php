<?php
/**
 * Single Product Info
 *
 * Contains: Mobile USPs, Variants, Reviews, Cross-sells, Price, Add-to-cart
 *
 * @package SunnyTree
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

global $product;

$product_id = $product->get_id();
$is_variable = $product->is_type('variable');

// Get cross-sell products
$cross_sell_ids = $product->get_cross_sell_ids();
$cross_sells = [];
if (! empty($cross_sell_ids)) {
    foreach (array_slice($cross_sell_ids, 0, 4) as $cross_sell_id) {
        $cross_sell_product = wc_get_product($cross_sell_id);
        if ($cross_sell_product && $cross_sell_product->is_visible()) {
            $cross_sells[] = $cross_sell_product;
        }
    }
}

?>

<!-- Mobile USPs -->
<ul class="sunny-product-usp visible-xs">
    <li>
        <svg width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M5.34445 9.21112L1.8889 5.75557L0.711121 6.93335L5.34445 11.5556L15.2889 1.61112L14.1111 0.444458L5.34445 9.21112Z" fill="#454545" stroke="#454545" stroke-width="0.5"></path>
        </svg>
        <span><?php esc_html_e('Voor 12 uur besteld, vandaag verzonden', 'sunnytree'); ?></span>
    </li>
    <li>
        <svg width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M5.34445 9.21112L1.8889 5.75557L0.711121 6.93335L5.34445 11.5556L15.2889 1.61112L14.1111 0.444458L5.34445 9.21112Z" fill="#454545" stroke="#454545" stroke-width="0.5"></path>
        </svg>
        <span><?php esc_html_e('Onze specialisten selecteren de beste boom/plant voor jou', 'sunnytree'); ?></span>
    </li>
    <li>
        <svg width="16" height="12" viewBox="0 0 16 12" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M5.34445 9.21112L1.8889 5.75557L0.711121 6.93335L5.34445 11.5556L15.2889 1.61112L14.1111 0.444458L5.34445 9.21112Z" fill="#454545" stroke="#454545" stroke-width="0.5"></path>
        </svg>
        <span><?php esc_html_e('99 klanten gaven ons gemiddeld 5,0 sterren', 'sunnytree'); ?></span>
    </li>
</ul>

<!-- Variant Selector -->
<?php if ($is_variable) : ?>
    <div class="page__product__attributes">
        <div class="product__attributes" id="product__attributes">
            <div class="page__product__variation">
                <div class="product__attribute product__attribute--color-picker">
                    <span class="content__title content__title--attribute"><?php esc_html_e('Varianten:', 'sunnytree'); ?></span>
                    <div class="sunny-varianten-wrapper" data-product-variants>
                        <?php
                        $available_variations = $product->get_available_variations();
                        $attributes = $product->get_variation_attributes();

                        // For single attribute products (most common: size)
                        foreach ($attributes as $attribute_name => $options) :
                            $order = 1;
                            foreach ($options as $option) :
                                $term = get_term_by('slug', $option, $attribute_name);
                                $display_name = $term ? $term->name : $option;

                                // Find the variation for this option
                                $variation_id = null;
                                $variation_data = null;
                                foreach ($available_variations as $variation) {
                                    $attr_key = 'attribute_' . sanitize_title($attribute_name);
                                    if (isset($variation['attributes'][$attr_key]) &&
                                        $variation['attributes'][$attr_key] === $option) {
                                        $variation_id = $variation['variation_id'];
                                        $variation_data = $variation;
                                        break;
                                    }
                                }

                                // First option is selected by default
                                $is_selected = ($order === 1);
                                $active_class = $is_selected ? ' sunny-varianten-tegel--active' : '';
                                ?>
                                <div style="order: <?php echo esc_attr($order); ?>;"
                                     class="sunny-varianten-tegel<?php echo esc_attr($active_class); ?>"
                                     data-variant-option
                                     data-attribute-name="<?php echo esc_attr(sanitize_title($attribute_name)); ?>"
                                     data-attribute-value="<?php echo esc_attr($option); ?>"
                                     data-variation-id="<?php echo esc_attr($variation_id); ?>">
                                    <?php echo esc_html($display_name); ?>
                                </div>
                                <?php
                                $order++;
                            endforeach;
                        endforeach;
                        ?>
                    </div>
                </div>
            </div>
            <div class="page__product__attribute"></div>
        </div>
    </div>
    <div class="page__product__staggered-price"></div>

    <!-- Hidden variation data for JS -->
    <script type="application/json" data-product-variations>
        <?php echo wp_json_encode($available_variations); ?>
    </script>
<?php endif; ?>

<!-- Cross-sells Section -->
<?php if (! empty($cross_sells)) : ?>
    <div class="sunny-c sunny-c--complete-order">
        <span class="sunny-c--complete-order__title title-font text-medium"><?php esc_html_e('Maak je bestelling compleet', 'sunnytree'); ?></span>
        <div class="sunny-c--complete-order__wrapper flex flex-direction--column border-radius border">
            <?php
            $cross_sell_index = 1;
            foreach ($cross_sells as $cross_sell_product) :
                $cs_id = $cross_sell_product->get_id();
                $cs_name = $cross_sell_product->get_name();
                $cs_price = $cross_sell_product->get_price();
                $cs_regular_price = $cross_sell_product->get_regular_price();
                $cs_sale_price = $cross_sell_product->get_sale_price();
                $cs_permalink = $cross_sell_product->get_permalink();
                $cs_image = wp_get_attachment_image_src($cross_sell_product->get_image_id(), 'thumbnail');
                $cs_image_url = $cs_image ? $cs_image[0] : wc_placeholder_img_src('thumbnail');
                ?>
                <div class="sunny-c--product-line p-relative">
                    <div class="sunny-c--product-line__wrapper flex align-items--center justify-content--space-between" style="gap:0.9375rem">
                        <div class="flex align-items--center" style="gap:0.9375rem">
                            <div class="sunny-c--product-line__image-wrapper">
                                <picture>
                                    <img width="60" height="60"
                                         class="product-line__image"
                                         src="<?php echo esc_url($cs_image_url); ?>"
                                         alt="<?php echo esc_attr($cs_name); ?>">
                                </picture>
                            </div>
                            <div class="flex flex-direction--column" style="gap:0.1rem">
                                <a href="<?php echo esc_url($cs_permalink); ?>" class="block-link no-hover title-font text-medium">
                                    <?php echo esc_html($cs_name); ?>
                                </a>
                                <div class="flex flex-wrap--wrap sunny-c--product-line__price" style="gap:0.5rem">
                                    <span data-price="<?php echo esc_attr($cs_price); ?>"
                                          data-totalsellprice="<?php echo esc_attr($cs_price); ?>"
                                          data-totaloriginalprice="<?php echo esc_attr($cs_regular_price); ?>">
                                        <?php echo wp_kses_post(wc_price($cs_price)); ?>
                                    </span>
                                    <?php if ($cs_sale_price && $cs_regular_price > $cs_sale_price) : ?>
                                        <span class="product-card__price--old"><?php echo wp_kses_post(wc_price($cs_regular_price)); ?></span>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        <div class="flex-shrink--0">
                            <input class="product-id" type="hidden" name="" value="<?php echo esc_attr($cs_id); ?>">
                            <input style="display: none;"
                                   class="product-amount product-card__input border border-radius text-center"
                                   type="text"
                                   value="0">
                            <button type="button"
                                    aria-label="<?php echo esc_attr(sprintf(__('Voeg %s toe', 'sunnytree'), $cs_name)); ?>"
                                    class="flex-direction--column btn btn-primary btn-icon btn-complete-order flex-shrink--0"
                                    data-cross-sell-add
                                    data-product-id="<?php echo esc_attr($cs_id); ?>">
                                <svg id="Group_162160" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                    <line id="Line_73" y2="14" transform="translate(12 5)" fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></line>
                                    <line id="Line_74" x2="14" transform="translate(5 12)" fill="none" stroke="#fff" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"></line>
                                </svg>
                            </button>
                        </div>
                        <div class="error-messages error__<?php echo esc_attr($cs_id); ?>__attributecombinations" style="display:none;">
                            <?php esc_html_e('Attribute combination error!', 'sunnytree'); ?>
                        </div>
                        <div class="error-messages error__<?php echo esc_attr($cs_id); ?>__amount" style="display:none;">
                            <?php esc_html_e('The minimal order amount is not reached!', 'sunnytree'); ?>
                        </div>
                        <div class="error-messages error__<?php echo esc_attr($cs_id); ?>__stock" style="display:none;">
                            <?php esc_html_e('This product is currently not in stock!', 'sunnytree'); ?>
                        </div>
                    </div>
                </div>
                <?php
                $cross_sell_index++;
            endforeach;
            ?>

            <!-- Base product (hidden input for form submission) -->
            <div class="sunny-c--complete-order__base-product">
                <input type="hidden" name="product[0][product_id]" value="<?php echo esc_attr($product_id); ?>">
                <input class="amount" type="hidden" name="product[0][product_amount]" min="1" value="1">
            </div>
        </div>
    </div>
<?php endif; ?>

<!-- Price Display -->
<div class="page__product__price">
    <div class="prices__wrapper">
        <div class="prices__content"></div>
        <div class="prices__content">
            <?php if ($is_variable) : ?>
                <div class="product__price" data-variation-price>
                    <span class="price__number"><?php echo wp_kses_post($product->get_price_html()); ?></span>
                </div>
            <?php else : ?>
                <div class="product__price">
                    <span class="price__number"><?php echo wp_kses_post(wc_price($product->get_price())); ?></span>
                    <?php if ($product->is_on_sale() && $product->get_regular_price()) : ?>
                        <span class="price__number--old"><?php echo wp_kses_post(wc_price($product->get_regular_price())); ?></span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
        <div>
            <span class="priceperpiece">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-currency-euro" width="44" height="44" viewBox="0 0 24 24" stroke-width="1.5" stroke="#2c3e50" fill="none" stroke-linecap="round" stroke-linejoin="round">
                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                    <path d="M17.2 7a6 7 0 1 0 0 10"></path>
                    <path d="M13 10h-8m0 4h8"></path>
                </svg>
                <span><?php esc_html_e('Vaste lage prijs', 'sunnytree'); ?></span>
            </span>
        </div>
    </div>
</div>

<!-- Add to Cart -->
<div class="sunny-c sunny-c--order-area flex--no-important align-items--center">
    <input type="number"
           class="hook__product-amount border border-radius"
           name="product_amount"
           id="product__amount"
           min="1"
           value="1">

    <?php if ($is_variable) : ?>
        <!-- Hidden fields for variable products -->
        <input type="hidden" name="variation_id" value="" data-variation-id>
        <?php foreach ($attributes as $attribute_name => $options) : ?>
            <input type="hidden"
                   name="attribute_<?php echo esc_attr(sanitize_title($attribute_name)); ?>"
                   value=""
                   data-attribute-field="<?php echo esc_attr(sanitize_title($attribute_name)); ?>">
        <?php endforeach; ?>
    <?php endif; ?>

    <div class="btn btn-primary flex--no-important align-items--center justify-content--center hook__global__products-order-multiple"
         tabindex="0"
         data-add-to-cart
         data-product-id="<?php echo esc_attr($product_id); ?>">
        <span class="one-product">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"></path>
            </svg>
            <?php esc_html_e('Bestellen', 'sunnytree'); ?>
        </span>
        <span style="display:none" class="more-products">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z"></path>
            </svg>
            <span class="amount"></span> <?php esc_html_e('Producten bestellen', 'sunnytree'); ?>
        </span>
    </div>
</div>

<!-- Error Messages -->
<div class="error-messages error__amount" style="display:none;">
    <?php esc_html_e('Dit product heeft een minimale afname aantal van 10', 'sunnytree'); ?>
</div>
<div class="error-messages error__stock" style="display:none;">
    <?php
    $stock_qty = $product->get_stock_quantity();
    if ($stock_qty) {
        printf(
            /* translators: %d: stock quantity */
            esc_html__('Er zijn er nog %d op voorraad', 'sunnytree'),
            $stock_qty
        );
    }
    ?>
</div>
