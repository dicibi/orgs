<?php

namespace Dicibi\Orgs\Contracts\Nested;

use Dicibi\Orgs\Contracts\Nested\Model as NestedModel;
use Illuminate\Contracts\Database\Query\Builder;

interface Actions
{

    /**
     * This will not return orphan structures.
     *
     * If NestedModel model passed, it'll search into
     * its children.
     *
     * If $depth is -1, it'll return until the
     * deepest structure. When $depth is 0, it'll
     * return only itself.
     *
     * @param Model $model
     * @param int $depth
     * @return NestedModel
     */
    public function tree(
        NestedModel $model,
        int          $depth = -1
    ): NestedModel;

    /**
     * Check if there's any orphans.
     *
     * @return bool
     */
    public function hasOrphan(): bool;

    /**
     * Get orphan structures as Builder.
     *
     * @return Builder
     */
    public function orphans(): Builder;

    /**
     * Detach given NestedModel from its parent.
     *
     * @param NestedModel $model
     * @return void
     */
    public function detach(
        NestedModel $model
    ): void;

    /**
     * Attach given NestedModel child to its parent.
     *
     * @param NestedModel $parent
     * @param NestedModel $child
     * @return void
     */
    public function attach(
        NestedModel $child,
        NestedModel $parent,
    ): void;

    /**
     * Safely delete given NestedModel.
     *
     * If $autoAdoption is true, the children will not be ignored,
     * they will auto assigned to their ancestor, the parent of deleted NestedModel.
     *
     * If $ancestor is null, it'll save the children to default ancestor. If $ancestor is available,
     * the children will be assigned to $ancestor instead the default ancestor.
     *
     * @param NestedModel $model
     * @param bool $autoAdoption
     * @param NestedModel|null $ancestor
     * @return void
     */
    public function delete(
        NestedModel  $model,
        bool         $autoAdoption = false,
        ?NestedModel $ancestor = null
    ): void;

    /**
     * Fix broken nested.
     *
     * @return void
     */
    public function fixTree(): void;

    /**
     * Check broken nested.
     *
     * @return void
     */
    public function isBroken(): bool;
}