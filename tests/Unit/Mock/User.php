<?php

namespace Dicibi\Orgs\Tests\Unit\Mock;

use Dicibi\Orgs\Concerns\HasEmployment;
use Illuminate\Database\Eloquent\Model;

class User extends Model
{
    use HasEmployment;

    protected $table = 'users';

    protected static function booted()
    {
        self::creating(function (self $model) {
            $model->name = 'John';
            $model->email = 'john@example.test';
            $model->password = bcrypt('12345678');
        });
    }
}