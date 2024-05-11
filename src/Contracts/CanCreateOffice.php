<?php

namespace Dicibi\Orgs\Contracts;

use Dicibi\Orgs\Contracts\Nested\CanNestedSet;
use Dicibi\Orgs\Models\Office;
use Dicibi\Orgs\Models\Structure;

interface CanCreateOffice
{
    /**
     * Create new title and append it as child of
     * the given title if available.
     *
     * @param string $name
     * @param Structure|null $structure
     * @param CanNestedSet|null $attachTo
     *
     * @return CanNestedSet|Office
     */
    public function create(
        string        $name,
        Structure     $structure = null,
        ?CanNestedSet $attachTo = null,
    ): CanNestedSet|Office;
}