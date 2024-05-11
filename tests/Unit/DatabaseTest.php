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

    public function test_structures_table()
    {
        Structure::query()->forceCreate([
            'name' => 'Lorem ipsum',
            'description' => 'describe'
        ]);
        self::assertEquals(1, Structure::query()->count());

        $structure = Structure::query()->first();
        assert($structure instanceof Structure);

        self::assertEquals('Lorem ipsum', $structure->name);
        self::assertEquals('describe', $structure->description);

        $structure->forceFill([
            'name' => 'Lorem',
            'description' => 'update desc'
        ]);
        $structure->save();

        $structure->fresh();

        self::assertEquals('Lorem', $structure->name);
        self::assertEquals('update desc', $structure->description);

        $structure->delete();
        self::assertEquals(0, Structure::query()->count());
    }

    public function test_structures_table_nested()
    {
        Structure::query()->forceCreate([
            'name' => 'Lorem ipsum',
            'description' => 'describe'
        ]);
        self::assertEquals(1, Structure::query()->count());

        $structure = Structure::query()->first();
        assert($structure instanceof Structure);

        self::assertEquals('Lorem ipsum', $structure->name);
        self::assertEquals('describe', $structure->description);

        $child = Structure::query()->forceCreate([
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
            $descendant = Structure::query()->forceCreate([
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
        $structure = Structure::query()->forceCreate([
            'name' => 'Structure A',
            'description' => 'lorem ipsum'
        ]);
        assert($structure instanceof Structure);

        Job\Title::query()->forceCreate([
            'name' => 'Lorem ipsum',
            'description' => 'describe',
            'structure_id' => $structure->id,
        ]);
        self::assertEquals(1, Job\Title::query()->count());

        $title = Job\Title::query()->first();
        assert($title instanceof Job\Title);

        self::assertEquals('Lorem ipsum', $title->name);
        self::assertEquals('describe', $title->description);
        self::assertEquals($structure->id, $title->structure_id);
    }
}