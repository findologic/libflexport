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

    /**
     * @expectedException \FINDOLOGIC\Export\Data\PropertyKeyNotAllowedException
     */
    public function testReservedPropertyKeyOrdernumberCausesException()
    {
        $property = new Property('ordernumber');
    }

    /**
     * @expectedException \FINDOLOGIC\Export\Data\PropertyKeyNotAllowedException
     */
    public function testReservedPropertyKeyThumbnailCausesException()
    {
        $property = new Property('thumbnail1');
    }

    /**
     * @expectedException \FINDOLOGIC\Export\Data\PropertyKeyNotAllowedException
     */
    public function testReservedPropertyKeyImageCausesException()
    {
        $property = new Property('image1');
    }
}