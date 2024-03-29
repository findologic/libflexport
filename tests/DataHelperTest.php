<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\Tests;

use Exception;
use FINDOLOGIC\Export\Data\AllKeywords;
use FINDOLOGIC\Export\Data\AllOrdernumbers;
use FINDOLOGIC\Export\Data\Attribute;
use FINDOLOGIC\Export\Data\Bonus;
use FINDOLOGIC\Export\Data\DateAdded;
use FINDOLOGIC\Export\Data\Description;
use FINDOLOGIC\Export\Data\Image;
use FINDOLOGIC\Export\Data\Keyword;
use FINDOLOGIC\Export\Data\Name;
use FINDOLOGIC\Export\Data\Ordernumber;
use FINDOLOGIC\Export\Data\OverriddenPrice;
use FINDOLOGIC\Export\Data\Price;
use FINDOLOGIC\Export\Data\SalesFrequency;
use FINDOLOGIC\Export\Data\Sort;
use FINDOLOGIC\Export\Data\Summary;
use FINDOLOGIC\Export\Data\Url;
use FINDOLOGIC\Export\Data\Group;
use FINDOLOGIC\Export\Data\Visibility;
use FINDOLOGIC\Export\Exceptions\AttributeKeyLengthException;
use FINDOLOGIC\Export\Exceptions\AttributeValueLengthException;
use FINDOLOGIC\Export\Exceptions\BadPropertyKeyException;
use FINDOLOGIC\Export\Exceptions\EmptyValueNotAllowedException;
use FINDOLOGIC\Export\Exceptions\GroupNameLengthException;
use FINDOLOGIC\Export\Exceptions\ItemIdLengthException;
use FINDOLOGIC\Export\Exceptions\ValueIsNotNumericException;
use FINDOLOGIC\Export\Helpers\DataHelper;
use FINDOLOGIC\Export\Helpers\NameAwareValue;
use ReflectionClass;
use ReflectionException;

