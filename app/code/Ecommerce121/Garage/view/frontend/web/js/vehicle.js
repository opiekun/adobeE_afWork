define([
    'uiElement',
    'jquery',
    'underscore'
], function (component, $, _) {
    'use strict';

    return component.extend({
        defaults: {
            title: '',
            vehicleId: '',
            description: '',
            deleteUrl: '',
            dropdowns: {},
            url: '',
            inProcess: false,
            values: {},
            selectedValues: {},
            activeDropdowns: [],
            tracks: {
                activeDropdowns: true
            },
            overlay: '#garage-finder-overlay'
        },

        initialize: function () {
            this._super();

            if (!this.values[this.dropdowns[0].dropdown_id]) {
                this.populateOptions(this);
            }
            this.initDropdowns(this.dropdowns[0].dropdown_id);
            window.dropdownValues = {};

            return this;
        },

        initDropdowns: function (dropdownId) {
            let self = this,
                Option = function (label, valueId) {
                    this.label = label;
                    this.valueId = valueId;
                };

            $(self.overlay).show();

            if (dropdownId) {
                _.each(this.dropdowns, function (item) {
                    if (item.dropdown_id === dropdownId) {
                        self.currentDropdown = item;
                        return false;
                    }
                });

                var dropdowns = [self.currentDropdown];
            } else {
                var dropdowns = this.dropdowns;
            }

            if (!$.isEmptyObject(self.values)) {
                _.each(dropdowns, function (item) {
                    let options = [],
                        dropdown,
                        dropdownKey,
                        selected = '',
                        dropdownId = item.dropdown_id;

                    //if value is saved in storage
                    if (!self.values[dropdownId] || typeof self.values[dropdownId].dropdown_id !== 'undefined') {
                        self.populateOptions(self, dropdownId);
                    } else {
                        _.each(self.getOptions(item.dropdown_id), function (item) {
                            for(var i = 0; i < options.length; i++) {
                                if (options[i].label == item.value) {
                                    return false;
                                }
                            }
                            options.push(new Option(item.value, item.value_id));
                            if (self.isSelected(item)) {
                                selected = item.value_id;
                            }
                        });

                        dropdown = {
                            options: options,
                            selected: selected,
                            dropdownId: item.dropdown_id,
                            required: item.required,
                            label: item.label
                        };

                        dropdownKey = _.findKey(self.activeDropdowns, function (dropdown) {
                            return dropdown.dropdownId === item.dropdown_id;
                        });

                        if (typeof dropdownKey === 'undefined') {
                            self.activeDropdowns.push(dropdown);
                        } else {
                            self.activeDropdowns.splice(dropdownKey, 1, dropdown)
                        }
                    }
                });
                var nextDropdownId = this.getNextDropdown(dropdownId);
                if (nextDropdownId) {
                    this.initDropdowns(nextDropdownId);
                } else {
                    self.activeDropdowns.find(element => element.dropdownId === dropdownId);
                    $(self.overlay).hide();

                }
            }
        },

        getOptions: function (dropdownId) {
            let selected = Object.values(this.selectedValues),
                values = [];

            if (typeof this.values[dropdownId] == 'undefined') {
                return [];
            }

            if (this.theFirstDropdown(this.values[dropdownId])) {
                _.each(this.values[dropdownId], function (value) {
                    if (selected.includes(value.value_id)) {
                        if (!window.dropdownValues) {
                            window.dropdownValues = {};
                        }
                        window.dropdownValues[dropdownId] = value;
                    }
                });
                return this.values[dropdownId];
            }

            _.each(this.values[dropdownId], function (value) {
                if (selected.includes(value.parent_id)) {
                    values.push(value);
                }
                if (selected.includes(value.value_id)) {
                    window.dropdownValues[dropdownId] = value;
                }
            });

            return values;
        },

        /**
         * amasty_finder treats parent_id = 0 as the first dropdown
         * @param values
         * @returns {boolean}
         */
        theFirstDropdown: function (values) {
            let value = _.find(values);
            return !((typeof value == 'undefined') || (value['parent_id'] !== '0'));
        },

        changeDropdown: function (data, event) {
            let dropdownId = $(event.currentTarget).attr('data-dropdown-id'),
                selected = data.selected;

            var dropdownIndex = this.dropdowns.findIndex(function (dropdown, index) {
                if (dropdown.dropdown_id === data.dropdownId) {
                    return true;
                }
            });

            if (typeof this.dropdowns[dropdownIndex + 1] !== 'undefined') {
                var nextDropdownId = this.dropdowns[dropdownIndex + 1].dropdown_id;

                if (!this.values[nextDropdownId] || typeof window.dropdownValues[nextDropdownId] == 'undefined') {
                    this.populateOptions(data, nextDropdownId);
                }
            }

            this.updateSelected(selected, dropdownId);
            this.initDropdowns(dropdownId);
        },

        getNextDropdown: function (dropdownId) {
            var dropdownIndex = this.dropdowns.findIndex(function (dropdown, index) {
                if (dropdown.dropdown_id === dropdownId) {
                    return true;
                }
            });

            if (typeof this.dropdowns[dropdownIndex + 1] !== 'undefined') {
                return this.dropdowns[dropdownIndex + 1].dropdown_id;
            }

            $(self.overlay).hide();
        },

        populateOptions: function (data, dropdownId) {
            let self = this;

            if (dropdownId === undefined) {
                if (data.dropdownId) {
                    dropdownId = data.dropdownId;
                } else {
                    dropdownId = data.dropdowns[0].dropdown_id;
                }
            }
            if (!self.inProcess) {
                self.inProcess = true;
                this.ajaxRequest = $.ajax({
                    url: self.url,
                    type: 'post',
                    data: {dropdown_id: dropdownId},
                    dataType: 'json',
                    success: function (response) {
                        self.values[dropdownId] = response[dropdownId];
                        self.inProcess = false;
                        self.initDropdowns(dropdownId);
                    },
                });
            }

            return this;
        },

        updateSelected: function (selected, dropdownId) {
            let self = this;

            if (selected) {
                this.selectedValues[dropdownId] = selected;
            }

            _.each(this.selectedValues, function (item, index) {
                if (index > dropdownId || (!selected && index === dropdownId)) {
                    delete self.selectedValues[index];
                }
            });
        },

        isSelected: function (option) {
            if (typeof this.selectedValues[option.dropdown_id] == 'undefined') {
                return false;
            }

            return this.selectedValues[option.dropdown_id] === option.value_id;
        },

        deleteVehicle: function () {
            if (!confirm('Are you sure?')) {
                return;
            }

            $.ajax({
                showLoader: true,
                url: this.deleteUrl,
                data: {
                    vehicle_id: this.vehicleId
                },
                type: 'post',
                dataType: 'json'
            }).done(function (response) {
                if (response.success) {
                    location.reload();
                }
            });
        }
    });
});
