<?php

namespace FINDOLOGIC\Export\Exceptions;

class AttributeKeyLengthException extends InternalCharacterLimitException
{
    public function __construct($attributeKey, $characterLimit)
    {
        parent::__construct('Attribute with name', $attributeKey, $characterLimit);
    }
}