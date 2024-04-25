<?php

namespace Dicibi\Orgs\Contracts;

use Dicibi\Orgs\Models\Structure;

interface StructureResolver
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
        ?Structure $attachTo = null,
    ): Structure;

}