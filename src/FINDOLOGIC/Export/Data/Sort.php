<?php

namespace FINDOLOGIC\Export\Data;

use FINDOLOGIC\Export\Exceptions\EmptyValueNotAllowedException;
use FINDOLOGIC\Export\Exceptions\ValueIsNotIntegerException;
use FINDOLOGIC\Export\Helpers\UsergroupAwareSimpleValue;

class Sort extends UsergroupAwareSimpleValue
{
    public function __construct()
    {
        parent::__construct('sorts', 'sort');
    }

    protected function validate($value): int
    {
        if ($value === '') {
            throw new EmptyValueNotAllowedException($this->getValueName());
        }

        if (!is_int($value)) {
            throw new ValueIsNotIntegerException($value);
        }

        return $value;
    }

    public function getValueName(): string
    {
        return 'sort';
    }
}
