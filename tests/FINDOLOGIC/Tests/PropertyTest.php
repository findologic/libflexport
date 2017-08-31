<?php

namespace FINDOLOGIC\Tests;

use FINDOLOGIC\Export\Data\Property;
use PHPUnit\Framework\TestCase;

class PropertyTest extends TestCase
{
    /**
     * @expectedException \FINDOLOGIC\Export\Data\DuplicateValueForUsergroupException
     */
    public function testAddingMultipleValuesPerUsergroupCausesException()
    {
        $property = new Property('prop');
        $property->addValue('foobar', 'usergroup');
        $property->addValue('foobar', 'usergroup');
    }

    /**
     * @expectedException \FINDOLOGIC\Export\Data\DuplicateValueForUsergroupException
     */
    public function testAddingMultipleValuesWithoutUsergroupCausesException()
    {
        $property = new Property('prop');
        $property->addValue('foobar');
        $property->addValue('foobar');
    }

    public function propertyKeyProvider()
    {
        return array(
            'reserved property "image\d+"' => array('image0', true),
            'reserved property "thumbnail\d+"' => array('thumbnail1', true),
            'reserved property "ordernumber"' => array('ordernumber', true),
            'non-reserved property key' => array('foobar', false)
        );
    }

    /**
     * @dataProvider propertyKeyProvider
     */
    public function testReservedPropertyKeysCausesException($key, $shouldCauseException)
    {
        try {
            new Property($key);
            if ($shouldCauseException) {
                $this->fail('Using a reserved property key should cause an exception.');
            }
        } catch (\Exception $exception) {
            $this->assertRegExp('/' . $key . '/', $exception->getMessage());
        }
    }
}
