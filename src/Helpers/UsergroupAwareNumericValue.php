<?php

namespace FINDOLOGIC\Export\Helpers;

use FINDOLOGIC\Export\Exceptions\ValueIsNotNumericException;

abstract class UsergroupAwareNumericValue extends UsergroupAwareSimpleValue
{
    protected function validate(mixed $value): string|int|float
    {
        $value = parent::validate($value);

        if (!is_numeric($value)) {
            throw new ValueIsNotNumericException();
        }

        return $value;
    }
}
