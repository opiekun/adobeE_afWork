define([
    'jquery',
    'jquery/ui',
    'mage/translate',
    'MutationObserver'
], function ($) {
    'use strict';

    $.widget('mage.stickyActions', {
        _create: function () {
            var container = $(this.element);
            var addToCartForm = $('.product-info-main');
            var addToCartButton = addToCartForm.find('.action.primary.tocart');
            var addToCartText = addToCartButton.text();

            if (addToCartText.length > 0) {
                var stickyAddToCartButton = jQuery('<a href="#"></a>');
                stickyAddToCartButton.text(addToCartText);
                stickyAddToCartButton.addClass('action primary tocart');
                container.append(stickyAddToCartButton);
                stickyAddToCartButton.click(function () {
                    addToCartButton.click();
                    return false;
                });
            }

            var header = $('.header.content');
            var stickyBar = $('.sticky-bar');
            var stickyTabs = $('.sticky-bar .tabs');
            var stickyNavTtl = $('.sticky-bar .tabs .ttl');
            var stickyRightBar =  $('.sticky-bar .right');
            var headerHeight = 0;
            var stickyBarHeight = 0;
            var floatingHeight = 0;
            stickyTabs.click(function (e) {
                $(this).toggleClass('open');
            });
            $(document).ready(function () {


                var stickyTabLink = $(stickyTabs).find('a');
                if ($(stickyTabLink).length > 0) {
                    $(stickyTabLink).each(function(){
                        var stickyHref = $(this).attr('href');
                        console.log(stickyHref);
                        if ($(stickyHref).length > 0) {
                            $(this).closest('li').css({'display':'list-item'});
                        }
                    })
                }

                $(document).on("scroll", onScroll);

                $('.sticky-bar .tabs a[href^="#"]').on('click', function (e) {
                    e.preventDefault();
                    if (header.length) headerHeight = header.height();
                    if (stickyBar.length) stickyBarHeight = stickyBar.height();

                    $(document).off("scroll");
                    stickyNavTtl.text($(this).text());
                    $('.sticky-bar .tabs a').each(function () {
                        $(this).removeClass('active');
                        stickyRightBar.removeClass('active');
                    })
                    $(this).addClass('active');
                    stickyRightBar.addClass('active');

                    var target = this.hash;
                    var $target = $(target);
                    if (!$target.length && target === '#related-products') $target = $('#crosssell-products');
                    if ($target.length > 0) {
                        var scrollValue = $target.offset().top - (headerHeight + stickyBarHeight);
                        if(target === '#info-main') scrollValue = 0;
                        $('html, body').stop().animate({
                            'scrollTop': scrollValue
                        }, 500, 'swing', function () {
                            $(document).on("scroll", onScroll);
                        });
                    }
                });
                $('.product-info-main .rating-summary').on('click', function (e) {
                    e.preventDefault();
                    if (header.length) headerHeight = header.height();
                    if (stickyBar.length) stickyBarHeight = stickyBar.height();

                    $(document).off("scroll");
                    stickyNavTtl.text($(this).text());
                    $('.sticky-bar .tabs a').each(function () {
                        $(this).removeClass('active');
                        stickyRightBar.removeClass('active');
                    })
                    // $(this).addClass('active');

                    var target = '#product-review-block';
                    var $target = $(target);
                    var scrollValue = $target.offset().top - (headerHeight + stickyBarHeight);
                    $('html, body').stop().animate({
                        'scrollTop': scrollValue
                    }, 500, 'swing', function () {
                        $(document).on("scroll", onScroll);
                    });
                });
            });
            $(window).load(function () {
                var localionHash = document.location.hash;
                if (localionHash == '#related-products') {
                    if (header.length) headerHeight = header.height();
                    if (stickyBar.length) stickyBarHeight = stickyBar.height();
                    $(document).off("scroll");
                    var target = localionHash;
                    var $target = $(target);
                    if (!$target.length) $target = $('#crosssell-products');
                    if ($target.length > 0) {
                        var scrollValue = $target.offset().top - (headerHeight + stickyBarHeight);
                        $('html, body').stop().animate({
                            'scrollTop': scrollValue
                        }, 500, 'swing', function () {
                            $(document).on("scroll", onScroll);
                        });
                    }
                }
            });
            function onScroll(event) {
                if (header.length) headerHeight = header.outerHeight();
                if (stickyBar.length) stickyBarHeight = stickyBar.outerHeight();
                stickyRightBar.removeClass('active');
                var scrollPosition = $(document).scrollTop();
                $('.sticky-bar .tabs a').each(function () {
                    var currentLink = $(this);
                    var refElement = $(currentLink.attr("href"));
                    if (refElement.offset()) {
                        if (currentLink.attr("href") === '#info-description' && scrollPosition < 1242) {
                            $('.sticky-bar .tabs a').removeClass("active");
                            currentLink.addClass("active");
                            stickyRightBar.addClass('active');
                            stickyNavTtl.text(currentLink.text());
                            return;
                        }
                        if (refElement.offset().top <= scrollPosition + (headerHeight + stickyBarHeight)) {
                            $('.sticky-bar .tabs a').removeClass("active");
                            currentLink.addClass("active");
                            stickyRightBar.addClass('active');
                            stickyNavTtl.text(currentLink.text());
                        } else {
                            currentLink.removeClass("active");
                        }
                    }
                });
            }

        }
    });

    return $.mage.stickyActions;
});
