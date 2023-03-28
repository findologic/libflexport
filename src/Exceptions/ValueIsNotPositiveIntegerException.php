<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\Exceptions;

use RuntimeException;

final class ValueIsNotPositiveIntegerException extends RuntimeException
{
    public function __construct(string|int $value)
    {
        parent::__construct(sprintf('%s is not an positive integer!', $value));
    }
}
