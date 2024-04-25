<?php

namespace Dicibi\Orgs\Models;

use Dicibi\Orgs\OrgModel;
use Kalnoy\Nestedset\NodeTrait;

/**
 * @property string $name
 * @property string $description
 */
class Structure extends OrgModel
{
    use NodeTrait;

    protected $table = 'structures';

    protected $fillable = [
        'name',
        'description',
    ];

}
