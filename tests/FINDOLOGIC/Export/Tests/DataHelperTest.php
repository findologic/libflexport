<?php

namespace FINDOLOGIC\Export\Tests;

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
use FINDOLOGIC\Export\Data\Price;
use FINDOLOGIC\Export\Data\SalesFrequency;
use FINDOLOGIC\Export\Data\Sort;
use FINDOLOGIC\Export\Data\Summary;
use FINDOLOGIC\Export\Data\Url;
use FINDOLOGIC\Export\Data\Usergroup;
use FINDOLOGIC\Export\Exceptions\AttributeKeyLengthException;
use FINDOLOGIC\Export\Exceptions\AttributeValueLengthException;
use FINDOLOGIC\Export\Exceptions\EmptyValueNotAllowedException;
use FINDOLOGIC\Export\Exceptions\GroupNameLengthException;
use FINDOLOGIC\Export\Exceptions\ItemIdLengthException;
use FINDOLOGIC\Export\Exceptions\ValueIsNotNumericException;
use FINDOLOGIC\Export\Helpers\DataHelper;
use FINDOLOGIC\Export\Helpers\NameAwareValue;
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use ReflectionException;

class DataHelperTest extends TestCase
{
    /**
     * @dataProvider emptyValueProvider
     *
     * @param string|int $value Value that should be checked.
     * @param bool $shouldCauseException Whether the value should cause an exception or not.
     */
    public function testEmptyValueDetectsEmptyStringsOnly($value, bool $shouldCauseException): void
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
     * @noinspection PhpMethodMayBeStaticInspection
     *
     * Scenarios for empty value validation.
     *
     * @return array Cases with the value to check and whether it should cause a validation issue.
     */
    public function emptyValueProvider(): array
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
    public function testNumericValuesAreValidated($value, bool $shouldCauseException): void
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
     * @noinspection PhpMethodMayBeStaticInspection
     *
     * Scenarios for numeric value validation.
     *
     * @return array Cases with the value to check and whether it should cause a validation issue.
     */
    public function numericValueProvider(): array
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

        $value = self::generateMultiByteCharacterString(16384);

        DataHelper::checkAttributeValueNotExceedingCharacterLimit('some attribute', $value);
    }

    /**
     * Test if item id character limit of data helper causes exception when called outside item class.
     */
    public function testItemIdCharacterLimitCausesException()
    {
        $this->expectException(ItemIdLengthException::class);

        $id = self::generateMultiByteCharacterString(256);

        DataHelper::checkItemIdNotExceedingCharacterLimit($id);
    }

    /**
     * Test if group name character limit of data helper causes exception when called outside item class.
     */
    public function testGroupNameCharacterLimitCausesException()
    {
        $this->expectException(GroupNameLengthException::class);

        $group = self::generateMultiByteCharacterString(256);

        DataHelper::checkCsvGroupNameNotExceedingCharacterLimit($group);
    }

    /**
     * Test if attribute key character limit of data helper causes exception when called outside item class.
     */
    public function testAttributeKeyCharacterLimitCausesException()
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
    public static function generateMultiByteCharacterString($stringLength)
    {
        return implode('', array_fill(0, $stringLength, '©'));
    }

    /**
     * @noinspection PhpMethodMayBeStaticInspection
     *
     * @return array
     */
    public function allValuesProvider()
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
            'Price' => [Price::class, [], 'price'],
            'SalesFrequency' => [SalesFrequency::class, [], 'salesFrequency'],
            'Sort' => [Sort::class, [], 'sort'],
            'Summary' => [Summary::class, [], 'summary'],
            'Url' => [Url::class, [], 'url'],
            'Usergroup' => [Usergroup::class, ['nice people'], 'usergroup']
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
    ) {
        $reflector = new ReflectionClass($class);
        /** @var NameAwareValue $value */
        $value = $reflector->newInstanceArgs($constructorArgs);

        $this->assertEquals($expectedName, $value->getValueName());
    }
}
