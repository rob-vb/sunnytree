<?php
/**
 * Single Product Image Gallery
 *
 * Uses the reference HTML structure with Swiper integration
 *
 * @package SunnyTree
 */

declare(strict_types=1);

defined('ABSPATH') || exit;

global $product;

$attachment_ids = $product->get_gallery_image_ids();
$main_image_id = $product->get_image_id();

// Build array of all images (main + gallery)
$all_images = [];
if ($main_image_id) {
    $all_images[] = $main_image_id;
}
$all_images = array_merge($all_images, $attachment_ids);

if (empty($all_images)) {
    ?>
    <div class="product__column product__column--images">
        <div class="product__images" id="product__images">
            <div class="product__image">
                <?php echo wc_placeholder_img('woocommerce_single'); ?>
            </div>
        </div>
    </div>
    <?php
    return;
}

$visible_thumbs = 6; // Number of visible thumbnails before "+X" indicator
$extra_count = count($all_images) - $visible_thumbs;
?>

<div class="product__column product__column--images">
    <div class="product__images" id="product__images">

        <!-- Main Slider -->
        <div class="product__image swiper" data-gallery-main>
            <!-- Navigation arrows -->
            <?php if (count($all_images) > 1) : ?>
                <div class="slick-prev slick-arrow" data-gallery-prev>
                    <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24">
                        <path d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M15.41 7.41L14 6l-6 6 6 6 1.41-1.41L10.83 12z"></path>
                    </svg>
                </div>
            <?php endif; ?>

            <div class="swiper-wrapper">
                <?php foreach ($all_images as $index => $image_id) :
                    $full_src = wp_get_attachment_image_url($image_id, 'full');
                    $large_src = wp_get_attachment_image_url($image_id, 'woocommerce_single');
                    $alt = get_post_meta($image_id, '_wp_attachment_image_alt', true) ?: $product->get_name();
                ?>
                    <span data-id="<?php echo esc_attr($index); ?>"
                          data-src="<?php echo esc_url($full_src); ?>"
                          class="swiper-slide">
                        <img src="<?php echo esc_url($large_src); ?>"
                             alt="<?php echo esc_attr($alt); ?>"
                             loading="<?php echo $index === 0 ? 'eager' : 'lazy'; ?>">
                    </span>
                <?php endforeach; ?>
            </div>

            <?php if (count($all_images) > 1) : ?>
                <div class="slick-next slick-arrow" data-gallery-next>
                    <svg xmlns="http://www.w3.org/2000/svg" height="24" viewBox="0 0 24 24" width="24">
                        <path d="M0 0h24v24H0z" fill="none"></path>
                        <path d="M10 6L8.59 7.41 13.17 12l-4.58 4.59L10 18l6-6z"></path>
                    </svg>
                </div>
            <?php endif; ?>
        </div>

        <!-- Thumbnail Strip -->
        <?php if (count($all_images) > 1) : ?>
            <div class="product__thumb" id="product__thumb">
                <?php foreach ($all_images as $index => $image_id) :
                    $thumb_src = wp_get_attachment_image_url($image_id, 'thumbnail');
                    $alt = get_post_meta($image_id, '_wp_attachment_image_alt', true) ?: $product->get_name();

                    // Determine visibility and data-count
                    $is_hidden = $index >= $visible_thumbs;
                    $show_count = ($index === $visible_thumbs - 1 && $extra_count > 0);
                    $classes = $is_hidden ? 'thumb--hide' : '';
                    if ($index === 0) {
                        $classes .= ' thumb--active';
                    }
                ?>
                    <span data-id="<?php echo esc_attr($index); ?>"
                          <?php if ($show_count) : ?>data-count="+<?php echo esc_attr($extra_count); ?>"<?php endif; ?>
                          class="<?php echo esc_attr(trim($classes)); ?>">
                        <img src="<?php echo esc_url($thumb_src); ?>"
                             alt="<?php echo esc_attr($alt); ?>"
                             loading="lazy">
                    </span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

    </div>
</div>
