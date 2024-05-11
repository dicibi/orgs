<?php

namespace Dicibi\Orgs\Models\Pivot;

use Dicibi\Orgs\OrgPivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * @property string $id
 * @property Position $position
 * @property string $note
 * @property int $employable_id
 * @property string $employable_type
 * @property mixed $employable
 */
class Employment extends OrgPivot
{
    protected $table = 'employments';

    public function setPositionAttribute(Position $position)
    {
        $this->attributes['position_id'] = $position->id;
    }

    public function setEmployableAttribute(mixed $employable)
    {
        assert(method_exists($employable, 'employments'));

        $this->attributes['employable_id'] = $employable->id;
        $this->attributes['employable_type'] = $employable::class;
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class);
    }

    public function employable(): MorphTo
    {
        return $this->morphTo();
    }

}
