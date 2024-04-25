<?php

namespace Dicibi\Orgs\Tests\Unit;

use Dicibi\Orgs\Models\Structure;
use Dicibi\Orgs\OrgModelWithNodeTrait;
use Dicibi\Orgs\Resolvers\Actions\StructureNestedActions;
use Dicibi\Orgs\Resolvers\StructureResolver;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase;

class NestedStructureTest extends TestCase
{
    use WithWorkbench;
    use RefreshDatabase;

    /**
     * @throws \Throwable
     */
    public function test_attach_action()
    {
        $actions = new StructureNestedActions();
        $resolver = new StructureResolver($actions);

        $root = Structure::query()->create([
            'name' => fake()->name,
            'description' => fake()->words(asText: true),
        ]);
        assert($root instanceof OrgModelWithNodeTrait);

        $child1 = Structure::query()->create([
            'name' => fake()->name,
            'description' => fake()->words(asText: true),
        ]);
        assert($child1 instanceof OrgModelWithNodeTrait);

        $child1_child1 = Structure::query()->create([
            'name' => fake()->name,
            'description' => fake()->words(asText: true),
        ]);
        assert($child1_child1 instanceof OrgModelWithNodeTrait);

        $child1_child1_child1 = Structure::query()->create([
            'name' => fake()->name,
            'description' => fake()->words(asText: true),
        ]);
        assert($child1_child1_child1 instanceof OrgModelWithNodeTrait);

        $child1_child2 = Structure::query()->create([
            'name' => fake()->name,
            'description' => fake()->words(asText: true),
        ]);
        assert($child1_child2 instanceof OrgModelWithNodeTrait);

        $child2 = Structure::query()->create([
            'name' => fake()->name,
            'description' => fake()->words(asText: true),
        ]);
        assert($child2 instanceof OrgModelWithNodeTrait);

        $child3 = Structure::query()->create([
            'name' => fake()->name,
            'description' => fake()->words(asText: true),
        ]);
        assert($child3 instanceof OrgModelWithNodeTrait);

        $child3_child1 = Structure::query()->create([
            'name' => fake()->name,
            'description' => fake()->words(asText: true),
        ]);
        assert($child3_child1 instanceof OrgModelWithNodeTrait);

        $resolver->actions()->attach($child1, $root);
        $resolver->actions()->attach($child1_child1, $child1);
        $resolver->actions()->attach($child1_child2, $child1);
        $resolver->actions()->attach($child1_child1_child1, $child1_child1);
        $resolver->actions()->attach($child2, $root);
        $resolver->actions()->attach($child3, $root);
        $resolver->actions()->attach($child3_child1, $child3);

        self::assertTrue($root->isAncestorOf($child1));
        self::assertTrue($root->isAncestorOf($child2));
        self::assertTrue($root->isAncestorOf($child3));

        self::assertTrue($root->isAncestorOf($child1_child1));
        self::assertTrue($root->isAncestorOf($child1_child1_child1));
        self::assertTrue($root->isAncestorOf($child1_child2));
        self::assertTrue($root->isAncestorOf($child3_child1));

        self::assertFalse($child1->isRoot());
        self::assertFalse($child2->isRoot());
        self::assertFalse($child3->isRoot());

        self::assertEquals(2, $child1->children()->count());
        self::assertEquals(3, $child1->descendants()->count());
        self::assertEquals(1, $child3->children()->count());
        self::assertEquals(7, $root->descendants()->count());
    }

    /**
     * @throws \Throwable
     */
    public function test_delete_action_without_auto_adoption()
    {
        $actions = new StructureNestedActions();
        $resolver = new StructureResolver($actions);

        $root = Structure::query()->create([
            'name' => fake()->name,
            'description' => fake()->words(asText: true),
        ]);
        assert($root instanceof OrgModelWithNodeTrait);

        $child1 = Structure::query()->create([
            'name' => fake()->name,
            'description' => fake()->words(asText: true),
        ]);
        assert($child1 instanceof OrgModelWithNodeTrait);

        $child1_child1 = Structure::query()->create([
            'name' => fake()->name,
            'description' => fake()->words(asText: true),
        ]);
        assert($child1_child1 instanceof OrgModelWithNodeTrait);

        $child1_child1_child1 = Structure::query()->create([
            'name' => fake()->name,
            'description' => fake()->words(asText: true),
        ]);
        assert($child1_child1_child1 instanceof OrgModelWithNodeTrait);

        $child1_child2 = Structure::query()->create([
            'name' => fake()->name,
            'description' => fake()->words(asText: true),
        ]);
        assert($child1_child2 instanceof OrgModelWithNodeTrait);

        $child2 = Structure::query()->create([
            'name' => fake()->name,
            'description' => fake()->words(asText: true),
        ]);
        assert($child2 instanceof OrgModelWithNodeTrait);

        $child3 = Structure::query()->create([
            'name' => fake()->name,
            'description' => fake()->words(asText: true),
        ]);
        assert($child3 instanceof OrgModelWithNodeTrait);

        $child3_child1 = Structure::query()->create([
            'name' => fake()->name,
            'description' => fake()->words(asText: true),
        ]);
        assert($child3_child1 instanceof OrgModelWithNodeTrait);

        $resolver->actions()->attach($child1, $root);
        $resolver->actions()->attach($child1_child1, $child1);
        $resolver->actions()->attach($child1_child2, $child1);
        $resolver->actions()->attach($child1_child1_child1, $child1_child1);
        $resolver->actions()->attach($child2, $root);
        $resolver->actions()->attach($child3, $root);
        $resolver->actions()->attach($child3_child1, $child3);

        self::assertEquals(1, $resolver->actions()->orphans()->count()); // only root is orphan
        $resolver->actions()->delete($child1);
        self::assertEquals(3, $resolver->actions()->orphans()->count()); // $child1's children now orphan

        $root = $root->fresh();

        self::assertEquals(2, $root->children()->count());
        self::assertEquals(3, $root->descendants()->count());
    }

