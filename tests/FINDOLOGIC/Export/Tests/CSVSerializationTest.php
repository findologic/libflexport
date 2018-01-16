<?php

namespace FINDOLOGIC\Export\Tests;

use FINDOLOGIC\Export\CSV\CSVExporter;
use FINDOLOGIC\Export\Data\Bonus;
use FINDOLOGIC\Export\Data\DateAdded;
use FINDOLOGIC\Export\Data\Description;
use FINDOLOGIC\Export\Data\Name;
use FINDOLOGIC\Export\Data\Price;
use FINDOLOGIC\Export\Data\SalesFrequency;
use FINDOLOGIC\Export\Data\Sort;
use FINDOLOGIC\Export\Data\Summary;
use FINDOLOGIC\Export\Data\Url;
use FINDOLOGIC\Export\Exporter;
use PHPUnit\Framework\TestCase;

class CSVSerializationTest extends TestCase
{
    /** @var CSVExporter */
    private $exporter;

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function setUp()
    {
        $this->exporter = Exporter::create(Exporter::TYPE_CSV);
    }

    public function tearDown()
    {
        try {
            unlink('/tmp/findologic.csv');
        } catch (\Exception $e) {
            // No need to delete a written file if the test didn't write it.
        }
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
        $export = $this->exporter->serializeItems([$item]);

        $this->assertInternalType('string', $export);
    }

    public function testCsvCanBeWrittenDirectlyToFile()
    {
        $item = $this->getMinimalItem();

        $expectedCsvContent = $this->exporter->serializeItems([$item], 0, 1, 1);
        $this->exporter->serializeItemsToFile('/tmp', [$item], 0, 1, 1);

        self::assertEquals($expectedCsvContent, file_get_contents('/tmp/findologic.csv'));
    }
}
