<?php
/**
 * Displayed when no products are found matching the current query
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/no-products-found.php.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 7.8.0
 */

declare(strict_types=1);

defined('ABSPATH') || exit;
?>
<div class="woocommerce-no-products-found">
	<?php wc_print_notice(esc_html__('No products were found matching your selection.', 'woocommerce'), 'notice'); ?>
</div>
