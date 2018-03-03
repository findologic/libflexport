<?php

namespace FINDOLOGIC\Export\Helpers;

class ValueIsNotUrlException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Value is not a valid url!');
    }
}

class UsergroupAwareUrlValue extends UsergroupAwareSimpleValue
{
    protected function validate($value)
    {
        $value = parent::validate($value);

        if (!filter_var($value, FILTER_VALIDATE_URL)) {
            throw new ValueIsNotUrlException();
        }

        return $value;
    }
}
