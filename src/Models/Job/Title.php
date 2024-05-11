<?php

namespace Dicibi\Orgs\Models\Job;

use Dicibi\Orgs\Contracts\Model\JobClanContract;
use Dicibi\Orgs\Contracts\Model\JobFamilyContract;
use Dicibi\Orgs\Models\Structure;
use Dicibi\Orgs\OrgNodeModelWithNodeTrait;
use Dicibi\Orgs\Traits\JobTitleTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * The main structural job that will be managed in tree.
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property int $job_family_id
 * @property int $structure_id
 *
 * @property Structure $structure
 * @property Model&JobFamilyContract $job_Family
 * @property Model&JobClanContract $job_clan
 */
class Title extends OrgNodeModelWithNodeTrait
{
    use JobTitleTrait;

    protected $table = 'job_titles';

    public function structure(): BelongsTo
    {
        return $this->belongsTo(Structure::class);
    }

    public function __get($key)
    {
        if (in_array($key, ['job_family', 'jobFamily']) && !is_null($jobFamilyClass = config('orgs.job_family'))) {
            return $this->belongsTo($jobFamilyClass);
        }

        if (in_array($key, ['job_clan', 'jobClan']) && !is_null($jobClanClass = config('orgs.job_clan'))) {
            return $this->belongsTo($jobClanClass);
        }

        return parent::__get($key);
    }
}