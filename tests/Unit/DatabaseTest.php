<?php

namespace Dicibi\Orgs\Tests\Unit;

use Dicibi\Orgs\Models\Job;
use Dicibi\Orgs\Models\Structure;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase;

class DatabaseTest extends TestCase
{
    use WithWorkbench;
    use RefreshDatabase;

    public function test_job_families_table()
    {
        Job\Family::query()->create([
            'name' => 'Lorem ipsum',
            'order' => 1
        ]);
        self::assertEquals(1, Job\Family::query()->count());

        $family = Job\Family::query()->first();
        assert($family instanceof Job\Family);

        self::assertEquals('Lorem ipsum', $family->name);
        self::assertEquals(1, $family->order);

        $family->update([
            'name' => 'Lorem',
            'order' => 2
        ]);
        $family->fresh();
        self::assertEquals('Lorem', $family->name);
        self::assertEquals(2, $family->order);

        $family->delete();
        self::assertEquals(0, Job\Family::query()->count());
    }

    public function test_structures_table()
    {
        Structure::query()->create([
            'name' => 'Lorem ipsum',
            'description' => 'describe'
        ]);
        self::assertEquals(1, Structure::query()->count());

        $structure = Structure::query()->first();
        assert($structure instanceof Structure);

        self::assertEquals('Lorem ipsum', $structure->name);
        self::assertEquals('describe', $structure->description);

        $structure->update([
            'name' => 'Lorem',
            'description' => 'update desc'
        ]);
        $structure->fresh();
        self::assertEquals('Lorem', $structure->name);
        self::assertEquals('update desc', $structure->description);

        $structure->delete();
        self::assertEquals(0, Structure::query()->count());
    }

    public function test_structures_table_nested()
    {
        Structure::query()->create([
            'name' => 'Lorem ipsum',
            'description' => 'describe'
        ]);
        self::assertEquals(1, Structure::query()->count());

        $structure = Structure::query()->first();
        assert($structure instanceof Structure);

        self::assertEquals('Lorem ipsum', $structure->name);
        self::assertEquals('describe', $structure->description);

        $child = Structure::query()->create([
            'name' => 'Lorem child',
            'description' => 'another'
        ]);
        assert($child instanceof Structure);

        self::assertFalse($structure->isDescendantOf($child));
        self::assertFalse($child->isDescendantOf($structure));

        $structure->appendNode($child);

        $structure->fresh();
        $child->fresh();

        self::assertTrue($structure->isAncestorOf($child));
        self::assertTrue($child->isDescendantOf($structure));

        for ($i = 0; $i < 5; $i++) {
            $descendant = Structure::query()->create([
                'name' => 'New child',
                'description' => 'another'
            ]);
            assert($descendant instanceof Structure);

            $structure->appendNode($descendant);
        }

        $structure->fresh();

        self::assertEquals(6, $structure->children()->count());

        $child->delete();

        self::assertEquals(5, $structure->children()->count());
    }

    public function test_titles_table()
    {
        $jobFamily = Job\Family::query()->create([
            'name' => 'Job Family A',
            'order' => 1
        ]);
        assert($jobFamily instanceof Job\Family);

        $structure = Structure::query()->create([
            'name' => 'Structure A',
            'description' => 'lorem ipsum'
        ]);
        assert($structure instanceof Structure);

        Job\Title::query()->create([
            'name' => 'Lorem ipsum',
            'description' => 'describe',
            'job_family_id' => $jobFamily->id,
            'structure_id' => $structure->id,
        ]);
        self::assertEquals(1, Job\Title::query()->count());

        $title = Job\Title::query()->first();
        assert($title instanceof Job\Title);

        self::assertEquals('Lorem ipsum', $title->name);
        self::assertEquals('describe', $title->description);
        self::assertEquals($jobFamily->id, $title->job_family_id);
        self::assertEquals($structure->id, $title->structure_id);
    }
}