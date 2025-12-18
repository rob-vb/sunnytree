<?php
/**
 * Sample Block - Server-side Render
 *
 * Comprehensive reference demonstrating all attribute types in PHP rendering.
 *
 * @package SunnyTree
 *
 * Available variables:
 * @var array    $attributes Block attributes from block.json
 * @var string   $content    Inner block content (for blocks with InnerBlocks)
 * @var WP_Block $block      Block instance with context and parsed block data
 */

declare(strict_types=1);

// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit;
}

// =============================================================================
// EXTRACT ATTRIBUTES WITH DEFAULTS
// =============================================================================

// String attributes
$title              = $attributes['title'] ?? '';
$content_text       = $attributes['content'] ?? '';
$rich_content       = $attributes['richContent'] ?? '';
$custom_class_name  = $attributes['customClassName'] ?? '';

// Boolean attributes
$show_title   = $attributes['showTitle'] ?? true;
$show_content = $attributes['showContent'] ?? true;

// Number attributes
$columns   = $attributes['columns'] ?? 1;
$gap       = $attributes['gap'] ?? 20;
$icon_size = $attributes['iconSize'] ?? 24;

// String with enum
$aspect_ratio          = $attributes['aspectRatio'] ?? 'auto';
$button_style          = $attributes['buttonStyle'] ?? 'primary';
$vertical_alignment    = $attributes['verticalAlignment'] ?? 'top';
$horizontal_alignment  = $attributes['horizontalAlignment'] ?? 'left';
$icon_name             = $attributes['iconName'] ?? 'star';

// Media attributes
$media_id  = $attributes['mediaId'] ?? 0;
$media_url = $attributes['mediaUrl'] ?? '';
$media_alt = $attributes['mediaAlt'] ?? '';

// Link attributes
$link_url    = $attributes['linkUrl'] ?? '';
$link_target = $attributes['linkTarget'] ?? '_self';
$link_rel    = $attributes['linkRel'] ?? '';

// Button attributes
$button_text = $attributes['buttonText'] ?? '';

// Array attributes
$selected_items = $attributes['selectedItems'] ?? [];
$items          = $attributes['items'] ?? [];

// Object attributes (nested)
$settings = $attributes['settings'] ?? [
    'autoplay' => false,
    'speed'    => 500,
    'loop'     => true,
];

// Date attribute
$date = $attributes['date'] ?? '';

// =============================================================================
// BUILD CSS CLASSES
// =============================================================================

$classes = [
    'sunnytree-sample-block',
    'has-columns-' . $columns,
    'valign-' . $vertical_alignment,
    'halign-' . $horizontal_alignment,
];

if (! empty($custom_class_name)) {
    $classes[] = $custom_class_name;
}

// =============================================================================
// BUILD INLINE STYLES (CSS Custom Properties)
// =============================================================================

$inline_styles = [
    '--columns' => $columns,
    '--gap'     => $gap . 'px',
];

if ('auto' !== $aspect_ratio) {
    $inline_styles['--aspect-ratio'] = $aspect_ratio;
}

$style_string = '';
foreach ($inline_styles as $property => $value) {
    $style_string .= esc_attr($property) . ':' . esc_attr($value) . ';';
}

// =============================================================================
// GET BLOCK WRAPPER ATTRIBUTES
// This function applies:
// - Block supports (colors, typography, spacing, borders, etc.)
// - Custom classes
// - Alignment classes
// - Anchor ID
// =============================================================================

$wrapper_attributes = get_block_wrapper_attributes([
    'class' => implode(' ', $classes),
    'style' => $style_string,
    // Data attributes for JavaScript interactivity
    'data-autoplay' => $settings['autoplay'] ? 'true' : 'false',
    'data-speed'    => (string) $settings['speed'],
    'data-loop'     => $settings['loop'] ? 'true' : 'false',
]);

// =============================================================================
// EARLY RETURN IF NO CONTENT
// =============================================================================

$has_content = ! empty($title) || ! empty($content_text) || ! empty($rich_content) || ! empty($media_url);

if (! $has_content) {
    return;
}

// =============================================================================
// HELPER: Get Dashicon SVG
// =============================================================================

$icons = [
    'star'        => '<path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/>',
    'heart'       => '<path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>',
    'check'       => '<path d="M9 16.17L4.83 12l-1.42 1.41L9 19 21 7l-1.41-1.41z"/>',
    'arrow-right' => '<path d="M12 4l-1.41 1.41L16.17 11H4v2h12.17l-5.58 5.59L12 20l8-8z"/>',
    'info'        => '<path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm1 15h-2v-6h2v6zm0-8h-2V7h2v2z"/>',
    'warning'     => '<path d="M1 21h22L12 2 1 21zm12-3h-2v-2h2v2zm0-4h-2v-4h2v4z"/>',
];

$icon_svg = isset($icons[$icon_name]) ? $icons[$icon_name] : $icons['star'];

// =============================================================================
// RENDER OUTPUT
// =============================================================================
?>

