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
            }
        },

        initialize: function () {
            this._super();
            this.loadDropdowns();
            window.dropdownValues = {};
            return this;
        },

        /**
         * Load the dropdown the first time
         */
        loadDropdowns: function() {
            let self = this;
            _.each(this.dropdowns, function (item, pos) {
                self.values[item.dropdown_id] = [];
                let dropdown = {
                    options: [],
                    selected: '',
                    dropdownId: item.dropdown_id,
                    required: item.required,
                    label: item.label
                };
                self.getOptions(item.dropdown_id);
                self.activeDropdowns.push(dropdown);
            });
            let item = this.dropdowns[0],
                dropdown = {
                    options: [],
                    selected: '',
                    dropdownId: item.dropdown_id,
                    required: item.required,
                    label: item.label
                },
                ajx = self.ajaxDropdown(item.dropdown_id);
            ajx.done(function(response) {
                dropdown['options'] = self.getOptionItems(item.dropdown_id).options;
                self.setDropdown(item, dropdown);
            });
        },

        /**
         * Load the dropdowns when change
         * @param dropdownId
         */
        initDropdowns: function (dropdownId) {
            let self = this;

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
                    let dropdown,
                        dropdownId = item.dropdown_id;

                    //if value is saved in storage
                    if (!self.values[dropdownId] || typeof self.values[dropdownId].dropdown_id !== 'undefined') {
                        self.populateOptions(self, dropdownId);
                    } else {
                        let optionsItems = self.getOptionItems(item.dropdown_id);
                        dropdown = {
                            options: optionsItems.options,
                            selected: optionsItems.selected,
                            dropdownId: item.dropdown_id,
                            required: item.required,
                            label: item.label
                        };

                        self.setDropdown(item, dropdown);
                    }
                });
                let nextDropdownId = this.getNextDropdown(dropdownId);
                if (nextDropdownId) {
                    this.initDropdowns(nextDropdownId)
                }
            }
        },

        /**
         * Get the options with the format nedded in the the html
         * @param dropdownId
         * @returns {{options: *[], selected: string}}
         */
        getOptionItems: function(dropdownId) {
            let self = this,
                optionItems = [],
                selected = "",
                Option = function (label, valueId) {
                    this.label = label;
                    this.valueId = valueId;
                };
            _.each(this.getOptions(dropdownId), function (optItem) {
                for(let i = 0; i < optionItems.length; i++) {
                    if (optionItems[i].label == optItem.value) {
                        return false;
                    }
                }
                optionItems.push(new Option(optItem.value, optItem.value_id));
                if (self.isSelected(optItem)) {
                    selected = optItem.value_id;
                }
            });
            return {options: optionItems, selected: selected};
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
         * Set the dropdown in the active dropdown to show in the view
         * @param item
         * @param dropdown
         */
        setDropdown: function (item, dropdown) {
            let dropdownKey = _.findKey(this.activeDropdowns, function (dropdown) {
                return dropdown.dropdownId === item.dropdown_id;
            });

            if (typeof dropdownKey === 'undefined') {
                this.activeDropdowns.push(dropdown);
            } else {
                this.activeDropdowns.splice(dropdownKey, 1, dropdown)
            }
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
                let ajaxRequest = self.ajaxDropdown(dropdownId);
                ajaxRequest.done(function(response) {
                    self.inProcess = false;
                    self.initDropdowns(dropdownId);
                });
            }
            return this;
        },

        /**
         * make the ajax call to load the dropdown data
         * @param dropdownId
         * @returns {*}
         */
        ajaxDropdown: function (dropdownId) {
            let self = this;
            let ajaxRequest = $.post(
                self.url,
                {dropdown_id: dropdownId},
                "json"
            );
            ajaxRequest.done(function(response) {
                self.values[dropdownId] = response[dropdownId];
            });
            return ajaxRequest;
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
