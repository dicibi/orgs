<?php

namespace Dicibi\Orgs\Traits;

use Dicibi\Orgs\Models\Job;
use Dicibi\Orgs\Models\Pivot\Position;

trait OfficeTrait
{
    public function openPositionFor(Job\Title $title, int $quota = 1): Position
    {
        $newPosition = new Position;
        $newPosition->title = $title;
        $newPosition->office = $this;
        $newPosition->quota = $quota;
        $newPosition->save();

        return $newPosition;
    }
}