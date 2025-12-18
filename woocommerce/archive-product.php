<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * @see https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 8.6.0
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

get_header('shop');

// Remove linked content hook from after_shop_loop - we render it explicitly after the row
remove_action('woocommerce_after_shop_loop', 'SunnyTree\CategoryLinkedPage\maybe_display_linked_content', 99);

/**
 * Hook: woocommerce_before_main_content.
 *
 * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
 * @hooked woocommerce_breadcrumb - 20
 * @hooked WC_Structured_Data::generate_website_data() - 30
 */
do_action('woocommerce_before_main_content');

/**
 * Hook: woocommerce_shop_loop_header.
 *
 * @since 8.6.0
 *
 * @hooked woocommerce_product_taxonomy_archive_header - 10
 */
do_action('woocommerce_shop_loop_header');
?>

<div class="container">
	<div class="row">
		<!-- Filter Sidebar Column -->
		<div class="col-xs-12 col-sm-12 col-md-3">
			<?php get_template_part('template-parts/woocommerce/filter-sidebar'); ?>
		</div>

		<!-- Products Column -->
		<div class="col-xs-12 col-sm-12 col-md-9">
			<?php if (is_product_category()) : ?>
				<h1 class="sunny-pagetitle"><?php single_term_title(); ?></h1>
			<?php endif; ?>

			<?php
			if (woocommerce_product_loop()) {

				/**
				 * Hook: woocommerce_before_shop_loop.
				 *
				 * @hooked woocommerce_output_all_notices - 10
				 * @hooked woocommerce_result_count - 20
				 * @hooked woocommerce_catalog_ordering - 30
				 */
				do_action('woocommerce_before_shop_loop');
				?>

				<!-- Mobile filter toggle button -->
				<span class="sunny-mobile-filter-btn" data-filter-mobile-toggle>
					<svg fill="none" viewBox="0 0 15 10" xmlns="http://www.w3.org/2000/svg">
						<path d="M5.83333 10H9.16667V8.33333H5.83333V10ZM0 0V1.66667H15V0H0ZM2.5 5.83333H12.5V4.16667H2.5V5.83333Z" fill="#fff"></path>
					</svg>
					<span><?php esc_html_e('Toon filters', 'sunnytree'); ?></span>
				</span>

				<div class="products-container" data-products-container>
					<?php
					woocommerce_product_loop_start();

					if (wc_get_loop_prop('total')) {
						while (have_posts()) {
							the_post();

							/**
							 * Hook: woocommerce_shop_loop.
							 */
							do_action('woocommerce_shop_loop');

							wc_get_template_part('content', 'product');
						}
					}

					woocommerce_product_loop_end();
					?>
				</div>

				<?php
				/**
				 * Hook: woocommerce_after_shop_loop.
				 *
				 * @hooked woocommerce_pagination - 10
				 */
				do_action('woocommerce_after_shop_loop');
			} else {
				/**
				 * Hook: woocommerce_no_products_found.
				 *
				 * @hooked wc_no_products_found - 10
				 */
				do_action('woocommerce_no_products_found');
			}
			?>
		</div>
	</div>

	<?php
	// Render linked page content inside the container
	if (is_product_category()) {
		\SunnyTree\CategoryLinkedPage\render_linked_page_content();
		\SunnyTree\CategoryLinkedPage\has_rendered_linked_content(true);
	}
	?>
</div>

<?php

/**
 * Hook: woocommerce_after_main_content.
 *
 * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
 */
do_action('woocommerce_after_main_content');

// Remove default sidebar - we have our own filter sidebar
remove_action('woocommerce_sidebar', 'woocommerce_get_sidebar', 10);

get_footer('shop');
