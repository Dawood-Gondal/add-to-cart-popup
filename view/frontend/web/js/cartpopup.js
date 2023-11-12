/**
 * @category    M2Commerce Enterprise
 * @package     M2Commerce_AddToCartPopup
 * @copyright   Copyright (c) 2023 M2Commerce Enterprise
 * @author      dawoodgondaldev@gmail.com
 */

define([
    'jquery',
    'uiElement',
    'ko',
    'Magento_Customer/js/customer-data',
    'Magento_Ui/js/modal/modal'
], function ($, Component, ko, customerData, modal) {

    'use strict';

    var modalPopupSelector = '[data-placeholder="cartpopup"]';

    return Component.extend({
        defaults: {
            template: 'M2Commerce_AddToCartPopup/popup'
        },
        isUpdated: ko.observable(false),
        products: ko.observable(false),
        successIcon: ko.observable(),
        cartTotalCount: ko.observable(),
        cartMessage: ko.observable(),
        shoppingCartUrl: '',

        /**
         * Update modal popup content.
         *
         * @param {Object} updatedCart
         * @returns void
         */
        update: function (updatedCart) {
            this.products(updatedCart.products);
            this.applyCartMessageBinding();
            this.cartTotalCount(updatedCart.cartTotalCount);
            this.isUpdated(false);

            $(modalPopupSelector).modal("openModal");
        },

        /**
         * Close Modal Popup Action
         */
        closeModal: function () {
            $(modalPopupSelector).modal("closeModal");
        },

        /**
         * Redirect to Shopping Cart
         */
        viewShoppingCart: function () {
            window.location.replace(this.shoppingCartUrl);
        },

        /**
         * Apply bindings to element loaded inside of cartMessage
         */
        applyCartMessageBinding: function () {
            ko.applyBindingsToNode(
                document.getElementById(
                    'cart-popup-total-count'),
                {text: this.cartTotalCount}
            );
        },

        /**
         * Initialize Component
         * @returns {*}
         */
        initialize: function () {
            var self = this,
                cartPopupData = customerData.get('cartpopup');

            /**
             * Update CartPopup only when an addToCart action is triggered
             */
            $(document).on('ajax:addToCart', function (sku, productIds, form, response) {
                self.isUpdated(true);
            });

            /**
             * Subscribe to changes on the CustomerData component
             */
            cartPopupData.subscribe(function (updatedCart) {
                if (self.isUpdated()) {
                    self.update(updatedCart);
                }
            }, this);

            /**
             * Set Modal Popup Component options
             */
            var options = {
                type: 'popup',
                responsive: true,
                innerScroll: false,
                modalClass: 'no-header-footer',
                buttons: [{
                    text: $.mage.__('Close'),
                    class: '',
                    click: function () {
                        this.closeModal();
                    }
                }]
            };

            modal(options, $(modalPopupSelector));

            return self._super();
        }
    })
});


