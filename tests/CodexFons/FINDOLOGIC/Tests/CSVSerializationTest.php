<?php

namespace CodexFons\FINDOLOGIC\Tests;


use CodexFons\FINDOLOGIC\Export\CSV\CSVExporter;
use CodexFons\FINDOLOGIC\Export\Data\Bonus;
use CodexFons\FINDOLOGIC\Export\Data\DateAdded;
use CodexFons\FINDOLOGIC\Export\Data\Description;
use CodexFons\FINDOLOGIC\Export\Data\Name;
use CodexFons\FINDOLOGIC\Export\Data\Price;
use CodexFons\FINDOLOGIC\Export\Data\SalesFrequency;
use CodexFons\FINDOLOGIC\Export\Data\Sort;
use CodexFons\FINDOLOGIC\Export\Data\Summary;
use CodexFons\FINDOLOGIC\Export\Data\Url;
use CodexFons\FINDOLOGIC\Export\Exporter;
use PHPUnit\Framework\TestCase;

class CSVSerializationTest extends TestCase
{
    /** @var CSVExporter */
    private $exporter;

    public function setUp()
    {
        $this->exporter = Exporter::create(Exporter::TYPE_CSV);
    }

    private function getMinimalItem()
    {
        $item = $this->exporter->createItem('123');

        $name = new Name();
        $name->setValue('Foobar &quot;</>]]>');
        $item->setName($name);

        $summary = new Summary();
        $summary->setValue('This is a summary. &quot;</>]]>');
        $item->setSummary($summary);

        $description = new Description();
        $description->setValue('This is a more verbose description. &quot;</>]]>');
        $item->setDescription($description);

        $price = new Price();
        $price->setValue('13.37');
        $item->setPrice($price);

        $url = new Url();
        $url->setValue('http://example.org/my-awesome-product.html');
        $item->setUrl($url);

        $bonus = new Bonus();
        $bonus->setValue(3);
        $item->setBonus($bonus);

        $salesFrequency = new SalesFrequency();
        $salesFrequency->setValue(42);
        $item->setSalesFrequency($salesFrequency);

        $dateAdded = new DateAdded();
        $dateAdded->setDateValue(new \DateTime());
        $item->setDateAdded($dateAdded);

        $sort = new Sort();
        $sort->setValue(2);
        $item->setSort($sort);

        return $item;
    }

    public function testMinimalItemIsExported()
    {
        $item = $this->getMinimalItem();
        $export = $this->exporter->serializeItems(array($item), 0, 1);

        // TODO assert
    }
}