import $ from 'jquery';

if ($('body').hasClass('ProductPage')) {
    var offsetAmount = 500;
    $(document).scroll(function() {
        if ($(document).scrollTop() >= offsetAmount) {
            $('body').addClass("scroll")
        } else {
            $('body').removeClass("scroll")
        }
    })
}

function setVw() {
    let vw = document.documentElement.clientWidth / 100;
    document.documentElement.style.setProperty('--vw', `${vw}px`)
}
setVw();
window.addEventListener('resize', setVw);

function matchAllHeights() {
    $('.sunny-product-title').matchHeight();
    $('.sunny-product-formaat-cat').matchHeight();
    $('.sunny-product-title-cat').matchHeight();
    $('.sunny-product-formaat').matchHeight();
    $('.sunny-cat-title').matchHeight();
    $('.sunny-special-content').matchHeight();
    // $('.sunny-product-cat').matchHeight()
}
$(document).ready(function() {
    function checkSize() {
        if ($(".deskCheck").css("float") == "none") {
            $('.sunny-footer-title.toggle').on('click', function() {
                $(this).toggleClass('opened');
                $(this).parent().find('.sunny-footer-content').stop().slideToggle()
            })
        } else {
            
        }
    }
    checkSize();
    setTimeout(function() {
        if ($('#id-8126652 .contact-page-form').length > 0) {
            $('label.control-label').each(function() {
                var labelText = $(this).text();
                $(this).next().find('.form-control').attr('placeholder', '' + labelText + '');
                labelText = labelText.trim().replace(' ', '').replace('*', '');
                $(this).next().attr('class', 'sunny-' + labelText + '');
                $(this).remove()
            })
        }
    }, 800);
    if ($('.WebShopBasket').length) {
        let henkie = $('#Basket > .container-content.spacer-m')
        $("#ContinueOrderButton").clone().appendTo(henkie)
    }
    if ($('.WebShopBasket').length) {
        let hhenkie = $('.sunny-sticky-btn-wrapper')
        $("#ContinueOrderButton").clone().appendTo(hhenkie)
    }
    $('.mobile-lan-selector').on('click', function() {
        $(this).toggleClass('open');
        $('.sunny-lan-mobile ul').slideToggle('fast')
    });
    setTimeout(function() {
        $('.relevant-product-title').matchHeight()
    }, 500)
    $('.sunny-m-search').on('click', function() {
        $('.sunny-search-bg').toggleClass('open');
        $('.sunny-main-icons').addClass('search-hidden');
        $('.sunny-search').slideToggle('fast');
        $('.sunny-search-input').focus()
    });
    $('.sunny-search-bg, .search-close').on('click', function() {
        $('.sunny-search-bg').removeClass('open');
        $('.sunny-main-icons').removeClass('search-hidden');
        $('.sunny-search').slideToggle('fast')
    });
    if ($('body').hasClass('ProductPage')) {
        $('.property__group').on('click', function() {
            $(this).find('.property__list').stop().slideToggle();
            $(this).toggleClass('open')
        })
    }
    $('.sunny-faq-question').on('click', function() {
        $(this).next('p').stop().slideToggle();
        $(this).toggleClass('open')
    });
    $('.ShowProductCategoryfilters').wrap('<div class="sunny-filter-container"></div>');
    $('.sunny-mobile-filter-btn').insertAfter('.ShowProductCategoryfilters');
    $('.sunny-mobile-filter-btn').addClass('visible');
    $('.sunny-mobile-filter-btn').on('click', function() {
        $('.sunny-filters').addClass('open');
        $('.sunny-f-menu-bg').addClass('open')
    });
    $('.sunny-f-menu-bg').on('click', function() {
        $('.sunny-filters').removeClass('open');
        $('.sunny-f-menu-bg').removeClass('open')
    });
    $('.sunny-m-filter-close').on('click', function() {
        $('.sunny-filters').removeClass('open');
        $('.sunny-f-menu-bg').removeClass('open')
    });
    $('.sunny-filters-toepassen').on('click', function() {
        $('.sunny-filters').removeClass('open');
        $('.sunny-f-menu-bg').removeClass('open')
    });
    $(".sunny-selected-lan").click(function() {
        $(".sunny-lan-select").slideToggle('open')
    });
    $('#responsive-menu-button').on('click', function() {
        $(this).toggleClass('is-active');
        $('header').toggleClass('menu-open');
        $('.sunny-main-menu').toggleClass('menu-open');
        $('.sunny-m-search').fadeToggle()
    });
    $('.sunny-close, .sunny-bg').on('click', function() {
        $('.sunny-main-menu').removeClass('menu-open');
        $('.sunny-bg').removeClass('bg-open');
        $('.sticky-sidebar').removeClass('z-index')
    });
    $('.sunny-next-menu').on('click', function() {
        $(this).next().addClass('open');
        $('.first').addClass('left');
        $('.sunny-menu-item').addClass('min-relative');
        setTimeout(function() {
            $(".sunny-main-menu").animate({
                scrollTop: 0
            }, "fast")
        }, 5)
    });
    $('.sunny-next-menu-sub').on('click', function() {
        $(this).next().addClass('open');
        $('.first').addClass('more-left');
        $('.second li').addClass('min-relative-sub');
        setTimeout(function() {
            $(".sunny-main-menu").scrollTop(0)
        }, 5)
    });
    $('.sunny-step-back-sub').click(function(e) {
        e.stopPropagation();
        $('.third').removeClass('open');
        $('.first').removeClass('more-left');
        setTimeout(function() {
            $('.second li').removeClass('min-relative-sub')
        }, 5);
        $('.first').addClass('left')
    });
    $('.sunny-step-back').click(function(e) {
        e.stopPropagation();
        $('.second').removeClass('open');
        $('.first').removeClass('left');
        setTimeout(function() {
            $('.sunny-menu-item').removeClass('min-relative')
        }, 5)
    });
    $('.sunny-more-li').mouseenter(function() {
        $('.sunny-menu-desk-bg').addClass('open')
    });
    $('.sunny-more-li').mouseleave(function() {
        $('.sunny-menu-desk-bg').removeClass('open')
    });
    
    $('.sunny-review-slider').on('beforeChange', function(event, slick, currentSlide, nextSlide) {
        $('.slider-nav a.active').removeClass('active');
        $('.slider-nav a').eq(nextSlide).addClass('active')
    });
    $('a[data-slide]').click(function(e) {
        $('.slider-nav a.active').removeClass('active');
        $(this).addClass('active');
        e.preventDefault();
        var slideno = $(this).data('slide');
    });
    matchAllHeights();
    
    if ($('body').hasClass('SearchPage')) {
        setTimeout(function() {
            matchAllHeights()
        }, 500)
        setTimeout(function() {
            matchAllHeights()
        }, 1000)
        setTimeout(function() {
            matchAllHeights()
        }, 2000)
    }
});
$('.sunny-preorder-info-trigger').click(function(e) {
    $('.sunny-preorder-info-popup').addClass('open');
    $('.sunny-preorder-info-popup-bg').addClass('open')
});
$('.sunny-preorder-info-popup-close, .sunny-preorder-info-popup-bg').click(function(e) {
    $('.sunny-preorder-info-popup').removeClass('open');
    $('.sunny-preorder-info-popup-bg').removeClass('open')
})