<?php
/**
 * Product Loop Start
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/loop-start.php.
 *
 * @see         https://woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     3.3.0
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

$columns = wc_get_loop_prop('columns');
?>
<ul class="products columns-<?php echo esc_attr($columns); ?>">
