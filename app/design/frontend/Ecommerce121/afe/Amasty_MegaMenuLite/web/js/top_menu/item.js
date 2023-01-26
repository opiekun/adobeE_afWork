/**
 *  Amasty Top Menu Item elements UI Component
 */

define([
    'jquery',
    'ko',
    'uiComponent',
    'uiRegistry',
    'underscore',
    'ammenu_helpers'
], function ($, ko, Component, registry, _, helpers) {
    'use strict';

    return Component.extend({
        defaults: {
            imports: {
                root_templates: 'index = ammenu_wrapper:templates',
                is_icons_available: 'index = ammenu_wrapper:is_icons_available',
                color_settings: 'index = ammenu_wrapper:color_settings',
                view_port: 'index = ammenu_wrapper:view_port',
                settings: 'index = ammenu_wrapper:settings'
            },
            components: [
                'index = ammenu_wrapper'
            ]
        },
        /**
         * @inheritDoc
         */
        initialize: function () {
            var self = this;

            self._super();

            registry.get(self.components, function () {
                if (!self.isMobile) {
                    helpers.initComponentsArray(arguments, self);

                    self.item = self._findElemById(self.id);
                    self.item.backgroundColor = ko.observable();
                    self.item.isActive.extend({ rateLimit: 500 });
                    self.item.isActive.subscribe(function (value) {
                        if (value) {
                            self.item.backgroundColor('#ffffff');
                            self.item.color('#000000');
                        } else {
                            self.item.backgroundColor('');
                            self.item.color(self.item.base_color);
                        }
                    });
                    self.item.submenu_position = {
                        right: ko.observable(false)
                    };
                }
            });

            return self;
        },

        /**
         * Menu item hover handler
         *
         * @public
         * @return {void}
         */
        onMouseenter: function (elem, event) {
            var self = this;
            this.item.isActive(true);
            // if (this.item.nodes.submenu && !this.item.submenu_position.checked) {
            //     this._setSubmenuPosition(this.item);
            // }
            // this.showMenu(event.target.parentElement);
            // $(event.target.parentElement).mouseleave(function () {
            //     self.hideMenu(this);
            // });
        },

        /**
         * Menu item mouse leave handler
         *
         * @public
         * @return {void}
         */
        onMouseleave: function (elem, event) {
            this.item.isActive(false);
        },

        /**
         * Find target elem by id in source data
         *
         * @private
         * @params {String} id
         * @returns {Object} targetElem
         */
        _findElemById: function () {
            var self = this,
                targetElem;

            self.ammenu_wrapper.data.elems.map(function (elem) {
                if (self.id === elem.id) {
                    targetElem = elem;
                }
            });

            return targetElem;
        },

        /**
         * Set current opened submenu position via view port
         * Only for root level
         *
         * @params {Object} elem
         * @return {Boolean} for propagation
         */
        _setSubmenuPosition: _.debounce(function (elem) {
            var submenuRect = elem.nodes.submenu.getBoundingClientRect(),
                inViewPort = submenuRect.right <= this.view_port.width;

            if (!inViewPort) {
                elem.submenu_position.right(1); // 0 is false
                elem.submenu_position.checked = true;
            }
        }, 300),
        showMenu: _.debounce(function (elem) {
            if(!$(elem).hasClass('open')) {
                $('.ammenu-items .main-item').removeClass('open');
                $('.header.holder .vehicle-wrapper').removeClass('open');
            }
            if($(elem).is(':hover')) $(elem).addClass('open');
            console.log('open');
        }, 500),
        hideMenu: _.debounce(function (elem) {
            $(elem).removeClass('open');
        }, 500)
    });
});
