<?php

namespace FINDOLOGIC\Export\Data;

use FINDOLOGIC\Export\Helpers\UsergroupAwareNumericValue;

class SalesFrequency extends UsergroupAwareNumericValue
{
    public function __construct()
    {
        parent::__construct('salesFrequencies', 'salesFrequency');
    }
}
