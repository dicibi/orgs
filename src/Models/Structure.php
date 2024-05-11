<?php

namespace Dicibi\Orgs\Models;

use Dicibi\Orgs\OrgNodeModelWithNodeTrait;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 */
class Structure extends OrgNodeModelWithNodeTrait
{
    protected $table = 'structures';
}
