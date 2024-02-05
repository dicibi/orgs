<?php

namespace Dicibi\Orgs;

use Illuminate\Support\ServiceProvider;

class OrgServiceProvider extends ServiceProvider
{

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/orgs.php', 'orgs');
    }

    function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/orgs.php' => config_path('orgs.php')
            ], 'orgs-config');
        }

        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }
    }

}
