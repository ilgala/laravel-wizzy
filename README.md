Laravel-wizzy
============
Laravel-wizzy was created by, and is maintained by [Filippo Galante](https://github.com/IlGala), and is a configurable laravel install wizard that permits a user to check system requirements, setup environment and run database migrations and seed. Feel free to check out the [change log](CHANGELOG.md), [releases](https://github.com/IlGala/laravel-wizzy/releases), [license](LICENSE), and [contribution guidelines](CONTRIBUTING.md).

[![StyleCI](https://styleci.io/repos/66010854/shield)](https://styleci.io/repos/66010854)
[![Latest Stable Version](https://poser.pugx.org/ilgala/laravel-wizzy/v/stable)](https://packagist.org/packages/ilgala/laravel-wizzy)
[![Total Downloads](https://poser.pugx.org/ilgala/laravel-wizzy/downloads)](https://packagist.org/packages/ilgala/laravel-wizzy)
[![Latest Unstable Version](https://poser.pugx.org/ilgala/laravel-wizzy/v/unstable)](https://packagist.org/packages/ilgala/laravel-wizzy)
[![License](https://poser.pugx.org/ilgala/laravel-wizzy/license)](https://packagist.org/packages/ilgala/laravel-wizzy)

## Installation

Requirements:

- PHP 5.5+
- Laravel

To get the latest version of Laravel Wizzy, simply require the project using [Composer](https://getcomposer.org):

```bash
$ composer require ilgala/wizzy
```

Instead, you may of course manually update your require block and run `composer update` if you so choose:

```json
{
    "require": {
        "ilgala/wizzy": "dev-master"
    }
}
```

Once Laravel Wizzy is installed, you need to register the service provider. Open up `config/app.php` and add the following to the `providers` key.

* `IlGala\LaravelWizzy\WizzyServiceProvider::class`

You can register the Wizzy facade in the `aliases` key of your `config/app.php` file if you like.

* `'Wizzy' => 'IlGala\LaravelWizzy\Facades\Wizzy'`


## Configuration

Laravel Wizzy supports optional configuration.

To get started, you'll need to publish all vendor assets:

```bash
$ php artisan vendor:publish
```

This will create a `config/wizzy.php` file in your app that you can modify to set your configuration. The `publish` command will also copy the package's views and the `public/assets` folder. Also, make sure you check for changes to the original config file in this package between releases.

There are several config options:

##### Wizzy Enable flag
This option specifies if the wizzy is enabled and will be considered only if the .env file doesn't contain any WIZZY_ENABLED key.
    
    Default: true

##### Application System Requirements
This set of options specifies the application requirements for php and the filesystem permissions. Only the required PHP version is mandatory forthe wizard.
    
    Default values
        'php' => [
            'required' => 'x.x.x', // Default: laravel required version
            'preferred' => 'x.x.x', // Default: laravel required version
        ],
        'php_extensions' => [ // Default: laravel required extensions
            'OpenSSL',
            'PDO',
            'Mbstring',
            'Tokenizer',
        ],
        'permissions' => [ // Default: laravel filesystem permissions
            'storage/app/' => '775',
            'storage/framework/' => '775',
            'storage/logs/' => '775',
            'bootstrap/cache/' => '775',
        ],
    ],
##### Wizzy Steps
This set of options specifies if the environment view and the database view are enabled in the wizard. If the parameters are setted to false, the wizard will skip one or both steps.
      
    environment default: true
    database default: true
  
##### Wizzy Routes Prefix
This option specifies Wizzy's routes group prefix in order to avoid conflicts with other routes of the application.

    Default: install

##### Environment Filename
Application's environment file used to parse the environment variables.

    Default: .env

##### Migrations Path
Application's path to migrations files.

    Default: database/migrations

##### Force Flag
If this option is setted to true, the migration command will be runned with --force attribute.

    Default: false

##### Conclusion Scripts
This set of options contains all the artisan scripts that will be runned during the last step of the wizard.

The defaults scripts runned are:

- **clear-compiled:** Remove the compiled class file
- **optimize:** Optimize the framework for better performance
- **config:clear:** Remove the configuration cache file
- **config:cache:** Create a cache file for faster configuration loading

For more informations about the artisan commands please refer to the official documentation.

##### Redirect To
This is the url used to redirect the user when the application install process is completed.

    Default: /


## Usage

##### Facades\Wizzy

This facade will dynamically pass static method calls to the 'wizzy' object in the ioc container which by default is the `IlGala\LaravelWizzy\Wizzy` class. This facade may be also used for a custom wizard creation. The declared methods may be used as helpers function for creating environment files, run database migrations or artisan commands.

    /**
     * Get wizzy route group prefix from the config file.
     *
     * @return string wizzy.prefix
     */
    Wizzy::getPrefix();

    /**
     * Get wizzy default evnironment filename from the config file.
     *
     * @return string wizzy.environment
     */
    Wizzy::getDefaultEnv();

    /**
     * Get wizzy conclusion view redirect url from the config file.
     *
     * @return string wizzy.redirectTo
     */
    Wizzy::getRedirectUrl();

    /**
     * Check if wizzy is enabled from the config file.
     *
     * @return bool true|false
     */
    Wizzy::isWizzyEnabled();

    /**
     * Check if wizzy environment step is enabled from the config file.
     *
     * @return string wizzy.steps.environment
     */
    Wizzy::isEnvironmentStepEnabled();

    /**
     * Check if wizzy database step is enabled from the config file.
     *
     * @return string wizzy.steps.database
     */
    Wizzy::isDatabaseStepEnabled();

    /**
     * Stores the $variables array as an environment file. If the $wizzy_enabled
     * variable is true, then it will add WIZZY_ENABLED=false variable in the
     * .env file.
     *
     * @param string $filename
     * @param string $variables
     * @param boolean $wizzy_enabled
     * @return string filename
     */
    Wizzy::($filename, $variables, $wizzy_enabled = false);

    /**
     * Runs the artisan 'migrate' command.
     *
     * @param string $path
     * @param boolean $refresh_database
     * @param boolean $seed_database
     */
    Wizzy::runMigration($path, $refresh_database, $seed_database);

    /**
     * Retrieve all the migration files in the given path.
     *
     * @param type $path
     * @return array
     */
    Wizzy::getMigrationsList($path);

    /**
     * Runs an artisan command.
     *
     * @param type $command
     * @param type $attributes
     * @return void
     */
    Wizzy::artisanCall($command, $attributes = []);



##### WizzyServiceProvider

This class contains no public methods of interest. This class should be added to the providers array in `config/app.php`. This class will setup ioc bindings.

##### WizzyMiddleware

Wizzy includes a middleware used to redirect the application routes to the installation wizard. In order to enable the redirection, you have to register the middleware as global in the app/Http/Kernel.php file like this:

    <?php

    namespace App\Http;

    use Illuminate\Foundation\Http\Kernel as HttpKernel;

    class Kernel extends HttpKernel
    {

        /**
         * The application's route middleware groups.
         *
         * @var array
         */
        protected $middlewareGroups = [
            'web' => [
                // Other middlewares
                // Register laravel wizzy as global middleware
                \IlGala\LaravelWizzy\Middleware\WizzyMiddleware::class,
            ],
            'api' => [
                'throttle:60,1',
            ],
        ];

Otherwise you can register the middleware as individual and select which routes of your application may run the wizard.

        /**
         * The application's route middleware.
         *
         * These middleware may be assigned to groups or used individually.
         *
         * @var array
         */
        protected $routeMiddleware = [
            // Other middlewares
            // Register laravel wizzy as individual middleware
            'wizzy' => \IlGala\LaravelWizzy\Middleware\WizzyMiddleware::class,
        ];

    }

##### WizzyController and WizzyInterface

The `WizzyInterface` can be used to create a `CustomWizardController` that may be called by the ajax calls of the jQueryPlugin.

###### routes.php


    Route::group(['prefix' => Wizzy::getPrefix(), 'namespace' => 'IlGala\LaravelWizzy', 'middleware' => 'web'], function () {
        Route::get('wizzy', ['as' => Wizzy::getPrefix() . '.wizzy', 'uses' => 'WizzyController@index']);
        Route::get('environment', ['as' => Wizzy::getPrefix() . '.environment', 'uses' => 'WizzyController@environment']);
        Route::get('database', ['as' => Wizzy::getPrefix() . '.database', 'uses' => 'WizzyController@database']);
        Route::get('conclusion', ['as' => Wizzy::getPrefix() . '.conclusion', 'uses' => 'WizzyController@conclusion']);
        Route::post('execute', ['as' => Wizzy::getPrefix() . '.execute', 'uses' => 'WizzyController@execute']);
    });

###### index(Request $request)

This method is called when the first wizard step is created. Required output for the jQuery plugin:
- `version`: 
    ```
    array [
        'required' => true|false,
        'preferred' => true|false,
        'version' => current_version|preferred_version|empty string
    ];
    ```
- `extensions`: 
   ```
    array [
        'ext1' => false|ext1 version,
        'ext2' => false|ext2 version,
        'ext3' => false|ext3 version,
        [...]
    ];
    ```
- `nextEnabled`: 
    
    ```
    true|false
    ```
    
###### environment(Request $request)
This method is called when the environment wizard step is created. Required output for the jQuery plugin:

- `filename`: 
    ```
    string
    ```
- `env_variables`: 
    ```
    array [
        'key' => 'variable value',
        'key' => 'variable value',
        'key' => 'variable value',
        'key' => 'variable value',
    ];
    ```
###### database(Request $request)
This method is called when the database wizard step is created. Required output for the jQuery plugin:

- `migrations`: 
    ```
    array [
        'migration name or migration filename',
        'migration name or migration filename',
        'migration name or migration filename',
        'migration name or migration filename',
    ];
    ```

###### conclusion(Request $request)
This method is called when the conclusion wizard step is created. The jQuery plugin doesn't require any output.

###### execute(Request $request)
This method is called from the Wizzy jQuery plugin to store the environment variables and run the database migration. The ajax call data object contains the following keys:

- Enviornment
    ```
    data = {
        view: 'environment',
        filename: 'filename',                           <-- default .env
        variables: 'key:value|key:value|key:value'      <-- default pattern
    };
    ```
- Enviornment
    ```
    data = {
        view: 'database',
        refresh: true|false,            <-- use to run migrate:refresh command
        seed: true|false,               <-- migrate:refresh --seed
    };
    ```


##### Views

This package is composed of three views:

- `index.blade.php`: This view has the same structure of the default views genereted with the `make:auth` command.
- `confirm_environment.blade.php`: The environment variables confirmation modal.
- `confirm_database.blade.php`: The database migration start modal.

The package's views are stored in `resources/views/vendor/wizzy`.

##### Wizzy plugin

The front-end jQuery plugin has been created using multiple patterns from [jQuery Boilerplate](https://jqueryboilerplate.com/). The plugin will initialize the wizard, manage the navigation between the steps and call the back-end using `$.ajax` calls.

The plugin is stored in `public/assets/js/wizzy` folder:
```
public/assets/js/wizzy
|-- css                 <-- Plugin css folder
|   |-- wizzy.css
|-- i18n                <-- Plugin locale folder
|   |-- en.js
|   |-- it.js
|   |-- [...]
|-- wizzy.js            <-- This is the plugin
```

###### Plugin initialization
    
    // Remember to include the scripts and the locale strings
    <script src="/assets/js/wizzy/wizzy.js"></script>
    <script src="/assets/js/wizzy/i18n/en.js"></script>
    
    // Initialization
    <script>
        $(document).ready(function () {
            $('#wizzy').wizzy({
                environment: {{ Wizzy::isEnvironmentStepEnabled() }},
                database: {{ Wizzy::isDatabaseStepEnabled() }},
                redirectUrl: "{{ Wizzy::getRedirectUrl() }}",
                welcomeRoute: "{{ route(Wizzy::getPrefix() . '.wizzy') }}",
                environmentRoute: "{{ route(Wizzy::getPrefix() . '.environment') }}",
                databaseRoute: "{{ route(Wizzy::getPrefix() . '.database') }}",
                conclusionRoute: "{{ route(Wizzy::getPrefix() . '.conclusion') }}",
                executeRoute: "{{ route(Wizzy::getPrefix() . '.execute') }}",
            });
        });
    </script>

###### Plugin options

Option | Default | Description |
------------ | ------------- | ------------- |
| environment      |  false | Enable/disable environment step |
| database         |  false | Enable/disable database step    |
| redirectUrl      |        /       | Conclusion step redirect url    |
| welcomeRoute     |        -       | GET ajax call used to populate welcome view |
| environmentRoute |        -       | GET ajax call used to populate enviornment view |
| databaseRoute    |        -       | GET ajax call used to populate database view |
| conclusionRoute  |        -       | GET ajax call used to populate conclusion view |
| executeRoute     |        -       | POST ajax call used to store environment variables and run database migrations |

###### Plugin callbacks

Callback | Default | Input | Description |
------------ | ------------- | ------------- | ------------- |
| beforeRenderCallback | - | container, view | Called everytime a new view is shown but when the container is still empty or has been cleared of the previous view elements |
| afterRenderCallback | - | container, view | Called after a new view content has been created |
| navigationCallback  | - | button, view | Called when a view navigation button is clicked anyway before beforeRenderCallback |
| previousCallback    | - | button, view | Called when the previous button is clicked anyway before beforeRenderCallback |
| nextCallback        | - | button, view |Called when the previous button is clicked anyway before beforeRenderCallback or before the store ajax call |
| conclusionCallback  | - | button, view |called when the previous button is clicked anyway before beforeRenderCallback |
| undoCallback | - | button, modal | Called when the undo modal button is clicked |
| environmentCallback | - | button, modal, data | environment submit button onClick event, if returns false no ajax call will be performed |
| environmentCallback | - | button, modal, data | database submit button onClick event, if returns false no ajax call will be performed |

###### Plugin methods

The plugin has the following public methods that can be called in this way:

    $('.wizzy').wizzy('publicMethod', ...);
    
Method | Input | Description |
------------ | ------------- | ------------- |
renderContent | container, view, beforeRenderCallback, afterRenderCallback | This method renders one of the wizard views. The container must be a jquery object (ex. `$('<div />')`), the view is a integer (1 => welcome, 2 => environment or database or conclusion, 3 => database or conclusion, 4: conclusion).  |

##### i18n

The translation files are located in `assets/js/wizzy/i18n`. The wizard has been translated in the following languages:

- **EN**: english
- **IT**: italian

If your language is missing please open an issue or make a pull request!

##### Examples

Here you can see a few examples of how you can customize the wizard.

###### Callback examples
   
    // Remember to include the scripts and the locale strings
    <script src="/assets/js/wizzy/wizzy.js"></script>
    <script src="/assets/js/wizzy/i18n/en.js"></script>
    
    // Initialization
    <script>
        $(document).ready(function () {
            $('#wizzy').wizzy({
                beforeRenderCallback: function(container, view) {
                    console.log(container); // jQuery object
                    console.log(view);      // 1, 2, 3, 4
                },
                afterRenderCallback: function(container, view) {
                    console.log(container); // jQuery object
                    console.log(view);      // 1, 2, 3, 4
                },
                navigationCallback: function(button, view) {
                    console.log(button);    // jQuery object
                    console.log(view);      // jQuery object
                },
                previousCallback: function(button, view) {
                    console.log(button);    // jQuery object
                    console.log(view);      // jQuery object
                },
                nextCallback: function(button, view) {
                    console.log(button);    // jQuery object
                    console.log(view);      // jQuery object
                },
                conclusionCallback: function(button, view) {
                    console.log(button);    // jQuery object
                    console.log(view);      // jQuery object
                },
                undoCallback: function(button, modal) {
                    console.log(button);    // jQuery object
                    console.log(modal);     // jQuery object
                },
                environmentRoute: function(container, view, data) {
                    console.log(button);    // jQuery object
                    console.log(view);      // jQuery object
                    console.log(data);      // jQuery object
                    
                    return true; // Ajax call will be performed,
                },
                environmentRoute: function(container, view, data) {
                    console.log(button);    // jQuery object
                    console.log(view);      // jQuery object
                    console.log(data);      // jQuery object
                    
                    return false; // Ajax call won't be performed,
                },
            });
        });
    </script>

###### Controller examples

    COMING SOON

## License

Laravel Wizzy is licensed under [The MIT License (MIT)](LICENSE).

## Known issues

- The alpha phase just started... I'm looking for issues...