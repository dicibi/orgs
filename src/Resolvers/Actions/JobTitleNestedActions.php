<?php

namespace Dicibi\Orgs\Resolvers\Actions;

use Dicibi\Orgs\Models\Job;
use Illuminate\Contracts\Database\Query\Builder;
use Kalnoy\Nestedset\NestedSet;

class JobTitleNestedActions extends AbstractNestedActions
{
    /**
     * @return bool
     */
    public function hasOrphan(): bool
    {
        return Job\Title::query()->where(NestedSet::PARENT_ID, null)->exists();
    }

    /**
     * @return Builder
     */
    public function orphans(): Builder
    {
        return Job\Title::query()->where(NestedSet::PARENT_ID, null);
    }

    public function fixTree(): void
    {
        Job\Title::fixTree();
    }

    public function isBroken(): bool
    {
        return Job\Title::isBroken();
    }
}