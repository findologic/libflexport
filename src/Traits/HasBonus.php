<?php

namespace FINDOLOGIC\Export\Traits;

use FINDOLOGIC\Export\Data\Bonus;

trait HasBonus
{
    use SupportsUserGroups;

    protected Bonus $bonus;

    public function getBonus(): Bonus
    {
        return $this->bonus;
    }

    public function setBonus(Bonus $bonus): void
    {
        $this->checkUsergroupAwareValue($bonus);

        $this->bonus = $bonus;
    }

    public function addBonus(float $bonus, string $usergroup = ''): void
    {
        $this->checkUsergroupString($usergroup);

        $this->bonus->setValue($bonus, $usergroup);
    }
}
