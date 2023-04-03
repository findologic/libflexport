<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\Traits;

use FINDOLOGIC\Export\Data\Visibility;

trait HasVisibility
{
    use SupportsUserGroups;

    protected Visibility $visibility;

    public function getVisibility(): Visibility
    {
        return $this->visibility;
    }

    public function setVisibility(Visibility $visibility): void
    {
        $this->checkUsergroupAwareValue($visibility);

        $this->visibility = $visibility;
    }

    public function addVisibility(mixed $visible, string $usergroup = ''): void
    {
        $this->checkUsergroupString($usergroup);

        $this->visibility->setValue($visible, $usergroup);
    }
}
