<?php

namespace Dicibi\Orgs\Contracts\Nested;

use Illuminate\Contracts\Database\Query\Builder;

interface CanManageNestedSet
{

    /**
     * This will not return orphan structures.
     *
     * If CanNestedSet model passed, it'll search into
     * its children.
     *
     * If $depth is -1, it'll return until the
     * deepest structure. When $depth is 0, it'll
     * return only itself.
     *
     * @param CanNestedSet $model
     * @param int $depth
     * @return CanNestedSet
     */
    public function tree(
        CanNestedSet $model,
        int          $depth = -1
    ): CanNestedSet;

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
     * Detach given CanNestedSet from its parent.
     *
     * @param CanNestedSet $model
     * @return void
     */
    public function detach(
        CanNestedSet $model
    ): void;

    /**
     * Attach given CanNestedSet child to its parent.
     *
     * @param CanNestedSet $parent
     * @param CanNestedSet $child
     * @return void
     */
    public function attach(
        CanNestedSet $child,
        CanNestedSet $parent,
    ): void;

    /**
     * Safely delete given CanNestedSet.
     *
     * If $autoAdoption is true, the children will not be ignored,
     * they will auto assigned to their ancestor, the parent of deleted CanNestedSet.
     *
     * If $ancestor is null, it'll save the children to default ancestor. If $ancestor is available,
     * the children will be assigned to $ancestor instead the default ancestor.
     *
     * @param CanNestedSet $model
     * @param bool $autoAdoption
     * @param CanNestedSet|null $ancestor
     * @return void
     */
    public function delete(
        CanNestedSet  $model,
        bool         $autoAdoption = false,
        ?CanNestedSet $ancestor = null
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