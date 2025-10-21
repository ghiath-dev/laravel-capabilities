<?php

namespace Holoultek\Capabilities;

use Holoultek\Capabilities\Commands\GenerateCapabilitiesCommand;
use Holoultek\Capabilities\Commands\GenerateRolesCommand;
use Holoultek\Capabilities\Middleware\CapabilitiesContextMiddleware;
use Holoultek\Capabilities\Middleware\CapabilityMiddleware;
use Holoultek\Capabilities\Services\UserContextManager;
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
        ], 'laravel-capabilities-config');

        $this->publishes([
            __DIR__.'/../database/migrations/create_capabilities_roles_tables.php.stub' => database_path('migrations/'.date('Y_m_d_His').'create_capabilities_roles_tables.php'),
        ], 'laravel-capabilities-migrations');

        $router = $this->app->make(Router::class);
        $router->aliasMiddleware('capability', CapabilityMiddleware::class);
        $router->aliasMiddleware('capabilities-context', CapabilitiesContextMiddleware::class);

        $this->app->singleton(UserContextManager::class, function () {
            return new UserContextManager();
        });

        AboutCommand::add('Laravel Capabilities', fn() => ['Version' => '0.1.2', 'Author' => 'ghiath-dev']);

        if ($this->app->runningInConsole()) {
            $this->commands([
                GenerateCapabilitiesCommand::class,
                GenerateRolesCommand::class,
            ]);
        }
    }
}
