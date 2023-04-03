<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\Traits;

use FINDOLOGIC\Export\Data\Description;

trait HasDescription
{
    use SupportsUserGroups;

    protected Description $description;

    public function getDescription(): Description
    {
        return $this->description;
    }

    public function setDescription(Description $description): void
    {
        $this->checkUsergroupAwareValue($description);

        $this->description = $description;
    }

    public function addDescription(string $description, string $usergroup = ''): void
    {
        $this->checkUsergroupString($usergroup);

        $this->description->setValue($description, $usergroup);
    }
}
