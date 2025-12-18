<?php
/**
 * Page Template
 *
 * @package SunnyTree
 */

declare(strict_types=1);

get_header();
?>

<div class="content-area content-area--page">
    <?php
    while (have_posts()) :
        the_post();
        get_template_part('template-parts/content/content', 'page');
    endwhile;
    ?>
</div>

<?php
get_footer();
