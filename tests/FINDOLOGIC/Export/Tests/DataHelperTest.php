<?php

namespace FINDOLOGIC\Export\Tests;

use FINDOLOGIC\Export\Exceptions\AttributeKeyLengthException;
use FINDOLOGIC\Export\Exceptions\AttributeValueLengthException;
use FINDOLOGIC\Export\Exceptions\GroupNameLengthException;
use FINDOLOGIC\Export\Exceptions\ItemIdLengthException;
use FINDOLOGIC\Export\Helpers\DataHelper;
use FINDOLOGIC\Export\Helpers\EmptyValueNotAllowedException;
use FINDOLOGIC\Export\Helpers\UsergroupAwareNumericValue;
use FINDOLOGIC\Export\Helpers\ValueIsNotNumericException;
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

    /**
     * Test if character limit of data helper causes exception when called outside attribute class.
     */
    public function testAttributeValueCharacterLimitCausesException()
    {
        $this->expectException(AttributeValueLengthException::class);

        $value = $this->generateMultiByteCharacterString(16384);

        DataHelper::checkAttributeValueNotExceedingCharacterLimit('some attribute', $value);
    }

    /**
     * Test if item id character limit of data helper causes exception when called outside item class.
     */
    public function testItemIdCharacterLimitCausesException()
    {
        $this->expectException(ItemIdLengthException::class);

        $id = $this->generateMultiByteCharacterString(256);

        DataHelper::checkItemIdNotExceedingCharacterLimit($id);
    }

    /**
     * Test if group name character limit of data helper causes exception when called outside item class.
     */
    public function testGroupNameCharacterLimitCausesException()
    {
        $this->expectException(GroupNameLengthException::class);

        $group = $this->generateMultiByteCharacterString(256);

        DataHelper::checkCsvGroupNameNotExceedingCharacterLimit($group);
    }

    /**
     * Test if attribute key character limit of data helper causes exception when called outside item class.
     */
    public function testAttributeKeyCharacterLimitCausesException()
    {
        $this->expectException(AttributeKeyLengthException::class);

        $attributeKey = $this->generateMultiByteCharacterString(248);

        DataHelper::checkCsvAttributeKeyNotExceedingCharacterLimit($attributeKey);
    }

    /**
     * Generate a multi byte character string.
     *
     * @param int $stringLength The string length to generate.
     * @return string The multi byte character string.
     */
    public function generateMultiByteCharacterString($stringLength)
    {
        return implode('', array_fill(0, $stringLength, '©'));
    }
}
