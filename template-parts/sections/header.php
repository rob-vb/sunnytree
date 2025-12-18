<div class="sunny-menu-desk-bg"></div>
<header>
    <div class="sunny-topbar">
        <div class="container">
<?php
            $usps = \SunnyTree\TemplateTags\get_usps();
            if (! empty($usps)) :
            ?>
            <ul class="sunny-topbar-usp">
                <?php foreach ($usps as $usp) : ?>
                    <li>
                        <?php if (! empty($usp['link'])) : ?>
                            <a href="<?php echo esc_url($usp['link']); ?>">
                        <?php endif; ?>
                        <?php \SunnyTree\TemplateTags\render_icon($usp['icon'], ['stroke-width' => '1.5']); ?>
                        <span><?php echo esc_html($usp['text']); ?></span>
                        <?php if (! empty($usp['link'])) : ?>
                            </a>
                        <?php endif; ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
            <div class="sunny-service-links">
                <a class="sunny-online-link" href="/contact">
                    <span class="sunny-online open"></span>
                    <span class="sunny-online-txt">Klantenservice</span>
                </a>
            </div>
            <!-- <div class="sunny-language">
                <div class="sunny-selected-lan">
                    <img src="https://sunnytree.nl/Files/10/334000/334323/Protom/3039936/Media/nl.svg" alt="NL">
                    <span>NL</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        class="feather feather-chevron-down">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </div>
                <div class="sunny-lan-select">
                    <ul>
                        <li>
                            <a href="https://sunnytree.de/">
                                <img src="https://sunnytree.nl/Files/10/334000/334323/Protom/3039936/Media/de.svg"
                                    alt="DE">
                                <span>DE</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div> -->
        </div>
    </div>

    <div class="sunny-logo-area">
        <div class="container">
            <div class="sunny-mobile-btn-wrapper">
                <div id="responsive-menu-button" class="hamburger sunny-hamburger--spin">
                    <span class="sunny-hamburger-box">
                        <span class="sunny-hamburger-inner"></span>
                    </span>
                </div>
            </div>
            <a class="sunny-logo" href="<?php echo esc_url(home_url('/')); ?>">
                <img height="50" width="194"
                    src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/sunnytree_logo.svg'); ?>"
                    alt="<?php esc_attr_e('logo Sunny Tree', 'sunnytree'); ?>" title="<?php esc_attr_e('logo Sunny Tree', 'sunnytree'); ?>">
            </a>
            <!-- <a target="_blank" href="https://g.co/kgs/iBvom8k" class="sunny-review">
                <span class="sunny-r-number">5,0/5</span>
                <span class="sunny-r-stars">
                    <img height="15" width="105"
                        src="https://sunnytree.nl/Files/10/334000/334323/Protom/3039936/Media/stars.svg" alt="stars"
                        title="stars">
                </span>
                <span class="sunny-r-amount">99 Reviews</span>
            </a> -->
            <form class="sunny-desktop-search" action="<?php echo esc_url(home_url('/')); ?>" method="get" role="search">
                <input type="hidden" name="post_type" value="product">
                <input class="sunny-search-input" type="text" name="s" id="SearchField" placeholder="Zoeken naar ..." value="<?php echo esc_attr(get_search_query()); ?>">
                <button type="submit" class="sunny-search-icon" aria-label="<?php esc_attr_e('Zoeken', 'sunnytree'); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"></path>
                    </svg>
                </button>
            </form>
            <div class="sunny-main-icons">
                <span class="sunny-m-search">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"></path>
                    </svg>
                </span>
                <a class="sunny-login" href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z">
                        </path>
                    </svg>
                </a>
                <!-- <a class="sunny-wishlist" href="/website/index.php?Show=CustomerWishlistLogin">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12Z">
                        </path>
                    </svg>
                </a> -->
                <div id="SmallBasket">
                    <a href="<?php echo esc_url(wc_get_cart_url()); ?>">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M2.25 3h1.386c.51 0 .955.343 1.087.835l.383 1.437M7.5 14.25a3 3 0 0 0-3 3h15.75m-12.75-3h11.218c1.121-2.3 2.1-4.684 2.924-7.138a60.114 60.114 0 0 0-16.536-1.84M7.5 14.25 5.106 5.272M6 20.25a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Zm12.75 0a.75.75 0 1 1-1.5 0 .75.75 0 0 1 1.5 0Z">
                            </path>
                        </svg>
                        <span class="sunny-counter"><?php echo WC()->cart ? WC()->cart->get_cart_contents_count() : 0; ?></span>
                    </a>
                    <div class="SmallBasketWrapper">
                        <div class="m-fcart-container cart-empty">
                            <div class="m-fcart">
                                <div class="m-fcart-wrapper">
                                    <div class="m-fcart-header">
                                        Winkelwagen (0)
                                    </div>
                                    <div class="m-fcart-content">
                                        <p class="no-product">Geen producten</p>
                                    </div>
                                    <div class="m-fcart-subfooter">
                                        <span>Totaal bedrag</span>
                                        <span class="medium">€ 0,00</span>
                                    </div>
                                    <div class="m-fcart-footer">
                                        <a class="sunny-btn sunny-btn-clear c-cart-btn"
                                            href="<?php echo esc_url(wc_get_cart_url()); ?>">Wijzig
                                            winkelwagen</a>
                                        <a class="sunny-btn sunny-btn-green c-cart-btn"
                                            href="<?php echo esc_url(wc_get_checkout_url()); ?>">Bestellen</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="sunny-search">
        <div class="container">
            <form class="sunny-search-input" action="<?php echo esc_url(home_url('/')); ?>" method="get" role="search">
                <input type="hidden" name="post_type" value="product">
                <input class="sunny-search-input" type="text" name="s" id="SearchField2" placeholder="Waar bent u naar op zoek?" value="<?php echo esc_attr(get_search_query()); ?>">
                <button type="submit" class="sunny-search-btn" aria-label="<?php esc_attr_e('Zoeken', 'sunnytree'); ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"></path>
                    </svg>
                </button>
            </form>
            <svg class="feather search-close feather-x" fill="none" stroke="currentColor" stroke-linecap="round"
                stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <line x1="18" x2="6" y1="6" y2="18"></line>
                <line x1="6" x2="18" y1="6" y2="18"></line>
            </svg>
        </div>
        <div class="sunny-search-bg"></div>
    </div>
