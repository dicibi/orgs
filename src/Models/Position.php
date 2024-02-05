<?php

namespace Dicibi\Orgs\Models;

use Dicibi\Orgs\Models\Family;
use Dicibi\Orgs\Models\Office;
use Dicibi\Orgs\Models\Pivot\OfficePosition;
use Dicibi\Orgs\Models\Structure;
use Dicibi\Orgs\OrgModel;
use Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Kalnoy\Nestedset\NodeTrait;
use Illuminate\Database\Eloquent\Relations;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

/**
 * Class Position.
 *
 * @property int $id
 * @property int _lft
 * @property int _rgt
 * @property int $structure_id
 * @property int|null $parent_id
 * @property int job_family_id
 * @property int classification_id
 * @property string name
 * @property string occupation
 * @property string job_main_task
 * @property string job_function
 * @property string job_responsibility
 * @property string job_authority
 * @property string job_qualification
 * @property string job_experience
 * @property string job_training
 * @property string job_capability
 * @property string job_emotional_quotient
 * @property Structure structure
 * @property Family jobFamily
 * @property OfficePosition|null officePosition this is pivot data, only available when accessed from App\Models\Office
 * @property \Illuminate\Database\Eloquent\Collection<int, Office> offices
 */
class Position extends OrgModel
{
    use HasFactory;
    use NodeTrait;
    use SoftDeletes;

    protected $table = 'positions';

    protected $fillable = [
        '_lft',
        '_rgt',
        'structure_id',
        'job_family_id',
        'name',
        'occupation',
        'job_main_task',
        'job_function',
        'job_responsibility',
        'job_authority',
        'job_qualification',
        'job_experience',
        'job_training',
        'job_capability',
        'job_emotional_quotient',
    ];

    protected $hidden = [
        'id',
        'structure_id',
        'job_family_id',
        'parent_id',
        'officePosition',
        'groupable',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Structure, \App\Models\Structure\Position>
     */
    public function structure(): Relations\BelongsTo
    {
        return $this->belongsTo(Structure::class, 'structure_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Family, \App\Models\Structure\Position>
     */
    public function jobFamily(): Relations\BelongsTo
    {
        return $this->belongsTo(Family::class, 'job_family_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Office>
     */
    public function offices(): Relations\BelongsToMany
    {
        return $this->belongsToMany(Office::class, (new OfficePosition())->getTable(), 'position_id', 'office_id')
            ->withPivot([
                'id',
                'num_employee_needed',
            ])
            ->using(OfficePosition::class)
            ->withTimestamps()
            ->as('officePosition');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough<OfficePositionUser>
     */
    public function officePositionUsers(): Relations\HasManyThrough
    {
        return $this->hasManyThrough(
            OfficePositionUser::class,
            OfficePosition::class,
            'position_id',
            'office_position_id',
            'id',
            'id',
        );
    }

    /**
     * Get the group for the position.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany<Group>
     */
    public function group(): Relations\MorphToMany
    {
        return $this->morphToMany(Group::class, 'groupable')->withTimestamps();
    }

    /**
     * flag whether a position has been assigned to the group
     * @return Attribute<bool, never-return>
     */
    public function isAssigned(): Attribute
    {
        return Attribute::make(
            get: fn() => $this->group()->exists()
        );
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<PermissionSet>
     */
    public function permissionSets(): Relations\BelongsToMany
    {
        return $this->belongsToMany(
            PermissionSet::class,
            'position_permission_set',
            'position_id',
            'permission_set_id'
        );
    }

    /**
     * @param $query
     * @param $grade
     * @return Builder
     */
    public function scopeByGrade($query, $grade): Builder
    {
        return $query->whereHas('officePositionUsers', function ($query) use ($grade) {
            $query->where('grade', $grade)
                ->where('is_active', true);
        });
    }

    protected static function boot(): void
    {
        parent::boot();

        self::registerModelEvent('trashed', function (Position $position) {
            $position->name = '[Telah Dihapus] ' . $position->name;
            $position->occupation = '[Telah Dihapus] ' . $position->occupation;

            $position->save();
        });
    }

    public function scopeByPermission($query, $permission): Builder
    {
        return $query->whereHas('permissionSets', function ($q) use ($permission) {
            $q->whereJsonContains('permissions', $permission);
        })
            ->with('officePositionUsers');
    }
}
