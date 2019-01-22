<?php

namespace FINDOLOGIC\Export\Data;

use FINDOLOGIC\Export\Helpers\UsergroupAwareSimpleValue;
use FINDOLOGIC\Export\Exceptions\EmptyValueNotAllowedException;
use FINDOLOGIC\Export\Exceptions\ValueIsNotPositiveIntegerException;

class SalesFrequency extends UsergroupAwareSimpleValue
{
    public function __construct()
    {
        parent::__construct('salesFrequencies', 'salesFrequency');
    }

    protected function validate($value): int
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
