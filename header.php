<?php
/**
 * Header Template
 *
 * @package SunnyTree
 */

declare(strict_types=1);

use function SunnyTree\TemplateTags\get_settings;
use function SunnyTree\TemplateTags\get_usps;
use function SunnyTree\TemplateTags\get_cart_count;
use function SunnyTree\TemplateTags\get_product_categories;
use function SunnyTree\TemplateTags\render_icon;
use function SunnyTree\TemplateTags\render_stars;
use function SunnyTree\TemplateTags\get_account_url;
use function SunnyTree\TemplateTags\get_cart_url;
use function SunnyTree\TemplateTags\get_wishlist_url;
use function SunnyTree\TemplateTags\get_wishlist_count;

$settings = get_settings();
$usps = get_usps();
$categories = get_product_categories();
$cart_count = get_cart_count();
$wishlist_count = get_wishlist_count();
?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" as="style" onload="this.onload=null;this.rel='stylesheet'" href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&amp;display=swap">
    <link rel="stylesheet" as="style" onload="this.onload=null;this.rel='stylesheet'" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&amp;display=swap">


    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php get_template_part('template-parts/sections/header'); ?>


<main id="main-content" class="site-main">
