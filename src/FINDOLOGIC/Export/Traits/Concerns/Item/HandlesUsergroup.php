<?php

namespace FINDOLOGIC\Export\Traits\Concerns\Item;

use FINDOLOGIC\Export\Data\Usergroup;

trait HandlesUsergroup
{
    protected $usergroups = [];

    /**
     * @param Usergroup $usergroup The usergroup element to add to the item.
     */
    public function addUsergroup(Usergroup $usergroup): void
    {
        array_push($this->usergroups, $usergroup);
    }

    /**
     * @param array $usergroups Array of usergroup elements which should be added to the item.
     */
    public function setAllUsergroups(array $usergroups): void
    {
        $this->usergroups = $usergroups;
    }
}
