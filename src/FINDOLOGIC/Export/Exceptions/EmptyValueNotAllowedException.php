<?php

namespace FINDOLOGIC\Export\Exceptions;

use RuntimeException;

class EmptyValueNotAllowedException extends RuntimeException
{
    public function __construct(string $message = 'Empty values are not allowed!')
    {
        parent::__construct($message);
    }
}
