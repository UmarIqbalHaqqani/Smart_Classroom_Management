<?php

namespace Larabuild\Pagebuilder;

use Exception;

class ServiceProvider extends \Illuminate\Support\ServiceProvider {
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register() {

        $configPath = __DIR__ . '/../config/pagebuilder.php';
        $this->mergeConfigFrom($configPath, 'pagebuilder');
        $this->app->bind('page_settings', function () {
            return new \Larabuild\Pagebuilder\PageSettings;
        });

        require_once('helpers.php');
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot() {
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        $this->loadViewsFrom(__DIR__ . '/../resources/views', 'pagebuilder');

        $this->loadTranslationsFrom(__DIR__ . '/../lang', 'pagebuilder');

        $this->publishes([
            __DIR__ . '/../config/pagebuilder.php' => config_path('pagebuilder.php')
        ], 'config');

        $this->publishes([
            __DIR__ . '/../public' => public_path('public/vendor/pagebuilder'),

        ], 'assets');

        $this->publishes([
            __DIR__ . '/../routes' => base_path('routes'),
        ], 'routes');

        $this->publishes([
            __DIR__ . '/../demo/pagebuilder' => resource_path('views/pagebuilder'),
            __DIR__ . '/../resources/views/layouts/pb-site.blade.php' => resource_path('views/layouts/pb-site.blade.php'),
            __DIR__ . '/../demo/css' => public_path('demo/css'),
            __DIR__ . '/../demo/images' => public_path('demo/images'),
        ], 'demos');

        if (!$this->app->runningInConsole()) {
            if (empty(config('pagebuilder'))) {
                throw new Exception("No pagebuilder config found, please run: php artisan vendor:publish --provider=\"Larabuild\Pagebuilder\ServiceProvider\" --tag=config");
            }
        }
    }
}