    /**
     * @throws \Throwable
     */
    public function test_delete_action_with_auto_adoption()
    {
        $actions = new StructureNestedActions();
        $resolver = new StructureResolver($actions);

        $root = Structure::query()->create([
            'name' => fake()->name,
            'description' => fake()->words(asText: true),
        ]);
        assert($root instanceof OrgModelWithNodeTrait);

        $child1 = Structure::query()->create([
            'name' => fake()->name,
            'description' => fake()->words(asText: true),
        ]);
        assert($child1 instanceof OrgModelWithNodeTrait);

        $child1_child1 = Structure::query()->create([
            'name' => fake()->name,
            'description' => fake()->words(asText: true),
        ]);
        assert($child1_child1 instanceof OrgModelWithNodeTrait);

        $child1_child1_child1 = Structure::query()->create([
            'name' => fake()->name,
            'description' => fake()->words(asText: true),
        ]);
        assert($child1_child1_child1 instanceof OrgModelWithNodeTrait);

        $child1_child2 = Structure::query()->create([
            'name' => fake()->name,
            'description' => fake()->words(asText: true),
        ]);
        assert($child1_child2 instanceof OrgModelWithNodeTrait);

        $child2 = Structure::query()->create([
            'name' => fake()->name,
            'description' => fake()->words(asText: true),
        ]);
        assert($child2 instanceof OrgModelWithNodeTrait);

        $child3 = Structure::query()->create([
            'name' => fake()->name,
            'description' => fake()->words(asText: true),
        ]);
        assert($child3 instanceof OrgModelWithNodeTrait);

        $child3_child1 = Structure::query()->create([
            'name' => fake()->name,
            'description' => fake()->words(asText: true),
        ]);
        assert($child3_child1 instanceof OrgModelWithNodeTrait);

        $resolver->actions()->attach($child1, $root);
        $resolver->actions()->attach($child1_child1, $child1);
        $resolver->actions()->attach($child1_child2, $child1);
        $resolver->actions()->attach($child1_child1_child1, $child1_child1);
        $resolver->actions()->attach($child2, $root);
        $resolver->actions()->attach($child3, $root);
        $resolver->actions()->attach($child3_child1, $child3);

        self::assertEquals(1, $resolver->actions()->orphans()->count()); // only root is orphan
        $resolver->actions()->delete($child1, true);
        self::assertEquals(1, $resolver->actions()->orphans()->count()); // root is still an orphan

        self::assertEquals(4, $root->children()->count());
        self::assertEquals(6, $root->descendants()->count());

        # ----------------------------------------------------------------------------------------------

        self::assertEquals(1, $resolver->actions()->orphans()->count()); // only root is orphan
        $resolver->actions()->delete($child3, true);
        self::assertEquals(1, $resolver->actions()->orphans()->count()); // root is still an orphan

        self::assertEquals(4, $root->children()->count());
        self::assertEquals(5, $root->descendants()->count());
    }

    public function test_orphans_check()
    {
        $actions = new StructureNestedActions();
        $resolver = new StructureResolver($actions);

        self::assertFalse($resolver->actions()->hasOrphan());

        $root = Structure::query()->create([
            'name' => fake()->name,
            'description' => fake()->words(asText: true),
        ]);
        assert($root instanceof OrgModelWithNodeTrait);

        $child1 = Structure::query()->create([
            'name' => fake()->name,
            'description' => fake()->words(asText: true),
        ]);
        assert($child1 instanceof OrgModelWithNodeTrait);

        $child1_child1 = Structure::query()->create([
            'name' => fake()->name,
            'description' => fake()->words(asText: true),
        ]);
        assert($child1_child1 instanceof OrgModelWithNodeTrait);

        self::assertTrue($resolver->actions()->hasOrphan());
    }

