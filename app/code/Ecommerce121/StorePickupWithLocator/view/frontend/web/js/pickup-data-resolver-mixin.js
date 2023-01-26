define([
    'mage/utils/wrapper',
    'Magento_Customer/js/customer-data'
], function (wrapper, customerData) {
    'use strict';

    return function (dataResolver) {
        dataResolver.curbsideData = wrapper.wrapSuper(dataResolver.curbsideData, function (param) {
            customerData.initStorage();

            this._super(param);
        });

        return dataResolver;
    };
});
