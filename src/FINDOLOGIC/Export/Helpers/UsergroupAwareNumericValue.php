<?php

namespace FINDOLOGIC\Export\Helpers;

use FINDOLOGIC\Export\Exceptions\ValueIsNotNumericException;

class UsergroupAwareNumericValue extends UsergroupAwareSimpleValue
{
    protected function validate($value)
    {
        $value = parent::validate($value);

        if (!is_numeric($value)) {
            throw new ValueIsNotNumericException();
        }

        return $value;
    }
}
