<?php

namespace Dicibi\Orgs\Contracts;

use Dicibi\Orgs\Contracts\Nested\CanNestedSet;
use Dicibi\Orgs\Models\Structure;

interface CanCreateJobTitle
{
    /**
     * Create new title and append it as child of
     * the given title if available.
     *
     * @param string $name
     * @param Structure $structure
     * @param CanNestedSet|null $attachTo
     *
     * @return CanNestedSet
     */
    public function create(
        string       $name,
        Structure    $structure,
        CanNestedSet $attachTo = null,
    ): CanNestedSet;
}