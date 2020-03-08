<?php

namespace FINDOLOGIC\Export\Traits\Concerns\Item;

use FINDOLOGIC\Export\Data\Sort;

trait HandlesSort
{
    /** @var Sort */
    protected $sort;

    public function getSort(): Sort
    {
        return $this->sort;
    }

    /**
     * @param Sort $sort The sort element to add to the item.
     */
    public function setSort(Sort $sort): void
    {
        $this->sort = $sort;
    }

    /**
     * Shortcut to easily add the sort value of the item.
     *
     * @param int $sort The sort value of the item.
     * @param string $usergroup The usergroup of the sort value.
     */
    public function addSort(int $sort, string $usergroup = ''): void
    {
        $this->sort->setValue($sort, $usergroup);
    }
}
