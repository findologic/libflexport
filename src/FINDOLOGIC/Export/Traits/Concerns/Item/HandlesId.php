<?php

namespace FINDOLOGIC\Export\Traits\Concerns\Item;

use FINDOLOGIC\Export\Helpers\DataHelper;

trait HandlesId
{
    /** @var string */
    protected $id;

    /**
     * @return string
     */
    public function getId(): string
    {
        return $this->id;
    }

    /**
     * @param string $id The id of the item to set
     */
    public function setId(string $id): void
    {
        DataHelper::checkItemIdNotExceedingCharacterLimit($id);
        $this->id = $id;
    }
}
