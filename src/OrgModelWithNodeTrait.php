<?php

namespace Dicibi\Orgs;

use Dicibi\Orgs\Contracts\Nested\Model as NestedModel;
use Kalnoy\Nestedset\Collection;
use Kalnoy\Nestedset\NodeTrait;

/**
 * @method withDepth()
 * @method static fixTree()
 * @method static bool isBroken()
 * @property Collection<OrgModelWithNodeTrait> $children
 */
abstract class OrgModelWithNodeTrait extends OrgModel implements NestedModel
{
    use NodeTrait;
}
