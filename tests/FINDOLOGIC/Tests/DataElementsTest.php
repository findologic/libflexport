<?php

namespace FINDOLOGIC\Tests;

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
        return array(
            'Keyword with empty value' => array('', Keyword::class, true),
            'Keyword with value' => array('value', Keyword::class, false),
            'Ordernumber with empty value' => array('', Ordernumber::class, true),
            'Ordernumber with value' => array('value', Ordernumber::class, false)
        );
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
        return array(
            'Bonus with empty value' => array('', Bonus::class, true),
            'Bonus with value' => array('value', Bonus::class, false),
            'Description with empty value' => array('', Description::class, true),
            'Description with value' => array('value', Description::class, false),
            'Name with empty value' => array('', Name::class, true),
            'Name with value' => array('value', Name::class, false),
            'Price with empty value' => array('', Price::class, true),
            'Price with value' => array('value', Price::class, false),
            'Price zero' => array(0, Price::class, false),
            'SalesFrequency with empty value' => array('', SalesFrequency::class, true),
            'SalesFrequency with value' => array('value', SalesFrequency::class, false),
            'Sort with empty value' => array('', Sort::class, true),
            'Sort with value' => array('value', Sort::class, false),
            'Summary with empty value' => array('', Summary::class, true),
            'Summary with value' => array('value', Summary::class, false),
            'Url with empty value' => array('', Url::class, true),
            'Url with value' => array('value', Url::class, false)
        );
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
        return array(
            'Attribute with empty key' => array('', array('value'), Attribute::class, true),
            'Attribute with empty value' => array('key', array(''), Attribute::class, true),
            'Attribute with valid key and value' => array('key', array('value'), Attribute::class, false),
            'Property with empty key' => array('', array('value'), Property::class, true),
            'Property with empty value' => array('key', array(''), Property::class, true),
            'Property with valid key and value' => array('key', array('value'), Property::class, false)
        );
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
