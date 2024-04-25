<?php

namespace Dicibi\Orgs\Tests\Unit;

use Dicibi\Orgs\Contracts\JobFamilyResolver;
use Dicibi\Orgs\Contracts\JobTitleResolver;
use Dicibi\Orgs\Contracts\OfficeResolver;
use Dicibi\Orgs\Contracts\OrganizationResolver;
use Dicibi\Orgs\Resolvers\StructureResolver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase;

class FacadeTest extends TestCase
{
    use WithWorkbench;
    use RefreshDatabase;

    /**
     * @throws \Throwable
     */
    public function test_facade_check()
    {
        $orgs = $this->app->get('orgs');
        assert($orgs instanceof OrganizationResolver);

        self::assertTrue($orgs->structures() instanceof StructureResolver);
        self::assertTrue($orgs->jobFamilies() instanceof JobFamilyResolver);
        self::assertTrue($orgs->jobTitles() instanceof JobTitleResolver);
        self::assertTrue($orgs->offices() instanceof OfficeResolver);
    }
}