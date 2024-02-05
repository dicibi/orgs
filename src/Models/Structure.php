<?php

namespace Dicibi\Orgs\Models;

use Dicibi\Orgs\OrgModel;
use Illuminate\Database\Eloquent\Relations;

class Structure extends OrgModel
{

    protected $table = 'structures';

    protected $fillable = [
        'name',
        'description',
    ];

    protected $hidden = [
        'id',
    ];

    /**
     * @var string[]
     */
    protected $appends = [
        'hash',
    ];

    /**
     * @return Relations\HasMany<Position>
     */
    public function positions(): Relations\HasMany
    {
        return $this->hasMany(Structure\Position::class, 'structure_id');
    }

    /**
     * @return Relations\HasMany<Office>
     */
    public function offices(): Relations\HasMany
    {
        return $this->hasMany(Office::class, 'structure_id');
    }
}
