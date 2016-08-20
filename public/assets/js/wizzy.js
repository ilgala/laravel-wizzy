/*
 *  Project: Laravel Wizzy
 *  Description: A laravel install wizard
 *  Author: IlGala
 *  License: MIT
 */
;
(function ($, window, document, undefined) {
    // Create the defaults once
    var pluginName = 'wizzy',
            defaults = {
                DEBUG: true
            };

    // The actual plugin constructor
    function Wizzy(element, options) {
        this.element = element;
        this.$element = $(element);

        this.options = $.extend({}, defaults, options);

        this._defaults = defaults;
        this._name = pluginName;

        this.init();
    }

    Wizzy.prototype = {
        init: function () {
            var $this = this;

            // Header initialization
            $this.$header = $('<div />', {class: 'panel-heading wizzy-heading'});
            var title = $('<h2 />', {class: 'wizzy-title', html: $.fn[pluginName].locale.welcome.title});
            $this.$element.append($this.$header.append(title));
        },
        // Private methods
        _log: function () {
            var console = window.console;

            if (console && console.log) {
                return console.log.apply(console, arguments);
            }
        },
        _warning: function () {
            var console = window.console;

            if (console && console.warn) {
                return console.warn.apply(console, arguments);
            } else {
                return Wizzy.log.apply(Wizzy, arguments);
            }
        },
        _error: function () {
            var console = window.console;

            if (console && console.error) {
                return console.error.apply(console, arguments);
            }
        },
        // Public methods
    };

    $.fn[pluginName] = function (options) {
        var args = arguments;

        // Is the first parameter an object (options), or was omitted,
        // instantiate a new instance of the plugin.
        if (options === undefined || typeof options === 'object') {
            // Global and per element defaults extension
            if (!(this instanceof $)) {
                $.extend(defaults, options)
            }

            return this.each(function () {

                // Only allow the plugin to be instantiated once,
                // so we check that the element has no plugin instantiation yet
                if (!$.data(this, 'plugin_' + pluginName)) {

                    // if it has no instance, create a new one,
                    // pass options to our plugin constructor,
                    // and store the plugin instance
                    // in the elements jQuery data object.
                    $.data(this, 'plugin_' + pluginName, new Wizzy(this, options));
                }
            });

            // If the first parameter is a string and it doesn't start
            // with an underscore or "contains" the `init`-function,
            // treat this as a call to a public method.
        } else if (typeof options === 'string' && options[0] !== '_' && options !== 'init') {

            // Cache the method call
            // to make it possible
            // to return a value
            var returns;

            this.each(function () {
                var instance = $.data(this, 'plugin_' + pluginName);

                // Tests that there's already a plugin-instance
                // and checks that the requested public method exists
                if (instance instanceof Wizzy && typeof instance[options] === 'function') {

                    // Call the method of our plugin instance,
                    // and pass it the supplied arguments.
                    returns = instance[options].apply(instance, Array.prototype.slice.call(args, 1));
                }

                // Allow instances to be destroyed via the 'destroy' method
                if (options === 'destroy') {
                    $.data(this, 'plugin_' + pluginName, null);
                }
            });

            // If the earlier cached method
            // gives a value back return the value,
            // otherwise return this to preserve chainability.
            return returns !== undefined ? returns : this;
        }
    };

}(jQuery, window, document));