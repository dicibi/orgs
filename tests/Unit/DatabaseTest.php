<?php

namespace Dicibi\Orgs\Tests\Unit;

use Dicibi\Orgs\Models\Job\Family;
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
        Family::query()->create([
            'name' => 'Lorem ipsum',
            'order' => 1
        ]);
        self::assertEquals(1, Family::query()->count());

        /** @var Family $family */
        $family = Family::query()->first();
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
        self::assertEquals(0, Family::query()->count());
    }

    public function test_structures_table()
    {
        Structure::query()->create([
            'name' => 'Lorem ipsum',
            'description' => 'describe'
        ]);
        self::assertEquals(1, Structure::query()->count());

        /** @var Structure $structure */
        $structure = Structure::query()->first();
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
        self::assertEquals(0, Family::query()->count());
    }

    public function test_structures_table_nested()
    {
        Structure::query()->create([
            'name' => 'Lorem ipsum',
            'description' => 'describe'
        ]);
        self::assertEquals(1, Structure::query()->count());

        /** @var Structure $structure */
        $structure = Structure::query()->first();
        self::assertEquals('Lorem ipsum', $structure->name);
        self::assertEquals('describe', $structure->description);

        /** @var Structure $child */
        $child = Structure::query()->create([
            'name' => 'Lorem child',
            'description' => 'another'
        ]);

        self::assertFalse($structure->isDescendantOf($child));
        self::assertFalse($child->isDescendantOf($structure));

        $structure->appendNode($child);

        $structure->fresh();
        $child->fresh();

        self::assertTrue($structure->isAncestorOf($child));
        self::assertTrue($child->isDescendantOf($structure));

        for ($i = 0; $i < 5; $i++) {
            /** @var Structure $descendant */
            $descendant = Structure::query()->create([
                'name' => 'New child',
                'description' => 'another'
            ]);

            $structure->appendNode($descendant);
        }

        $structure->fresh();

        self::assertEquals(6, $structure->children()->count());

        $child->delete();

        self::assertEquals(5, $structure->children()->count());
    }
}