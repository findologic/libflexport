<?php

namespace FINDOLOGIC\Export\Data;

use FINDOLOGIC\Export\Helpers\UsergroupAwareMultiValueItem;

class Keyword extends UsergroupAwareMultiValueItem
{
    public function __construct(string $value, string $usergroup = '')
    {
        parent::__construct('keyword', $value, $usergroup);
    }

    public function getValueName(): string
    {
        return 'keyword';
    }
}
