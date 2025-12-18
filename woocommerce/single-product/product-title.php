<?php
/**
 * Single Product Title - Desktop Version
 *
 * @package SunnyTree
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

use function SunnyTree\ProductSubtitle\get_product_subtitle;

global $product;

$subtitle = get_product_subtitle($product->get_id());
?>
<div class="product__title">
    <h1><?php the_title(); ?></h1>
    <?php if ($subtitle) : ?>
        <span class="product__subtitle"><?php echo esc_html($subtitle); ?></span>
    <?php endif; ?>
</div>
