<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\Exceptions;

use RuntimeException;

final class PropertyKeyNotAllowedException extends RuntimeException
{
    public function __construct(string $key)
    {
        $format = 'Property key "%s" is reserved for internal use and overwritten when importing.';
        parent::__construct(sprintf($format, $key));
    }
}
