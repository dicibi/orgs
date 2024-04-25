<?php

namespace Dicibi\Orgs\Models;

use Illuminate\Database\Eloquent\Model;
use Kalnoy\Nestedset\NodeTrait;

class Office extends Model
{
    use NodeTrait;

    protected $table = 'offices';

    protected $fillable = [
        'name',
    ];
}
