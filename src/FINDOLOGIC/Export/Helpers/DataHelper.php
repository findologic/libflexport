<?php

namespace FINDOLOGIC\Export\Helpers;

class EmptyValueNotAllowedException extends \RuntimeException
{
    public function __construct($message = 'Empty values are not allowed!')
    {
        parent::__construct($message);
    }
}

class InvalidUrlException extends \RuntimeException
{
    public function __construct()
    {
        parent::__construct('Value is not a valid url!');
    }
}

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

/**
 * Thrown in case a property key is used for CSV export containing characters that would break the format.
 */
class BadPropertyKeyException extends \RuntimeException
{
    public function __construct($propertyKey)
    {
        parent::__construct(sprintf(
            'Tabs and line feed characters are not allowed in property key "%s", as they would break the format.',
            $propertyKey
        ));
    }
}

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

class ItemIdValueLengthException extends \RuntimeException
{
    public function __construct($id, $characterLimit)
    {
        parent::__construct(sprintf(
            'Item with id "%s" exceeds the internal character limit of %d!',
            $id,
            $characterLimit
        ));
    }
}

class GroupNameValueLengthException extends \RuntimeException
{
    public function __construct($group, $characterLimit)
    {
        parent::__construct(sprintf(
            'Group with name "%s" exceeds the internal character limit of %d!',
            $group,
            $characterLimit
        ));
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
    /*
     * Internal character limit for attribute values.
     */
    const ATTRIBUTE_CHARACTER_LIMIT = 16383;

    /*
     * Internal character limit for item id.
     */
    const ITEM_ID_CHARACTER_LIMIT = 255;

    /*
     * Internal character limit for group names of CSV export.
     */
    const CSV_GROUP_CHARACTER_LIMIT = 255;

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
    public static function validateUrl($url)
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
    public static function checkForIllegalCsvPropertyKeys($propertyKey)
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
        if (mb_strlen($attributeValue) > self::ATTRIBUTE_CHARACTER_LIMIT) {
            throw new AttributeValueLengthException($attributeName, self::ATTRIBUTE_CHARACTER_LIMIT);
        }
    }

    /**
     * @param string $id Attribute value to check if it exceeds character limit.
     */
    public static function checkItemIdNotExceedingCharacterLimit($id)
    {
        if (mb_strlen($id) > self::ITEM_ID_CHARACTER_LIMIT) {
            throw new ItemIdValueLengthException($id, self::ITEM_ID_CHARACTER_LIMIT);
        }
    }

    /**
     * @param string $group Group name to check if it exceeds character limit.
     */
    public static function checkCsvGroupNameNotExceedingCharacterLimit($group)
    {
        if (mb_strlen($group) > self::CSV_GROUP_CHARACTER_LIMIT) {
            throw new GroupNameValueLengthException($group, self::CSV_GROUP_CHARACTER_LIMIT);
        }
    }
}
