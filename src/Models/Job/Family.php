<?php

namespace Dicibi\Orgs\Models\Job;

use Dicibi\Orgs\OrgModel;
use Illuminate\Database\Eloquent\Relations;

class Family extends OrgModel
{
    protected $table = 'job_families';

    protected $hidden = [
        'job_clan_id',
        'id',
    ];

    protected $fillable = [
        'job_clan_id',
        'name',
        'order',
    ];

    public function clan(): Relations\BelongsTo
    {
        return $this->belongsTo(Clan::class, 'job_clan_id');
    }
}
