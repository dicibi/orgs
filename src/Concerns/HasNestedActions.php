<?php

namespace Dicibi\Orgs\Concerns;

use Dicibi\Orgs\Contracts\Nested\CanManageNestedSet as NestedActions;

trait HasNestedActions
{
    protected NestedActions $nestedActions;

    public function __construct(NestedActions $nestedActions)
    {
        $this->nestedActions = $nestedActions;
    }

    public function setNestedActions(NestedActions $nestedActions): static
    {
        $this->nestedActions = $nestedActions;
        return $this;
    }

    public function actions(): NestedActions
    {
        return $this->nestedActions;
    }
}