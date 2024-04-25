<?php

namespace Dicibi\Orgs\Resolvers\Actions;

use Dicibi\Orgs\Models\Structure;
use Illuminate\Contracts\Database\Query\Builder;
use Kalnoy\Nestedset\NestedSet;

class StructureNestedActions extends AbstractNestedActions
{
    /**
     * @return bool
     */
    public function hasOrphan(): bool
    {
        return Structure::query()->where(NestedSet::PARENT_ID, null)->exists();
    }

    /**
     * @return Builder
     */
    public function orphans(): Builder
    {
        return Structure::query()->where(NestedSet::PARENT_ID, null);
    }

    public function fixTree(): void
    {
        Structure::fixTree();
    }

    public function isBroken(): bool
    {
        return Structure::isBroken();
    }
}