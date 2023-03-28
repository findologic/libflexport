<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\Exceptions;

use RuntimeException;

final class AttributeValueLengthException extends RuntimeException
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
