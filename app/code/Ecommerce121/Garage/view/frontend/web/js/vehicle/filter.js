define([
    'uiElement',
    'jquery',
    'underscore',
    'Magento_Customer/js/customer-data',
], function (component, $, _, customerData) {
    'use strict';

    return component.extend({
        defaults: {
            vehicles: [],
            options: [],
            tracks: {
                options: true
            }
        },
        initialize: function () {
            this._super();
            this.initOptions();
            return this;
        },

        initOptions: function () {
            let Option = function (label, url, values) {
                this.label = label;
                this.url = url;
                this.values = values;
            };
            let self = this;

            _.each(this.vehicles, function (vehicle) {
                self.options.push(new Option(vehicle.label, vehicle.url, vehicle.values));
            });
        },

        saveSearchParams: function (values) {
            let params = [],
                paramsToBeSaved = {},
                storage = $.initNamespaceStorage('ecommerce121-vehicle-search').localStorage;

            _.each(values, function (item, index) {
                let value_id = item.value_id,
                    label = item.name;
                if (value_id) {
                    paramsToBeSaved[index] = value_id;
                    params.push(label);
                }
            });

            storage.set('selected', paramsToBeSaved);
        },

        search: function (data, event) {
            let url = $(event.currentTarget).val();
            if (!url) {
                return;
            }
            let self = this;
            _.each(data.options, function (vehicle) {
                if (vehicle.url == url) {
                    self.saveSearchParams(vehicle.values);
                    return false;
                }
            });
            window.location.href = url;
        },
        /**
         * Check if customer is logged in
         *
         * @return {boolean}
         */
        isLoggedInCustomer: function () {
            return customerData.get('customer')()?.fullname;
        },
    });
});
