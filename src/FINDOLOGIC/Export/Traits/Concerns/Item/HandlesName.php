<?php

namespace FINDOLOGIC\Export\Traits\Concerns\Item;

use FINDOLOGIC\Export\Data\Name;

trait HandlesName
{
    /** @var Name */
    protected $name;

    public function getName(): Name
    {
        return $this->name;
    }

    /**
     * @param Name $name The name element to add to the item.
     */
    public function setName(Name $name): void
    {
        $this->name = $name;
    }

    /**
     * Shortcut to easily add the name of the item.
     *
     * @param string $name The name of the item.
     * @param string $usergroup The usergroup of the name element.
     */
    public function addName(string $name, string $usergroup = ''): void
    {
        $this->name->setValue($name, $usergroup);
    }
}
