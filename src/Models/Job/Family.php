<?php

namespace Dicibi\Orgs\Models\Job;

use Dicibi\Orgs\OrgModel;

/**
 * The high-level categorization of job title & position.
 *
 * @property int $id
 * @property string $name
 * @property int $order
 */
class Family extends OrgModel
{
    protected $table = 'job_families';

    protected $fillable = [
        'name',
        'order',
    ];
}
