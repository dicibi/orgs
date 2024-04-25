<?php

namespace Dicibi\Orgs;

use Dicibi\Orgs\Contracts\JobFamilyResolver;
use Dicibi\Orgs\Contracts\JobTitleResolver;
use Dicibi\Orgs\Contracts\OfficeResolver;
use Dicibi\Orgs\Contracts\OrganizationResolver as OrganizationResolverContract;
use Dicibi\Orgs\Contracts\StructureResolver;

readonly class Organizer implements OrganizationResolverContract
{

    public function __construct(
        private StructureResolver $structureResolver,
        private JobFamilyResolver $jobFamilyResolver,
        private JobTitleResolver  $titleResolver,
        private OfficeResolver    $officeResolver,
    )
    {
    }

    public function structures(): StructureResolver
    {
        return $this->structureResolver;
    }

    public function jobFamilies(): JobFamilyResolver
    {
        return $this->jobFamilyResolver;
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