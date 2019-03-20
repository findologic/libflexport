<?php

namespace FINDOLOGIC\Export\Exceptions;

use RuntimeException;

class EmptyValueNotAllowedException extends RuntimeException
{
    public function __construct(string $valueName)
    {
        parent::__construct(sprintf('Empty values are not allowed for "%s" values.', $valueName));
    }
}
