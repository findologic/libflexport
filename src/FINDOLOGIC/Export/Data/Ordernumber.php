<?php

namespace FINDOLOGIC\Export\Data;

use FINDOLOGIC\Export\Helpers\UsergroupAwareMultiValueItem;

class Ordernumber extends UsergroupAwareMultiValueItem
{
    public function __construct($value, string $usergroup = '')
    {
        parent::__construct('ordernumber', $value, $usergroup);
    }
}
