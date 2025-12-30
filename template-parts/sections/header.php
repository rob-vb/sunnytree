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
                <?php
                $settings = \SunnyTree\Settings\get_settings();
                $is_open = \SunnyTree\Settings\is_customer_service_open();
                $status_class = $is_open ? 'open' : 'closed';
                ?>
                <a class="sunny-online-link" href="<?php echo esc_url($settings['customer_service_url']); ?>">
                    <span class="sunny-online <?php echo esc_attr($status_class); ?>"></span>
                    <span class="sunny-online-txt"><?php echo esc_html($settings['customer_service_text']); ?></span>
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
                    alt="<?php esc_attr_e('logo Sunnytree', 'sunnytree'); ?>" title="<?php esc_attr_e('logo Sunnytree', 'sunnytree'); ?>">
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
        <?php

       wp_nav_menu([
            'theme_location' => 'sunny-main-menu',
            'walker' => new \SunnyTree\Sunny_Mega_Menu_Walker(),
            'menu_class' => 'first',
            'container' => false
        ]);
?>
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