    public function test_orphans_query()
    {
        $actions = new StructureNestedActions();
        $resolver = new StructureResolver($actions);

        self::assertFalse($resolver->actions()->hasOrphan());

        $root = Structure::query()->create([
            'name' => fake()->name,
            'description' => fake()->words(asText: true),
        ]);
        assert($root instanceof OrgModelWithNodeTrait);

        $child1 = Structure::query()->create([
            'name' => fake()->name,
            'description' => fake()->words(asText: true),
        ]);
        assert($child1 instanceof OrgModelWithNodeTrait);

        $child1_child1 = Structure::query()->create([
            'name' => fake()->name,
            'description' => fake()->words(asText: true),
        ]);
        assert($child1_child1 instanceof OrgModelWithNodeTrait);

        $resolver->actions()->attach($child1, $root);
        $resolver->actions()->attach($child1_child1, $child1);

        self::assertTrue($resolver->actions()->orphans()->exists());
        self::assertEquals(1, $resolver->actions()->orphans()->count());
    }

    public function test_fixtree()
    {
        $actions = new StructureNestedActions();
        $resolver = new StructureResolver($actions, $actions);

        self::assertFalse($resolver->actions()->hasOrphan());

        $root = Structure::query()->create([
            'name' => fake()->name,
            'description' => fake()->words(asText: true),
        ]);
        assert($root instanceof OrgModelWithNodeTrait);

        $child1 = Structure::query()->create([
            'name' => fake()->name,
            'description' => fake()->words(asText: true),
        ]);
        assert($child1 instanceof OrgModelWithNodeTrait);

        $child1_child1 = Structure::query()->create([
            'name' => fake()->name,
            'description' => fake()->words(asText: true),
        ]);
        assert($child1_child1 instanceof OrgModelWithNodeTrait);

        $resolver->actions()->attach($child1, $root);
        $resolver->actions()->attach($child1_child1, $child1);

        $child1_child1->setLft($root->getLft());
        $child1_child1->save();

        $child1->setLft($child1->getRgt());
        $child1->save();

        self::assertTrue(Structure::isBroken());

        $resolver->actions()->fixTree();

        self::assertFalse(Structure::isBroken());
    }

    public function test_tree()
    {
        $actions = new StructureNestedActions();
        $resolver = new StructureResolver($actions);

        $root = Structure::query()->create([
            'name' => fake()->name,
            'description' => fake()->words(asText: true),
        ]);
        assert($root instanceof OrgModelWithNodeTrait);

        $child1 = Structure::query()->create([
            'name' => fake()->name,
            'description' => fake()->words(asText: true),
        ]);
        assert($child1 instanceof OrgModelWithNodeTrait);

        $child1_child1 = Structure::query()->create([
            'name' => fake()->name,
            'description' => fake()->words(asText: true),
        ]);
        assert($child1_child1 instanceof OrgModelWithNodeTrait);

        $child1_child1_child1 = Structure::query()->create([
            'name' => fake()->name,
            'description' => fake()->words(asText: true),
        ]);
        assert($child1_child1_child1 instanceof OrgModelWithNodeTrait);

        $child1_child2 = Structure::query()->create([
            'name' => fake()->name,
            'description' => fake()->words(asText: true),
        ]);
        assert($child1_child2 instanceof OrgModelWithNodeTrait);

        $child2 = Structure::query()->create([
            'name' => fake()->name,
            'description' => fake()->words(asText: true),
        ]);
        assert($child2 instanceof OrgModelWithNodeTrait);

        $child3 = Structure::query()->create([
            'name' => fake()->name,
            'description' => fake()->words(asText: true),
        ]);
        assert($child3 instanceof OrgModelWithNodeTrait);

        $child3_child1 = Structure::query()->create([
            'name' => fake()->name,
            'description' => fake()->words(asText: true),
        ]);
        assert($child3_child1 instanceof OrgModelWithNodeTrait);

        $resolver->actions()->attach($child1, $root);
        $resolver->actions()->attach($child1_child1, $child1);
        $resolver->actions()->attach($child1_child2, $child1);
        $resolver->actions()->attach($child1_child1_child1, $child1_child1);
        $resolver->actions()->attach($child2, $root);
        $resolver->actions()->attach($child3, $root);
        $resolver->actions()->attach($child3_child1, $child3);

        $child1_tree = $resolver->actions()->tree($child1);
        assert($child1_tree instanceof OrgModelWithNodeTrait);

        self::assertEquals(2, $child1_tree->children->count());

        $root_tree = $resolver->actions()->tree($root);
        assert($root_tree instanceof OrgModelWithNodeTrait);

        self::assertEquals(3, $root_tree->children->count()); // root's children
        self::assertEquals(2, $root_tree->children->first()->children->count()); // child1's children
        self::assertEquals(1, $root_tree->children->first()->children->first()->children->count()); // child1->child1's children
    }

    public function test_actions_dependency()
    {
        $oldActions = new StructureNestedActions();
        $resolver = new StructureResolver($oldActions);

        self::assertSame($resolver->actions(), $oldActions);

        $newActions = new StructureNestedActions();
        $resolver->setNestedActions($newActions);

        self::assertSame($resolver->actions(), $newActions);
        self::assertNotSame($resolver->actions(), $oldActions);
    }
}