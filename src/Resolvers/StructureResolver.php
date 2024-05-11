<?php

namespace Dicibi\Orgs\Resolvers;

use Dicibi\Orgs\Concerns\HasNestedActions;
use Dicibi\Orgs\Contracts\CanCreateStructure;
use Dicibi\Orgs\Contracts\Nested\CanNestedSet;
use Dicibi\Orgs\Models\Structure;

class StructureResolver implements CanCreateStructure
{
    use HasNestedActions;

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
}