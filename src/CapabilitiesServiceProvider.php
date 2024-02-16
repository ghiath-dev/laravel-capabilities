<?php

namespace Holoultek\Capabilities;

use Holoultek\Capabilities\Commands\GenerateCapabilitiesCommand;
use Holoultek\Capabilities\Commands\GenerateRolesCommand;
use Holoultek\Capabilities\Middleware\CapabilityMiddleware;
use Illuminate\Foundation\Console\AboutCommand;
use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;

class CapabilitiesServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/../config/capabilities.php' => config_path('capabilities.php'),
            __DIR__.'/../config/roles.php' => config_path('roles.php'),
        ], 'laravel-capabilities');

        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('capability', CapabilityMiddleware::class);

        AboutCommand::add('Laravel Capabilities', fn () => ['Version' => '0.0.5']);

        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateCapabilitiesCommand::class,
                GenerateRolesCommand::class,
            ]);
        }
    }
}
