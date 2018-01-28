<?php

namespace FINDOLOGIC\Export\Data;

use FINDOLOGIC\Export\Helpers\UsergroupAwareNumericValue;

class Price extends UsergroupAwareNumericValue
{
    public function __construct()
    {
        parent::__construct('prices', 'price');
    }
}
