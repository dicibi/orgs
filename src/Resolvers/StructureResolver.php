<?php

namespace Dicibi\Orgs\Resolvers;

use Dicibi\Orgs\Concerns\HasNestedActions;
use Dicibi\Orgs\Contracts\StructureResolver as StructureRepositoryContract;
use Dicibi\Orgs\Models\Structure;

class StructureResolver implements StructureRepositoryContract
{
    use HasNestedActions;

    public function create(
        string     $name,
        ?string    $description = null,
        ?Structure $attachTo = null,
    ): Structure
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