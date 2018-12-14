<?php

namespace FINDOLOGIC\Export\Tests;

use FINDOLOGIC\Export\Data\Attribute;
use FINDOLOGIC\Export\Data\Price;
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
    public function setUp()
    {
        $this->exporter = Exporter::create(Exporter::TYPE_XML);
    }

    private function getMinimalItem()
    {
        $item = $this->exporter->createItem('123');

        $price = new Price();
        $price->setValue('13.37');
        $item->setPrice($price);

        return $item;
    }

    /**
     * @expectedException \FINDOLOGIC\Export\Data\EmptyElementsNotAllowedException
     */
    public function testAddingEmptyAttributesCauseException()
    {
        $item = $this->getMinimalItem();
        $attribute = new Attribute('empty attribute', []);
        $item->addAttribute($attribute);
    }
}
