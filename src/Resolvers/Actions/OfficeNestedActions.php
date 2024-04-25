<?php

namespace Dicibi\Orgs\Resolvers\Actions;

use Dicibi\Orgs\Models\Office;
use Illuminate\Contracts\Database\Query\Builder;
use Kalnoy\Nestedset\NestedSet;

class OfficeNestedActions extends AbstractNestedActions
{
    /**
     * @return bool
     */
    public function hasOrphan(): bool
    {
        return Office::query()->where(NestedSet::PARENT_ID, null)->exists();
    }

    /**
     * @return Builder
     */
    public function orphans(): Builder
    {
        return Office::query()->where(NestedSet::PARENT_ID, null);
    }

    public function fixTree(): void
    {
        Office::fixTree();
    }

    public function isBroken(): bool
    {
        return Office::isBroken();
    }
}