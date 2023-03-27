<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\Exceptions;

use RuntimeException;

final class ItemsExceedCountValueException extends RuntimeException
{
    public function __construct()
    {
        $message = 'The number of items must not exceed the count value';
        parent::__construct($message);
    }
}
