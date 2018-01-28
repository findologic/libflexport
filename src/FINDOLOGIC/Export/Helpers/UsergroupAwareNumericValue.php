<?php

namespace FINDOLOGIC\Export\Helpers;

class ValueIsNotNumericException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Value is not a valid number!');
    }
}

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