<div <?php echo $wrapper_attributes; ?>>
    <div class="sunnytree-sample-block__inner">

        <?php // ============================================= ?>
        <?php // MEDIA SECTION ?>
        <?php // ============================================= ?>
        <?php if (! empty($media_url)) : ?>
            <div class="sunnytree-sample-block__media">
                <?php if (! empty($link_url)) : ?>
                    <a
                        href="<?php echo esc_url($link_url); ?>"
                        target="<?php echo esc_attr($link_target); ?>"
                        <?php echo ! empty($link_rel) ? 'rel="' . esc_attr($link_rel) . '"' : ''; ?>
                        class="sunnytree-sample-block__media-link"
                    >
                <?php endif; ?>

                <img
                    src="<?php echo esc_url($media_url); ?>"
                    alt="<?php echo esc_attr($media_alt); ?>"
                    class="sunnytree-sample-block__image"
                    <?php if ('auto' !== $aspect_ratio) : ?>
                        style="aspect-ratio: <?php echo esc_attr($aspect_ratio); ?>; object-fit: cover;"
                    <?php endif; ?>
                />

                <?php if (! empty($link_url)) : ?>
                    </a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php // ============================================= ?>
        <?php // CONTENT SECTION ?>
        <?php // ============================================= ?>
        <div class="sunnytree-sample-block__content-wrapper">

            <?php // Icon ?>
            <?php if (! empty($icon_name)) : ?>
                <span
                    class="sunnytree-sample-block__icon"
                    style="width: <?php echo (int) $icon_size; ?>px; height: <?php echo (int) $icon_size; ?>px;"
                >
                    <svg
                        xmlns="http://www.w3.org/2000/svg"
                        viewBox="0 0 24 24"
                        fill="currentColor"
                        width="<?php echo (int) $icon_size; ?>"
                        height="<?php echo (int) $icon_size; ?>"
                        aria-hidden="true"
                    >
                        <?php
                        // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped -- SVG path is from trusted source
                        echo $icon_svg;
                        ?>
                    </svg>
                </span>
            <?php endif; ?>

            <?php // Title ?>
            <?php if ($show_title && ! empty($title)) : ?>
                <h3 class="sunnytree-sample-block__title">
                    <?php echo esc_html($title); ?>
                </h3>
            <?php endif; ?>

            <?php // Content (plain text with line breaks) ?>
            <?php if ($show_content && ! empty($content_text)) : ?>
                <div class="sunnytree-sample-block__content">
                    <?php echo wp_kses_post(nl2br($content_text)); ?>
                </div>
            <?php endif; ?>

            <?php // Rich Content (HTML from RichText with source: html) ?>
            <?php if (! empty($rich_content)) : ?>
                <div class="sunnytree-sample-block__rich-content">
                    <?php echo wp_kses_post($rich_content); ?>
                </div>
            <?php endif; ?>

            <?php // Date display ?>
            <?php if (! empty($date)) : ?>
                <time
                    class="sunnytree-sample-block__date"
                    datetime="<?php echo esc_attr($date); ?>"
                >
                    <?php
                    $date_obj = new DateTime($date);
                    echo esc_html($date_obj->format(get_option('date_format') . ' ' . get_option('time_format')));
                    ?>
                </time>
            <?php endif; ?>

            <?php // Button ?>
            <?php if (! empty($button_text)) : ?>
                <div class="sunnytree-sample-block__button-wrapper">
                    <?php if (! empty($link_url)) : ?>
                        <a
                            href="<?php echo esc_url($link_url); ?>"
                            target="<?php echo esc_attr($link_target); ?>"
                            <?php echo ! empty($link_rel) ? 'rel="' . esc_attr($link_rel) . '"' : ''; ?>
                            class="sunnytree-sample-block__button sunnytree-sample-block__button--<?php echo esc_attr($button_style); ?>"
                        >
                            <?php echo esc_html($button_text); ?>
                        </a>
                    <?php else : ?>
                        <span class="sunnytree-sample-block__button sunnytree-sample-block__button--<?php echo esc_attr($button_style); ?>">
                            <?php echo esc_html($button_text); ?>
                        </span>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        </div>

        <?php // ============================================= ?>
        <?php // TAGS SECTION (Array attribute) ?>
        <?php // ============================================= ?>
        <?php if (! empty($selected_items)) : ?>
            <div class="sunnytree-sample-block__tags">
                <?php foreach ($selected_items as $tag) : ?>
                    <span class="sunnytree-sample-block__tag">
                        <?php echo esc_html($tag); ?>
                    </span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <?php // ============================================= ?>
        <?php // ITEMS SECTION (Array of objects) ?>
        <?php // ============================================= ?>
        <?php if (! empty($items)) : ?>
            <ul class="sunnytree-sample-block__items">
                <?php foreach ($items as $item) : ?>
                    <li class="sunnytree-sample-block__item">
                        <?php if (isset($item['title'])) : ?>
                            <strong><?php echo esc_html($item['title']); ?></strong>
                        <?php endif; ?>
                        <?php if (isset($item['description'])) : ?>
                            <span><?php echo esc_html($item['description']); ?></span>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

    </div>

    <?php // ============================================= ?>
    <?php // DEBUG OUTPUT (Development only) ?>
    <?php // ============================================= ?>
    <?php if (defined('WP_DEBUG') && WP_DEBUG && current_user_can('manage_options')) : ?>
        <details class="sunnytree-sample-block__debug">
            <summary><?php esc_html_e('Debug: Block Attributes', 'sunnytree'); ?></summary>
            <pre><?php echo esc_html(wp_json_encode($attributes, JSON_PRETTY_PRINT)); ?></pre>
        </details>
    <?php endif; ?>

</div>
