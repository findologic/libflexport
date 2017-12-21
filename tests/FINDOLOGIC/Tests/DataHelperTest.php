<?php

namespace FINDOLOGIC\Tests;

use FINDOLOGIC\Export\Data\Price;
use FINDOLOGIC\Export\Helpers\ValueIsNotNumericException;
use FINDOLOGIC\Export\Helpers\EmptyValueNotAllowedException;
use FINDOLOGIC\Export\Helpers\UsergroupAwareSimpleValue;
use PHPUnit\Framework\TestCase;

class DataHelperTest extends TestCase
{
    /**
     * @dataProvider emptyValueProvider
     *
     * @param $value string|int value that should be checked.
     * @param $shouldCauseException bool should an exception be caused by given parameter.
     */
    public function testEmptyValueDetectsEmptyStringsOnly($value, $shouldCauseException)
    {
        try {
            UsergroupAwareSimpleValue::validate($value);

            if ($shouldCauseException) {
                $this->fail('Should be detected as empty value.');
            }
        } catch (EmptyValueNotAllowedException $e) {
            if (!$shouldCauseException) {
                $this->fail('Should not be detected as empty value.');
            }
        }
    }

    /**
     * Scenarios for empty value validation.
     *
     * @return array Cases with the value to check and whether it should cause a validation issue.
     */
    public function emptyValueProvider()
    {
        return [
            'empty string' => ['', true],
            'non-zero integer' => [123, false],
            'zero as integer' => [0, false],
            'non-zero float' => [12.3, false],
            'zero as float' => [0.0, false],
            'zero as string' => ['0', false],
            'null' => ['', true],
            'false' => [false, true]
        ];
    }

    /**
     * @dataProvider priceValueProvider
     *
     * @param $value string|int value that should be checked.
     * @param $shouldCauseException bool should an exception be caused by given parameter.
     */
    public function testPriceValueDetectsNumericsOnly($value, $shouldCauseException)
    {
        try {
            Price::validate($value);

            if ($shouldCauseException) {
                $this->fail('Should be detected as numeric value.');
            }
        } catch (ValueIsNotNumericException $e) {
            if (!$shouldCauseException) {
                $this->fail('Should not be detected as numeric value.');
            }
        }
    }

    /**
     * Scenarios for numeric price value validation.
     *
     * @return array Cases with the value to check and whether it should cause a validation issue.
     */
    public function priceValueProvider()
    {
        return [
            'string' => ['blubbergurke', true],
            'non-zero integer' => [123, false],
            'zero as integer' => [0, false],
            'non-zero float' => [12.3, false],
            'zero as float' => [0.0, false],
            'zero as string' => ['0', false]
        ];
    }
}
