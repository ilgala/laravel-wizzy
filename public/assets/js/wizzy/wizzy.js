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
                loading: '<div class="loading col-md-12 text-center"><i class="fa fa-spinner fa-spin fa-2x fa-fw"></i></div>',
                environment: false,
                database: false,
                interface: {
                    $btnPrevious: $('<a />', {class: 'btn btn-default wizzy-previous-btn', disabled: true}),
                    $btnNext: $('<a />', {class: 'btn btn-default wizzy-next-btn', disabled: true}),
                    $btnComplete: $('<a />', {class: 'btn btn-default wizzy-next-btn', disabled: true}),
                }
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
            $this.$footer = $('<div />', {class: 'panel-footer wizzy-footer clearfix'});
            var row = $('<div />', {class: 'row'});
            var container = $('<div />', {class: 'col-md-offset-7 col-md-5'});
            var btnGroup = $('<div />', {class: 'btn-group btn-group-justified mbot-10'});
            // Setup locale
            $this.options.interface.$btnPrevious.html($.fn[pluginName].locale.interface.previous);
            $this.options.interface.$btnNext.html($.fn[pluginName].locale.interface.next);
            $this.options.interface.$btnComplete.html($.fn[pluginName].locale.interface.complete);
            $this.options.interface.$btnComplete.attr('href', $this.options.redirectUrl);
            $this.$element.append($this.$footer.append(row.append(container.append(btnGroup
                    .append($this.options.interface.$btnPrevious)
                    .append($this.options.interface.$btnNext)
                    .append($this.options.interface.$btnComplete)))));

            // Hide complete button
            $this.options.interface.$btnComplete.hide();

            $this.renderContent($this.$body, 1, $this.options.beforeRenderCallback, $this.options.afterRenderCallback);
            // previous button listener
            $this.options.interface.$btnPrevious.click(function (e) {
                e.preventDefault();
                var attr = $(this).attr('disabled');
                if (typeof attr === typeof undefined || attr === false) {
                    $this.renderContent($this.$body, $this.view.data('step') - 1, $this.options.beforeRenderCallback, $this.options.afterRenderCallback);
                }
            });
            // previous button listener
            $this.options.interface.$btnNext.click(function (e) {
                e.preventDefault();
                var attr = $(this).attr('disabled');
                if (typeof attr === typeof undefined || attr === false) {
                    if ($this.view.data('viewName') == 'environment') {
                        // Setup modal
                        var $modal = $('#wizzy-environment-confirm-modal');
                        $modal.find('.modal-title').html($.fn[pluginName].locale.views.environment.modal.title);
                        $modal.find('.modal-body').html($.fn[pluginName].locale.views.environment.modal.body);
                        $modal.find('.wizzy-undo-btn').html($.fn[pluginName].locale.views.environment.modal.undo);
                        $modal.find('.wizzy-undo-btn').click(function () {
                            $modal.find('.alert').remove();
                        });

                        $modal.find('.wizzy-accept-btn').html($.fn[pluginName].locale.views.environment.modal.confirm);
                        $modal.find('.wizzy-accept-btn').click(function () {
                            // Setup ajax data
                            var filename = '';
                            if ($this.environmentForm.find('input.wizzy-env-filename').val().length > 0) {
                                if (filename.startsWith('.')) {
                                    filename = filename.concat($this.environmentForm.find('input.wizzy-env-filename').val() + '.env');
                                } else {
                                    filename = filename.concat('.' + $this.environmentForm.find('input.wizzy-env-filename').val() + '.env');
                                }
                            }

                            var data = {
                                view: 'environment',
                                filename: filename,
                                variables: ''
                            };

                            $.each($this.environmentForm.find('input.wizzy-env-variable'), function (index, input) {
                                data.variables = data.variables.concat($(input).data('name') + ':' + $(input).val());

                                if (index < $this.environmentForm.find('input.wizzy-env-variable').length - 1) {
                                    data.variables = data.variables.concat('|');
                                }
                            });

                            $this._debug(data);

                            // Ajax call
                            $.ajax({
                                url: $this.options.executeRoute,
                                method: 'POST',
                                dataType: 'JSON',
                                data: data,
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                async: true,
                                success: function (response) {
                                    $this._debug(response);
                                    if (response.token !== undefined) {
                                        $this._debug('TOKEN REFRESH');
                                        $('meta[name="csrf-token"]').attr('content', response.token);
                                    }
                                    $modal.modal('hide');

                                    $this.renderContent($this.$body, $this.view.data('step') + 1, $this.options.beforeRenderCallback, $this.options.afterRenderCallback);
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    $this._error(jqXHR);
                                    $this._error(jqXHR.responseText);

                                    var error = JSON.parse(jqXHR.responseText);

                                    var alert = $('<div />', {class: 'alert alert-danger', html: error.variables[0]});

                                    $modal.find('.modal-body').append(alert);

                                    $this._error(errorThrown);
                                    $this._error(textStatus);
                                }
                            });
                        });

                        // Show modal
                        $modal.modal('show');
                    } else if ($this.view.data('viewName') == 'database') {
                        // Setup modal
                        var $modal = $('#wizzy-database-confirm-modal');
                        $modal.find('.modal-title').html($.fn[pluginName].locale.views.database.modal.title);
                        $modal.find('.modal-body').html($.fn[pluginName].locale.views.database.modal.body);
                        $modal.find('.wizzy-undo-btn').html($.fn[pluginName].locale.views.database.modal.undo);
                        $modal.find('.wizzy-undo-btn').click(function () {
                            $modal.find('.alert').remove();
                        });

                        $modal.find('.wizzy-accept-btn').html($.fn[pluginName].locale.views.database.modal.confirm);
                        $modal.find('.wizzy-accept-btn').click(function () {
                            // modify modal
                            var loadingRow = $('<div />', {class: 'row'});
                            var loading = $($this.options.loading).clone();
                            loading.html('<h3>' + $.fn[pluginName].locale.views.database.migrations + '</h3>' + loading.html());
                            $modal.find('.modal-body').append(loadingRow.append(loading));

                            // Setup ajax data
                            var data = {
                                view: 'database',
                                refresh: $this.databaseForm.find('input[name=refresh]').prop('checked'),
                                seed: $this.databaseForm.find('input[name=seed]').prop('checked')
                            };

                            $this._debug(data);

                            // Ajax call
                            $.ajax({
                                url: $this.options.executeRoute,
                                method: 'POST',
                                dataType: 'JSON',
                                data: data,
                                headers: {
                                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                                },
                                async: true,
                                success: function (response) {
                                    $this._debug(response);
                                    if (response.token !== undefined) {
                                        $this._debug('TOKEN REFRESH');
                                        $('meta[name="csrf-token"]').attr('content', response.token);
                                    }

                                    // Remove loading
                                    loadingRow.remove();

                                    $modal.modal('hide');

                                    $this.renderContent($this.$body, $this.view.data('step') + 1, $this.options.beforeRenderCallback, $this.options.afterRenderCallback);
                                },
                                error: function (jqXHR, textStatus, errorThrown) {
                                    $this._error(jqXHR);
                                    $this._error(jqXHR.responseText);

                                    // Remove loading
                                    loadingRow.remove();

                                    var error = JSON.parse(jqXHR.responseText);

                                    var alert = $('<div />', {class: 'alert alert-danger', html: error.variables[0]});

                                    $modal.find('.modal-body').append(alert);

                                    $this._error(errorThrown);
                                    $this._error(textStatus);
                                }
                            });
                        });

                        // Show modal
                        $modal.modal('show');
                    } else {
                        $this.renderContent($this.$body, $this.view.data('step') + 1, $this.options.beforeRenderCallback, $this.options.afterRenderCallback);
                    }
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

            // Setup variables
            var step = 1;
            var btnGroup = $('<div />', {class: 'btn-group btn-group-justified wizzy-step'});
            var welcomeBtn = $('<a />', {href: '#', class: 'btn btn-success', html: $.fn[pluginName].locale.views.welcome.title});

            // Welcome data
            welcomeBtn.data('step', step);
            welcomeBtn.data('stepEnabled', true);
            welcomeBtn.click(function () {
                var button = $(this);
                if (button.data('stepEnabled')) {
                    $this.renderContent($this.$body, button.data('step'), $this.options.beforeRenderCallback, $this.options.afterRenderCallback);
                }
            });
            btnGroup.append(welcomeBtn);
            step++;

            // Environment data
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
                step++;
            }

            // Database data
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
                step++;
            }

            // Conclusion data
            var conclusionBtn = $('<a />', {href: '#', class: 'btn btn-default disabled', html: $.fn[pluginName].locale.views.conclusion.title});
            conclusionBtn.data('step', step);
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
            container.append('<hr/>').append(subtitle).append(message).append('<hr/>');
        },
        _renderWelcomeView: function (container, step) {
            var $this = this;
            // Setup view
            $this.view = $('<div />', {class: 'wizzy-view wizzy-welcome'});
            $this.view.data('step', step);
            $this.view.data('viewName', 'welcome');
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
                        $this._debug('TOKEN REFRESH');
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
                    } else {
                        var reloadBtn = $('<button />', {class: 'btn btn-block btn-info', html: '<i class="fa fa-refresh"></i>'});
                        reloadBtn.click(function () {
                            window.location.reload();
                        });
                        requirements.append('<hr/>').append(reloadBtn)
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
        _renderEnvironmentView: function (container, step) {
            var $this = this;
            // Setup view
            $this.view = $('<div />', {class: 'wizzy-view wizzy-environment'});
            $this.view.data('step', step);
            $this.view.data('viewName', 'environment');
            $this._setupView($this.view, 'environment');

            // Update navigation
            var attr = $this.$footer.find('.wizzy-previous-btn').attr('disabled');

            if (typeof attr !== typeof undefined || attr !== false) {
                $this.$footer.find('.wizzy-previous-btn').removeAttr('disabled', false);
            }

            $this.options.interface.$btnNext.show();
            $this.options.interface.$btnComplete.hide();

            // Setup view content
            var environment = $('<div />', {class: 'col-md-12 wizzy-environment'});

            // Setup form
            $this.environmentForm = $('<form/>', {class: 'form-horizontal'});
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
                        $this._debug('TOKEN REFRESH');
                        $('meta[name="csrf-token"]').attr('content', response.token);
                    }

                    var formGroup = $('<div />', {class: 'form-group'});
                    var label = $('<label />', {class: 'col-sm-2 control-label', html: $.fn[pluginName].locale.views.environment.filename});
                    var container = $('<div />', {class: 'col-sm-10'});
                    var inputGroup = $('<div />', {class: 'input-group'});
                    var input = $('<input />', {type: 'text', class: 'wizzy-env-filename form-control', val: response.filename, placeholder: $.fn[pluginName].locale.views.environment.placeholder});
                    var dotEnvSpan = $('<span />', {class: 'input-group-addon', html: '.env'});
                    $this.environmentForm.append(formGroup.append(label).append(container.append(inputGroup.append(input).append(dotEnvSpan)))).append('<hr/>');
                    $.each(response.env_variables, function (key, value) {
                        var formGroup = $('<div />', {class: 'form-group'});
                        var label = $('<label />', {class: 'col-sm-2 control-label', html: key});
                        var container = $('<div />', {class: 'col-sm-10'});
                        var input = $('<input />', {type: 'text', class: 'wizzy-env-variable form-control', placeholder: $.fn[pluginName].locale.views.environment.placeholder, value: value});
                        input.data('name', key);
                        $this.environmentForm.append(formGroup.append(label).append(container.append(input)));
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
                        var valueInput = $('<input />', {type: 'text', class: 'wizzy-env-variable form-control', placeholder: $.fn[pluginName].locale.views.environment.placeholder});
                        $this.environmentForm.find('.wizzy-environment-actions').prepend(formGroup.append(removeContainer.append(removeBtn)).append(keyContainer.append(keyInput)).append(valueContainer.append(valueInput)));
                    });
                    $this.environmentForm.append(actionsFormGroup.append(actionsContainer.append(addBtn)));
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $this._error(jqXHR);
                    $this._error(jqXHR.responseText);
                    $this._error(errorThrown);
                    $this._error(textStatus);
                }
            });
            // Append view
            container.append($this.view.append(environment.append($this.environmentForm)));
        },
        _renderDatabaseView: function (container, step) {
            var $this = this;
            // Setup view
            $this.view = $('<div />', {class: 'wizzy-view wizzy-database'});
            $this.view.data('step', step);
            $this.view.data('viewName', 'database');
            $this._setupView($this.view, 'database');

            // Update navigation
            var attr = $this.$footer.find('.wizzy-previous-btn').attr('disabled');

            if (typeof attr !== typeof undefined || attr !== false) {
                $this.$footer.find('.wizzy-previous-btn').removeAttr('disabled', false);
            }

            $this.options.interface.$btnNext.show();
            $this.options.interface.$btnComplete.hide();

            // Setup view content
            var database = $('<div />', {class: 'col-md-12 wizzy-database'});

            // Setup migration list
            var list = $('<ul />', {class: 'list-group'});

            // setup form
            $this.databaseForm = $('<form/>', {class: 'form-horizontal'});

            // Ajax call
            $.ajax({
                url: $this.options.databaseRoute,
                method: 'GET',
                dataType: 'JSON',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                async: true,
                success: function (response) {
                    $this._debug(response);
                    if (response.token !== undefined) {
                        $this._debug('TOKEN REFRESH');
                        $('meta[name="csrf-token"]').attr('content', response.token);
                    }

                    $.each(response.migrations, function (index, migration) {
                        var li = $('<li />', {class: 'list-group-item', html: migration});
                        list.append(li);
                    });

                    var refreshFormGroup = $('<div />', {class: 'form-group col-md-6'});
                    var refreshLabel = $('<label />', {class: 'col-sm-10 control-label', html: '<input name="refresh" type="checkbox"> ' + $.fn[pluginName].locale.views.database.refresh});
                    var refreshContainer = $('<div />', {class: 'col-sm-2'});
                    $this.databaseForm.append(refreshFormGroup.append(refreshLabel).append(refreshContainer));

                    var seedFormGroup = $('<div />', {class: 'form-group col-md-6'});
                    var seedLabel = $('<label />', {class: 'col-sm-10 control-label', html: '<input name="seed" type="checkbox"> ' + $.fn[pluginName].locale.views.database.seed});
                    var seedContainer = $('<div />', {class: 'col-sm-2'});
                    $this.databaseForm.append(seedFormGroup.append(seedLabel).append(seedContainer));
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    $this._error(jqXHR);
                    $this._error(jqXHR.responseText);
                    $this._error(errorThrown);
                    $this._error(textStatus);
                }
            });

            // Append view
            container.append($this.view.append(database.append(list).append($this.databaseForm)));
        },
        _renderConclusionView: function (container, step) {
            var $this = this;
            // Setup view
            $this.view = $('<div />', {class: 'wizzy-view wizzy-conclusion'});
            $this.view.data('step', step);
            $this.view.data('viewName', 'conclusion');
            $this._setupView($this.view, 'conclusion');

            // Update navigation
            var attr = $this.$footer.find('.wizzy-previous-btn').attr('disabled');

            if (typeof attr === typeof undefined || attr === false) {
                $this.$footer.find('.wizzy-previous-btn').attr('disabled', true);
            }

            $this.options.interface.$btnNext.hide();
            $this.options.interface.$btnComplete.show();

            // Append view
            container.append($this.view);
        },
        // Public methods
        renderContent: function (container, type, beforeRenderCallback, afterRenderCallback) {
            var $this = this;
            // Setup container
            var loading = $($this.options.loading).clone();
            container.find('.wizzy-view').remove();
            container.append(loading);
            // Before render callback
            if (typeof beforeRenderCallback == 'function') {
                beforeRenderCallback(container, type);
            }

            // View render
            switch (type) {
                case 1:
                    $this._debug('render welcome view');
                    $this._renderWelcomeView(container, type);
                    break;
                case 2:
                    if ($this.options.environment) {
                        $this._debug('render environment view');
                        $this._renderEnvironmentView(container, type);
                    } else if ($this.options.database) {
                        $this._debug('render database view');
                        $this._renderDatabaseView(container, type);
                    } else {
                        $this._debug('render conclusion view');
                        $this._renderConclusionView(container, type);
                    }
                    break;
                case 3:
                    if ($this.options.database) {
                        $this._debug('render database view');
                        $this._renderDatabaseView(container, type);
                    } else {
                        $this._debug('render conclusion view');
                        $this._renderConclusionView(container, type);
                    }
                    break;
                case 4:
                    $this._debug('render conclusion view');
                    $this._renderConclusionView(container, type);
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