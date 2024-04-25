<?php

namespace Dicibi\Orgs;

use Illuminate\Database\Eloquent\Model;

class OrgModel extends Model
{

    public function getTable(): string
    {
        return str_starts_with(parent::getTable(), config('orgs.prefix'))
            ? parent::getTable()
            : config('orgs.prefix') . parent::getTable();
    }

}
