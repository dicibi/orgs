<?php

namespace Dicibi\Orgs\Models\Pivot;

use Dicibi\Orgs\Models\Job\Title;
use Dicibi\Orgs\Models\Office;
use Dicibi\Orgs\OrgPivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property string $id
 * @property Position $position
 * @property string $note
 * @property int $employable_id
 * @property string $employable_type
 */
class Employment extends OrgPivot
{
    protected $table = 'employments';

    public function setPositionAttribute(Position $position)
    {
        $this->attributes['position_id'] = $position->id;
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function title(): HasOneThrough
    {
        return $this->hasOneThrough(Title::class, Position::class);
    }

    public function office(): HasOneThrough
    {
        return $this->hasOneThrough(Office::class, Position::class);
    }

    public function employable(): MorphTo
    {
        return $this->morphTo();
    }

}
