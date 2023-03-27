<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\Exceptions;

use RuntimeException;

final class ValueIsNotAllowedException extends RuntimeException
{
    public function __construct(string $value, string $allowed)
    {
        parent::__construct(sprintf('%s is not allowed! Value must be %s.', $value, $allowed));
    }
}
