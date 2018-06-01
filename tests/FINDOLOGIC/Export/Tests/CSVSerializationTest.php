<?php

namespace FINDOLOGIC\Export\Tests;

use FINDOLOGIC\Export\CSV\CSVExporter;
use FINDOLOGIC\Export\Data\Attribute;
use FINDOLOGIC\Export\Data\Bonus;
use FINDOLOGIC\Export\Data\DateAdded;
use FINDOLOGIC\Export\Data\Description;
use FINDOLOGIC\Export\Data\Image;
use FINDOLOGIC\Export\Data\Keyword;
use FINDOLOGIC\Export\Data\Name;
use FINDOLOGIC\Export\Data\Ordernumber;
use FINDOLOGIC\Export\Data\Price;
use FINDOLOGIC\Export\Data\Property;
use FINDOLOGIC\Export\Data\SalesFrequency;
use FINDOLOGIC\Export\Data\Sort;
use FINDOLOGIC\Export\Data\Summary;
use FINDOLOGIC\Export\Data\Url;
use FINDOLOGIC\Export\Data\Usergroup;
use FINDOLOGIC\Export\Exporter;
use FINDOLOGIC\Export\Helpers\BadPropertyKeyException;
use PHPUnit\Framework\TestCase;

class CSVSerializationTest extends TestCase
{
    const DEFAULT_CSV_HEADING = "id\tordernumber\tname\tsummary\tdescription\tprice\tinstead\tmaxprice\ttaxrate\t" .
        "url\timage\tattributes\tkeywords\tgroups\tbonus\tsales_frequency\tdate_added\tsort";

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

