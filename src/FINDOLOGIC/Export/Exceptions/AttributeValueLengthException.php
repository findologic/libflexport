<?php

namespace FINDOLOGIC\Export\Exceptions;

class AttributeValueLengthException extends InternalCharacterLimitException
{
    public function __construct($attributeName, $characterLimit)
    {
        parent::__construct('Value of attribute', $attributeName, $characterLimit);
    }
}
