<?php

namespace Dicibi\Orgs\Tests\Unit;

use Dicibi\Orgs\Organizer;
use Dicibi\Orgs\Resolvers\JobTitleResolver;
use Dicibi\Orgs\Resolvers\OfficeResolver;
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
        assert($orgs instanceof Organizer);

        self::assertTrue($orgs->structures() instanceof StructureResolver);
        self::assertTrue($orgs->jobTitles() instanceof JobTitleResolver);
        self::assertTrue($orgs->offices() instanceof OfficeResolver);
    }
}