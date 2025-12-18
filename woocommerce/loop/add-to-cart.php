<?php
/**
 * Loop Add to Cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/add-to-cart.php.
 *
 * @see         https://woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     9.2.0
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

global $product;

$aria_describedby = isset($args['aria-describedby_text'])
	? sprintf('aria-describedby="woocommerce_loop_add_to_cart_link_describedby_%s"', esc_attr($product->get_id()))
	: '';

echo apply_filters(
	'woocommerce_loop_add_to_cart_link',
	sprintf(
		'<a href="%s" %s data-quantity="%s" class="%s" %s>%s</a>',
		esc_url($product->add_to_cart_url()),
		$aria_describedby,
		esc_attr(isset($args['quantity']) ? $args['quantity'] : 1),
		esc_attr(isset($args['class']) ? $args['class'] : 'button'),
		isset($args['attributes']) ? wc_implode_html_attributes($args['attributes']) : '',
		esc_html($product->add_to_cart_text())
	),
	$product,
	$args
);

if (isset($args['aria-describedby_text'])) : ?>
	<span id="woocommerce_loop_add_to_cart_link_describedby_<?php echo esc_attr($product->get_id()); ?>" class="screen-reader-text">
		<?php echo esc_html($args['aria-describedby_text']); ?>
	</span>
<?php endif;
