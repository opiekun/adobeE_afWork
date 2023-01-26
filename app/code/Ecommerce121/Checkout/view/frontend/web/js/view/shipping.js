define(
    [],
    function () {
        'use strict';

        return function (ShippingComponent) {
            return ShippingComponent.extend({
                defaults: {
                    template: 'Ecommerce121_Checkout/shipping',
                    shippingFormTemplate: 'Ecommerce121_Checkout/shipping-address/form'
                }
            });
        }
    }
);