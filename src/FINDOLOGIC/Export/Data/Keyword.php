<?php

namespace FINDOLOGIC\Export\Data;


use FINDOLOGIC\Export\Helpers\UsergroupAwareMultiValueItem;

class Keyword extends UsergroupAwareMultiValueItem
{
    public function __construct($value, $usergroup = '')
    {
        parent::__construct('keyword', $value, $usergroup);
    }
}