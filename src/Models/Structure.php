<?php

namespace Dicibi\Orgs\Models;

use Dicibi\Orgs\OrgModelWithNodeTrait;
use Kalnoy\Nestedset\NestedSet;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 */
class Structure extends OrgModelWithNodeTrait
{
    protected $table = 'structures';

    protected $fillable = [
        'name',
        'description',
    ];

}
