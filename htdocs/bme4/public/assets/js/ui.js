/**
* APP - UI
*/
var APP = APP || {};
var loader;

(function () {
    APP.UI = {
        init: function () {
            // session alert checker
            APP.UI.loadSessionAlert();

            // bind tabs with overflowing content
            APP.UI.bindTabOverflow();
        },

        /**
         * @function APP.UI.loadSessionAlert
         * - Checks if an alert is present in session, alerts if found
         *
         */
        loadSessionAlert: function () {
            var sessionAlert = sessionStorage.getItem('alert');
            if (!sessionAlert || sessionAlert === 'null') {
                return;
            }

            var alertObj;

            try {
                alertObj = JSON.parse(sessionAlert);
            } catch (error) {
                console.log('Session alert value should be a stringified json', error);
                return;
            }

            APP.UI.alert(alertObj);
            sessionStorage.setItem('alert', null);
        },

        /**
         * @function APP.UI.showLoader
         * - Prompts a message that a process is in progress
         * - Generally called when fetching data via ajax call
         *
         * @params {String} message - message content
         */
        showLoader: function(message) {
            Swal.fire({
                title: message || 'Processing...',
                allowOutsideClick: false,
                timerProgressBar: true,
                didOpen: function () {
                    Swal.showLoading();
                }
            });
        },

        /**
         * @function APP.UI.hideLoader
         * - Closes a prompted alert loader
         * - Generally called when ajax call has finished 
         *
         */
        hideLoader: function() {
            Swal.close();
        },

        /**
         * @function APP.UI.alert
         * - Prompts a toast message
         *
         * @params {Object} _opts option object that supports alert content { type, msg, position }
         */
        alert: function(_opts) {
            var opts = _opts || {};
            var type = opts.type || 'error';
            var message = opts.msg || 'Something went wrong while processing your request.';
            var position = opts.position || 'top-end';

            var Toast = Swal.mixin({
                toast: true,
                position: position,
                showConfirmButton: false,
                showCloseButton: true
            });

            Toast.fire({
              icon: type,
              title: message
            });
        },

        /**
         * @function APP.UI.showErrorMessages
         * - Shows an alert message for a list of errors
         *
         * @params {Array} errors array of string that contains a list of errors 
         * @params {String} _header custom header for the list of errors
         */
        showErrorMessages: function(errors, _header) {
            if (!errors || !errors.length) {
                return;
            }

            var header = _header || 'There was a problem while processing your request.';
            var messages = errors.join('<br/>');
            var alertContent = '<div class="alert alert-danger alert-dismissible ncmh-alert">' +
                '<i class="fa fa-exclamation-triangle" aria-hidden="true"></i>' +
                '<button class="close" data-dismiss="alert">&times;</button>' +
                '<strong> ' + header + '</strong><br/>' +
                '<span>' + messages + '</span>' +
                '</div>';

           $('.content-wrapper').prepend(alertContent);
        },

        /**
         * @function APP.UI.bindTabOverflow
         * - Binds scroller buttons when tab overflows
         *
         */
        bindTabOverflow: function() {
            var $navTabs = $('.nav-tabs');

            if (!$navTabs || !$navTabs.length) {
                return;
            }

            var minimizedPx = 175;
            var areTabsOverflowing = $navTabs.prop('scrollWidth') > $navTabs.width() + minimizedPx;
            if (!areTabsOverflowing) {
                return $('.tab-navigation').remove();
            }

            var tabNavigations = '<div class="form-inline tab-navigation">' +
                '<div class="scroller scroller-left"><h4 class="fa fa-angle-left"></h4></div>' +
                '&nbsp;' +
                '<div class="scroller scroller-right"><h4 class="fa fa-angle-right"></h4></div>' +
            '</div>';
            $('.nav-tabs-wrapper').append(tabNavigations);

            var currentPosition = 0;
            var scrollIncrement = 200;

            // bind listeners for newly appended scroller buttons
            $('.scroller-right').click(function() {
                currentPosition = $navTabs.scrollLeft() + scrollIncrement;
                $navTabs.animate({ scrollLeft: currentPosition }, 'fast');
            });

            $('.scroller-left').click(function() {
                currentPosition = $navTabs.scrollLeft() - scrollIncrement;
                $navTabs.animate({ scrollLeft: currentPosition }, 'fast');
            });

            APP.UI.resizeNavTabs();
            APP.UI.bindOnWindowResize();
        },

        /**
         * @function APP.UI.resizeNavTabs
         * - Resizes max width for tabs with overflowing content
         *
         */
        resizeNavTabs: function() {
            var $navTabs = $('.nav-tabs');
            var $navTabsWrapper = $('.nav-tabs-wrapper');

            if (!$navTabs || !$navTabs.length || !$navTabsWrapper || !$navTabsWrapper.length) {
                return;
            }

            var scrollerPx = 70;
            $navTabs.css('max-width', $navTabsWrapper.width() - scrollerPx);
        },

        /**
         * @function APP.UI.bindOnWindowResize
         * - Listens to window resize event to resize overflowing tabs
         *
         */
        bindOnWindowResize: function() {
            $(window).on('resize', function(e) {
                APP.UI.resizeNavTabs();
            });
        }
    };

    $(document).ready(function () {
        APP.UI.init();
    });
})();
