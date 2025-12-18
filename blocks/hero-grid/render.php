<?php
/**
 * Hero Grid Block - Server-side Render
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

// Section 1 attributes
$section1_image_url   = $attributes['section1ImageUrl'] ?? '';
$section1_image_alt   = $attributes['section1ImageAlt'] ?? '';
$section1_title       = $attributes['section1Title'] ?? '';
$section1_description = $attributes['section1Description'] ?? '';
$section1_link_url    = $attributes['section1LinkUrl'] ?? '';
$section1_link_text   = $attributes['section1LinkText'] ?? '';
$section1_link_target = $attributes['section1LinkTarget'] ?? '_self';

// Section 2 attributes
$section2_image_url = $attributes['section2ImageUrl'] ?? '';
$section2_image_alt = $attributes['section2ImageAlt'] ?? '';

// Section 3 attributes
$section3_image_url   = $attributes['section3ImageUrl'] ?? '';
$section3_image_alt   = $attributes['section3ImageAlt'] ?? '';
$section3_title       = $attributes['section3Title'] ?? '';
$section3_description = $attributes['section3Description'] ?? '';
$section3_link_url    = $attributes['section3LinkUrl'] ?? '';
$section3_link_text   = $attributes['section3LinkText'] ?? '';
$section3_link_target = $attributes['section3LinkTarget'] ?? '_self';

// Build wrapper attributes
$wrapper_attributes = get_block_wrapper_attributes([
    'class' => 'sunny-banner sunny-banner-weergave-3',
]);

// Link rel attribute for external links
$link_rel = '_blank' === $section1_link_target ? 'noopener noreferrer' : '';
$link3_rel = '_blank' === $section3_link_target ? 'noopener noreferrer' : '';
?>

<div <?php echo $wrapper_attributes; ?>>
    <div class="sunny-banner-wrapper">
        <div class="sunny-main-banner-wrapper">

            <!-- Section 1: Main Banner (Large Left) -->
            <div class="sunny-main-banner">
                <?php if (! empty($section1_link_url)) : ?>
                <a
                    href="<?php echo esc_url($section1_link_url); ?>"
                    target="<?php echo esc_attr($section1_link_target); ?>"
                    <?php echo ! empty($link_rel) ? 'rel="' . esc_attr($link_rel) . '"' : ''; ?>
                >
                <?php endif; ?>

                    <?php if (! empty($section1_image_url)) : ?>
                        <picture>
                            <img
                                src="<?php echo esc_url($section1_image_url); ?>"
                                alt="<?php echo esc_attr($section1_image_alt); ?>"
                            />
                        </picture>
                    <?php endif; ?>

                    <div class="sunny-main-content">
                        <?php if (! empty($section1_title)) : ?>
                            <span class="sunny-main-banner-title">
                                <?php echo wp_kses_post($section1_title); ?>
                            </span>
                        <?php endif; ?>

                        <?php if (! empty($section1_description)) : ?>
                            <span class="sunny-main-banner-subtitle">
                                <?php echo wp_kses_post($section1_description); ?>
                            </span>
                        <?php endif; ?>

                        <?php if (! empty($section1_link_text)) : ?>
                            <span class="sunny-main-banner-button">
                                <?php echo esc_html($section1_link_text); ?>
                            </span>
                        <?php endif; ?>
                    </div>

                <?php if (! empty($section1_link_url)) : ?>
                </a>
                <?php endif; ?>
            </div>

        </div>

        <div class="sunny-sub-banner-wrapper">

            <!-- Section 2: Top Right (Image Only) -->
            <div class="sunny-sub-banner">
                <?php if (! empty($section2_image_url)) : ?>
                    <picture>
                        <img
                            src="<?php echo esc_url($section2_image_url); ?>"
                            alt="<?php echo esc_attr($section2_image_alt); ?>"
                        />
                    </picture>
                <?php endif; ?>
                <div class="sunny-sub-content"></div>
            </div>

            <!-- Section 3: Bottom Right -->
            <div class="sunny-sub-banner">
                <?php if (! empty($section3_link_url)) : ?>
                <a
                    href="<?php echo esc_url($section3_link_url); ?>"
                    target="<?php echo esc_attr($section3_link_target); ?>"
                    <?php echo ! empty($link3_rel) ? 'rel="' . esc_attr($link3_rel) . '"' : ''; ?>
                >
                <?php endif; ?>

                    <?php if (! empty($section3_image_url)) : ?>
                        <picture>
                            <img
                                src="<?php echo esc_url($section3_image_url); ?>"
                                alt="<?php echo esc_attr($section3_image_alt); ?>"
                            />
                        </picture>
                    <?php endif; ?>

                    <div class="sunny-sub-content">
                        <?php if (! empty($section3_title)) : ?>
                            <span class="sunny-sub-banner-title">
                                <?php echo wp_kses_post($section3_title); ?>
                            </span>
                        <?php endif; ?>

                        <?php if (! empty($section3_description)) : ?>
                            <span class="sunny-sub-banner-subtitle">
                                <?php echo wp_kses_post($section3_description); ?>
                            </span>
                        <?php endif; ?>
                    </div>

                <?php if (! empty($section3_link_url)) : ?>
                </a>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>
