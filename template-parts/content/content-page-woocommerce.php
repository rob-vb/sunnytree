<?php
/**
 * Template Part: WooCommerce Page Content (Cart, Checkout, My Account)
 *
 * @package SunnyTree
 */

declare(strict_types=1);
?>

<div id="post-<?php the_ID(); ?>" <?php post_class('page-content'); ?>>
    <header class="page-content__header">
        <div class="container">
            <?php the_title('<h1 class="page-content__title">', '</h1>'); ?>
        </div>
    </header>

    <div class="page-content__body">
        <div class="container">
            <?php the_content(); ?>
        </div>
    </div>
</div>