    private function getMinimalItem($exporter = null)
    {
        if ($exporter === null) {
            $exporter = $this->exporter;
        }

        $item = $exporter->createItem('123');

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

    public function testKitchenSink()
    {
        $expectedId = '123';
        $expectedOrdernumbers = ['987654321', 'BAC-123'];
        $expectedName = 'Velobike';
        $expectedSummary = 'This is a brief exposition of the product.';
        $expectedDescription =
            'Here I keep on rambling in length about how the product will enhance your existence as a consumer.';
        $expectedPrice = 11.99;
        $expectedInsteadPrice = 10.11;
        $expectedMaxPrice = 20.00;
        $expectedTaxRate = 20;
        $expectedUrl = 'https://example.org/wonderful_product.html';
        $expectedImage = 'https://example.org/wonderful_product.png';
        $expectedAttributes = [
            'cat' => ['Bikes', 'Bikes_Racing Bikes'],
            'vendor' => ['Ultrabikes'],
            'use' => ['Outdoor', 'Race', "&=<>=%"]
        ];
        $expectedKeywords = ['bike', 'race', 'velobike', 'ultrabikes'];
        $expectedGroups = [1, 2, 3];
        $expectedBonus = 3;
        $expectedSalesFrequency = 123;
        $expectedDateAdded = new \DateTime();
        $expectedSort = 0;
        $expectedPropertyKeys = ['availability', 'sale'];
        $expectedProperties = [
            $expectedPropertyKeys[0] => 'Ready to ship',
            $expectedPropertyKeys[1] => 'true'
        ];

        $expectedAttributeArray = [];
        foreach ($expectedAttributes as $attribute => $values) {
            $expectedAttributeArray []= implode('&', array_map(function ($value) use ($attribute) {
                return sprintf('%s=%s', $attribute, urlencode($value));
            }, $values));
        }
        $expectedAttributeString = implode('&', $expectedAttributeArray);

        $exporter = Exporter::create(Exporter::TYPE_CSV, 20, $expectedPropertyKeys);

        $expectedCsvLine = sprintf(
            "%s\t%s\t%s\t%s\t%s\t%.2f\t%.2f\t%.2f\t%.2f\t%s\t%s\t%s\t%s\t%s\t%d\t%d\t%s\t%d\t%s\n",
            $expectedId,
            implode('|', $expectedOrdernumbers),
            $expectedName,
            $expectedSummary,
            $expectedDescription,
            $expectedPrice,
            $expectedInsteadPrice,
            $expectedMaxPrice,
            $expectedTaxRate,
            $expectedUrl,
            $expectedImage,
            $expectedAttributeString,
            implode(',', $expectedKeywords),
            implode(',', $expectedGroups),
            $expectedBonus,
            $expectedSalesFrequency,
            $expectedDateAdded->format('U'),
            $expectedSort,
            implode("\t", array_values($expectedProperties))
        );

        $item = $exporter->createItem($expectedId);

        foreach ($expectedOrdernumbers as $ordernumber) {
            $item->addOrdernumber(new Ordernumber($ordernumber));
        }

        $item->addName($expectedName);
        $item->addSummary($expectedSummary);
        $item->addDescription($expectedDescription);
        $item->addPrice($expectedPrice);
        $item->setInsteadPrice($expectedInsteadPrice);
        $item->setMaxPrice($expectedMaxPrice);
        $item->setTaxRate($expectedTaxRate);
        $item->addUrl($expectedUrl);
        $item->addImage(new Image($expectedImage));

        foreach ($expectedAttributes as $attribute => $values) {
            $item->addAttribute(new Attribute($attribute, $values));
        }

        foreach ($expectedKeywords as $keyword) {
            $item->addKeyword(new Keyword($keyword));
        }

        foreach ($expectedGroups as $group) {
            $item->addUsergroup(new Usergroup($group));
        }

        $item->addBonus($expectedBonus);
        $item->addSalesFrequency($expectedSalesFrequency);
        $item->addDateAdded($expectedDateAdded);
        $item->addSort($expectedSort);

        foreach ($expectedProperties as $key => $value) {
            $item->addProperty(new Property($key, ['' => $value]));
        }

        $item->addName($expectedName);

        $this->assertEquals($expectedCsvLine, $item->getCsvFragment($expectedPropertyKeys));
    }

    public function testItemsCanHaveVaryingProperties()
    {
        $firstPropertyName = 'only first item';
        $secondPropertyName = 'all items';
        $thirdPropertyName = 'second and third item';

        $exporter = Exporter::create(
            Exporter::TYPE_CSV,
            20,
            [
                $firstPropertyName, $secondPropertyName, $thirdPropertyName]
        );

        $firstItem = $this->getMinimalItem($exporter);
        $firstItem->addProperty(new Property($firstPropertyName, [null => 'first value']));
        $firstItem->addProperty(new Property($secondPropertyName, [null => 'second value']));

        $secondItem = $this->getMinimalItem($exporter);
        $secondItem->addProperty(new Property($secondPropertyName, [null => 'second value']));
        $secondItem->addProperty(new Property($thirdPropertyName, [null => 'third value']));

        $thirdItem = $this->getMinimalItem($exporter);
        $thirdItem->addProperty(new Property($thirdPropertyName, [null => 'third value']));
        $thirdItem->addProperty(new Property($secondPropertyName, [null => 'second value']));

        $items = [$firstItem, $secondItem, $thirdItem];
        $csv = $exporter->serializeItems($items, 0, count($items), count($items));
        $lines = explode("\n", $csv);

        $expectedCsvHeading = sprintf(
            "%s\t%s\t%s\t%s",
            self::DEFAULT_CSV_HEADING,
            $firstPropertyName,
            $secondPropertyName,
            $thirdPropertyName
        );
        $this->assertEquals($expectedCsvHeading, $lines[0]);
    }

    public function illegalPropertyProvider()
    {
        return [
            'tab' => [new Property("This\tcontains\ttabs", [null => 'some value'])],
            'line feed' => [new Property("This\ncontains\nline\nfeeds", [null => 'some value'])]
        ];
    }

    /**
     * @expectedException \FINDOLOGIC\Export\Helpers\BadPropertyKeyException
     * @dataProvider illegalPropertyProvider
     *
     * @param Property $property The property with an illegal key to test.
     */
    public function testFormatBreakingCharactersAreNotAllowedInPropertyKeys(Property $property)
    {
        $exporter = Exporter::create(
            Exporter::TYPE_CSV,
            20,
            [$property->getKey()]
        );

        $item = $this->getMinimalItem($exporter);
        $item->addProperty($property);

        $exporter->serializeItems([$item], 0, 1, 1);
    }
}
