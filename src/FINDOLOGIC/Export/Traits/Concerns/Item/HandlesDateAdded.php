<?php

namespace FINDOLOGIC\Export\Traits\Concerns\Item;

use DateTimeInterface;
use FINDOLOGIC\Export\Data\DateAdded;

trait HandlesDateAdded
{
    /** @var DateAdded */
    protected $dateAdded;

    public function getDateAdded(): DateAdded
    {
        return $this->dateAdded;
    }

    /**
     * @param DateAdded $dateAdded The date added element to add to the item.
     */
    public function setDateAdded(DateAdded $dateAdded): void
    {
        $this->dateAdded = $dateAdded;
    }

    /**
     * Shortcut to easily add the date added value of the item.
     *
     * @param DateTimeInterface $dateAdded The date on which the item was added to the ecommerce system.
     * @param string $usergroup The usergroup of the date added value.
     */
    public function addDateAdded(DateTimeInterface $dateAdded, string $usergroup = ''): void
    {
        $this->dateAdded->setDateValue($dateAdded, $usergroup);
    }
}
