<?php

namespace Dicibi\Orgs;

use Dicibi\Orgs\Resolvers\JobTitleResolver;
use Dicibi\Orgs\Resolvers\OfficeResolver;
use Dicibi\Orgs\Resolvers\StructureResolver;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\ServiceProvider;

class OrgServiceProvider extends ServiceProvider
{

    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/orgs.php', 'orgs');

        $this->app->bind('orgs', fn(Application $app) => new Organizer(
            $app->make(StructureResolver::class),
            $app->make(JobTitleResolver::class),
            $app->make(OfficeResolver::class),
        ));
    }

    function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/orgs.php' => config_path('orgs.php')
            ], 'orgs-config');
        }

        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
        }
    }

}
