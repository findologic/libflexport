<?php

namespace FINDOLOGIC\Export\Data;

use FINDOLOGIC\Export\Helpers\UsergroupAwareSimpleValue;
use FINDOLOGIC\Export\Helpers\EmptyValueNotAllowedException;

class ValueIsNotPositivIntegerException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Value is not a positiv integer!');
    }
}

class SalesFrequency extends UsergroupAwareSimpleValue
{
    public function __construct()
    {
        parent::__construct('salesFrequencies', 'salesFrequency');
    }

    protected function validate($value)
    {
        if ($value === '') {
            throw new EmptyValueNotAllowedException();
        }

        if (!is_int($value) || $value < 0) {
            throw new ValueIsNotPositivIntegerException();
        }

        return $value;
    }
}
