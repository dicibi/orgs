<?php

namespace Dicibi\Orgs\Contracts\Nested;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;

interface CanNestedSet
{
    /**
     * @return HasMany
     */
    public function children();

    /**
     * @return bool
     */
    public function isRoot();

    /**
     * @return bool
     */
    public function isLeaf();

    /**
     * @param array $columns
     *
     * @return Collection
     */
    public function getAncestors(array $columns = ['*']);

    /**
     * Make this node a root node.
     *
     * @return $this
     */
    public function makeRoot();

    /**
     * Save node as root.
     *
     * @return bool
     */
    public function saveAsRoot();
}