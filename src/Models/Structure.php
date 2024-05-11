<?php

namespace Dicibi\Orgs\Models;

use Dicibi\Orgs\OrgNodeModelWithNodeTrait;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property string $description
 *
 * @property Collection $offices
 * @property Collection $titles
 */
class Structure extends OrgNodeModelWithNodeTrait
{
    protected $table = 'structures';

    public function offices(): HasMany
    {
        return $this->hasMany(Office::class);
    }

    public function titles(): HasMany
    {
        return $this->hasMany(Job\Title::class);
    }
}
