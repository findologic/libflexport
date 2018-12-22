<?php

namespace FINDOLOGIC\Export\Exceptions;

class AttributeValueLengthException extends \RuntimeException
{
    public function __construct($attributeName, $characterLimit)
    {
        parent::__construct(sprintf(
            'Value of attribute "%s" exceeds the internal character limit of %d!',
            $attributeName,
            $characterLimit
        ));
    }
}