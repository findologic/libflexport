<?php

namespace FINDOLOGIC\Export\Traits;

use FINDOLOGIC\Export\Helpers\DataHelper;

trait HasId
{
    protected string $id;

    public function getId(): string
    {
        return $this->id;
    }

    public function setId(string $id): void
    {
        DataHelper::checkItemIdNotExceedingCharacterLimit($id);
        $this->id = $id;
    }
}
