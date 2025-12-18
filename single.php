<?php
/**
 * Single Post Template
 *
 * @package SunnyTree
 */

declare(strict_types=1);

get_header();
?>

<div class="content-area content-area--single">
    <?php
    while (have_posts()) :
        the_post();
        get_template_part('template-parts/content/content', 'single');
    endwhile;
    ?>
</div>

<?php
get_footer();
