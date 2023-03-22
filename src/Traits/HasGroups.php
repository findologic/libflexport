<?php

namespace FINDOLOGIC\Export\Traits;

use FINDOLOGIC\Export\Data\Group;

trait HasGroups
{
    /** @var Group[] */
    protected array $groups = [];

    /**
     * @return Group[]
     */
    public function getGroups(): array
    {
        return $this->groups;
    }

    public function addGroup(Group $group): void
    {
        $this->groups[] = $group;
    }

    public function setAllGroups(array $groups): void
    {
        $this->groups = $groups;
    }
}
