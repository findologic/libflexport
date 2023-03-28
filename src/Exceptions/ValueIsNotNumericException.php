<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\Exceptions;

use RuntimeException;

final class ValueIsNotNumericException extends RuntimeException
{
    public function __construct()
    {
        parent::__construct('Value is not a valid number!');
    }
}
