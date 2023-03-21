<?php

namespace FINDOLOGIC\Export\Exceptions;

use RuntimeException;

abstract class InternalCharacterLimitException extends RuntimeException
{
    public function __construct($elementInfo, $attributeName, $characterLimit)
    {
        parent::__construct(sprintf(
            '%s "%s" exceeds the internal character limit of %d!',
            $elementInfo,
            $attributeName,
            $characterLimit
        ));
    }
}
