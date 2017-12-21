<?php

namespace FINDOLOGIC\Export\Helpers;

class ValueIsNotNumericException extends EmptyValueNotAllowedException
{
    public function __construct()
    {
        parent::__construct('Value is not a valid number!');
    }
}

class UsergroupAwareNumericValue
{
    public static function validate($value)
    {
        $value = trim($value);

        if ($value === '') {
            throw new EmptyValueNotAllowedException();
        }
        if (!is_numeric($value)) {
            throw new ValueIsNotNumericException();
        }

        return $value;
    }
}
