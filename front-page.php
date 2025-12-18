<?php
/**
 * Front Page Template
 *
 * @package SunnyTree
 */

declare(strict_types=1);

get_header();
?>

<div class="content-area content-area--front-page">
    <?php
    while (have_posts()) :
        the_post();
        the_content();
    endwhile;
    ?>
</div>

<?php
get_footer();
