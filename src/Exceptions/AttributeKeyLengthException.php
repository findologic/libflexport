<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\Exceptions;

final class AttributeKeyLengthException extends InternalCharacterLimitException
{
    public function __construct(string $attributeKey, int $characterLimit)
    {
        parent::__construct('Attribute with name', $attributeKey, $characterLimit);
    }
}
