<?php

namespace FINDOLOGIC\Export\Tests;

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
        return [
            'reserved property "image\d+"' => ['image0', true],
            'reserved property "thumbnail\d+"' => ['thumbnail1', true],
            'reserved property "ordernumber"' => ['ordernumber', true],
            'non-reserved property key' => ['foobar', false]
        ];
    }

    /**
     * @dataProvider propertyKeyProvider
     */
    public function testReservedPropertyKeysCausesException($key, $shouldCauseException)
    {
        try {
            $property = new Property($key);
            if ($shouldCauseException) {
                $this->fail('Using a reserved property key should cause an exception.');
            } else {
                // The following assertion exists mostly to ensure that PHPUnit does not lament
                // the lack of assertions in this successful test.
                $this->assertNotNull($property);
            }
        } catch (\Exception $exception) {
            $this->assertRegExp('/' . $key . '/', $exception->getMessage());
        }
    }
}
