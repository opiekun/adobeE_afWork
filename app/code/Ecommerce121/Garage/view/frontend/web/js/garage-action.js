define([
    'uiElement',
    'jquery',
    'underscore',
    'uiRegistry'
], function (component, $, _, registry) {
    'use strict';

    return component.extend({
        defaults: {
            saveUrl: '',
            backUrl: '',
            vehicleComponentNames: []
        },

        saveGarage: function () {
            $.ajax({
                showLoader: true,
                url: this.saveUrl,
                data: this.getData(),
                type: 'post',
                dataType: 'json'
            }).done(function (response) {
                if (response.success) {
                    location.reload();
                }
            });
        },

        getData: function () {
            let data = [],
                vehicleComponent;

            _.each(this.vehicleComponentNames, function (component) {
                vehicleComponent = registry.get(component);
                if (vehicleComponent
                    && (!!vehicleComponent.vehicleId || Object.keys(vehicleComponent.selectedValues).length > 0)
                ) {
                    data.push({
                        vehicle_id: vehicleComponent.vehicleId,
                        values: vehicleComponent.selectedValues,
                    });
                }
            });

            return {
                vehicles: data
            };
        }
    });
});
