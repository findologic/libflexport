<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\Tests;

use FINDOLOGIC\Export\Data\Attribute;
use FINDOLOGIC\Export\Data\Item;
use FINDOLOGIC\Export\Data\Price;
use FINDOLOGIC\Export\Data\Property;
use FINDOLOGIC\Export\Enums\ExporterType;
use FINDOLOGIC\Export\Exceptions\EmptyElementsNotAllowedException;
use FINDOLOGIC\Export\Exporter;
use FINDOLOGIC\Export\XML\XMLExporter;

final class ItemTest extends TestCase
{
    /** @var XMLExporter */
    private Exporter $exporter;

    protected function setUp(): void
    {
        $this->exporter = Exporter::create(ExporterType::XML);
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
        $this->expectException(EmptyElementsNotAllowedException::class);
        $this->expectExceptionMessage(
            'Elements with empty values are not allowed. "Attribute" with the name "empty attribute"'
        );

        $item = $this->getMinimalItem();
        $attribute = new Attribute('empty attribute', []);
        $item->addAttribute($attribute);
    }

    public function testMergingEmptyAttributesCauseException(): void
    {
        $this->expectException(EmptyElementsNotAllowedException::class);
        $this->expectExceptionMessage(
            'Elements with empty values are not allowed. "Attribute" with the name "empty attribute"'
        );

        $item = $this->getMinimalItem();
        $attribute = new Attribute('empty attribute', []);
        $item->addMergedAttribute($attribute);
    }

    public function testAddingEmptyPropertiesCauseException(): void
    {
        $this->expectException(EmptyElementsNotAllowedException::class);
        $this->expectExceptionMessage(
            'Elements with empty values are not allowed. "Property" with the name "empty property"'
        );

        $item = $this->getMinimalItem();
        $property = new Property('empty property', []);
        $item->addProperty($property);
    }
}
