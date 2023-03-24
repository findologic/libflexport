<?php

namespace FINDOLOGIC\Export\Exceptions;

use RuntimeException;

class ValueIsNotAllowedException extends RuntimeException
{
    public function __construct(string $value, string $allowed)
    {
        parent::__construct(sprintf('%s is not allowed! Value must be %s.', $value, $allowed));
    }
}
