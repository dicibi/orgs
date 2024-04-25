<?php

namespace Dicibi\Orgs\Resolvers;

use Dicibi\Orgs\Concerns\HasNestedActions;
use Dicibi\Orgs\Contracts\OfficeResolver as OfficeResolverContract;

class OfficeResolver implements OfficeResolverContract
{
    use HasNestedActions;
}