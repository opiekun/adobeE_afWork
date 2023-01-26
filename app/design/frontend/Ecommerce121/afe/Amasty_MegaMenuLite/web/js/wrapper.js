/**
 *  Amasty MegaMenu Wrapper UI Component
 *
 *  @desc Component Mega Menu Lite Module
 */

define([
    'jquery',
    'ko',
    'uiComponent',
    'underscore',
    'ammenu_color_helper'
], function ($, ko, Component, _, colorHelper) {
    'use strict';

    return Component.extend({
        defaults: {
            template: 'Amasty_MegaMenuLite/wrapper',
            templates: {
                drill_wrapper: 'Amasty_MegaMenu/sidebar_menu/drill/wrapper',
                sidebar_type_switcher: 'Amasty_MegaMenuLite/sidebar_menu/type_switcher',
                greetings: 'Amasty_MegaMenuLite/components/greetings',
                link: 'Amasty_MegaMenuLite/components/items/link',
                label: 'Amasty_MegaMenuLite/components/items/label',
                close_button: 'Amasty_MegaMenuLite/components/buttons/close',
                icon: 'Amasty_MegaMenuLite/components/icon',
                hamburger: 'Amasty_MegaMenuLite/hamburger_menu/top/wrapper',
                menu_title: 'Amasty_MegaMenuLite/sidebar_menu/title',
                hamburger_items: 'Amasty_MegaMenuLite/hamburger_menu/items',
                tree_active_level: 'Amasty_MegaMenuLite/components/tree/active_level',
                accordion: 'Amasty_MegaMenuLite/sidebar_menu/accordion/wrapper'
            },
            icons: {
                create_account: 'Amasty_MegaMenuLite/components/icons/create_account',
                currency: 'Amasty_MegaMenuLite/components/icons/currency',
                exit: 'Amasty_MegaMenuLite/components/icons/exit',
                language: 'Amasty_MegaMenuLite/components/icons/language',
                settings: 'Amasty_MegaMenuLite/components/icons/settings',
                sign_in: 'Amasty_MegaMenuLite/components/icons/sign_in',
                user: 'Amasty_MegaMenuLite/components/icons/user',
                wishlist: 'Amasty_MegaMenuLite/components/icons/wishlist',
                chevron: 'Amasty_MegaMenuLite/components/icons/chevron',
                double_chevron: 'Amasty_MegaMenuLite/components/icons/double_chevron'
            },
            view_port: {
                height: $(window).height(),
                width: $(window).width()
            },
            settings: {
                delay: 100
            }
        },

        /**
         * @inheritDoc
         */
        initialize: function () {
            var self = this;

            self._super();

            self.initElem(self.data);
            self.initElems(self.data.elems, 0, self.data);

            this._generateBaseColors();
            return self;
        },

        /**
         * @inheritDoc
         */
        initObservable: function () {
            this._super()
                .observe({
                    isSticky: false
                });

            this.data.isRoot = true;

            return this;
        },

        /**
         * Init Target elements method
         *
         * @params {Object} elems
         * @params {Number} level
         * @params {Object} parent
         * @return {void}
         */
        initElems: function (elems, level, parent) {
            var self = this;

            _.each(elems, function (elem, index) {
                self.initElem(elem, level, parent, index);

                if (elem.elems.length) {
                    self.initElems(elem.elems, level + 1, elem);
                }
            });
        },

        /**
         * Init Target element colors method
         *
         * @params {Object} elem
         * @return {void}
         */
        initElemColors: function (elem) {
            var self = this;

            elem.color = ko.observable(elem.current ? self.color_settings.current_category_color : self.color_settings.main_menu_text);
            elem.base_color = elem.color();
        },

        /**
         * Init Target element method
         *
         * @params {Object} elem
         * @params {Number} level
         * @params {Object} parent
         * @params {Object} index element index position
         * @return {void}
         */
        initElem: function (elem, level, parent, index) {
            elem.isActive = ko.observable(false);
            elem.level = ko.observable(level);
            elem.isContentActive = ko.observable(false);
            elem.isSubmenuVisible = ko.observable(true);
            elem.additionalClasses = [];
            elem.isVisible = true;

            if (!_.isUndefined(index)) {
                elem.navLevelSelector = 'nav-' + index;
            }

            if (!elem.is_category) {
                this._initCustomItem(elem);
            }

            if (level === 0) {
                this._initRoot(elem);
            }

            if (parent) {
                elem.parent = parent;
            }

            if (parent && !_.isUndefined(parent.navLevelSelector)) {
                elem.navLevelSelector = parent.navLevelSelector + '-' + index;
            }

            this.initElemColors(elem);
        },

        /**
         * Init root submenu element
         *
         * @param {Object} elem
         * @return {void}
         */
        _initRoot: function (elem) {
            elem.width_value = ko.observable(elem.width_value);
            elem.nodes = {};

            elem.isSubmenuVisible(
                !elem.submenu_type && elem.content && elem.content.trim().length > 7 ||
                elem.submenu_type && elem.type.value && !elem.hide_content && elem.elems.length
            );

            if (elem.width === 0) {
                elem.width_value('100%');
            }

            if (elem.width === 1) {
                elem.width_value('max-content');
            }

            if (elem.width_value() && elem.width === 2) {
                elem.width_value(elem.width_value() + 'px');
            }
        },

        /**
         * Init Custom item
         *
         * @params {Object} elem
         * @return {void}
         */
        _initCustomItem: function (elem) {
            if (
                elem.status === 2 && this.isMobile ||
                elem.status === 3 && !this.isMobile
            ) {
                elem.isVisible = false;
            }
        },

        /**
         * Generating base color setting from base customers colors
         * @return {void}
         */
        _generateBaseColors: function () {
            this.color_settings.border = colorHelper.getLighten(this.color_settings.toggle_icon_color, 0.16);
            this.color_settings.third_level_menu = colorHelper.getAltered(this.color_settings.submenu_text, 0.2);
            this.color_settings.toggle_icon_color_hover = colorHelper.getDarken(this.color_settings.toggle_icon_color, 0.2);
            this.color_settings.toggle_icon_color_active = colorHelper.getDarken(this.color_settings.toggle_icon_color, 0.3);
        }
    });
});
