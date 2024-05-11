<?php

namespace Dicibi\Orgs\Models;

use Dicibi\Orgs\Contracts\Model\OfficeContract;
use Dicibi\Orgs\OrgNodeModelWithNodeTrait;
use Dicibi\Orgs\Traits\OfficeTrait;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property int $id
 * @property string $name
 * @property ?string $address
 * @property int $structure_id
 *
 * @property Structure $structure
 */
class Office extends OrgNodeModelWithNodeTrait implements OfficeContract
{
    use OfficeTrait;

    protected $table = 'offices';

    public function setStructureAttribute(?Structure $structure)
    {
        $this->attributes['structure_id'] = $structure?->id;
    }

    public function structure(): BelongsTo
    {
        return $this->belongsTo(Structure::class);
    }

    public function titles(): HasMany
    {
        return $this->hasMany(Job\Title::class);
    }
}
