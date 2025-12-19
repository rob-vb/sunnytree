<?php
/**
 * Single Product Image Gallery
 *
 * Uses SwiperJS thumbs gallery pattern with PhotoSwipe lightbox
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
?>

<div class="product__column product__column--images">
    <div class="product__images" id="product__images">

        <!-- Main Slider -->
        <div class="swiper product__gallery-main" id="product__gallery">
            <div class="swiper-wrapper">
                <?php foreach ($all_images as $index => $image_id) :
                    $full_src = wp_get_attachment_image_url($image_id, 'full');
                    $large_src = wp_get_attachment_image_url($image_id, 'woocommerce_single');
                    $full_meta = wp_get_attachment_metadata($image_id);
                    $alt = get_post_meta($image_id, '_wp_attachment_image_alt', true) ?: $product->get_name();
                    $width = $full_meta['width'] ?? 1200;
                    $height = $full_meta['height'] ?? 1200;
                ?>
                    <div class="swiper-slide">
                        <a href="<?php echo esc_url($full_src); ?>"
                           data-pswp-width="<?php echo esc_attr($width); ?>"
                           data-pswp-height="<?php echo esc_attr($height); ?>">
                            <img src="<?php echo esc_url($large_src); ?>"
                                 alt="<?php echo esc_attr($alt); ?>"
                                 loading="<?php echo $index === 0 ? 'eager' : 'lazy'; ?>">
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
            <?php if (count($all_images) > 1) : ?>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            <?php endif; ?>
        </div>

        <!-- Thumbnail Slider -->
        <?php if (count($all_images) > 1) : ?>
            <div thumbsSlider="" class="swiper product__gallery-thumbs">
                <div class="swiper-wrapper">
                    <?php foreach ($all_images as $image_id) :
                        $thumb_src = wp_get_attachment_image_url($image_id, 'thumbnail');
                        $alt = get_post_meta($image_id, '_wp_attachment_image_alt', true) ?: $product->get_name();
                    ?>
                        <div class="swiper-slide">
                            <img src="<?php echo esc_url($thumb_src); ?>"
                                 alt="<?php echo esc_attr($alt); ?>"
                                 loading="lazy">
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>
