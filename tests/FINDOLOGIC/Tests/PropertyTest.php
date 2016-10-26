<?php

namespace FINDOLOGIC\Tests;


use FINDOLOGIC\XmlExport\Elements\Property;
use PHPUnit\Framework\TestCase;

class PropertyTest extends TestCase
{
    /**
     * @expectedException \FINDOLOGIC\XmlExport\Elements\DuplicateValueForUsergroupException
     */
    public function testAddingMultipleValuesPerUsergroupCausesException()
    {
        $property = new Property('prop');
        $property->addValue('foobar', 'usergroup');
        $property->addValue('foobar', 'usergroup');
    }

    /**
     * @expectedException \FINDOLOGIC\XmlExport\Elements\DuplicateValueForUsergroupException
     */
    public function testAddingMultipleValuesWithoutUsergroupCausesException()
    {
        $property = new Property('prop');
        $property->addValue('foobar');
        $property->addValue('foobar');
    }
}