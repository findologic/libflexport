<?php

namespace FINDOLOGIC\Export\Exceptions;

use RuntimeException;

class ValueIsNotNumericException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Value is not a valid number!');
    }
}
