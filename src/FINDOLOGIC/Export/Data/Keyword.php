<?php

namespace FINDOLOGIC\Export\Data;

use FINDOLOGIC\Export\Helpers\UsergroupAwareMultiValueItem;

class Keyword extends UsergroupAwareMultiValueItem
{
    public function __construct($value, string $usergroup = '')
    {
        parent::__construct('keyword', $value, $usergroup);
    }
}
