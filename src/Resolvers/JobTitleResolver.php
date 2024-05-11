<?php

namespace Dicibi\Orgs\Resolvers;

use Dicibi\Orgs\Concerns\HasNestedAction;
use Dicibi\Orgs\Contracts\CanCreateJobTitle;
use Dicibi\Orgs\Contracts\Nested\CanManageNestedSet;
use Dicibi\Orgs\Contracts\Nested\CanNestedSet;
use Dicibi\Orgs\Models\Job;
use Dicibi\Orgs\Models\Structure;
use Illuminate\Contracts\Database\Query\Builder;
use Kalnoy\Nestedset\NestedSet;

class JobTitleResolver implements CanCreateJobTitle, CanManageNestedSet
{
    use HasNestedAction;

    public function create(string $name, Structure $structure = null, CanNestedSet $attachTo = null): CanNestedSet
    {
        $newTitle = new Job\Title();
        $newTitle->name = $name;
        $newTitle->structure_id = !is_null($structure) ? $structure->{$structure->getKey()} : null;
        $newTitle->save();

        if (!is_null($attachTo)) {
            $attachTo->appendNode($newTitle);
        }

        return $newTitle;
    }
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