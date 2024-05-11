<?php

namespace Dicibi\Orgs;

use Illuminate\Database\Eloquent\Relations\Pivot;

class OrgPivot extends Pivot
{

    public function getTable(): string
    {
        return str_starts_with(parent::getTable(), config('orgs.prefix'))
            ? parent::getTable()
            : config('orgs.prefix') . parent::getTable();
    }

}
