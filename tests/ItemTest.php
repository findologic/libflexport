<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\Tests;

use FINDOLOGIC\Export\Data\Attribute;
use FINDOLOGIC\Export\Data\Item;
use FINDOLOGIC\Export\Data\Price;
use FINDOLOGIC\Export\Data\Property;
use FINDOLOGIC\Export\Data\Variant;
use FINDOLOGIC\Export\Enums\ExporterType;
use FINDOLOGIC\Export\Exceptions\EmptyElementsNotAllowedException;
use FINDOLOGIC\Export\Exporter;
use FINDOLOGIC\Export\Traits\HasAttributes;
use FINDOLOGIC\Export\Traits\HasBonus;
use FINDOLOGIC\Export\Traits\HasDateAdded;
use FINDOLOGIC\Export\Traits\HasDescription;
use FINDOLOGIC\Export\Traits\HasGroups;
use FINDOLOGIC\Export\Traits\HasId;
use FINDOLOGIC\Export\Traits\HasImages;
use FINDOLOGIC\Export\Traits\HasKeywords;
use FINDOLOGIC\Export\Traits\HasName;
use FINDOLOGIC\Export\Traits\HasOrdernumbers;
use FINDOLOGIC\Export\Traits\HasOverriddenPrice;
use FINDOLOGIC\Export\Traits\HasParentId;
use FINDOLOGIC\Export\Traits\HasPrice;
use FINDOLOGIC\Export\Traits\HasProperties;
use FINDOLOGIC\Export\Traits\HasSalesFrequency;
use FINDOLOGIC\Export\Traits\HasSort;
use FINDOLOGIC\Export\Traits\HasSummary;
use FINDOLOGIC\Export\Traits\HasUrl;
use FINDOLOGIC\Export\Traits\HasVariants;
use FINDOLOGIC\Export\Traits\HasVisibility;
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

    public function testSupportedDataForItems(): void
    {
        $expectedTraits = [
            HasAttributes::class,
            HasBonus::class,
            HasDateAdded::class,
            HasDescription::class,
            HasGroups::class,
            HasId::class,
            HasImages::class,
            HasKeywords::class,
            HasName::class,
            HasOrdernumbers::class,
            HasOverriddenPrice::class,
            HasPrice::class,
            HasProperties::class,
            HasSalesFrequency::class,
            HasSort::class,
            HasSummary::class,
            HasUrl::class,
            HasVariants::class,
            HasVisibility::class,
        ];

        $class = new \ReflectionClass(Item::class);
        $actualTraits = $class->getTraitNames();

        $this->assertEquals($expectedTraits, $actualTraits);
    }

    public function testSupportedDataForVariants(): void
    {
        $expectedTraits = [
            HasAttributes::class,
            HasGroups::class,
            HasId::class,
            HasImages::class,
            HasName::class,
            HasOrdernumbers::class,
            HasOverriddenPrice::class,
            HasParentId::class,
            HasPrice::class,
            HasProperties::class,
            HasUrl::class,
        ];

        $class = new \ReflectionClass(Variant::class);
        $actualTraits = $class->getTraitNames();

        $this->assertEquals($expectedTraits, $actualTraits);
    }
}
