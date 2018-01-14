<?php

namespace FINDOLOGIC\Export\Data;

use FINDOLOGIC\Export\Helpers\UsergroupAwareNumericValue;

class Sort extends UsergroupAwareNumericValue
{
    public function __construct()
    {
        parent::__construct('sorts', 'sort');
    }
}
