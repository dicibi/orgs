<?php

namespace Dicibi\Orgs\Models;

use Dicibi\Orgs\Contracts\Model\OfficeContract;
use Dicibi\Orgs\OrgNodeModelWithNodeTrait;

class Office extends OrgNodeModelWithNodeTrait implements OfficeContract
{
    protected $table = 'offices';
}
