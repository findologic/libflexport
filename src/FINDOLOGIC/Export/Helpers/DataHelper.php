<?php

namespace FINDOLOGIC\Export\Helpers;

use FINDOLOGIC\Export\Exceptions\AttributeValueLengthException;
use FINDOLOGIC\Export\Exceptions\BadPropertyKeyException;
use FINDOLOGIC\Export\Exceptions\EmptyValueNotAllowedException;
use FINDOLOGIC\Export\Exceptions\InvalidUrlException;

/**
 * Class DataHelper
 * @package FINDOLOGIC\Export\Helpers
 *
 * Collection of helper methods for data elements.
 */
class DataHelper
{
    /*
     * Internal character limit for attribute values.
     */
    private const CHARACTER_LIMIT = 16383;

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
     * Checks if the provided input complies to a FINDOLOGIC valid url.
     * URL needs to have a http[s] schema.
     * See https://docs.findologic.com/doku.php?id=export_patterns:xml#urls
     *
     * @param string $url The input to check.
     * @throws InvalidUrlException If the input is no url.
     * @return string Returns the url if valid.
     */
    public static function validateUrl(string $url)
    {
        if (!filter_var($url, FILTER_VALIDATE_URL) || !preg_match('/http[s]?:\/\/.*/', $url)) {
            throw new InvalidUrlException();
        }

        return $url;
    }

    /**
     * Verifies that property keys for use in CSV export don't contain characters that could break the format fatally.
     *
     * @param string $propertyKey The property key to check.
     * @throw BadPropertyKeyException In case the property key contains dangerous characters.
     */
    public static function checkForIllegalCsvPropertyKeys(string $propertyKey)
    {
        if (strpos($propertyKey, "\t") !== false || strpos($propertyKey, "\n") !== false) {
            throw new BadPropertyKeyException($propertyKey);
        }
    }

    /**
     * @param string $attributeName Attribute name to output in exception.
     * @param string $attributeValue Attribute value to check if it exceeds character limit.
     */
    public static function checkAttributeValueNotExceedingCharacterLimit($attributeName, $attributeValue)
    {
        if (mb_strlen($attributeValue) > self::CHARACTER_LIMIT) {
            throw new AttributeValueLengthException($attributeName, self::CHARACTER_LIMIT);
        }
    }
}
