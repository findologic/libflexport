<?php

namespace FINDOLOGIC\Export\Helpers;

class EmptyValueNotAllowedException extends \RuntimeException
{
    public function __construct($message = 'Empty values are not allowed!')
    {
        parent::__construct($message);
    }
}

class UnsupportedValueException extends \BadMethodCallException
{
    public function __construct($unsupportedValueName)
    {
        parent::__construct(sprintf('%s is not a supported value for the XML export format. Use a property instead.'));
    }
}

/**
 * Class DataHelper
 * @package FINDOLOGIC\Export\Helpers
 *
 * Collection of helper methods for data elements.
 */
class DataHelper
{
    /**
     * Checks if the provided value is empty.
     *
     * @param string|int|float $value The value to check. Regardless of type, it is coerced into a string.
     * @throws EmptyValueNotAllowedException If the value is empty.
     * @return string Returns the value if not empty.
     */
    public static function checkForEmptyValue($value)
    {
        $value = trim($value);

        if ($value === '') {
            throw new EmptyValueNotAllowedException();
        }

        return $value;
    }
}
