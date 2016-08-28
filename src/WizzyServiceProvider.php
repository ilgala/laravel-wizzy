<?php

/*
 * This file is part of Laravel Wizzy package.
 *
 * (c) Filippo Galante <filippo.galante@b-ground.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace IlGala\LaravelWizzy;

use Illuminate\Foundation\Application as LaravelApplication;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Laravel\Lumen\Application as LumenApplication;

/**
 * This is the Wizzy service provider class.
 *
 * @author ilgala
 */
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
        $this->handleViews();
        $this->handleAssets();
        $this->handleRoutes();
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app['wizzy'] = $this->app->share(function () {
            return new Wizzy();
        });

        $this->app->alias('wizzy', Wizzy::class);
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

    /**
     * Setup wizzy config.
     *
     * @return void
     */
    private function handleConfigs()
    {
        $source = realpath(__DIR__.'/../config/wizzy.php');

        if ($this->app instanceof LaravelApplication && $this->app->runningInConsole()) {
            $this->publishes([$source => config_path('wizzy.php')]);
        } elseif ($this->app instanceof LumenApplication) {
            $this->app->configure('wizzy');
        }

        $this->mergeConfigFrom($source, 'wizzy');
    }

    /**
     * Setup wizzy views.
     *
     * @return void
     */
    private function handleViews()
    {
        $this->loadViewsFrom(__DIR__.'/Views', 'wizzy');

        $this->publishes([__DIR__.'/Views' => base_path('resources/views/vendor/wizzy')]);
    }

    /**
     * Setup wizzy assets.
     *
     * @return void
     */
    private function handleAssets()
    {
        $this->publishes([__DIR__.'/../public/assets' => public_path('assets')], 'public');
    }

    /**
     * Setup wizzy routes.
     *
     * @return void
     */
    private function handleRoutes()
    {
        include __DIR__.'/routes.php';
    }
}
