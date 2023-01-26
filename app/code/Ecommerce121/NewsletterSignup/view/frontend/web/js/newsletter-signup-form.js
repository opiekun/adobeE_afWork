define([
    'Ecommerce121_Garage/js/vehicle',
    'jquery',
    'underscore'
], function (component, $, _) {
    'use strict';

    return component.extend({
        defaults: {
            newsletterSignupUrl: '',
            redirectUrl: ''
        },

        submitForm: function (form) {
            let params = {},
                self = this;

            _.each($(form).find('input'), function (item) {
                if ($(item).attr('name')) {
                    params[$(item).attr('name')] = $(item).val();
                }
            });

            _.each($(form).find('option:selected'), function (item) {
                params[$(item).closest('select').attr('name')] = $(item).text();
            });

            $.ajax({
                showLoader: true,
                url: self.newsletterSignupUrl,
                data: params,
                type: 'post',
                dataType: 'json'
            }).done(function (data) {
                if (data.success) {
                    window.location.href = self.redirectUrl;
                }
            });
        },

        getFormKey: function () {
            return $.cookie('form_key');
        }
    });
});
