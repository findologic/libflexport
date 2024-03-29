<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\Exceptions;

use RuntimeException;

/**
 * Thrown in case a property key is used for CSV export containing characters that would break the format.
 */
final class BadPropertyKeyException extends RuntimeException
{
    public function __construct(string $propertyKey)
    {
        parent::__construct(sprintf(
            'Tabs and line feed characters are not allowed in property key "%s", as they would break the format.',
            $propertyKey
        ));
    }
}
