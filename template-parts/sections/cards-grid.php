<?php
/**
 * Template Part: Cards Grid
 *
 * @package SunnyTree
 */

declare(strict_types=1);

$cards = [
    [
        'title'       => __('Feature One', 'sunnytree'),
        'description' => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore.', 'sunnytree'),
    ],
    [
        'title'       => __('Feature Two', 'sunnytree'),
        'description' => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore.', 'sunnytree'),
    ],
    [
        'title'       => __('Feature Three', 'sunnytree'),
        'description' => __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore.', 'sunnytree'),
    ],
];
?>

<section class="pattern-cards">
    <div class="pattern-cards__grid">
        <?php foreach ($cards as $card) : ?>
            <div class="pattern-card">
                <h3 class="pattern-card__title"><?php echo esc_html($card['title']); ?></h3>
                <p class="pattern-card__description"><?php echo esc_html($card['description']); ?></p>
            </div>
        <?php endforeach; ?>
    </div>
</section>
