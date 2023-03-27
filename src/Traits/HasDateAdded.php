<?php

namespace FINDOLOGIC\Export\Traits;

use DateTimeInterface;
use FINDOLOGIC\Export\Data\DateAdded;

trait HasDateAdded
{
    use SupportsUserGroups;

    protected DateAdded $dateAdded;

    public function getDateAdded(): DateAdded
    {
        return $this->dateAdded;
    }

    public function setDateAdded(DateAdded $dateAdded): void
    {
        $this->checkUsergroupAwareValue($dateAdded);

        $this->dateAdded = $dateAdded;
    }

    public function addDateAdded(DateTimeInterface $dateAdded, string $usergroup = ''): void
    {
        $this->checkUsergroupString($usergroup);

        $this->dateAdded->setDateValue($dateAdded, $usergroup);
    }
}
