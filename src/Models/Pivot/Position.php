<?php

namespace Dicibi\Orgs\Models\Pivot;

use Dicibi\Orgs\Contracts\Model\OfficeContract;
use Dicibi\Orgs\Models\Job;
use Dicibi\Orgs\Models\Office;
use Dicibi\Orgs\OrgPivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;
use Kalnoy\Nestedset\NodeTrait;

/**
 * @property string $id
 * @property int $title_id
 * @property int $office_id
 * @property string $job_function
 * @property string $job_description
 * @property int $quota
 * @property string $note
 *
 * @property  Job\Title $title
 * @property  OfficeContract $office
 */
class Position extends OrgPivot
{
    use NodeTrait;

    protected $keyType = 'string';

    public $incrementing = false;

    protected $table = 'positions';

    public function setTitleAttribute(Job\Title $title)
    {
        $this->attributes['title_id'] = $title->id;
    }

    public function setOfficeAttribute(OfficeContract $office)
    {
        $this->attributes['office_id'] = $office->{$office->getKeyName()};
    }

    public function title(): BelongsTo
    {
        return $this->belongsTo(Job\Title::class);
    }

    public function office(): BelongsTo
    {
        return $this->belongsTo(Office::class);
    }

    protected static function booted()
    {
        static::creating(function (Position $position) {
            $position->id = Str::ulid()->toRfc4122();
        });
    }

}
