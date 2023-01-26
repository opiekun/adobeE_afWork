/**
 *  Amasty Category Tree UI Component
 */

define([
    'jquery',
    'ko',
    'uiComponent',
    'underscore',
    'ammenu_helpers'
], function ($, ko, Component, _, helpers) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Amasty_MegaMenuLite/components/tree/tree',
            templates: {
                title: 'Amasty_MegaMenuLite/components/tree/title',
                active_level: 'Amasty_MegaMenuLite/components/tree/active_level'
            },
            imports: {
                root_templates: 'index = ammenu_wrapper:templates',
                color_settings: 'index = ammenu_wrapper:color_settings',
                icons: 'index = ammenu_wrapper:icons',
                is_icons_available: 'index = ammenu_wrapper:is_icons_available'
            }
        },

        /**
         * Init Target elements method
         *
         * @public
         * @params {Object} data - current data {activeLevel, columns}
         * @return {void}
         */
        init: function (data) {
            this._initElems(data.activeLevel);
            this.setCurrentColor(data.activeLevel, this.color_settings.submenu_text);
            data.activeLevel = ko.observable(data.activeLevel);
        },

        /**
         * Set prev level from current active level parent
         *
         * @public
         * @params {Object} activeLevel - current active level
         * @return {Boolean} for stop or continuous propagation
         */
        setPreviousLevel: function (activeLevel) {
            if (activeLevel().level() > 1) {
                this.setCurrentColor(activeLevel(), activeLevel().base_color);
                activeLevel(activeLevel().parent);

                return false;
            }

            return true;
        },

        /**
         * Set next level from current active level elems
         *
         * @public
         * @params {Object} activeLevel - current active level
         * @params {Object} elem - target elem
         * @return {Boolean} for continuous propagation if this link
         */
        setNextLevel: function (activeLevel, elem) {
            if (elem.elems.length) {
                this.setCurrentColor(elem, this.color_settings.submenu_text);
                activeLevel(elem);

                return false;
            }

            return true;
        },

        /**
         * Menu item hover handler
         *
         * @public
         * @params {Object} elem
         * @return {void}
         */
        onMouseenter: function (event) {
            $(event.target.parentElement).find('.ammenu-category-tree').removeClass('active');
            $(event.target.parentElement).find('.ammenu-category-tree').removeClass('-index-0');
            $(event.target).addClass('active');
        },

        /**
         * Menu item mouse leave handler
         *
         * @public
         * @params {Object} elem
         * @return {void}
         */
        onMouseleave: function (event) {

        },

        /**
         * Set Menu Elem Target Color
         *
         * @public
         * @params {Object} elem
         * @params {String} color
         * @return {void}
         */
        setCurrentColor: function (elem, color) {
            if (elem.current) {
                elem.color(this.color_settings.current_category_color);
            } else {
                elem.color(color);
            }
        },

        /**
         * Init Target elements method
         *
         * @private
         * @params {Object} elems
         * @return {void}
         */
        _initElems: function (element) {
            var self = this;

            _.each(element.elems, function (elem) {
                if (elem.elems.length) {
                    self._initElems(elem);
                }

                self._initElem(elem);
            });

            if (element.elems.length) {
                helpers.initAllItemLink(element, this.color_settings.third_level_menu);
            }
        },

        /**
         * Init Target element method
         *
         * @params {Object} elem
         * @return {void}
         */
        _initElem: function (elem) {
            this.setCurrentColor(elem, this.color_settings.third_level_menu);
            elem.base_color = this.color_settings.third_level_menu;
        }
    });
});
