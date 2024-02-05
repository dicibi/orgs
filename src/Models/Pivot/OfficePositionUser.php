<?php

namespace Dicibi\Orgs\Models\Pivot;

use Dicibi\Orgs\Models\Office;
use Dicibi\Orgs\Models\Structure\Position;
use Dicibi\Orgs\OrgPivot;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

/**
 * @property int|string $num_employee_needed
 * @property string $user_id
 * @property bool $is_active
 * @property Carbon|null $end_date
 * @property Carbon $start_date
 * @property int|string $office_position_id
 * @property string|null $note
 * @property string|null $office_occupation
 * @property string $occupation
 * @property Position|null $position
 * @property Office|null $office
 */
class OfficePositionUser extends OrgPivot
{
    use HasUuids;
    use SoftDeletes;

    protected $table = 'office_position_user';

    protected $keyType = 'string';

    protected $primaryKey = 'id';

    protected $hidden = [
        'office_position_id',
        'user_id',
    ];

    protected $fillable = [
        'office_position_id',
        'user_id',
        'is_active',
        'office_occupation',
        'grade',
        'note',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * @var array<int, string> $appends
     */
    protected $appends = [
        'occupation',
    ];

    public function neighborIdentifiers(): array
    {
        return ['user_id'];
    }

    public function getIsActive(): bool
    {
        return $this->is_active;
    }

    public function setIsActive(bool $isActive): void
    {
        $this->is_active = $isActive;
    }

    public function getStartDate(): Carbon
    {
        return $this->start_date;
    }

    public function getEndDate(): ?Carbon
    {
        return $this->end_date;
    }

    public function setEndDate(Carbon|null $date): void
    {
        $this->end_date = $date;
    }

    /**
     * @return HasOneThrough<Position>
     */
    public function position(): HasOneThrough
    {
        return $this->hasOneThrough(
            Position::class,
            OfficePosition::class,
            'id',
            'id',
            'office_position_id',
            'position_id',
        )->withTrashed();
    }

    /**
     * @return HasOneThrough<Office>
     */
    public function office(): HasOneThrough
    {
        return $this->hasOneThrough(
            Office::class,
            OfficePosition::class,
            'id',
            'id',
            'office_position_id',
            'office_id',
        );
    }

    /**
     * @return BelongsTo<User, OfficePositionUser>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(config('orgs.user'), 'user_id', 'id');
    }

    /**
     * @return Attribute<string, never-return>
     */
    public function occupation(): Attribute
    {
        return Attribute::get(function () {
            if ($this->office_occupation) {
                return $this->office_occupation;
            }

            if ($this->getKey()) {
                /** @var string|null $occupationName */
                $occupationName = Cache::get('office_position_user_occupation:' . $this->getKey());

                if ($occupationName) {
                    return $occupationName;
                }
            }

            /** @var string $occupationName */
            $occupationName = $this->position()->value('occupation');

            if ($this->getKey()) {
                Cache::set('office_position_user_occupation:' . $this->getKey(), $occupationName, 60 * 60 * 24);
            }

            return $occupationName;
        });
    }

}
