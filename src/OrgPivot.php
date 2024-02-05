<?php

namespace Dicibi\Orgs;

use Illuminate\Database\Eloquent\Relations\Pivot;

class OrgPivot extends Pivot
{

    public function getTable(): string
    {
        return (config('prefix') . $this->table) ?? parent::getTable();
    }

}
