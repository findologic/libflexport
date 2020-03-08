<?php

namespace FINDOLOGIC\Export\Traits\Concerns\Item;

use FINDOLOGIC\Export\Data\Description;

trait HandlesDescription
{
    /** @var Description */
    protected $description;

    public function getDescription(): Description
    {
        return $this->description;
    }

    /**
     * @param Description $description The description element to add to the item.
     */
    public function setDescription(Description $description): void
    {
        $this->description = $description;
    }

    /**
     * Shortcut to easily add the description of the item.
     *
     * @param string $description The description of the item.
     * @param string $usergroup The usergroup of the description.
     */
    public function addDescription(string $description, string $usergroup = ''): void
    {
        $this->description->setValue($description, $usergroup);
    }
}
