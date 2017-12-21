<?php

namespace FINDOLOGIC\Export\Data;

use FINDOLOGIC\Export\Helpers\UsergroupAwareSimpleValue;
use FINDOLOGIC\Export\Helpers\UsergroupAwareNumericValue;

class Bonus extends UsergroupAwareSimpleValue
{
    public function __construct()
    {
        parent::__construct('bonuses', 'bonus');
    }

    public static function validate($value)
    {
        return UsergroupAwareNumericValue::validate($value);
    }
}
