<?php

namespace Dicibi\Orgs\Concerns;

use Dicibi\Orgs\Models\Pivot\Employment;
use Dicibi\Orgs\Models\Pivot\Position;
use Illuminate\Database\Eloquent\Relations\MorphMany;

/**
 * @property Employment $active_position
 */
trait HasEmployment
{
    protected string $activePositionOrderColumn = 'id';

    public function employments(): MorphMany
    {
        return $this->morphMany(Employment::class, 'employable');
    }

    public function assignPosition(Position $position): Employment
    {
        /** @var Employment */
        return $this->employments()->forceCreate([
            'position_id' => $position->id
        ]);
    }

    /**
     * @return Employment
     */
    public function activePosition(): Employment
    {
        /** @var Employment */
        return $this->employments()->latest($this->activePositionOrderColumn)->first();
    }

    public function getActivePositionAttribute(): Employment
    {
        return $this->activePosition();
    }
}