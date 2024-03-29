<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\Helpers;

use FINDOLOGIC\Export\CSV\CSVConfig;
use FINDOLOGIC\Export\Exceptions\ValueIsNotAllowedException;

/**
 * Class UsergroupAwareBoolValue
 * @package FINDOLOGIC\Export\Helpers
 *
 * Boolean values that can differ per usergroup.
 */
abstract class UsergroupAwareBoolValue extends UsergroupAwareSimpleValue
{
    protected int $default;

    public function __construct(string $collectionName, string $itemName, int|bool $default)
    {
        parent::__construct($collectionName, $itemName);

        $this->default = $this->validate($default);
    }

    protected function validate(mixed $value): int
    {
        $isValidInt = is_numeric($value) && (intval($value) === 1 || intval($value) === 0);
        $isBooleanString = is_string($value) && (strtolower($value) === 'true' || strtolower($value) === 'false');
        $isBoolean = is_bool($value);

        if ($isValidInt || $isBooleanString || $isBoolean) {
            $boolValue = ($isValidInt && intval($value) === 1) ||
                ($isBooleanString && strtolower($value) === 'true') ||
                $isBoolean && $value;

            // Using boolean values leads to inconsistent string casting within XML generation
            return $boolValue ? 1 : 0;
        } else {
            throw new ValueIsNotAllowedException($value, 'a boolean, 1 or 0');
        }
    }

    /**
     * @inheritdoc
     */
    public function getCsvFragment(CSVConfig $csvConfig): string
    {
        $value = $this->default;

        if (array_key_exists('', $this->getValues())) {
            $value = $this->getValues()[''];
        }

        return (string) $value;
    }
}
