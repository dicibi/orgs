<?php

namespace Dicibi\Orgs\Models;

use Dicibi\Orgs\OrgModelWithNodeTrait;

class Office extends OrgModelWithNodeTrait
{
    protected $table = 'offices';

    protected $fillable = [
        'name',
    ];
}
