<?php

namespace FINDOLOGIC\Export\Tests;

use Exception;
use FINDOLOGIC\Export\Data\Property;
use FINDOLOGIC\Export\Exceptions\DuplicateValueForUsergroupException;

class PropertyTest extends TestCase
{
    public function testAddingMultipleValuesPerUsergroupCausesException(): void
    {
        $this->expectException(DuplicateValueForUsergroupException::class);

        $property = new Property('prop');
        $property->addValue('foobar', 'usergroup');
        $property->addValue('foobar', 'usergroup');
    }

    public function testAddingMultipleValuesWithoutUsergroupCausesException(): void
    {
        $this->expectException(DuplicateValueForUsergroupException::class);

        $property = new Property('prop');
        $property->addValue('foobar');
        $property->addValue('foobar');
    }

    /**
     * @noinspection PhpMethodMayBeStaticInspection
     *
     * @return array
     */
    public function propertyKeyProvider(): array
    {
        return [
            'reserved property "image\d+"' => ['image0', true],
            'reserved property "thumbnail\d+"' => ['thumbnail1', true],
            'reserved property "ordernumber"' => ['ordernumber', true],
            'non-reserved property key' => ['foobar', false],
            'non-reserved property key containing "ordernumber"' => ['main_ordernumber', false],
            'non-reserved property key containing "image0"' => ['main_image0', false],
            'non-reserved property key containing "thumbnail0"' => ['main_thumbnail0', false],
        ];
    }

    /**
     * @dataProvider propertyKeyProvider
     * @param string $key
     * @param bool $shouldCauseException
     */
    public function testReservedPropertyKeysCausesException(string $key, bool $shouldCauseException): void
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
        } catch (Exception $exception) {
            if (!$shouldCauseException) {
                $this->fail('Using a non-reserved property key should not cause an exception.');
            } else {
                $this->assertMatchesRegularExpression('/' . $key . '/', $exception->getMessage());
            }
        }
    }

    public function testNonAssociativePropertyValueCausesException(): void
    {
        try {
            new Property('foo', ['bar']);
        } catch (Exception $exception) {
            $warningMessage = 'Property values have to be associative, like $key => $value. The key "0" has to be a ' .
                'string, integer given.';
            $this->assertEquals($exception->getMessage(), $warningMessage);
        }
    }
}
