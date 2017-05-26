<?php

namespace CodexFons\FINDOLOGIC\Export\Data;


use CodexFons\FINDOLOGIC\Export\Helpers\UsergroupAwareMultiValueItem;

class Ordernumber extends UsergroupAwareMultiValueItem
{
    public function __construct($value, $usergroup = '')
    {
        parent::__construct('ordernumber', $value, $usergroup);
    }
}