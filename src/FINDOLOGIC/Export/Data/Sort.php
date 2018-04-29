<?php

namespace FINDOLOGIC\Export\Data;

use FINDOLOGIC\Export\Helpers\UsergroupAwareSimpleValue;
use FINDOLOGIC\Export\Helpers\EmptyValueNotAllowedException;

class ValueIsNotIntegerException extends \RuntimeException
{
    public function __construct($value)
    {
        parent::__construct(sprintf('%s is not an integer!', $value));
    }
}

class Sort extends UsergroupAwareSimpleValue
{
    public function __construct()
    {
        parent::__construct('sorts', 'sort');
    }

    protected function validate($value)
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
