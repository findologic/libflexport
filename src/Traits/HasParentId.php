<?php

namespace FINDOLOGIC\Export\Traits;

use FINDOLOGIC\Export\Helpers\DataHelper;

trait HasParentId
{
    protected string $parentId;

    public function getParentId(): string
    {
        return $this->parentId;
    }

    public function setParentId(string $parentId): void
    {
        DataHelper::checkItemIdNotExceedingCharacterLimit($parentId);
        $this->parentId = $parentId;
    }
}
