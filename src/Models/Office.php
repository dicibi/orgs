<?php

namespace Dicibi\Orgs\Models;

use Dicibi\IndoRegion\Concerns\HasIndonesiaRegionData;
use Dicibi\Orgs\Models\Pivot\OfficePosition;
use Dicibi\Orgs\OrgModel;
use Illuminate\Database\Eloquent\Relations;
use Kalnoy\Nestedset\NodeTrait;

class Office extends OrgModel
{
    protected $table = 'offices';

    use NodeTrait;
    use HasIndonesiaRegionData;

    protected $fillable = [
        'structure_id',
        'type_id',
        'parent_id',
        'code',
        'name',
        'address',
    ];

    protected $hidden = [
        'id',
        'structure_id',
        'type_id',
        'parent_id',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<Structure, Office>
     */
    public function structure(): Relations\BelongsTo
    {
        return $this->belongsTo(Structure::class, 'structure_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<Position>
     */
    public function positions(): Relations\BelongsToMany
    {
        return $this->belongsToMany(Position::class, (new OfficePosition())->getTable(), 'office_id', 'position_id')
            ->withPivot([
                'id',
                'num_employee_needed',
            ])
            ->using(OfficePosition::class)
            ->withTimestamps()
            ->as('officePosition');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne<Office>
     * @deprecated use parent relation from nodetrait instead
     */
    public function parentOffice(): Relations\HasOne
    {
        return $this->hasOne(self::class, 'id', 'parent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<Office>
     * @deprecated use children relation from nodetrait instead
     */
    public function childrenOffice(): Relations\HasMany
    {
        return $this->hasMany(self::class, 'parent_id', 'id');
    }

}
