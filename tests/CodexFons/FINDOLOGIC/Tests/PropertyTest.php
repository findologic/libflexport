<?php

namespace CodexFons\FINDOLOGIC\Tests;


use CodexFons\FINDOLOGIC\Export\Data\Property;
use PHPUnit\Framework\TestCase;

class PropertyTest extends TestCase
{
    /**
     * @expectedException \CodexFons\FINDOLOGIC\Export\Data\DuplicateValueForUsergroupException
     */
    public function testAddingMultipleValuesPerUsergroupCausesException()
    {
        $property = new Property('prop');
        $property->addValue('foobar', 'usergroup');
        $property->addValue('foobar', 'usergroup');
    }

    /**
     * @expectedException \CodexFons\FINDOLOGIC\Export\Data\DuplicateValueForUsergroupException
     */
    public function testAddingMultipleValuesWithoutUsergroupCausesException()
    {
        $property = new Property('prop');
        $property->addValue('foobar');
        $property->addValue('foobar');
    }
}