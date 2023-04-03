<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\Data;

use FINDOLOGIC\Export\Helpers\UsergroupAwareMultiValueItem;

final class Keyword extends UsergroupAwareMultiValueItem
{
    public function __construct(string $value, string $usergroup = '')
    {
        parent::__construct('keyword', $value, $usergroup);
    }

    /**
     * @inheritDoc
     */
    public function getValueName(): string
    {
        return 'keyword';
    }
}
