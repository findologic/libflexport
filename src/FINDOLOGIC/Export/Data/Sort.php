<?php

namespace FINDOLOGIC\Export\Data;

use FINDOLOGIC\Export\Helpers\UsergroupAwareSimpleValue;
use FINDOLOGIC\Export\Exceptions\EmptyValueNotAllowedException;
use FINDOLOGIC\Export\Exceptions\ValueIsNotIntegerException;

class Sort extends UsergroupAwareSimpleValue
{
    public function __construct()
    {
        parent::__construct('sorts', 'sort');
    }

    protected function validate($value): int
    {
        if ($value === '') {
            throw new EmptyValueNotAllowedException();
        }

        if (!is_int($value)) {
            throw new ValueIsNotIntegerException($value);
        }

        return $value;
    }
}
