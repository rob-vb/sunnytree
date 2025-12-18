<?php
/**
 * Single Product Title - Mobile Version
 *
 * @package SunnyTree
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

use function SunnyTree\ProductSubtitle\get_product_subtitle;

global $product;

$subtitle = get_product_subtitle($product->get_id());
?>
<span class="h1-mob">
    <?php the_title(); ?>
    <?php if ($subtitle) : ?>
        <span><?php echo esc_html($subtitle); ?></span>
    <?php endif; ?>
</span>
<div class="sunny-product-hoogte"></div>
