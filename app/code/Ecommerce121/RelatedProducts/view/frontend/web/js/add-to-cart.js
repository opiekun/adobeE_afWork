define([
    'uiElement',
    'jquery'
], function (component, $, priceUtils, _) {
    'use strict';

    return component.extend({
        defaults: {
            ajaxUrl: ""
        },

        initialize: function () {
            this._super();

            return this;
        },

        addToCart: function () {
            let productIds = [],
                self = this;

            $('[name="related_products[]"]:checked').each(function (index, element) {
                productIds.push($(element).val());
            });

            if (!productIds.length) {
                alert('Check related products to add them to cart');
                return;
            }

            $.ajax({
                showLoader: true,
                url: self.ajaxUrl,
                data: {
                    form_key: $("input[name='form_key']").val(),
                    related_products: productIds.join(',')
                },
                type: 'post',
                dataType: 'json'
            }).done(function (data) {
                window.scrollTo(0, 0);
                location.reload();
            });
        },

        isDisplayed: function () {
            return !!$('[name="related_products[]"]').length;
        }
    });
});
