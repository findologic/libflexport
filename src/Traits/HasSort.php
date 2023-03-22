<?php

namespace FINDOLOGIC\Export\Traits;

use FINDOLOGIC\Export\Data\Sort;

trait HasSort
{
    use SupportsUserGroups;

    protected Sort $sort;

    public function getSort(): Sort
    {
        return $this->sort;
    }

    public function setSort(Sort $sort): void
    {
        $this->checkUsergroupAwareValue($sort);

        $this->sort = $sort;
    }

    public function addSort(int $sort, string $usergroup = ''): void
    {
        $this->checkUsergroupString($usergroup);

        $this->sort->setValue($sort, $usergroup);
    }
}
