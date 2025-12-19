<?php
/**
 * Single Product Title - Mobile Version
 *
 * @package SunnyTree
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

global $product;

// Get the deepest (most specific) category for this product
$terms = wc_get_product_terms($product->get_id(), 'product_cat', ['orderby' => 'parent', 'order' => 'DESC']);
$category = null;
$max_depth = -1;

foreach ($terms as $term) {
    $depth = count(get_ancestors($term->term_id, 'product_cat', 'taxonomy'));
    if ($depth > $max_depth) {
        $max_depth = $depth;
        $category = $term;
    }
}
?>
<span class="h1-mob">
    <?php if ($category) : ?>
        <a href="<?php echo esc_url(get_term_link($category)); ?>"><?php echo esc_html($category->name); ?></a>
    <?php endif; ?>
    <span><?php the_title(); ?></span>
</span>
<div class="sunny-product-hoogte"></div>
