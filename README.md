Laravel-wizzy
============
Laravel-wizzy was created by, and is maintained by [Filippo Galante](https://github.com/IlGala), and is a configurable laravel install wizard that permits a user to check system requirements, setup environment and run database migrations and seed. Feel free to check out the [change log](CHANGELOG.md), [releases](https://github.com/IlGala/laravel-wizzy/releases), [license](LICENSE), and [contribution guidelines](CONTRIBUTING.md).
## Installation

Either [PHP](https://php.net) 5.5+ or [HHVM](http://hhvm.com) 3.6+ are required.

To get the latest version of Laravel Wizzy, simply require the project using [Composer](https://getcomposer.org):

```bash
$ composer require ilgala/wizzy
```

Instead, you may of course manually update your require block and run `composer update` if you so choose:

```json
{
    "require": {
        "ilgala/wizzy": "^1.0"
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

This will create a `config/wizzy.php` file in your app that you can modify to set your configuration. Also, make sure you check for changes to the original config file in this package between releases.

There are several config options:

##### OPT1

This option [...]


## Usage

##### Facades\Wizzy

This facade is used [...].

##### WizzyServiceProvider

This class contains no public methods of interest. This class should be added to the providers array in `config/app.php`. This class will setup ioc bindings.

##### WizzyMiddleware

##### Views

##### Wizzy plugin

##### i18n

##### Real Examples

Here you can see a few examples of how you can customize the wizard.


## License

Laravel Wizzy is licensed under [The MIT License (MIT)](LICENSE).

# Known issues

 - On MAMP, when calling `Artisan::call('config:clear'); Artisan::call('config:cache');` Laravel seems not to update environment variables from the .env file