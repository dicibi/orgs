<?php

namespace Dicibi\Orgs\Resolvers;

use Dicibi\Orgs\Concerns\HasNestedActions;
use Dicibi\Orgs\Contracts\CanCreateJobTitle;
use Dicibi\Orgs\Contracts\Nested\CanNestedSet;
use Dicibi\Orgs\Models\Job\Title;
use Dicibi\Orgs\Models\Structure;

class JobTitleResolver implements CanCreateJobTitle
{
    use HasNestedActions;

    public function create(string $name, Structure $structure = null, CanNestedSet $attachTo = null): CanNestedSet
    {
        $newTitle = new Title();
        $newTitle->name = $name;
        $newTitle->structure_id = !is_null($structure) ? $structure->{$structure->getKey()} : null;
        $newTitle->save();

        if (!is_null($attachTo)) {
            $attachTo->appendNode($newTitle);
        }

        return $newTitle;
    }
}