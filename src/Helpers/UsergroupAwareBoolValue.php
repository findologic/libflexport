<?php

namespace FINDOLOGIC\Export\Helpers;

use FINDOLOGIC\Export\Exceptions\ValueIsNotAllowedException;

/**
 * Class UsergroupAwareSimpleValue
 * @package FINDOLOGIC\Export\Helpers
 *
 * Simple values that can differ per usergroup, but have one value at most for each.
 */
abstract class UsergroupAwareBoolValue extends UsergroupAwareSimpleValue
{
    protected function validate(mixed $value): string
    {
        $isValidInt = is_numeric($value) && (intval($value) === 1 || intval($value) === 0);
        $isBooleanString = strtolower($value) === 'true' || strtolower($value) === 'false';
        $isBoolean = is_bool($value);

        if ($isValidInt || $isBooleanString || $isBoolean) {
            $boolValue = ($isValidInt && $value === 1) ||
                ($isBooleanString && strtolower($value) === 'true') ||
                $isBoolean && $value;

            // Using boolean values leads to inconsistent string casting within XML generation
            return $boolValue ? 1 : 0;
        } else {
            throw new ValueIsNotAllowedException($value, 'a boolean, 1 or 0');
        }
    }
}
