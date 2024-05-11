<?php

namespace Dicibi\Orgs\Resolvers;

use Dicibi\Orgs\Concerns\HasNestedAction;
use Dicibi\Orgs\Contracts\CanCreateStructure;
use Dicibi\Orgs\Contracts\Nested\CanManageNestedSet;
use Dicibi\Orgs\Contracts\Nested\CanNestedSet;
use Dicibi\Orgs\Models\Structure;
use Illuminate\Contracts\Database\Query\Builder;
use Kalnoy\Nestedset\NestedSet;

class StructureResolver implements CanCreateStructure, CanManageNestedSet
{
    use HasNestedAction;

    public function create(
        string                      $name,
        ?string                     $description = null,
        CanNestedSet|null $attachTo = null,
    ): CanNestedSet
    {
        $newStructure = new Structure();
        $newStructure->name = $name;
        $newStructure->description = $description;
        $newStructure->save();

        if (!is_null($attachTo)) {
            $attachTo->appendNode($newStructure);
        }

        return $newStructure;
    }

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