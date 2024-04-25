<?php

namespace Dicibi\Orgs\Models\Job;

use Dicibi\Orgs\OrgModel;
use Kalnoy\Nestedset\NodeTrait;

/**
 * The main structural job that will be managed in tree.
 */
class Title extends OrgModel
{
    use NodeTrait;

    protected $table = 'job_titles';

    protected $fillable = [
        'name',
    ];
}