<?php

namespace Dicibi\Orgs\Contracts;

use Dicibi\Orgs\Contracts\Nested\CanNestedSet;
use Dicibi\Orgs\Models\Structure;

interface CanCreateStructure
{
    /**
     * Create new structure and append it as child of
     * the given structure if available.
     *
     * @param string $name
     * @param string|null $description
     * @param Structure|null $attachTo
     *
     * @return Structure
     */
    public function create(
        string     $name,
        ?string    $description = null,
        ?CanNestedSet $attachTo = null,
    ): CanNestedSet;
}