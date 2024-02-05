<?php

namespace Dicibi\Orgs\Models\Pivot;

use Dicibi\Orgs\Models\Office;
use Dicibi\Orgs\Models\Position;
use Dicibi\Orgs\OrgPivot;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations;
use Kalnoy\Nestedset\NodeTrait;

/**
 * @property string $id
 * @property int $num_employee_needed
 * @property OfficePositionUser|null $info                is a pivot model and only available when called from User model
 * @property Office $office
 * @property Position $position
 * @property int $office_id
 * @property int $position_id
 * @property string|null $note
 * @property OfficePosition|null $parent_office_position
 * @property Collection<int, OfficePosition> $children_office_position
 */
class OfficePosition extends OrgPivot
{
    use HasUuids;
    use NodeTrait;

    protected $table = 'office_position';

    protected $keyType = 'string';

    protected $hidden = [
        'id',
        'office_id',
        'position_id',
    ];

    protected $casts = [
        'num_employee_needed' => 'int',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Office, OfficePosition>
     */
    public function office(): Relations\BelongsTo
    {
        return $this->belongsTo(Office::class, 'office_id', 'id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Position, OfficePosition>
     */
    public function position(): Relations\BelongsTo
    {
        return $this->belongsTo(Position::class, 'position_id', 'id')->withTrashed();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<\App\Models\User>
     */
    public function users(): Relations\BelongsToMany
    {
        return $this->belongsToMany(config('orgs.user'), 'office_position_user', 'office_position_id', 'user_id')
            ->using(OfficePositionUser::class)
            ->as('info')
            ->withPivot([
                'id',
                'is_active',
                'grade',
                'decree_type_id',
                'decree_number',
                'note',
                'start_date',
                'end_date',
                'office_occupation',
            ])
            ->withTimestamps();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Casts\Attribute<Position|null, null>
     */
    public function parentOfficePosition(): Attribute
    {
        return new Attribute(
            get: fn() => OfficePosition::query()
                ->where('office_id', $this->office_id)
                ->where('position_id', $this->position->parent_id)
                ->first()
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Casts\Attribute<\Illuminate\Database\Eloquent\Collection<int, Position>, null>
     */
    public function childrenOfficePosition(): Attribute
    {
        return new Attribute(
            get: fn() => OfficePosition::query()
                ->where('office_id', $this->office_id)
                ->where('position_id', $this->position->children()->pluck('id'))
                ->get()
        );
    }
}
