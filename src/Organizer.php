<?php

namespace Dicibi\Orgs;

use Dicibi\Orgs\Resolvers\JobTitleResolver;
use Dicibi\Orgs\Resolvers\OfficeResolver;
use Dicibi\Orgs\Resolvers\StructureResolver;

class Organizer
{

    public function __construct(
        private StructureResolver $structureResolver,
        private JobTitleResolver  $titleResolver,
        private OfficeResolver    $officeResolver,
    )
    {
    }

    public function structures(): StructureResolver
    {
        return $this->structureResolver;
    }

    public function jobTitles(): JobTitleResolver
    {
        return $this->titleResolver;
    }

    public function offices(): OfficeResolver
    {
        return $this->officeResolver;
    }
}