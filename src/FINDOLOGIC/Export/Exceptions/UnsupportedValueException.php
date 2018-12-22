<?php

namespace FINDOLOGIC\Export\Exceptions;

class UnsupportedValueException extends \BadMethodCallException
{
    public function __construct($unsupportedValueName)
    {
        parent::__construct(sprintf(
            '%s is not a supported value for the XML export format. Use a property instead.',
            $unsupportedValueName
        ));
    }
}
