<?php

namespace Dicibi\Orgs\Resolvers;

use Dicibi\Orgs\Concerns\HasNestedAction;
use Dicibi\Orgs\Contracts\CanCreateOffice;
use Dicibi\Orgs\Contracts\Nested\CanManageNestedSet;
use Dicibi\Orgs\Contracts\Nested\CanNestedSet;
use Dicibi\Orgs\Models\Office;
use Dicibi\Orgs\Models\Structure;
use Illuminate\Contracts\Database\Query\Builder;
use Kalnoy\Nestedset\NestedSet;

class OfficeResolver implements CanManageNestedSet, CanCreateOffice
{
    use HasNestedAction;

    public function create(string $name, Structure $structure = null, ?CanNestedSet $attachTo = null): CanNestedSet|Office
    {
        $newOffice = new Office;
        $newOffice->name = $name;
        $newOffice->structure = $structure;
        $newOffice->save();

        if (!is_null($attachTo)) {
            $attachTo->appendNode($newOffice);
        }

        return $newOffice;
    }

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