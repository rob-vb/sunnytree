<?php
/**
 * Category Grid Block - Server-side Render
 *
 * @package SunnyTree
 *
 * @var array    $attributes Block attributes from block.json
 * @var string   $content    Inner block content
 * @var WP_Block $block      Block instance
 */

declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

// Build wrapper attributes
$wrapper_attributes = get_block_wrapper_attributes([
    'class' => 'sunny-categories',
]);
?>

<div <?php echo $wrapper_attributes; ?>>
    <div class="row">
        <?php for ($i = 1; $i <= 6; $i++) :
            $prefix = 'section' . $i;

            $image_url   = $attributes[$prefix . 'ImageUrl'] ?? '';
            $image_alt   = $attributes[$prefix . 'ImageAlt'] ?? '';
            $title       = $attributes[$prefix . 'Title'] ?? '';
            $link_url    = $attributes[$prefix . 'LinkUrl'] ?? '';
            $subtitle    = $attributes[$prefix . 'Subtitle'] ?? 'bekijk ons aanbod';
            $link_target = $attributes[$prefix . 'LinkTarget'] ?? '_self';

            // Skip empty sections
            if (empty($title) && empty($link_url)) {
                continue;
            }

            $link_rel = '_blank' === $link_target ? 'noopener noreferrer' : '';
        ?>
            <div class="col-xs-6 col-sm-6 col-md-4">
                <a href="<?php echo esc_url($link_url); ?>"
                    class="sunny-cat-block"
                    target="<?php echo esc_attr($link_target); ?>"
                    <?php echo ! empty($link_rel) ? 'rel="' . esc_attr($link_rel) . '"' : ''; ?>>
                    <span class="sunny-cat-img">
                        <?php if (! empty($image_url)) : ?>
                            <img src="<?php echo esc_url($image_url); ?>"
                                    alt="<?php echo esc_attr($image_alt); ?>"
                                    class="sunny-cat-image" />
                        <?php endif; ?>
                    </span>
                    <div class="sunny-flex-container">
                        <span class="sunny-cat-title" style="height: 46px;">
                            <?php echo esc_html($title); ?>
                            <span class="sunny-aanbod-sub-title"><?php echo esc_html($subtitle); ?></span>
                        </span>
                        <svg class="sunny-product-arrow" width="20" height="17" viewBox="0 0 20 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M1 9.42589H16.586L11.222 14.7899C10.8315 15.1804 10.8315 15.8136 11.2218 16.2039C11.6123 16.5944 12.2457 16.5944 12.6362 16.2039L19.707 9.13289C19.7535 9.08639 19.795 9.03514 19.8315 8.98064C19.8482 8.95539 19.86 8.92814 19.8745 8.90189C19.891 8.87089 19.91 8.84139 19.9233 8.80864C19.9375 8.77489 19.9455 8.73989 19.9555 8.70489C19.9637 8.67714 19.9745 8.65064 19.9802 8.62214C19.9932 8.55714 20 8.49164 20 8.42589C20 8.42514 19.9998 8.42439 19.9998 8.42364C19.9995 8.35889 19.993 8.29389 19.9802 8.23014C19.9742 8.20014 19.963 8.17239 19.9543 8.14289C19.9445 8.10964 19.937 8.07589 19.9235 8.04364C19.909 8.00889 19.8895 7.97739 19.8715 7.94464C19.858 7.92014 19.8472 7.89514 19.8318 7.87164C19.7952 7.81639 19.7532 7.76489 19.7065 7.71814L12.636 0.647887C12.2455 0.257387 11.6123 0.257387 11.2218 0.647637C10.8313 1.03814 10.8313 1.67139 11.2218 2.06214L16.5858 7.42589H1C0.44775 7.42589 0 7.87364 0 8.42589C0 8.97814 0.44775 9.42589 1 9.42589Z" fill="#95976F"></path>
                        </svg>
                    </div>
                </a>
            </div>
        <?php endfor; ?>
    </div>
</div>