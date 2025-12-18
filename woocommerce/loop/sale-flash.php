<?php
/**
 * Product loop sale flash
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/sale-flash.php.
 *
 * @see         https://woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     1.6.4
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

global $post, $product;

if ($product->is_on_sale()) :
	echo apply_filters(
		'woocommerce_sale_flash',
		'<span class="onsale">' . esc_html__('Sale!', 'woocommerce') . '</span>',
		$post,
		$product
	);
endif;
