<?php

namespace Dicibi\Orgs\Resolvers\Actions;

use Dicibi\Orgs\Contracts\Exceptions\NotNestedModelException;
use Dicibi\Orgs\Contracts\Nested\Actions as NestedActionsContract;
use Dicibi\Orgs\Contracts\Nested\Model as NestedModel;
use Dicibi\Orgs\OrgModelWithNodeTrait;
use Illuminate\Support\Facades\DB;

abstract class AbstractNestedActions implements NestedActionsContract
{
    /**
     * @inheritdoc
     * @throws \Throwable
     */
    public function tree(NestedModel $model, int $depth = -1): NestedModel
    {
        $this->check($model);

        assert($model instanceof OrgModelWithNodeTrait);

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
        NestedModel  $model,
        bool         $autoAdoption = false,
        ?NestedModel $ancestor = null,
    ): void
    {
        $this->check($model);

        assert($model instanceof OrgModelWithNodeTrait);

        try {
            DB::beginTransaction();
            if ($autoAdoption) {
                $root = $ancestor
                    ?? $model->parent()->first()
                    ?? $model->ancestors()->first();
                assert($root instanceof OrgModelWithNodeTrait);

                $model
                    ->children()
                    ->each(fn(OrgModelWithNodeTrait $child) => $root->appendNode($child));
            } else {
                $model
                    ->children()
                    ->each(fn(OrgModelWithNodeTrait $child) => $child->saveAsRoot());
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
     * @param NestedModel $model
     * @throws \Throwable
     */
    public function detach(NestedModel $model): void
    {
        $this->check($model);

        assert($model instanceof OrgModelWithNodeTrait);

        $model->saveAsRoot();
    }

    /**
     * @inheritdoc
     *
     * @param NestedModel $child
     * @param NestedModel $parent
     * @throws \Throwable
     */
    public function attach(NestedModel $child, NestedModel $parent): void
    {
        $this->check($child, $parent);

        assert($child instanceof OrgModelWithNodeTrait);
        assert($parent instanceof OrgModelWithNodeTrait);

        $parent->appendNode($child);
    }

    /**
     * @throws NotNestedModelException|\Throwable
     */
    protected function check(NestedModel ...$models): bool
    {
        foreach ($models as $model) {
            throw_if(
                !($model instanceof OrgModelWithNodeTrait),
                new NotNestedModelException(($model::class) . ') must implement NestedModel to use NestedActions.')
            );
        }
        return true;
    }

}