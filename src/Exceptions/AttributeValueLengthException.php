<?php

namespace FINDOLOGIC\Export\Exceptions;

use RuntimeException;

class AttributeValueLengthException extends RuntimeException
{
    public function __construct(string $attributeName, int $characterLimit)
    {
        parent::__construct(sprintf(
            'Value of attribute "%s" exceeds the internal character limit of %d!',
            $attributeName,
            $characterLimit
        ));
    }
}
