/**
 * Copyright © Magento, Inc. All rights reserved.
 * See COPYING.txt for license details.
 */

define([
    'jquery',
    'mage/smart-keyboard-handler',
    'underscore',
    'mage/mage',
    'mage/ie-class-fixer',
    'domReady!'
], function ($, keyboardHandler, _) {
    'use strict';

    $('.cart-summary').mage('sticky', {
        container: '#maincontent'
    });

    $('.panel.header > .header.links').clone().appendTo('#store\\.links');

    keyboardHandler.apply();
    var vehicleWrapper = $('.header.holder .vehicle-wrapper'),
        menuDelayTime = 500;
    $(vehicleWrapper).hover(function () {
        vehicleWrapper.delay(menuDelayTime).queue(function(next){
            if($(this).is(':hover')) $(this).addClass('open');
            next();
        });
    }, function () {
        vehicleWrapper.delay(menuDelayTime).queue(function(next){
            if(!($(this).is(':hover'))) $(this).removeClass('open');
            next();
        });
    });

    $("#search_mini_form .label").on('click', function(e) {
        if ($(this).hasClass('active')) {
            $(this).removeClass( 'active');
        } else {
            $(this).addClass('active');
            let input = $("#search");
            let data =  input.val();
            input.focus().val("").focus().val(data);
        }
        e.preventDefault();
    });

    const pathname = window.location.pathname;
    if (pathname === '/amfinder') {
        document.title = $('#page-title-heading span.base').text();
    }
});