</header>

<div class="sunny-bg"></div>

<nav class="sunny-main-menu">
    <span class="m-fix"></span>
    <div class="container">
        <ul class="first">
            <li class="sunny-menu-title">Menu</li>
            <li class="sunny-more-li sunny-menu-item">
                <a id="31588785" href="https://sunnytree.nl/olijfbomen" class="sunny-more-arrow">
                    <span class="sunny-arrow-span">Olijfboom</span>
                    <svg class="sunny-next-menu-desk-arrow-super feather feather-chevron-down" fill="none"
                        stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </a>
                <svg class="sunny-next-menu feather feather-chevron-right" fill="none" stroke="currentColor"
                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
                <ul class="second">
                    <div class="container">
                        <li class="sunny-menu-title">Olijfboom</li>
                        <li class="sunny-step-back">
                            <svg class="sunny-back feather feather-chevron-left" fill="none" stroke="currentColor"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <polyline points="15 18 9 12 15 6"></polyline>
                            </svg>
                            <a class="step-back" href="#">Alle categorieën</a>
                        </li>
                        <div class="row">
                            <div class="sunny-desk-left col-md-9">
                                <li><a class="sunny-bold-menu-item" href="https://sunnytree.nl/olijfbomen">Bekijk alle
                                        producten</a></li>
                                <li><a href="https://sunnytree.nl/Bonsai">Bonsai</a></li>
                                <li><a href="https://sunnytree.nl/Pom-Pon">Olijfboom Pom-Pon</a></li>
                                <li><a href="https://sunnytree.nl/Olijfboom-gladde-stam">Olijfboom gladde stam</a></li>
                                <li><a href="https://sunnytree.nl/Multistam-Olijfboom">Multistam Olijfboom</a></li>
                                <li><a href="https://sunnytree.nl/Overige-Olijfbomen">Overige Olijfbomen</a></li>
                            </div>
                            <div class="sunny-desk-right col-md-3">
                                <a href="https://sunnytree.nl/olijfbomen" class="sunny-main-menu-img"
                                    style="background-image: url(https://sunnytree.nl/Files/10/334000/334323//CategoryPhotos/Large/31588785.jpg);">
                                    <span class="sunny-megamenu-img-title">
                                        <span>Bekijk assortiment</span>
                                        <svg fill="none" stroke-width="1.5" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="m6 12h12.5m0 0-6-6m6 6-6 6" stroke="currentColor"
                                                stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </ul>
            </li>
            <li class="sunny-more-li sunny-menu-item">
                <a id="31588788" href="https://sunnytree.nl/Palmbomen" class="sunny-more-arrow">
                    <span class="sunny-arrow-span">Palmboom</span>
                    <svg class="sunny-next-menu-desk-arrow-super feather feather-chevron-down" fill="none"
                        stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </a>
                <svg class="sunny-next-menu feather feather-chevron-right" fill="none" stroke="currentColor"
                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
                <ul class="second">
                    <div class="container">
                        <li class="sunny-menu-title">Palmboom</li>
                        <li class="sunny-step-back">
                            <svg class="sunny-back feather feather-chevron-left" fill="none" stroke="currentColor"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <polyline points="15 18 9 12 15 6"></polyline>
                            </svg>
                            <a class="step-back" href="#">Alle categorieën</a>
                        </li>
                        <div class="row">
                            <div class="sunny-desk-left col-md-9">
                                <li><a class="sunny-bold-menu-item" href="https://sunnytree.nl/Palmbomen">Bekijk alle
                                        producten</a></li>
                                <li><a href="https://sunnytree.nl/Trachycarpus-Fortunei">Trachycarpus Fortunei</a></li>
                                <li><a href="https://sunnytree.nl/Chamaerosunny-Humilis">Chamaerops Humilis</a></li>
                                <li><a href="https://sunnytree.nl/Washingtonia-Robusta">Washingtonia Robusta</a></li>
                                <li><a href="https://sunnytree.nl/Phoenix-Roebelenii">Phoenix Roebelenii</a></li>
                                <li><a href="https://sunnytree.nl/Phoenix-Canariensis">Phoenix Canariensis</a></li>
                                <li><a href="https://sunnytree.nl/Cycas-Revoluta">Cycas Revoluta</a></li>
                                <li><a href="https://sunnytree.nl/Yucca-Filifera">Yucca Filifera</a></li>
                                <li><a href="https://sunnytree.nl/Yucca-Rostrata">Yucca Rostrata</a></li>
                                <li><a href="https://sunnytree.nl/Cordyline-Australis">Cordyline Australis</a></li>
                            </div>
                            <div class="sunny-desk-right col-md-3">
                                <a href="https://sunnytree.nl/Palmbomen" class="sunny-main-menu-img"
                                    style="background-image: url(https://sunnytree.nl/Files/10/334000/334323//CategoryPhotos/Large/31588788.jpg);">
                                    <span class="sunny-megamenu-img-title">
                                        <span>Bekijk assortiment</span>
                                        <svg fill="none" stroke-width="1.5" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="m6 12h12.5m0 0-6-6m6 6-6 6" stroke="currentColor"
                                                stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </ul>
            </li>
            <li class="sunny-more-li sunny-menu-item">
                <a id="31588791" href="https://sunnytree.nl/Citrusbomen-kopen" class="sunny-more-arrow">
                    <span class="sunny-arrow-span">Citrusboom</span>
                    <svg class="sunny-next-menu-desk-arrow-super feather feather-chevron-down" fill="none"
                        stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </a>
                <svg class="sunny-next-menu feather feather-chevron-right" fill="none" stroke="currentColor"
                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
                <ul class="second">
                    <div class="container">
                        <li class="sunny-menu-title">Citrusboom</li>
                        <li class="sunny-step-back">
                            <svg class="sunny-back feather feather-chevron-left" fill="none" stroke="currentColor"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <polyline points="15 18 9 12 15 6"></polyline>
                            </svg>
                            <a class="step-back" href="#">Alle categorieën</a>
                        </li>
                        <div class="row">
                            <div class="sunny-desk-left col-md-9">
                                <li><a class="sunny-bold-menu-item" href="https://sunnytree.nl/Citrusbomen-kopen">Bekijk
                                        alle producten</a></li>
                                <li><a href="https://sunnytree.nl/citroenboom-kopen">Citroenboom</a></li>
                                <li><a href="https://sunnytree.nl/limoenboom-kopen">Limoenboom</a></li>
                                <li><a href="https://sunnytree.nl/Granaatappelboom-kopen">Granaatappelboom</a></li>
                                <li><a href="https://sunnytree.nl/Kumquat-kopen">Kumquat</a></li>
                                <li><a href="https://sunnytree.nl/Grapefruitboom-kopen">Grapefruitboom</a></li>
                                <li><a href="https://sunnytree.nl/sinaasappelboom-kopen">Sinaasappelboom</a></li>
                                <li><a href="https://sunnytree.nl/mandarijnenboom">Mandarijnenboom</a></li>
                            </div>
                            <div class="sunny-desk-right col-md-3">
                                <a href="https://sunnytree.nl/Citrusbomen-kopen" class="sunny-main-menu-img"
                                    style="background-image: url(https://sunnytree.nl/Files/10/334000/334323//CategoryPhotos/Large/31588791.jpg);">
                                    <span class="sunny-megamenu-img-title">
                                        <span>Bekijk assortiment</span>
                                        <svg fill="none" stroke-width="1.5" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="m6 12h12.5m0 0-6-6m6 6-6 6" stroke="currentColor"
                                                stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </ul>
            </li>
            <li class="sunny-menu-item">
                <a id="31588794" href="https://sunnytree.nl/Vijgenbomen" class="">
                    Vijgenboom
                </a>
            </li>
            <li class="sunny-menu-item">
                <a id="31588797" href="https://sunnytree.nl/druivenboom" class="">
                    Druivenboom
                </a>
            </li>
            <li class="sunny-more-li sunny-menu-item">
                <a id="31588800" href="https://sunnytree.nl/Overige-bomen-en-planten" class="sunny-more-arrow">
                    <span class="sunny-arrow-span">Overige Bomen &amp; Planten</span>
                    <svg class="sunny-next-menu-desk-arrow-super feather feather-chevron-down" fill="none"
                        stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </a>
                <svg class="sunny-next-menu feather feather-chevron-right" fill="none" stroke="currentColor"
                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
                <ul class="second">
                    <div class="container">
                        <li class="sunny-menu-title">Overige Bomen &amp; Planten</li>
                        <li class="sunny-step-back">
                            <svg class="sunny-back feather feather-chevron-left" fill="none" stroke="currentColor"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <polyline points="15 18 9 12 15 6"></polyline>
                            </svg>
                            <a class="step-back" href="#">Alle categorieën</a>
                        </li>
                        <div class="row">
                            <div class="sunny-desk-left col-md-9">
                                <li><a class="sunny-bold-menu-item"
                                        href="https://sunnytree.nl/Overige-bomen-en-planten">Bekijk alle producten</a>
                                </li>
                                <li><a href="https://sunnytree.nl/Oleander-kopen">Oleander</a></li>
                                <li><a href="https://sunnytree.nl/Magnolia-kopen">Magnolia</a></li>
                                <li><a href="https://sunnytree.nl/Pinus-Pinea">Pinus Pinea</a></li>
                                <li><a href="https://sunnytree.nl/Callistemon-kopen">Callistemon</a></li>
                                <li><a href="https://sunnytree.nl/Italiaanse-Cipres-Totem">Italiaanse Cipres Totem</a>
                                </li>
                                <li><a href="https://sunnytree.nl/Toscaanse-Jasmijn">Toscaanse Jasmijn</a></li>
                                <li><a href="https://sunnytree.nl/Laurier-kopen">Laurier</a></li>
                                <li><a href="https://sunnytree.nl/Eucalyptus">Eucalyptus</a></li>
                                <li><a href="https://sunnytree.nl/Japanse-Wolmispel">Japanse Wolmispel</a></li>
                                <li><a href="https://sunnytree.nl/Albizia">Albizia</a></li>
                            </div>
                            <div class="sunny-desk-right col-md-3">
                                <a href="https://sunnytree.nl/Overige-bomen-en-planten" class="sunny-main-menu-img"
                                    style="background-image: url(https://sunnytree.nl/Files/10/334000/334323//CategoryPhotos/Large/31588800.jpg);">
                                    <span class="sunny-megamenu-img-title">
                                        <span>Bekijk assortiment</span>
                                        <svg fill="none" stroke-width="1.5" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="m6 12h12.5m0 0-6-6m6 6-6 6" stroke="currentColor"
                                                stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </ul>
            </li>
            <li class="sunny-menu-item">
                <a id="31588803" href="https://sunnytree.nl/Accessoires" class="">
                    Accessoires
                </a>
            </li>
            <li class="sunny-more-li sunny-menu-item">
                <a id="31588806" href="https://sunnytree.nl/Verzorging" class="sunny-more-arrow">
                    <span class="sunny-arrow-span">Verzorging</span>
                    <svg class="sunny-next-menu-desk-arrow-super feather feather-chevron-down" fill="none"
                        stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <polyline points="6 9 12 15 18 9"></polyline>
                    </svg>
                </a>
                <svg class="sunny-next-menu feather feather-chevron-right" fill="none" stroke="currentColor"
                    stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg">
                    <polyline points="9 18 15 12 9 6"></polyline>
                </svg>
                <ul class="second">
                    <div class="container">
                        <li class="sunny-menu-title">Verzorging</li>
                        <li class="sunny-step-back">
                            <svg class="sunny-back feather feather-chevron-left" fill="none" stroke="currentColor"
                                stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <polyline points="15 18 9 12 15 6"></polyline>
                            </svg>
                            <a class="step-back" href="#">Alle categorieën</a>
                        </li>
                        <div class="row">
                            <div class="sunny-desk-left col-md-9">
                                <li><a class="sunny-bold-menu-item" href="https://sunnytree.nl/Verzorging">Bekijk alle
                                        producten</a></li>
                                <li><a href="https://sunnytree.nl/Verzorgingsproducten">Verzorgingsproducten</a></li>
                                <li><a href="https://sunnytree.nl/Verzorgingsinformatie">Verzorgingsinformatie</a></li>
                            </div>
                            <div class="sunny-desk-right col-md-3">
                                <a href="https://sunnytree.nl/Verzorging" class="sunny-main-menu-img"
                                    style="background-image: url(https://sunnytree.nl/Files/10/334000/334323//CategoryPhotos/Large/31588806.jpg);">
                                    <span class="sunny-megamenu-img-title">
                                        <span>Bekijk assortiment</span>
                                        <svg fill="none" stroke-width="1.5" viewBox="0 0 24 24"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path d="m6 12h12.5m0 0-6-6m6 6-6 6" stroke="currentColor"
                                                stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </ul>
            </li>
            <li class="sunny-lan-mobile-li">
                <ul class="sunny-lan-mobile">
                    <li class="mobile-lan-selector">
                        <img src="https://sunnytree.nl/Files/10/334000/334323/Protom/3039936/Media/nl.svg" alt="NL"
                            title="NL">
                        <span>Nederlands</span>
                        <svg class="feather feather-chevron-down" fill="none" stroke="currentColor"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24"
                            xmlns="http://www.w3.org/2000/svg">
                            <polyline points="6 9 12 15 18 9"></polyline>
                        </svg>
                    </li>
                    <li>
                        <ul>
                            <li><a href="https://sunnytree.de/"><img
                                        src="https://sunnytree.nl/Files/10/334000/334323/Protom/3039936/Media/de.svg"
                                        alt="DE"><span>Deutsch</span></a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="sunny-mobile-bottom"></li>
        </ul>
    </div>
</nav>

<?php
$mobile_usps = \SunnyTree\TemplateTags\get_usps();
if (! empty($mobile_usps)) :
    $first_usp = reset($mobile_usps);
?>
<div class="sunny-mobile-usp">
    <div class="container">
        <ul>
            <li>
                <?php \SunnyTree\TemplateTags\render_icon($first_usp['icon'], ['stroke-width' => '1.5']); ?>
                <span><?php echo esc_html($first_usp['text']); ?></span>
            </li>
        </ul>
    </div>
</div>
<?php endif; ?>

<div class="sunny-mobile-review">
    <div class="container">
        <a target="_blank" href="https://g.co/kgs/iBvom8k" class="sunny-review">
            <span class="sunny-r-number">5,0/5</span>
            <span class="sunny-r-stars">
                <img height="15" width="105"
                    src="https://sunnytree.nl/Files/10/334000/334323/Protom/3039936/Media/stars.svg" alt="stars"
                    title="stars">
            </span>
            <span class="sunny-r-amount">99 Reviews</span>
        </a>
    </div>
</div>
