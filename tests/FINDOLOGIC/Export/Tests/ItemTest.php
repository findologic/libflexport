<?php

namespace FINDOLOGIC\Export\Tests;

use FINDOLOGIC\Export\Data\Attribute;
use FINDOLOGIC\Export\Data\Item;
use FINDOLOGIC\Export\Exceptions\EmptyElementsNotAllowedException;
use FINDOLOGIC\Export\Data\Price;
use FINDOLOGIC\Export\Data\Property;
use FINDOLOGIC\Export\Exporter;
use FINDOLOGIC\Export\XML\XMLExporter;
use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase
{

    /** @var XMLExporter */
    private $exporter;

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function setUp(): void
    {
        $this->exporter = Exporter::create(Exporter::TYPE_XML);
    }

    private function getMinimalItem(): Item
    {
        $item = $this->exporter->createItem('123');

        $price = new Price();
        $price->setValue('13.37');
        $item->setPrice($price);

        return $item;
    }

    public function testAddingEmptyAttributesCauseException(): void
    {
        try {
            $item = $this->getMinimalItem();
            $attribute = new Attribute('empty attribute', []);
            $item->addAttribute($attribute);
            $this->fail('Assigning attributes with empty values should cause an exception!');
        } catch (EmptyElementsNotAllowedException $e) {
            $expectedMessage = 'Elements with empty values are not allowed. "Attribute" with the name ' .
                '"empty attribute"';
            $this->assertEquals($expectedMessage, $e->getMessage());
        }
    }

    public function testAddingEmptyPropertiesCauseException(): void
    {
        try {
            $item = $this->getMinimalItem();
            $property = new Property('empty property', []);
            $item->addProperty($property);
            $this->fail('Assigning properties with empty values should cause an exception!');
        } catch (EmptyElementsNotAllowedException $e) {
            $expectedMessage = 'Elements with empty values are not allowed. "Property" with the name "empty property"';
            $this->assertEquals($expectedMessage, $e->getMessage());
        }
    }
}
