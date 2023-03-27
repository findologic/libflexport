<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\Helpers;

use FINDOLOGIC\Export\Exceptions\AttributeKeyLengthException;
use FINDOLOGIC\Export\Exceptions\AttributeValueLengthException;
use FINDOLOGIC\Export\Exceptions\BadPropertyKeyException;
use FINDOLOGIC\Export\Exceptions\EmptyValueNotAllowedException;
use FINDOLOGIC\Export\Exceptions\GroupNameLengthException;
use FINDOLOGIC\Export\Exceptions\InvalidUrlException;
use FINDOLOGIC\Export\Exceptions\ItemIdLengthException;

/**
 * Class DataHelper
 * @package FINDOLOGIC\Export\Helpers
 *
 * Collection of helper methods for data elements.
 */
final class DataHelper
{
    /**
     * Internal character limit for attribute values.
     * @var int
     */
    public const ATTRIBUTE_CHARACTER_LIMIT = 16383;

    /**
     * Internal character limit for item id.
     * @var int
     */
    public const ITEM_ID_CHARACTER_LIMIT = 255;

    /**
     * Internal character limit for group names of CSV export.
     * @var int
     */
    public const CSV_GROUP_CHARACTER_LIMIT = 255;

    /**
     * Internal character limit for attribute key names of CSV export.
     * @var int
     */
    public const CSV_ATTRIBUTE_KEY_CHARACTER_LIMIT = 247;

    /**
     * Checks if the provided value is empty.
     *
     * @param string $valueName Name of the value, for better error reporting.
     * @param mixed $value The value to check. Regardless of type, it is coerced into a string.
     * @return string Returns the value if not empty.
     */
    public static function checkForEmptyValue(string $valueName, mixed $value): string
    {
        $value = trim((string) $value);

        if ($value === '') {
            throw new EmptyValueNotAllowedException($valueName);
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
    public static function validateUrl(string $url): string
    {
        if (!filter_var($url, FILTER_VALIDATE_URL) || !preg_match('/http[s]?:\/\/.*/', $url)) {
            throw new InvalidUrlException($url);
        }

        return $url;
    }

    /**
     * Verifies that property keys for use in CSV export don't contain characters that could break the format fatally.
     *
     * @param string[] $columnKeys The property keys to check.
     * @return array The validated property keys.
     * @throws  BadPropertyKeyException In case the property key contains dangerous characters.
     */
    public static function checkForInvalidCsvColumnKeys(array $columnKeys): array
    {
        foreach ($columnKeys as $columnKey) {
            if (str_contains($columnKey, "\t") || str_contains($columnKey, "\n")) {
                throw new BadPropertyKeyException($columnKey);
            }
        }

        return $columnKeys;
    }

    /**
     * @param string $attributeName Attribute name to output in exception.
     * @param string $attributeValue Attribute value to check if it exceeds character limit.
     */
    public static function checkAttributeValueNotExceedingCharacterLimit(
        string $attributeName,
        string $attributeValue
    ): void {
        if (mb_strlen($attributeValue) > self::ATTRIBUTE_CHARACTER_LIMIT) {
            throw new AttributeValueLengthException($attributeName, self::ATTRIBUTE_CHARACTER_LIMIT);
        }
    }

    /**
     * @param string $id Attribute value to check if it exceeds character limit.
     */
    public static function checkItemIdNotExceedingCharacterLimit(string $id): void
    {
        if (mb_strlen($id) > self::ITEM_ID_CHARACTER_LIMIT) {
            throw new ItemIdLengthException($id, self::ITEM_ID_CHARACTER_LIMIT);
        }
    }

    /**
     * @param string $group Group name to check if it exceeds character limit.
     */
    public static function checkCsvGroupNameNotExceedingCharacterLimit(string $group): void
    {
        if (mb_strlen($group) > self::CSV_GROUP_CHARACTER_LIMIT) {
            throw new GroupNameLengthException($group, self::CSV_GROUP_CHARACTER_LIMIT);
        }
    }

    /**
     * @param string $group Group name to check if it exceeds character limit.
     */
    public static function checkCsvAttributeKeyNotExceedingCharacterLimit(string $group): void
    {
        if (mb_strlen($group) > self::CSV_ATTRIBUTE_KEY_CHARACTER_LIMIT) {
            throw new AttributeKeyLengthException($group, self::CSV_ATTRIBUTE_KEY_CHARACTER_LIMIT);
        }
    }

    public static function sanitize(string $input, $stripTags = true): string
    {
        if ($stripTags) {
            $input = strip_tags($input);
        }

        return preg_replace('/[\t\n\r]/', ' ', $input);
    }
}
