<?php

namespace FINDOLOGIC\Export\Traits\Concerns\Item;

use FINDOLOGIC\Export\Data\Bonus;

trait HandlesBonus
{
    /** @var Bonus */
    protected $bonus;

    public function getBonus(): Bonus
    {
        return $this->bonus;
    }

    /**
     * @param Bonus $bonus The bonus element to add to the item.
     */
    public function setBonus(Bonus $bonus): void
    {
        $this->bonus = $bonus;
    }

    /**
     * Shortcut to easily add the bonus of the item. The value must be a numeric.
     *
     * @param float $bonus The bonus value of the item.
     * @param string $usergroup The usergroup of the bonus value.
     */
    public function addBonus(float $bonus, string $usergroup = ''): void
    {
        $this->bonus->setValue($bonus, $usergroup);
    }
}
