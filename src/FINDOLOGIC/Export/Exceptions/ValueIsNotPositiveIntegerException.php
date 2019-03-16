<?php

namespace FINDOLOGIC\Export\Exceptions;

class ValueIsNotPositiveIntegerException extends \RuntimeException
{
    public function __construct(string $value)
    {
        parent::__construct(sprintf('%s is not an positive integer!', $value));
    }
}
