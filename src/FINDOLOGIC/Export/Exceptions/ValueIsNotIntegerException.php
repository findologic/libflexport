<?php

namespace FINDOLOGIC\Export\Exceptions;

class ValueIsNotIntegerException extends \RuntimeException
{
    public function __construct($value)
    {
        parent::__construct(sprintf('%s is not an integer!', $value));
    }
}
