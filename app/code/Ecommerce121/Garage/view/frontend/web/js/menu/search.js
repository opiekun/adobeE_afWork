define([
    'Ecommerce121_Garage/js/vehicle',
    'jquery',
    'underscore',
    'mage/url',
    'ko',
    'jquery/jquery-storageapi'
], function (component, $, _, urlBuilder, ko) {
    'use strict';

    return component.extend({
        defaults:{
            disableSubmit: false,
            tracks: {
                disableSubmit: true
            }
        },

        initialize: function (data) {
            let storage = $.initNamespaceStorage('ecommerce121-vehicle-search').localStorage;
            let self = this;

            data.selectedValues = storage.get('selected');
            data.values = storage.get('values');

            this._super(data);

            _.each(this.dropdowns, function (item) {
                if (!(item.dropdown_id in self.selectedValues) && item.required) {
                    self.disableSubmit = true;
                }
            });

            return this;
        },

        changeDropdown: function (data, event) {
            this._super(data, event);

            this.disableSubmit = !$("#ecommerce121-vehicle-search-form").valid();
            $(this.dropdowns).each(function (index, dropdown) {
                $('#dropdown-search-' + dropdown.dropdown_id + '-error').hide();
            });
        },

        onSubmitForm: function (form) {
            let params = [],
                paramsToBeSaved = {},
                lastValue,
                storage = $.initNamespaceStorage('ecommerce121-vehicle-search').localStorage,
                url = '';

            _.each($(form).find('select'), function (item) {
                let value = $(item).val(),
                    label = $(item).find('option:selected').text();
                if (value) {
                    paramsToBeSaved[$(item).attr('data-dropdown-id')] = value;
                    params.push(label);
                    lastValue = value;
                }
            });

            storage.set('selected', paramsToBeSaved);
            storage.set('values', window.dropdownValues);

            url = urlBuilder.build('amfinder') + '?find=';
            window.location.href = url
                + params.join('-').toLowerCase().replace(/[^\da-zA-Z]/g, '-')
                + '-'
                + lastValue;
        }
    });
});
