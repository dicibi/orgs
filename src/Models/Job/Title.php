<?php

namespace Dicibi\Orgs\Models\Job;

use Dicibi\Orgs\OrgModelWithNodeTrait;

/**
 * The main structural job that will be managed in tree.
 *
 * @property string $name
 * @property string $description
 * @property int $job_family_id
 * @property int $structure_id
 */
class Title extends OrgModelWithNodeTrait
{
    protected $table = 'job_titles';

    protected $fillable = [
        'name',
        'description',
        'structure_id',
        'job_family_id',
    ];
}