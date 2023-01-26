define([
    'jquery'
], function ($) {
    'use strict';

    return function (widget) {

        $.widget('mage.SwatchRenderer', widget, {
            _RenderControls: function () {
                this._super();
                var swatchLength = $('.swatch-attribute').length;

                if(swatchLength >= 1 && $('body.catalog-product-view').length > 0) {
                    $('.swatch-option').first().trigger('click');
                }

            }
        });

        return $.mage.SwatchRenderer;
    }
});
