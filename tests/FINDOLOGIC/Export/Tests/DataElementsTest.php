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
            new $elementType($value);
            if ($shouldCauseException) {
                $this->fail('Adding empty values should cause exception!');
            }
        } catch (\Exception $exception) {
            $this->assertEquals(EmptyValueNotAllowedException::class, get_class($exception));
        }
    }

    /**
     * Provides a data set for testing if adding empty values to elements of type UsergroupAwareSimpleValue fails.
     *
     * @return array Scenarios with a value, the element class and whether this input should cause an exception.
     */
    public function simpleValueItemProvider()
    {
        return [
            'Bonus with empty value' => ['', Bonus::class, true],
            'Bonus with value' => ['value', Bonus::class, false],
            'Description with empty value' => ['', Description::class, true],
            'Description with value' => ['value', Description::class, false],
            'Name with empty value' => ['', Name::class, true],
            'Name with value' => ['value', Name::class, false],
            'Price with empty value' => ['', Price::class, true],
            'Price with value' => ['value', Price::class, false],
            'Price zero' => [0, Price::class, false],
            'SalesFrequency with empty value' => ['', SalesFrequency::class, true],
            'SalesFrequency with value' => ['value', SalesFrequency::class, false],
            'Sort with empty value' => ['', Sort::class, true],
            'Sort with value' => ['value', Sort::class, false],
            'Summary with empty value' => ['', Summary::class, true],
            'Summary with value' => ['value', Summary::class, false],
            'Url with empty value' => ['', Url::class, true],
            'Url with value' => ['value', Url::class, false]
        ];
    }

    /**
     * @dataProvider simpleValueItemProvider
     * @param string $value
     * @param string $elementType
     * @param bool $shouldCauseException
     */
    public function testAddingEmptyValuesToSimpleItemsCausesException(
        $value = '',
        $elementType = '',
        $shouldCauseException = true
    ) {
        try {
            $element = new $elementType();
            $element->setValue($value);
            if ($shouldCauseException) {
                $this->fail('Adding empty values should cause exception!');
            }
        } catch (\Exception $exception) {
            $this->assertEquals(EmptyValueNotAllowedException::class, get_class($exception));
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
            }
        } catch (\Exception $exception) {
            $this->assertEquals(EmptyValueNotAllowedException::class, get_class($exception));
        }
    }
}
