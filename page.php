<?php
/**
 * Page Template
 *
 * @package SunnyTree
 */

declare(strict_types=1);

get_header();

// Check if this is a WooCommerce account/cart/checkout page
$is_wc_page = function_exists('is_cart') && (is_cart() || is_checkout() || is_account_page());
$template_suffix = $is_wc_page ? 'page-woocommerce' : 'page';
?>

<div class="content-area content-area--page">
    <?php
    while (have_posts()) :
        the_post();
        get_template_part('template-parts/content/content', $template_suffix);
    endwhile;
    ?>
</div>

<?php
get_footer();
