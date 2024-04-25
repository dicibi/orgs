<?php

namespace Dicibi\Orgs\Resolvers;

use Dicibi\Orgs\Concerns\HasNestedActions;
use Dicibi\Orgs\Contracts\JobTitleResolver as JobTitleResolverContract;

class JobTitleResolver implements JobTitleResolverContract
{
    use HasNestedActions;
}