final class DataHelperTest extends TestCase
{
    /**
     * Scenarios for empty value validation.
     *
     * @return array Cases with the value to check and whether it should cause a validation issue.
     */
    public static function emptyValueProvider(): array
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
     * @dataProvider emptyValueProvider
     *
     * @param mixed $value Value that should be checked.
     * @param bool $shouldCauseException Whether the value should cause an exception or not.
     */
    public function testEmptyValueDetectsEmptyStringsOnly(mixed $value, bool $shouldCauseException): void
    {
        $expectedValueNames = 'foobar';

        try {
            $value = DataHelper::checkForEmptyValue($expectedValueNames, $value);

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
                $this->assertEquals(
                    sprintf('Empty values are not allowed for "%s" values.', $expectedValueNames),
                    $e->getMessage()
                );
            }
        }
    }

    /**
     * Scenarios for numeric value validation.
     *
     * @return array Cases with the value to check and whether it should cause a validation issue.
     */
    public static function numericValueProvider(): array
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
     * @dataProvider numericValueProvider
     *
     * @param mixed $value Value that should be checked.
     * @param bool $shouldCauseException Whether the value should cause an exception or not.
     */
    public function testNumericValuesAreValidated(mixed $value, bool $shouldCauseException): void
    {
        try {
            $numericValueElement = new DummyNumericValue('dummies', 'dummy');
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
     * Scenarios for property key validation.
     *
     * @return array Cases with the value to check and whether it should cause a validation issue.
     */
    public static function columnKeyProvider(): array
    {
        return [
            'Valid column keys' => [
                ['valid_column_key', 'also-a_valid-column-key',],
                false,
            ],
            'Invalid column keys' => [
                ["invalid\tvalid_column_key\nkey", "invalid\tkey", "invalid\nkey",],
                true,
            ],
            'Mixed valid and invalid column keys' => [
                ['valid_column_key',"invalid\tcolumn\nkey",],
                true,
            ],
        ];
    }

    /**
     * @dataProvider columnKeyProvider
     *
     * @param array $columnKeys The keys to check.
     * @param bool $shouldCauseException Whether the array should cause an exception or not.
     */
    public function testAddingInvalidCsvPropertyKeysCausesException(
        array $columnKeys,
        bool  $shouldCauseException
    ): void {
        try {
            $validatedColumnKeys = DataHelper::checkForInvalidCsvColumnKeys($columnKeys);

            $this->assertEquals($columnKeys, $validatedColumnKeys);
        } catch (Exception $exception) {
            if (!$shouldCauseException) {
                $this->fail('This should not fail.');
            } else {
                $this->assertEquals(BadPropertyKeyException::class, $exception::class);
            }
        }
    }

    /**
     * Test if character limit of data helper causes exception when called outside attribute class.
     */
    public function testAttributeValueCharacterLimitCausesException(): void
    {
        $this->expectException(AttributeValueLengthException::class);

        $value = self::generateMultiByteCharacterString(16384);

        DataHelper::checkAttributeValueNotExceedingCharacterLimit('some attribute', $value);
    }

    /**
     * Test if item id character limit of data helper causes exception when called outside item class.
     */
    public function testItemIdCharacterLimitCausesException(): void
    {
        $this->expectException(ItemIdLengthException::class);

        $id = self::generateMultiByteCharacterString(256);

        DataHelper::checkItemIdNotExceedingCharacterLimit($id);
    }

    /**
     * Test if group name character limit of data helper causes exception when called outside item class.
     */
    public function testGroupNameCharacterLimitCausesException(): void
    {
        $this->expectException(GroupNameLengthException::class);

        $group = self::generateMultiByteCharacterString(256);

        DataHelper::checkCsvGroupNameNotExceedingCharacterLimit($group);
    }

    /**
     * Test if attribute key character limit of data helper causes exception when called outside item class.
     */
    public function testAttributeKeyCharacterLimitCausesException(): void
    {
        $this->expectException(AttributeKeyLengthException::class);

        $attributeKey = self::generateMultiByteCharacterString(248);

        DataHelper::checkCsvAttributeKeyNotExceedingCharacterLimit($attributeKey);
    }

    /**
     * Generate a multi byte character string.
     *
     * @param int $stringLength The string length to generate.
     * @return string The multi byte character string.
     */
    public static function generateMultiByteCharacterString(int $stringLength): string
    {
        return implode('', array_fill(0, $stringLength, '©'));
    }

    public static function allValuesProvider(): array
    {
        return [
            'AllKeywords' => [AllKeywords::class, [], 'allKeywords'],
            'AllOrdernumbers' => [AllOrdernumbers::class, [], 'allOrdernumbers'],
            'Attribute' => [Attribute::class, ['foo'], 'attribute'],
            'Bonus' => [Bonus::class, [], 'bonus'],
            'DateAdded' => [DateAdded::class, [], 'dateAdded'],
            'Description' => [Description::class, [], 'description'],
            'Image' => [Image::class, ['https://example.org/foo.png'], 'image'],
            'Keyword' => [Keyword::class, ['keyword value'], 'keyword'],
            'Name' => [Name::class, [], 'name'],
            'Ordernumber' => [Ordernumber::class, ['ordernumber value'], 'ordernumber'],
            'OverriddenPrice' => [OverriddenPrice::class, ['ordernumber value'], 'overriddenPrice'],
            'Price' => [Price::class, [], 'price'],
            'SalesFrequency' => [SalesFrequency::class, [], 'salesFrequency'],
            'Sort' => [Sort::class, [], 'sort'],
            'Summary' => [Summary::class, [], 'summary'],
            'Url' => [Url::class, [], 'url'],
            'Group' => [Group::class, ['nice people'], 'group'],
            'Visibility' => [Visibility::class, ['true'], 'visible']
        ];
    }

    /**
     * @dataProvider allValuesProvider
     *
     * @param string $class The class to check for its name.
     * @param array $constructorArgs Arguments for the constructor of $class.
     * @param string $expectedName The name the class should have.
     * @throws ReflectionException
     */
    public function testValuesKnowTheirOwnNames(
        string $class,
        array $constructorArgs,
        string $expectedName
    ): void {
        $reflector = new ReflectionClass($class);
        /** @var NameAwareValue $value */
        $value = $reflector->newInstanceArgs($constructorArgs);

        $this->assertEquals($expectedName, $value->getValueName());
    }
}
