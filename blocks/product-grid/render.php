<?php
/**
 * Product Grid Block - Server-side Render
 *
 * @package SunnyTree
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Block default content.
 * @var WP_Block $block      Block instance.
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

// Check if WooCommerce is active
if (! class_exists('WooCommerce')) {
	return;
}

$product_ids  = $attributes['productIds'] ?? [];
$columns      = $attributes['columns'] ?? 4;
$display_type = $attributes['displayType'] ?? 'selected';
$category     = $attributes['category'] ?? '';
$limit        = $attributes['limit'] ?? 8;
$order_by     = $attributes['orderBy'] ?? 'date';
$order        = $attributes['order'] ?? 'DESC';

// Build query arguments
$query_args = [
	'post_type'      => 'product',
	'post_status'    => 'publish',
	'posts_per_page' => $limit,
	'orderby'        => $order_by,
	'order'          => $order,
];

// Handle different display types
switch ($display_type) {
	case 'selected':
		if (empty($product_ids)) {
			return;
		}
		$query_args['post__in'] = $product_ids;
		$query_args['orderby']  = 'post__in';
		$query_args['posts_per_page'] = -1;
		break;

	case 'category':
		if (! empty($category)) {
			$query_args['tax_query'] = [
				[
					'taxonomy' => 'product_cat',
					'field'    => 'slug',
					'terms'    => $category,
				],
			];
		}
		break;

	case 'featured':
		$query_args['tax_query'] = [
			[
				'taxonomy' => 'product_visibility',
				'field'    => 'name',
				'terms'    => 'featured',
			],
		];
		break;

	case 'on_sale':
		$query_args['post__in'] = wc_get_product_ids_on_sale();
		if (empty($query_args['post__in'])) {
			return;
		}
		break;
}

$products_query = new WP_Query($query_args);

if (! $products_query->have_posts()) {
	return;
}

$wrapper_attributes = get_block_wrapper_attributes([
	'class' => 'sunny-product-container',
]);

// Map columns to Bootstrap grid classes
$col_class = 'col-xs-12 col-sm-3 col-md-3';
switch ($columns) {
	case 2:
		$col_class = 'col-xs-12 col-sm-6 col-md-6';
		break;
	case 3:
		$col_class = 'col-xs-12 col-sm-4 col-md-4';
		break;
	case 4:
	default:
		$col_class = 'col-xs-12 col-sm-3 col-md-3';
		break;
	case 6:
		$col_class = 'col-xs-12 col-sm-2 col-md-2';
		break;
}
?>

<div <?php echo $wrapper_attributes; ?>>
	<div class="row product-row">
		<?php
		while ($products_query->have_posts()) :
			$products_query->the_post();
			global $product;

			if (! is_a($product, WC_Product::class) || ! $product->is_visible()) {
				continue;
			}

			$product_id     = $product->get_id();
			$permalink      = $product->get_permalink();
			$product_name   = $product->get_name();
			$is_on_sale     = $product->is_on_sale();
			$is_in_stock    = $product->is_in_stock();

			// Calculate sale percentage
			$sale_percentage = '';
			if ($is_on_sale) {
				$regular_price = (float) $product->get_regular_price();
				$sale_price    = (float) $product->get_sale_price();

				if ($regular_price > 0 && $sale_price > 0) {
					$sale_percentage = round((($regular_price - $sale_price) / $regular_price) * 100);
				}
			}

			// Get product image
			$image_id  = $product->get_image_id();
			$image_alt = $image_id ? get_post_meta($image_id, '_wp_attachment_image_alt', true) : $product_name;

			// Get prices
			$regular_price = $product->get_regular_price();
			$current_price = $product->get_price();
			?>
			<div class="<?php echo esc_attr($col_class); ?>">
				<div class="sunny-product">
					<a class="sunny-product-link" href="<?php echo esc_url($permalink); ?>"></a>

					<?php if ($is_on_sale && $sale_percentage) : ?>
						<div class="sale-label">
							<span>-<?php echo esc_html($sale_percentage); ?>%</span>
						</div>
					<?php endif; ?>

					<a class="sunny-product-img" href="<?php echo esc_url($permalink); ?>">
						<?php if (! $is_in_stock) : ?>
							<div class="sunny-stock-label">
								<span><?php esc_html_e('Tijdelijk niet op voorraad', 'sunnytree'); ?></span>
							</div>
						<?php endif; ?>

						<?php echo $product->get_image('woocommerce_thumbnail', ['alt' => esc_attr($image_alt)]); ?>
					</a>

					<div class="sunny-product-content">
						<a href="<?php echo esc_url($permalink); ?>" class="sunny-product-title-cat">
							<?php echo esc_html($product_name); ?>
						</a>

						<div class="sunny-special-content">
							<?php do_action('sunnytree_product_special_content', $product); ?>
						</div>

						<div class="sunny-flex-container">
							<div class="sunny-product-prices">
								<div class="sunny-price">
									<?php if ($is_on_sale && $regular_price) : ?>
										<p class="sunny-from-price"><?php echo wc_price($regular_price); ?></p>
									<?php endif; ?>
									<p class="sunny-current-price"><?php echo wc_price($current_price); ?></p>
								</div>
							</div>

							<a class="sunny-product-arrow" href="<?php echo esc_url($permalink); ?>">
								<svg class="sunny-transition" width="20" height="17" viewBox="0 0 20 17" fill="none" xmlns="http://www.w3.org/2000/svg">
									<path d="M1 9.42589H16.586L11.222 14.7899C10.8315 15.1804 10.8315 15.8136 11.2218 16.2039C11.6123 16.5944 12.2457 16.5944 12.6362 16.2039L19.707 9.13289C19.7535 9.08639 19.795 9.03514 19.8315 8.98064C19.8482 8.95539 19.86 8.92814 19.8745 8.90189C19.891 8.87089 19.91 8.84139 19.9233 8.80864C19.9375 8.77489 19.9455 8.73989 19.9555 8.70489C19.9637 8.67714 19.9745 8.65064 19.9802 8.62214C19.9932 8.55714 20 8.49164 20 8.42589C20 8.42514 19.9998 8.42439 19.9998 8.42364C19.9995 8.35889 19.993 8.29389 19.9802 8.23014C19.9742 8.20014 19.963 8.17239 19.9543 8.14289C19.9445 8.10964 19.937 8.07589 19.9235 8.04364C19.909 8.00889 19.8895 7.97739 19.8715 7.94464C19.858 7.92014 19.8472 7.89514 19.8318 7.87164C19.7952 7.81639 19.7532 7.76489 19.7065 7.71814L12.636 0.647887C12.2455 0.257387 11.6123 0.257387 11.2218 0.647637C10.8313 1.03814 10.8313 1.67139 11.2218 2.06214L16.5858 7.42589H1C0.44775 7.42589 0 7.87364 0 8.42589C0 8.97814 0.44775 9.42589 1 9.42589Z" fill="#95976F"/>
								</svg>
							</a>
						</div>
					</div>
				</div>
			</div>
			<?php
		endwhile;
		wp_reset_postdata();
		?>
	</div>
</div>