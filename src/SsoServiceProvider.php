<?php

namespace RabbitDigital\SsoClient;

use Illuminate\Support\ServiceProvider;

class SsoServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__ . '/../config/config.php', 'sso_client');

        // Register the main class to use with the facade
        $this->app->singleton('sso_client', function () {
            return new SsoClient;
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/config.php' => config_path('sso_service_url.php'),
            ], 'config');
        }
    }
}
