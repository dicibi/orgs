<?php

namespace Dicibi\Orgs\Traits;

use Dicibi\Orgs\Models\Office;
use Dicibi\Orgs\Models\Pivot\Position;

trait JobTitleTrait
{
    public function openPositionIn(Office $office, int $quota = 1): Position
    {
        $newPosition = new Position;
        $newPosition->office = $office;
        $newPosition->title = $this;
        $newPosition->quota = $quota;
        $newPosition->save();

        return $newPosition;
    }
}