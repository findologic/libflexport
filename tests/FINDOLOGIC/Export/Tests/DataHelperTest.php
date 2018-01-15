<?php

namespace FINDOLOGIC\Export\Tests;

use FINDOLOGIC\Export\Helpers\UsergroupAwareNumericValue;
use FINDOLOGIC\Export\Helpers\ValueIsNotNumericException;
use FINDOLOGIC\Export\Helpers\EmptyValueNotAllowedException;
use FINDOLOGIC\Export\Helpers\DataHelper;
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
            $value = DataHelper::checkForEmptyValue($value);

            if ($shouldCauseException) {
                $this->fail('Should be detected as empty value.');
            } else {
                // The following assertion exists mostly to ensure that PHPUnit does not lament
                // the lack of assertions in this successful test.
                $this->assertNotNull($value);
            }
        } catch (EmptyValueNotAllowedException $e) {
            if (!$shouldCauseException) {
                $this->fail('Should not be detected as empty value.');
            } else {
                $this->assertEquals('Empty values are not allowed!', $e->getMessage());
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
     * @dataProvider numericValueProvider
     *
     * @param $value string|int|bool value that should be checked.
     * @param $shouldCauseException bool should an exception be caused by given parameter.
     */
    public function testNumericValuesAreValidated($value, $shouldCauseException)
    {
        try {
            $numericValueElement = new UsergroupAwareNumericValue('dummies', 'dummy');
            $numericValueElement->setValue($value);

            if ($shouldCauseException) {
                $this->fail('Should be detected as numeric value.');
            } else {
                // The following assertion exists mostly to ensure that PHPUnit does not lament
                // the lack of assertions in this successful test.
                $this->assertNotNull($numericValueElement);
            }
        } catch (ValueIsNotNumericException $e) {
            if (!$shouldCauseException) {
                $this->fail('Should not be detected as numeric value.');
            } else {
                $this->assertEquals('Value is not a valid number!', $e->getMessage());
            }
        }
    }

    /**
     * Scenarios for numeric value validation.
     *
     * @return array Cases with the value to check and whether it should cause a validation issue.
     */
    public function numericValueProvider()
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
