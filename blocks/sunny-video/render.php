<?php
/**
 * Sunny Video Block - Server-side Render
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

// Extract attributes with defaults
$video_url        = $attributes['videoUrl'] ?? '';
$poster_image_url = $attributes['posterImageUrl'] ?? '';
$heading          = $attributes['heading'] ?? '';
$content_html     = $attributes['content'] ?? '';
$button_text      = $attributes['buttonText'] ?? '';
$button_url       = $attributes['buttonUrl'] ?? '';
$button_target    = $attributes['buttonTarget'] ?? '_self';

// Early return if no content
$has_content = ! empty($video_url) || ! empty($heading) || ! empty($content_html);
if (! $has_content) {
    return;
}

// Link rel for external links
$link_rel = '_blank' === $button_target ? 'noopener noreferrer' : '';
?>

<div class="sunny-premium-content-main-wrapper">
    <div class="sunny-premium-content-image">
        <?php if (! empty($video_url)) : ?>
            <video width="100%" loop autoplay muted controls playsinline<?php echo ! empty($poster_image_url) ? ' poster="' . esc_url($poster_image_url) . '"' : ''; ?>>
                <source src="<?php echo esc_url($video_url); ?>" type="video/mp4">
            </video>
        <?php endif; ?>
    </div>

    <div class="sunny-premium-content-wrapper">
        <?php if (! empty($heading)) : ?>
            <h3 class="sunny-premium-content-titel"><?php echo esc_html($heading); ?></h3>
        <?php endif; ?>

        <?php if (! empty($content_html)) : ?>
            <div class="sunny-premium-content-text"><?php echo wp_kses_post($content_html); ?></div>
        <?php endif; ?>

        <?php if (! empty($button_text) && ! empty($button_url)) : ?>
            <a href="<?php echo esc_url($button_url); ?>" target="<?php echo esc_attr($button_target); ?>"<?php echo ! empty($link_rel) ? ' rel="' . esc_attr($link_rel) . '"' : ''; ?> class="sunny-premium-content-button"><?php echo esc_html($button_text); ?></a>
        <?php elseif (! empty($button_text)) : ?>
            <span class="sunny-premium-content-button"><?php echo esc_html($button_text); ?></span>
        <?php endif; ?>
    </div>
</div>
