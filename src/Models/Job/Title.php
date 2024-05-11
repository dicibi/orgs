<?php

namespace Dicibi\Orgs\Models\Job;

use Dicibi\Orgs\OrgNodeModelWithNodeTrait;

/**
 * The main structural job that will be managed in tree.
 *
 * @property string $name
 * @property string $description
 * @property int $job_family_id
 * @property int $structure_id
 */
class Title extends OrgNodeModelWithNodeTrait
{
    protected $table = 'job_titles';
}