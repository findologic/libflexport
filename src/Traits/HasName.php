<?php

namespace FINDOLOGIC\Export\Traits;

use FINDOLOGIC\Export\Data\Name;

trait HasName
{
    use SupportsUserGroups;

    protected Name $name;

    public function getName(): Name
    {
        return $this->name;
    }

    public function addName(string $name, string $usergroup = ''): void
    {
        $this->checkUsergroupString($usergroup);

        $this->name->setValue($name, $usergroup);
    }

    public function setName(Name $name): void
    {
        $this->checkUsergroupAwareValue($name);

        $this->name = $name;
    }
}
