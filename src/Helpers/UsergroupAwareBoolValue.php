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
    protected function validate(mixed $value): bool
    {
        $value = parent::validate($value);

        $isValidInt = is_numeric($value) && (intval($value) === 1 || intval($value) === 0);
        $isBoolean = is_bool($value);

        if ($isValidInt || $isBoolean) {
            return true;
        } else {
            throw new ValueIsNotAllowedException($value, 'a boolean, 1 or 0');
        }
    }
}
