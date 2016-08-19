<?php

namespace IlGala\LaravelWizzy;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

class WizzyServiceProvider extends LaravelServiceProvider
{

    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Bootstrap the application events.
     *
     * @return void
     */
    public function boot()
    {

        $this->handleConfigs();
        // $this->handleMigrations();
        $this->handleViews();
        $this->handleTranslations();
        $this->handleRoutes();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {

        $this->app['wizzy'] = $this->app->singleton('wizzy', function () {
            return new Wizzy();
        });

        $this->app->alias('github', Wizzy::class);
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {

        return ['wizzy'];
    }

    private function handleConfigs()
    {

        $configPath = __DIR__ . '/../config/wizzy.php';

        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$source => config_path('wizzy.php')]);
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('wizzy');
        }

        $this->mergeConfigFrom($configPath, 'wizzy');
    }

    private function handleTranslations()
    {

        $this->loadTranslationsFrom(__DIR__ . '/Lang', 'wizzy');
    }

    private function handleViews()
    {

        $this->loadViewsFrom(__DIR__ . '/Views', 'wizzy');

        $this->publishes([__DIR__ . '/Views' => base_path('resources/views/vendor/wizzy')]);
    }

    private function handleMigrations()
    {

        $this->publishes([__DIR__ . '/Migrations' => base_path('database/migrations')]);
    }

    private function handleRoutes()
    {

        include __DIR__ . '/routes.php';
    }

}
