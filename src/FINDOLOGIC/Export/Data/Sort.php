<?php

namespace FINDOLOGIC\Export\Data;

use FINDOLOGIC\Export\Helpers\UsergroupAwareSimpleValue;
use FINDOLOGIC\Export\Helpers\UsergroupAwareNumericValue;

class Sort extends UsergroupAwareSimpleValue
{
    public function __construct()
    {
        parent::__construct('sorts', 'sort');
    }

    static function validate($value)
    {
        return UsergroupAwareNumericValue::validate($value);
    }
}
