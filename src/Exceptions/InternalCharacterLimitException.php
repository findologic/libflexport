<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\Exceptions;

use RuntimeException;

abstract class InternalCharacterLimitException extends RuntimeException
{
    public function __construct(string $elementInfo, string $attributeName, string $characterLimit)
    {
        parent::__construct(sprintf(
            '%s "%s" exceeds the internal character limit of %d!',
            $elementInfo,
            $attributeName,
            $characterLimit
        ));
    }
}
