<?php

namespace Dicibi\Orgs\Models\Job;

use Dicibi\Orgs\OrgModel;
use Illuminate\Database\Eloquent\Relations;

class Clan extends OrgModel
{
    protected $table = 'job_clans';

    /** {@inheritDoc} */
    protected $hidden = [
        'id',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array<string>
     */
    protected $fillable = [
        'name',
        'order',
    ];

    /**
     * @return Relations\HasMany<Family>
     */
    public function families(): Relations\HasMany
    {
        return $this->hasMany(Family::class, 'job_clan_id', 'id');
    }
}
