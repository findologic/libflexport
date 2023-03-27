<?php

namespace FINDOLOGIC\Export\Exceptions;

class AttributeKeyLengthException extends InternalCharacterLimitException
{
    public function __construct(string $attributeKey, string $characterLimit)
    {
        parent::__construct('Attribute with name', $attributeKey, $characterLimit);
    }
}
