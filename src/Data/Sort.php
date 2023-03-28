<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\Data;

use FINDOLOGIC\Export\Exceptions\EmptyValueNotAllowedException;
use FINDOLOGIC\Export\Exceptions\ValueIsNotIntegerException;
use FINDOLOGIC\Export\Helpers\UsergroupAwareSimpleValue;

final class Sort extends UsergroupAwareSimpleValue
{
    public function __construct()
    {
        parent::__construct('sorts', 'sort');
    }

    protected function validate(mixed $value): int
    {
        if ($value === '') {
            throw new EmptyValueNotAllowedException($this->getValueName());
        }

        if (!is_int($value)) {
            throw new ValueIsNotIntegerException($value);
        }

        return $value;
    }

    /**
     * @inheritDoc
     */
    public function getValueName(): string
    {
        return 'sort';
    }
}
