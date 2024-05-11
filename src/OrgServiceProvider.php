<?php

namespace Dicibi\Orgs;

use Dicibi\Orgs\Contracts\Model\JobClanContract;
use Dicibi\Orgs\Contracts\Model\JobFamilyContract;
use Dicibi\Orgs\Contracts\Model\OfficeContract;
use Dicibi\Orgs\Resolvers\Actions\JobTitleNestedActions;
use Dicibi\Orgs\Resolvers\Actions\OfficeNestedActions;
use Dicibi\Orgs\Resolvers\Actions\StructureNestedActions;
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

        // config checks
        if (!is_null(config('orgs.office'))) {
            $officeModel = config('orgs.office');
            assert((new $officeModel) instanceof OfficeContract);
        }

        if (!is_null(config('orgs.job_family'))) {
            $jobFamilyModel = config('orgs.job_family');
            assert((new $jobFamilyModel) instanceof JobFamilyContract);
        }

        if (!is_null(config('orgs.job_clan'))) {
            $jobClanModel = config('orgs.job_clan');
            assert((new $jobClanModel) instanceof JobClanContract);
        }

        $this->app->bind('orgs', function (Application $app) {
            return new Organizer(
                new StructureResolver(new StructureNestedActions()),
                new JobTitleResolver(new JobTitleNestedActions()),
                new OfficeResolver(new OfficeNestedActions())
            );
        });
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
