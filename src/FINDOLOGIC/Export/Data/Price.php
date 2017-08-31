<?php

namespace FINDOLOGIC\Export\Data;

use FINDOLOGIC\Export\Helpers\UsergroupAwareSimpleValue;

class Price extends UsergroupAwareSimpleValue
{
    public function __construct()
    {
        parent::__construct('prices', 'price');
    }
}
