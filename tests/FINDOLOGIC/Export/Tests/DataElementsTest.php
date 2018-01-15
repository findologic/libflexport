<?php

namespace FINDOLOGIC\Export\Tests;

use FINDOLOGIC\Export\Data\Attribute;
use FINDOLOGIC\Export\Data\Bonus;
use FINDOLOGIC\Export\Data\DateAdded;
use FINDOLOGIC\Export\Data\Description;
use FINDOLOGIC\Export\Data\Keyword;
use FINDOLOGIC\Export\Data\Name;
use FINDOLOGIC\Export\Data\Ordernumber;
use FINDOLOGIC\Export\Data\Price;
use FINDOLOGIC\Export\Data\Property;
use FINDOLOGIC\Export\Data\SalesFrequency;
use FINDOLOGIC\Export\Data\Sort;
use FINDOLOGIC\Export\Data\Summary;
use FINDOLOGIC\Export\Data\Url;
use FINDOLOGIC\Export\Helpers\EmptyValueNotAllowedException;
use FINDOLOGIC\Export\Helpers\ValueIsNotNumericException;
use PHPUnit\Framework\TestCase;

class DataElementsTest extends TestCase
{
    /**
     * Provides a data set for testing if initializing elements of type UsergroupAwareMultiValueItem
     * with an empty value fails.
     *
     * @return array Scenarios with a value, the element class and whether this input should cause an exception.
     */
    public function multiValueItemProvider()
    {
        return [
            'Keyword with empty value' => ['', Keyword::class, true],
            'Keyword with value' => ['value', Keyword::class, false],
            'Ordernumber with empty value' => ['', Ordernumber::class, true],
            'Ordernumber with value' => ['value', Ordernumber::class, false]
        ];
    }

    /**
     * @dataProvider multiValueItemProvider
     * @param string $value
     * @param string $elementType
     * @param bool $shouldCauseException
     */
    public function testAddingEmptyValuesToMultiValueItemCausesException(
        $value = '',
        $elementType = '',
        $shouldCauseException = true
    ) {
        try {
            $element = new $elementType($value);
            if ($shouldCauseException) {
                $this->fail('Adding empty values should cause exception!');
            } else {
                // The following assertion exists mostly to ensure that PHPUnit does not lament
                // the lack of assertions in this successful test.
                $this->assertNotNull($element);
            }
        } catch (\Exception $exception) {
            $this->assertEquals(EmptyValueNotAllowedException::class, get_class($exception));
        }
    }

    /**
     * Provides a data set for testing if adding empty values to elements of type UsergroupAwareSimpleValue fails.
     *
     * @return array Scenarios with a value, the element class and the expected exception, or null if none is supposed
     *      to be thrown.
     */
    public function simpleValueItemProvider()
    {
        return [
            'Bonus with empty value' => ['', Bonus::class, EmptyValueNotAllowedException::class],
            'Bonus with value' => [1337, Bonus::class, null],
            'Bonus with non-numeric value' => ['test', Bonus::class, ValueIsNotNumericException::class],
            'Description with empty value' => ['', Description::class, EmptyValueNotAllowedException::class],
            'Description with value' => ['value', Description::class, null],
            'Name with empty value' => ['', Name::class, EmptyValueNotAllowedException::class],
            'Name with value' => ['value', Name::class, null],
            'Price with empty value' => ['', Price::class, EmptyValueNotAllowedException::class],
            'Price with numeric value' => [1337, Price::class, null],
            'Price zero' => [0, Price::class, null],
            'Price with non-numeric value' => ['test', Price::class, ValueIsNotNumericException::class],
            'SalesFrequency with empty value' => ['', SalesFrequency::class,
                EmptyValueNotAllowedException::class],
            'SalesFrequency with value' => [1337, SalesFrequency::class, null],
            'SalesFrequency with non-numeric value' => ['test', SalesFrequency::class,
                ValueIsNotNumericException::class],
            'Sort with empty value' => ['', Sort::class, EmptyValueNotAllowedException::class],
            'Sort with value' => [1337, Sort::class, null],
            'Sort with non-numeric value' => ['test', Sort::class, ValueIsNotNumericException::class],
            'Summary with empty value' => ['', Summary::class, EmptyValueNotAllowedException::class],
            'Summary with value' => ['value', Summary::class, null],
            'Url with empty value' => ['', Url::class, EmptyValueNotAllowedException::class],
            'Url with value' => ['value', Url::class, null]
        ];
    }

    /**
     * @dataProvider simpleValueItemProvider
     * @param string $value
     * @param string $elementType
     * @param \Exception|null $expectedException
     */
    public function testAddingEmptyValuesToSimpleItemsCausesException(
        $value = '',
        $elementType = '',
        $expectedException = null
    ) {
        try {
            $element = new $elementType();
            $element->setValue($value);
            if ($expectedException !== null) {
                $this->fail('Adding empty values should cause exception!');
            } else {
                // The following assertion exists mostly to ensure that PHPUnit does not lament
                // the lack of assertions in this successful test.
                $this->assertNotNull($element);
            }
        } catch (\Exception $e) {
            $this->assertEquals($expectedException, get_class($e));
        }
    }

    /**
     * @expectedException \BadMethodCallException
     */
    public function testCallingSetValueMethodOfDateAddedClassCausesException()
    {
        $element = new DateAdded();
        $element->setValue("");
    }

    /**
     * Provides a data set for testing if adding empty keys or values to attribute and property elements fails.
     *
     * @return array Scenarios with key, one or more values, the element class and whether this input should cause
     *      an exception.
     */
    public function emptyValueProvider()
    {
        return [
            'Attribute with empty key' => ['', ['value'], Attribute::class, true],
            'Attribute with empty value' => ['key', [''], Attribute::class, true],
            'Attribute with valid key and value' => ['key', ['value'], Attribute::class, false],
            'Property with empty key' => ['',['value'], Property::class, true],
            'Property with empty value' => ['key', [''], Property::class, true],
            'Property with valid key and value' => ['key', ['value'], Property::class, false]
        ];
    }

    /**
     * @dataProvider emptyValueProvider
     * @param string $key
     * @param string $value
     * @param string $elementType
     * @param bool $shouldCauseException
     */
    public function testAddingEmptyValueCausesException(
        $key = '',
        $value = '',
        $elementType = '',
        $shouldCauseException = true
    ) {
        try {
            $element = new $elementType($key, $value);
            if ($shouldCauseException) {
                $this->fail('Adding empty values should cause exception!');
            } else {
                // The following assertion exists mostly to ensure that PHPUnit does not lament
                // the lack of assertions in this successful test.
                $this->assertNotNull($element);
            }
        } catch (\Exception $exception) {
            $this->assertEquals(EmptyValueNotAllowedException::class, get_class($exception));
        }
    }
}
