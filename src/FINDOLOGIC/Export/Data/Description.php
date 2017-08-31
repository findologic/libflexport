<?php

namespace FINDOLOGIC\Export\Data;

use FINDOLOGIC\Export\Helpers\UsergroupAwareSimpleValue;

class Description extends UsergroupAwareSimpleValue
{
    public function __construct()
    {
        parent::__construct('descriptions', 'description');
    }
}
