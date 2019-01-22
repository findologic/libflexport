<?php

namespace FINDOLOGIC\Export\Exceptions;

/**
 * Thrown in case a property key is used for CSV export containing characters that would break the format.
 */
class BadPropertyKeyException extends \RuntimeException
{
    public function __construct($propertyKey)
    {
        parent::__construct(sprintf(
            'Tabs and line feed characters are not allowed in property key "%s", as they would break the format.',
            $propertyKey
        ));
    }
}
