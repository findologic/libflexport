<?php

namespace FINDOLOGIC\Export\Helpers;

use Throwable;

class EmptyValueNotAllowedException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Empty values are not allowed!');
    }
}
class InvalidPriceException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Price is not a valid number!');
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

    /**
     * Checks if the provided price is a numeric number.
     *
     * @param $value int $value The value to check.
     * @return bool Returns true if the price is a valid numeric number.
     */
    public static function checkForValidPrice($value)
    {
        $value = trim($value);

        if (!is_numeric($value)) {
            throw new InvalidPriceException();
        }

        return true;
    }
}
