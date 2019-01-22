<?php

namespace FINDOLOGIC\Export\Tests;

use FINDOLOGIC\Export\Data\Attribute;
use FINDOLOGIC\Export\Helpers\UsergroupAwareNumericValue;
use FINDOLOGIC\Export\Exceptions\ValueIsNotNumericException;
use FINDOLOGIC\Export\Exceptions\EmptyValueNotAllowedException;
use FINDOLOGIC\Export\Helpers\DataHelper;
use PHPUnit\Framework\TestCase;

class DataHelperTest extends TestCase
{
    /**
     * @dataProvider emptyValueProvider
     *
     * @param string|int $value Value that should be checked.
     * @param bool $shouldCauseException Whether the value should cause an exception or not.
     */
    public function testEmptyValueDetectsEmptyStringsOnly($value, bool $shouldCauseException)
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
     * @param string|int|bool $value Value that should be checked.
     * @param bool $shouldCauseException Whether the value should cause an exception or not.
     */
    public function testNumericValuesAreValidated($value, bool $shouldCauseException)
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

    /**
     * Test if character limit of data helper causes exception when called outside attribute class.
     *
     * @expectedException \FINDOLOGIC\Export\Exceptions\AttributeValueLengthException
     */
    public function testCharacterLimitCausesException()
    {
        $value = implode('', array_fill(0, 16384, '©'));

        DataHelper::checkAttributeValueNotExceedingCharacterLimit('some attribute', $value);
    }
}
