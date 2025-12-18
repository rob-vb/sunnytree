<?php
/**
 * Loop Price
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/price.php.
 *
 * @see         https://woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     1.6.4
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

global $product;

$price_html = $product->get_price_html();

if ($price_html) : ?>
	<span class="price"><?php echo $price_html; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped ?></span>
<?php endif;
