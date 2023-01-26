define(
    [],
    function () {
        'use strict';

        return function (EmailComponent) {
            return EmailComponent.extend({
                defaults: {
                    template: 'Ecommerce121_Checkout/form/element/email'
                }
            });
        }
    }
);