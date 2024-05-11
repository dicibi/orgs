<?php

namespace Dicibi\Orgs\Resolvers;

use Dicibi\Orgs\Concerns\HasNestedAction;
use Dicibi\Orgs\Contracts\Nested\CanManageNestedSet;
use Dicibi\Orgs\Models\Office;
use Illuminate\Contracts\Database\Query\Builder;
use Kalnoy\Nestedset\NestedSet;

class OfficeResolver implements CanManageNestedSet
{
    use HasNestedAction;

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