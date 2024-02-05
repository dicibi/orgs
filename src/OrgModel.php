<?php

namespace Dicibi\Orgs;

use Illuminate\Database\Eloquent\Model;

class OrgModel extends Model
{

    public function getTable(): string
    {
        return (config('prefix') . $this->table) ?? parent::getTable();
    }

}
