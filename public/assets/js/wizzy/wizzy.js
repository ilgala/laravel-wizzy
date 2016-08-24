/*
 *  Project: Laravel Wizzy
 *  Description: A laravel install wizard
 *  Author: IlGala
 *  License: MIT
 */
;
(function ($, window, document, undefined) {
    var pluginName = 'wizzy',
            defaults = {
                loading: '<div class="loading col-md-12 text-center"><i class="fa fa-spinner fa-spin fa-3x fa-fw"></i></div>',
                environment: false,
                database: false,
            },
            DEBUG = true;

    // The plugin constructor
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
            var title = $('<h2 />', {class: 'wizzy-title', html: $.fn[pluginName].locale.views.welcome.title});
            $this.$element.append($this.$header.append(title));

            // Body initialization
            $this.$body = $('<div />', {class: 'panel-body wizzy-body'});

            $this.$navigation = $('<div />', {class: 'col-md-12 wizzy-navigation mbot-10'});
            var btnGroup = $this._initializeNavigation();
            $this.$navigation.append(btnGroup);

            $this.$element.append($this.$body.append($this.$navigation));

            // Footer initialization
            $this.$footer = $('<div />', {class: 'panel-footer wizzy-footer'});
            var row = $('<div />', {class: 'row'});
            var container = $('<div />', {class: 'col-md-offset-8 col-md-4'});
            var btnGroup = $('<div />', {class: 'btn-group btn-group-justified'});
            var btnPrevious = $('<div />', {class: 'btn btn-default wizzy-previous-btn', disabled: true, html: $.fn[pluginName].locale.interface.previous});
            var btnNext = $('<div />', {class: 'btn btn-default wizzy-next-btn', disabled: true, html: $.fn[pluginName].locale.interface.next});

            $this.$element.append($this.$footer.append(row.append(container.append(btnGroup.append(btnPrevious).append(btnNext)))));
            $this.renderContent($this.$body, 1, $this.options.beforeRenderCallback, $this.options.afterRenderCallback);

            // previous button listener
            btnPrevious.click(function (e) {
                e.preventDefault();
                var attr = $(this).attr('disabled');

                if (typeof attr === typeof undefined || attr === false) {
                    $this.renderContent($this.$body, $this.view.data('step') - 1, $this.options.beforeRenderCallback, $this.options.afterRenderCallback);
                }
            });

            // previous button listener
            btnNext.click(function (e) {
                e.preventDefault();
                var attr = $(this).attr('disabled');

                if (typeof attr === typeof undefined || attr === false) {
                    $this.renderContent($this.$body, $this.view.data('step') + 1, $this.options.beforeRenderCallback, $this.options.afterRenderCallback);
                }
            });
        },
        // Utility methods
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
        _debug: function () {
            var console = window.console;

            if (console && console.debug && DEBUG) {
                return console.warn.apply(console, arguments);
            } else if (DEBUG) {
                return Wizzy.log.apply(Wizzy, arguments);
            }
        },
        _error: function () {
            var console = window.console;

            if (console && console.error) {
                return console.error.apply(console, arguments);
            }
        },
        // Private methods
        _initializeNavigation: function () {
            var $this = this;
            var btnGroup = $('<div />', {class: 'btn-group btn-group-justified wizzy-step'});

            var welcomeBtn = $('<a />', {href: '#', class: 'btn btn-success', html: $.fn[pluginName].locale.views.welcome.title});
            welcomeBtn.data('step', 1);
            welcomeBtn.data('stepEnabled', true);
            welcomeBtn.click(function () {
                var button = $(this);

                if (button.data('stepEnabled')) {
                    $this.renderContent($this.$body, button.data('step'), $this.options.beforeRenderCallback, $this.options.afterRenderCallback);
                }
            });
            btnGroup.append(welcomeBtn);

            if ($this.options.environment) {
                var environmentBtn = $('<a />', {href: '#', class: 'btn btn-default disabled', html: $.fn[pluginName].locale.views.environment.title});
                environmentBtn.data('step', 2);
                welcomeBtn.data('stepEnabled', false);
                environmentBtn.click(function () {
                    var button = $(this);

                    if (button.data('stepEnabled')) {
                        $this.renderContent($this.$body, button.data('step'), $this.options.beforeRenderCallback, $this.options.afterRenderCallback);
                    }
                });
                btnGroup.append(environmentBtn);
            }

            if ($this.options.database) {
                var databaseBtn = $('<a />', {href: '#', class: 'btn btn-default disabled', html: $.fn[pluginName].locale.views.database.title});
                databaseBtn.data('step', 3);
                welcomeBtn.data('stepEnabled', false);
                databaseBtn.click(function () {
                    var button = $(this);

                    if (button.data('stepEnabled')) {
                        $this.renderContent($this.$body, button.data('step'), $this.options.beforeRenderCallback, $this.options.afterRenderCallback);
                    }
                });
                btnGroup.append(databaseBtn);
            }

            var conclusionBtn = $('<a />', {href: '#', class: 'btn btn-default disabled', html: $.fn[pluginName].locale.views.conclusion.title});
            conclusionBtn.data('step', 4);
            welcomeBtn.data('stepEnabled', false);
            conclusionBtn.click(function () {
                var button = $(this);

                if (button.data('stepEnabled')) {
                    $this.renderContent($this.$body, button.data('step'), $this.options.beforeRenderCallback, $this.options.afterRenderCallback);
                }
            });
            btnGroup.append(conclusionBtn);

            return btnGroup;
        },
        _setupView: function (container, view) {
            var $this = this;
            $this.$header.find('.wizzy-title').html($.fn[pluginName].locale.views[view].title);

            var subtitle = $('<h4 />', {class: 'wizzy-view-title', html: $.fn[pluginName].locale.views[view].subtitle});
            var message = $('<p />', {class: 'wizzy-view-title', html: $.fn[pluginName].locale.views[view].message});

            container.append(subtitle).append(message).append('<hr/>');
        },
        _renderWelcomeView: function (container) {
            var $this = this;

            // Setup view
            $this.view = $('<div />', {class: 'wizzy-welcome'});
            $this.view.data('step', 1);

            $this._setupView($this.view, 'welcome');

            // Update navigation
            $this.$footer.find('.wizzy-previous-btn').attr('disabled', true);

            // Setup view content
            var requirements = $('<div />', {class: 'col-md-12 wizzy-requirements'});
            var phpList = $('<ul />', {class: 'list-group'});
            var extensionsList = $('<ul />', {class: 'list-group'});

            $.ajax({
                url: $this.options.welcomeRoute,
                method: 'GET',
                dataType: 'JSON',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                async: true,
                success: function (response) {
                    $this._debug(response);

                    if (response.token !== undefined) {
                        $('meta[name="csrf-token"]').attr('content', response.token);
                    }

                    // Php version
                    var itemClass = response.version.required ? 'list-group-item-danger' : response.version.preferred ? 'list-group-item-warning' : 'list-group-item-success'
                    var li = $('<li />', {class: 'list-group-item ' + itemClass});

                    var iconValue = response.version.required && response.version.preferred ? 'fa-times' : 'fa-check';
                    var text = '';
                    if (response.version.length > 0 && response.version.required) {
                        text = $.fn[pluginName].locale.views.welcome.requiredVersion + ' ' + response.version;
                    } else if (response.version.length > 0 && response.version.required) {
                        text = $.fn[pluginName].locale.views.welcome.preferredVersion + ' ' + response.version;
                    } else {
                        text = $.fn[pluginName].locale.views.welcome.version;
                    }

                    var badge = '<span class="badge"><i class="fa ' + iconValue + '"></i></span> ' + text;

                    phpList.append(li.append(badge));

                    // Extensions
                    $.each(response.extensions, function (key, value) {
                        var itemClass = value == false ? 'list-group-item-danger' : 'list-group-item-success';
                        var iconValue = value == false ? 'fa-times' : 'fa-check';

                        var li = $('<li />', {class: 'list-group-item ' + itemClass});
                        var badge = '<span class="badge"><i class="fa ' + iconValue + '"></i></span> ' + key + (value != false ? ': ' + value : '');

                        extensionsList.append(li.append(badge));
                    });

                    // Enable next
                    if (response.nextEnabled == true) {
                        $this.$footer.find('.wizzy-next-btn').removeAttr('disabled');
                    }
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $this._error(jqXHR);
                    $this._error(jqXHR.responseText);
                    $this._error(errorThrown);
                    $this._error(textStatus);
                }
            });

            // Append view
            var phpSeparator = $('<h4 />', {html: $.fn[pluginName].locale.views.welcome.php});
            var extensionsSeparator = $('<h4 />', {html: $.fn[pluginName].locale.views.welcome.extensions});
            container.append($this.view.append(requirements.append(phpSeparator).append(phpList).append(extensionsSeparator).append(extensionsList)));
        },
        _renderEnvironmentView: function (container) {
            var $this = this;

            // Setup view
            $this.view = $('<div />', {class: 'wizzy-environment'});
            $this.view.data('step', 2);

            $this._setupView($this.view, 'environment');

            // Update navigation
            $this.$footer.find('.wizzy-previous-btn').removeAttr('disabled', true);

            // Setup view content
            var environment = $('<div />', {class: 'col-md-12 wizzy-environment'});

            // Setup view content
            var environmentForm = $('<form/>', {class: 'form-horizontal'});

            $.ajax({
                url: $this.options.environmentRoute,
                method: 'GET',
                dataType: 'JSON',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                async: true,
                success: function (response) {
                    $this._debug(response);

                    if (response.token !== undefined) {
                        $('meta[name="csrf-token"]').attr('content', response.token);
                    }

                    var formGroup = $('<div />', {class: 'form-group'});
                    var label = $('<label />', {class: 'col-sm-2 control-label', html: $.fn[pluginName].locale.views.environment.filename});
                    var container = $('<div />', {class: 'col-sm-10'});
                    var inputGroup = $('<div />', {class: 'input-group'});
                    var dotSpan = $('<span />', {class: 'input-group-addon', html: '.'});
                    var input = $('<input />', {type: 'text', class: 'form-control', placeholder: $.fn[pluginName].locale.views.environment.placeholder});
                    var dotEnvSpan = $('<span />', {class: 'input-group-addon', html: '.env'});

                    environmentForm.append(formGroup.append(label).append(container.append(inputGroup.append(dotSpan).append(input).append(dotEnvSpan)))).append('<hr/>');

                    $.each(response.env_variables, function (key, value) {
                        var formGroup = $('<div />', {class: 'form-group'});
                        var label = $('<label />', {class: 'col-sm-2 control-label', html: key});
                        var container = $('<div />', {class: 'col-sm-10'});
                        var input = $('<input />', {type: 'text', class: 'form-control', placeholder: $.fn[pluginName].locale.views.environment.placeholder, value: value});

                        environmentForm.append(formGroup.append(label).append(container.append(input)));
                    });

                    var actionsFormGroup = $('<div />', {class: 'wizzy-environment-actions form-group'});
                    var actionsContainer = $('<div />', {class: 'col-sm-offset-2 col-sm-10'});
                    var addBtn = $('<button />', {type: 'button', class: 'btn btn-sm btn-primary', html: '<i class="fa fa-plus"></i> ' + $.fn[pluginName].locale.views.environment.add});
                    addBtn.click(function () {
                        var formGroup = $('<div />', {class: 'form-group'});
                        var removeContainer = $('<div />', {class: 'col-sm-2 text-center'});
                        var removeBtn = $('<button />', {type: 'button', class: 'btn btn-sm btn-danger', html: '<i class="fa fa-minus"></i>'});
                        removeBtn.click(function () {
                            $(this).parent().parent().remove();
                        });

                        var keyContainer = $('<div />', {class: 'col-sm-2'});
                        var keyInput = $('<input />', {type: 'text', class: 'form-control', placeholder: $.fn[pluginName].locale.views.environment.placeholder, value: 'NEW_KEY'});
                        var valueContainer = $('<div />', {class: 'col-sm-8'});
                        var valueInput = $('<input />', {type: 'text', class: 'form-control', placeholder: $.fn[pluginName].locale.views.environment.placeholder});

                        environmentForm.find('.wizzy-environment-actions').prepend(formGroup.append(removeContainer.append(removeBtn)).append(keyContainer.append(keyInput)).append(valueContainer.append(valueInput)));
                    });

                    var saveBtn = $('<button />', {type: 'button', class: 'btn btn-sm btn-success pull-right', html: '<i class="fa fa-save"></i> ' + $.fn[pluginName].locale.views.environment.save});
                    saveBtn.click(function () {
                        $.ajax({
                            url: $this.options.environmentRoute,
                            method: 'GET',
                            dataType: 'JSON',
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                            },
                            async: true,
                            success: function (response) {

                            },
                            error: function (jqXHR, textStatus, errorThrown) {
                                $this._error(jqXHR);
                                $this._error(jqXHR.responseText);
                                $this._error(errorThrown);
                                $this._error(textStatus);
                            }
                        });
                    });

                    environmentForm.append(actionsFormGroup.append(actionsContainer.append(addBtn).append(saveBtn)));
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $this._error(jqXHR);
                    $this._error(jqXHR.responseText);
                    $this._error(errorThrown);
                    $this._error(textStatus);
                }
            });

            // Append view
            container.append($this.view.append(environment.append(environmentForm)));
        },
        _renderDatabaseView: function (container) {
            var $this = this;

            // Setup view
            $this.view = $('<div />', {class: 'wizzy-database'});
            $this.view.data('step', 3);

            $this._setupView($this.view, 'database');

            // Setup view content
            // Append view
            container.append($this.view);
        },
        _renderConclusionView: function (container) {
            var $this = this;

            // Setup view
            $this.view = $('<div />', {class: 'wizzy-conclusion'});
            $this.view.data('step', 4);

            $this._setupView($this.view, 'conclusion');

            // Setup view content
            // Append view
            container.append($this.view);
        },
        // Public methods
        renderContent: function (container, type, beforeRenderCallback, afterRenderCallback) {
            var $this = this;

            // Setup container
            var loading = $($this.options.loading).clone();
            container.empty();
            container.append(loading);

            // Before render callback
            if (typeof beforeRenderCallback == 'function') {
                beforeRenderCallback(container, type);
            }

            // View render
            switch (type) {
                case 1:
                    $this._debug('render welcome view');
                    $this._renderWelcomeView(container);
                    break;
                case 2:
                    $this._debug('render environment view');
                    $this._renderEnvironmentView(container);
                    break;
                case 3:
                    $this._debug('render database view');
                    $this._renderDatabaseView(container);
                    break;
                case 4:
                    $this._debug('render conclusion view');
                    $this._renderConclusionView(container);
                    break;
                default:
                    $this._error('undefined view :(');
            }

            // Remove loader
            container.find('.loading').remove();

            // After render callback
            if (typeof afterRenderCallback == 'function') {
                afterRenderCallback(container, type);
            }
        }
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