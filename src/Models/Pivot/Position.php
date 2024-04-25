<?php

namespace Dicibi\Orgs\Models\Pivot;

use Dicibi\Orgs\OrgPivot;

class Position extends OrgPivot
{
    protected $table = 'positions';

    protected $fillable = [
        'name',
    ];
}
