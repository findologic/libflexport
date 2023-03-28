<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\Exceptions;

use RuntimeException;

final class ValueIsNotIntegerException extends RuntimeException
{
    public function __construct(mixed $value)
    {
        parent::__construct(sprintf('%s is not an integer!', $value));
    }
}
