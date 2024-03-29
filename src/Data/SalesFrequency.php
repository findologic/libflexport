<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\Data;

use FINDOLOGIC\Export\Exceptions\EmptyValueNotAllowedException;
use FINDOLOGIC\Export\Exceptions\ValueIsNotPositiveIntegerException;
use FINDOLOGIC\Export\Helpers\UsergroupAwareSimpleValue;

final class SalesFrequency extends UsergroupAwareSimpleValue
{
    public function __construct()
    {
        parent::__construct('salesFrequencies', 'salesFrequency');
    }

    protected function validate(mixed $value): int
    {
        if ($value === '') {
            throw new EmptyValueNotAllowedException($this->getValueName());
        }

        if (!is_int($value) || $value < 0) {
            throw new ValueIsNotPositiveIntegerException($value);
        }

        return $value;
    }

    /**
     * @inheritDoc
     */
    public function getValueName(): string
    {
        return 'salesFrequency';
    }
}
