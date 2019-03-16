<?php

namespace FINDOLOGIC\Export\Data;

use FINDOLOGIC\Export\Helpers\EmptyValueNotAllowedException;
use FINDOLOGIC\Export\Helpers\UsergroupAwareSimpleValue;

class ValueIsNotPositiveIntegerException extends \RuntimeException
{
    public function __construct($value)
    {
        parent::__construct(sprintf('%s is not an positive integer!', $value));
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
            throw new ValueIsNotPositiveIntegerException($value);
        }

        return $value;
    }
}
