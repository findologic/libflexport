<?php

namespace FINDOLOGIC\Export\Exceptions;

class ValueIsNotIntegerException extends \RuntimeException
{
    public function __construct(string $value)
    {
        parent::__construct(sprintf('%s is not an integer!', $value));
    }
}
