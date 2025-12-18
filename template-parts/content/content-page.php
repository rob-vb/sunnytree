<?php
/**
 * Template Part: Page Content
 *
 * @package SunnyTree
 */

declare(strict_types=1);
?>

<div id="post-<?php the_ID(); ?>" <?php post_class('page-content'); ?>>
    <header class="page-content__header">
        <?php the_title('<h1 class="page-content__title">', '</h1>'); ?>
    </header>

    <div class="page-content__body">
        <?php the_content(); ?>
    </div>
</div>
