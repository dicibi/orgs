<?php

namespace Dicibi\Orgs\Contracts;

interface OrganizationResolver
{

    public function structures(): StructureResolver;

    public function jobFamilies(): JobFamilyResolver;

    public function jobTitles(): JobTitleResolver;

    public function offices(): OfficeResolver;

}