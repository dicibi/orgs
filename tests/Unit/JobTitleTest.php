<?php

namespace Dicibi\Orgs\Tests\Unit;

use Dicibi\Orgs\Models\Job\Title;
use Dicibi\Orgs\Organizer;
use Dicibi\Orgs\OrgNodeModelWithNodeTrait;
use Dicibi\Orgs\Resolvers\JobTitleResolver;
use Dicibi\Orgs\Resolvers\StructureResolver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase;

class JobTitleTest extends TestCase
{
    use WithWorkbench;
    use RefreshDatabase;

    public function test_create_titles()
    {
        $orgs = app('orgs');

        assert($orgs instanceof Organizer);

        $jobTitle = $orgs->jobTitles();

        assert($jobTitle instanceof JobTitleResolver);

        $president = $jobTitle->create('CEO / President Director');

        $this->assertDatabaseCount(new Title, 1);

        $directorate1 = $jobTitle->create('Operational Director', attachTo: $president);
        $directorate2 = $jobTitle->create('Financial Director', attachTo: $president);
        $directorate3 = $jobTitle->create('Tech. Director', attachTo: $president);

        $this->assertDatabaseCount(new Title, 4);
    }

    /**
     * The tests include Commissioner Board and Directorate
     *
     * @return void
     */
    public function test_create_titles_and_structure()
    {
        $orgs = app('orgs');

        assert($orgs instanceof Organizer);

        $jobTitle = $orgs->jobTitles();

        assert($jobTitle instanceof JobTitleResolver);

        $structure = $orgs->structures();

        assert($structure instanceof StructureResolver);

        /**
         * Commissioner Board:
         *   President Commissioner
         *   |--> Commissioner)
         */
        $commissionerStructure = $structure->create('Commissioner Board');
        $presidentCommissioner = $jobTitle->create('President Commissioner', structure: $commissionerStructure);
        $commissioner = $jobTitle->create('Commissioner', structure: $commissionerStructure, attachTo: $presidentCommissioner);

        /**
         * Directorate Board:
         *   President Director
         *   |--> Director of Finance
         *   |--> Director of Operations
         *   |--> Director of Technology
         */
        $directorateStructure = $structure->create('Directorate');
        $presidentDirector = $jobTitle->create('President Director', structure: $directorateStructure);
        $directorOfFinance = $jobTitle->create('Director of Finance', structure: $directorateStructure, attachTo: $presidentDirector);
        $directorOfOperations = $jobTitle->create('Director of Operations', structure: $directorateStructure, attachTo: $presidentDirector);
        $directorOfTechnology = $jobTitle->create('Director of Technology', structure: $directorateStructure, attachTo: $presidentDirector);

        /**
         * We attach President Director to be under President Commissioner directly.
         * |--------------------------------------------------------|
         * |         President Commissioner  <---- ancestor-0       |
         * |                 |                                      |
         * |         ------------------------                       |
         * |         |                      |                       |
         * |    Commissioner(s)        President Director           |
         * |                               |                        |
         * |                     ----------------------------       |
         * |                    |            |              |       |
         * | under test -->  Finance    Operations      Technology  |
         * |                                                        |
         * |--------------------------------------------------------|
         */
        assert($presidentCommissioner instanceof OrgNodeModelWithNodeTrait);
        $presidentCommissioner->appendNode($presidentDirector);

        $this->assertEquals($directorOfFinance->getAncestors()->first()->name, $presidentCommissioner->name);

        assert($presidentDirector instanceof OrgNodeModelWithNodeTrait);
        $this->assertEquals($presidentDirector->parent()->first()->name, $presidentCommissioner->name);
    }

}