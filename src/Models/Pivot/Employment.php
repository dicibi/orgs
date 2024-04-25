<?php

namespace Dicibi\Orgs\Models\Pivot;

use Dicibi\Orgs\OrgPivot;

class Employment extends OrgPivot
{
    protected $table = 'employments';

    protected $fillable = [
        'job_function',
        'job_description',
        'note',
        'start_date',
        'end_date',
        'is_active',
    ];
}
