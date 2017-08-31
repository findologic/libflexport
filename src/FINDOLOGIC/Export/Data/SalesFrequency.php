<?php

namespace FINDOLOGIC\Export\Data;

use FINDOLOGIC\Export\Helpers\UsergroupAwareSimpleValue;

class SalesFrequency extends UsergroupAwareSimpleValue
{
    public function __construct()
    {
        parent::__construct('salesFrequencies', 'salesFrequency');
    }
}
