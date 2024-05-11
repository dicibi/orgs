<?php

namespace Dicibi\Orgs\Concerns;

use Dicibi\Orgs\Contracts\Nested\CanNestedSet;
use Dicibi\Orgs\OrgNodeModelWithNodeTrait;
use Illuminate\Support\Facades\DB;

trait HasNestedAction
{

    /**
     * @inheritdoc
     * @throws \Throwable
     */
    public function tree(CanNestedSet $model, int $depth = -1): CanNestedSet
    {
        if ($depth === 0) return $model;

        $tree = $depth > 0
            ? $model->withDepth()->having('depth', '=', $depth)->get()->toTree()
            : $model->descendants()->get()->toTree();

        $model->children = $tree;

        return $model;
    }

    /**
     * @inheritdoc
     * @throws \Throwable
     */
    public function delete(
        CanNestedSet  $model,
        bool         $autoAdoption = false,
        ?CanNestedSet $ancestor = null,
    ): void
    {
        try {
            DB::beginTransaction();
            if ($autoAdoption) {
                $root = $ancestor
                    ?? $model->parent()->first()
                    ?? $model->ancestors()->first();

                $model
                    ->children()
                    ->each(fn(OrgNodeModelWithNodeTrait $child) => $root->appendNode($child));
            } else {
                $model
                    ->children()
                    ->each(fn(OrgNodeModelWithNodeTrait $child) => $child->saveAsRoot());
            }

            $model->delete();
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }

    /**
     * @inheritdoc
     *
     * @param CanNestedSet $model
     * @throws \Throwable
     */
    public function detach(CanNestedSet $model): void
    {
        $model->saveAsRoot();
    }

    /**
     * @inheritdoc
     *
     * @param CanNestedSet $child
     * @param CanNestedSet $parent
     * @throws \Throwable
     */
    public function attach(CanNestedSet $child, CanNestedSet $parent): void
    {
        $parent->appendNode($child);
    }
}