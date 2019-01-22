<?php

namespace FINDOLOGIC\Export\Exceptions;

class EmptyElementsNotAllowedException extends \RuntimeException
{
    public function __construct($elementType, $elementKey)
    {
        $message = "Elements with empty values are not allowed. '{$elementType}' with the name '{$elementKey}'";
        parent::__construct($message);
    }
}
