<?php

namespace Dicibi\Orgs;

use Dicibi\Orgs\Contracts\Nested\CanNestedSet;
use Kalnoy\Nestedset\NodeTrait;

abstract class OrgNodeModelWithNodeTrait extends OrgModel implements CanNestedSet
{
    use NodeTrait;
}
