<?php

namespace Dicibi\Orgs\Tests\Unit;

use Dicibi\Orgs\Organizer;
use Dicibi\Orgs\Resolvers\StructureResolver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase;

class StructureTest extends TestCase
{
    use WithWorkbench;
    use RefreshDatabase;

    function test_structure()
    {
        $orgs = app('orgs');

        assert($orgs instanceof Organizer);

        $structure = $orgs->structures();

        assert($structure instanceof StructureResolver);

        $centralStructure = $structure->create('Central Office');

        $this->assertDatabaseCount($centralStructure, 1);

        /**
         * Central Office
         *  |--Branch Office
         */
        $branchStructure = $structure->create('Branch Office', attachTo: $centralStructure);

        $this->assertDatabaseCount($branchStructure, 2);

        $parent = $structure->tree($centralStructure);

        $this->assertEquals(1, $parent->children()->count());


        /**
         * Central Office           <-- ancestor 0
         *  |--Branch Office        <-- ancestor 1
         *     |--Support Branch Office
         */
        $supportBranchStructure = $structure->create('Support Branch Office', attachTo: $branchStructure);

        $this->assertEquals(2, $supportBranchStructure->getAncestors()->count());
        $this->assertEquals($supportBranchStructure->getAncestors()->first()->name, $centralStructure->name);
        $this->assertEquals($supportBranchStructure->getAncestors()->get(1)->name, $branchStructure->name);

        $supportBranchStructure->saveAsRoot(); // make it have no ancestors
        $this->assertTrue($supportBranchStructure->isRoot());
        $this->assertEquals(0, $supportBranchStructure->getAncestors()->count());
    }
}