<?php
/**
 * Blocks Registration
 *
 * @package SunnyTree
 */

declare(strict_types=1);

namespace SunnyTree\Blocks;

// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit;
}

/**
 * Register custom block category
 *
 * @param array $categories Existing block categories
 * @return array Modified block categories
 */
function register_block_category(array $categories): array
{
    // Add our custom category at the beginning
    array_unshift($categories, [
        'slug'  => 'sunnytree',
        'title' => __('SunnyTree', 'sunnytree'),
        'icon'  => 'palmtree', // Dashicon name or null
    ]);

    return $categories;
}
add_filter('block_categories_all', __NAMESPACE__ . '\register_block_category', 10, 1);

/**
 * Register all blocks from the blocks directory
 */
function register_blocks(): void
{
    $blocks_dir = SUNNYTREE_DIR . '/blocks';

    // Get all directories in the blocks folder (each directory is a block)
    $block_folders = glob($blocks_dir . '/*', GLOB_ONLYDIR);

    if (! $block_folders) {
        return;
    }

    foreach ($block_folders as $block_folder) {
        $block_json = $block_folder . '/block.json';

        // Only register if block.json exists
        if (file_exists($block_json)) {
            register_block_type($block_folder);
        }
    }
}
add_action('init', __NAMESPACE__ . '\register_blocks');

/**
 * Enqueue block editor assets
 */
function enqueue_block_editor_assets(): void
{
    $blocks_dir = SUNNYTREE_DIR . '/blocks';
    $blocks_uri = SUNNYTREE_URI . '/blocks';

    // Get all block directories
    $block_folders = glob($blocks_dir . '/*', GLOB_ONLYDIR);

    if (! $block_folders) {
        return;
    }

    foreach ($block_folders as $block_folder) {
        $block_name = basename($block_folder);
        $editor_script = $block_folder . '/editor.js';
        $editor_style = $block_folder . '/editor.css';

        // Enqueue editor script if exists and not already registered via block.json
        if (file_exists($editor_script)) {
            $asset_file = $block_folder . '/editor.asset.php';
            $dependencies = ['wp-blocks', 'wp-element', 'wp-editor', 'wp-components', 'wp-i18n'];
            $version = SUNNYTREE_VERSION;

            if (file_exists($asset_file)) {
                $asset = require $asset_file;
                $dependencies = $asset['dependencies'] ?? $dependencies;
                $version = $asset['version'] ?? $version;
            }

            wp_enqueue_script(
                "sunnytree-{$block_name}-editor",
                $blocks_uri . "/{$block_name}/editor.js",
                $dependencies,
                $version,
                true
            );
        }

        // Enqueue editor style if exists
        if (file_exists($editor_style)) {
            wp_enqueue_style(
                "sunnytree-{$block_name}-editor",
                $blocks_uri . "/{$block_name}/editor.css",
                ['wp-edit-blocks'],
                SUNNYTREE_VERSION
            );
        }
    }
}
add_action('enqueue_block_editor_assets', __NAMESPACE__ . '\enqueue_block_editor_assets');
