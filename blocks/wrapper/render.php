<?php
/**
 * Wrapper Block - Server-side Render
 *
 * @package SunnyTree
 *
 * @var array    $attributes Block attributes
 * @var string   $content    Inner block content
 * @var WP_Block $block      Block instance
 */

declare(strict_types=1);

if (! defined('ABSPATH')) {
    exit;
}

$wrapper_attributes = get_block_wrapper_attributes([
    'class' => 'sunny-wrapper',
]);
?>
<div <?php echo $wrapper_attributes; ?>>
    <div class="container">
        <?php echo $content; ?>
    </div>
</div>
