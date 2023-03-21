<?php

namespace FINDOLOGIC\Export\Tests;

use BadMethodCallException;
use DateTime;
use DOMDocument;
use FINDOLOGIC\Export\CSV\CSVExporter;
use FINDOLOGIC\Export\Data\Attribute;
use FINDOLOGIC\Export\Data\Bonus;
use FINDOLOGIC\Export\Data\DateAdded;
use FINDOLOGIC\Export\Data\Description;
use FINDOLOGIC\Export\Data\Image;
use FINDOLOGIC\Export\Data\Item;
use FINDOLOGIC\Export\Data\Keyword;
use FINDOLOGIC\Export\Data\Name;
use FINDOLOGIC\Export\Data\Ordernumber;
use FINDOLOGIC\Export\Data\Price;
use FINDOLOGIC\Export\Data\Property;
use FINDOLOGIC\Export\Data\SalesFrequency;
use FINDOLOGIC\Export\Data\Sort;
use FINDOLOGIC\Export\Data\Summary;
use FINDOLOGIC\Export\Data\Url;
use FINDOLOGIC\Export\Data\Group;
use FINDOLOGIC\Export\Exceptions\BadPropertyKeyException;
use FINDOLOGIC\Export\Exporter;
use FINDOLOGIC\Export\Helpers\UsergroupAwareMultiValueItem;
use FINDOLOGIC\Export\Helpers\UsergroupAwareSimpleValue;
use InvalidArgumentException;

class CSVSerializationTest extends TestCase
{
    private const DEFAULT_CSV_HEADING = "id\tparent_id\tordernumber\tname\tsummary\tdescription\tprice\t" .
        "overriddenPrice\turl\tkeywords\tgroups\tbonus\tsales_frequency\tdate_added\tsort";

    private const CSV_PATH = '/tmp/findologic.csv';

    /** @var CSVExporter */
    private $exporter;

    /**
     * @SuppressWarnings(PHPMD.StaticAccess)
     */
    public function setUp(): void
    {
        $this->exporter = Exporter::create(Exporter::TYPE_CSV);
    }

    public function tearDown(): void
    {
        if (file_exists(self::CSV_PATH)) {
            // Cleanup file after tests have created it.
            unlink(self::CSV_PATH);
        }
    }

    private function getMinimalItem($exporter = null): Item
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
        $dateAdded->setDateValue(new DateTime());
        $item->setDateAdded($dateAdded);

        $sort = new Sort();
        $sort->setValue(2);
        $item->setSort($sort);

        return $item;
    }

    public function testMinimalItemIsExported(): void
    {
        $item = $this->getMinimalItem();
        $export = $this->exporter->serializeItems([$item]);

        $this->assertIsString($export);
    }

    public function testHeadingIsOnlyWrittenForFirstPage(): void
    {
        $item = $this->getMinimalItem();
        $export = $this->exporter->serializeItems([$item], 0, 1, 2);

        $this->assertStringContainsString(self::DEFAULT_CSV_HEADING, $export);

        $item = $this->getMinimalItem();
        $export = $this->exporter->serializeItems([$item], 1, 1, 2);

        $this->assertStringNotContainsString(self::DEFAULT_CSV_HEADING, $export);
    }

    public function testCsvCanBeWrittenDirectlyToFile(): void
    {
        $item = $this->getMinimalItem();
        $expectedCsvContent = $this->exporter->serializeItems([$item], 0, 1, 1);

        $this->exporter->serializeItemsToFile('/tmp', [$item], 0, 1, 1);

        $this->assertEquals($expectedCsvContent, file_get_contents(self::CSV_PATH));
        $this->assertCount(2, file(self::CSV_PATH));
    }

    public function testCsvWillNotOverrideItselfWhenHavingMultipleSteps(): void
    {
        $item = $this->getMinimalItem();
        $expectedCsvContent = $this->exporter->serializeItems([$item, $item], 0, 2, 2);

        $this->exporter->serializeItemsToFile('/tmp', [$item], 0, 1, 2);
        $this->exporter->serializeItemsToFile('/tmp', [$item], 1, 1, 2);

        $this->assertEquals($expectedCsvContent, file_get_contents(self::CSV_PATH));
        $this->assertCount(3, file(self::CSV_PATH));
    }

    public function testCsvWillNotOverrideItselfWhenPassingACountHigherThenZero(): void
    {
        $item = $this->getMinimalItem();
        $expectedInitialData = 'This is some pretty nice data.';
        $expectedCsvContent = $this->exporter->serializeItems([$item], 1, 1, 1);

        file_put_contents(self::CSV_PATH, $expectedInitialData);
        $this->exporter->serializeItemsToFile('/tmp', [$item], 1, 1, 1);

        $actualContents = file_get_contents(self::CSV_PATH);
        $this->assertStringStartsWith($expectedInitialData, $actualContents);
        $this->assertStringEndsWith($expectedCsvContent, $actualContents);
    }

    public function testCsvWillOverrideItselfWhenPassingAnInitialCount(): void
    {
        $item = $this->getMinimalItem();
        $expectedCsvContent = $this->exporter->serializeItems([$item], 0, 1, 1);

        file_put_contents(self::CSV_PATH, 'This is some pretty nice data.');
        $this->exporter->serializeItemsToFile('/tmp', [$item], 0, 1, 1);

        $this->assertEquals($expectedCsvContent, file_get_contents(self::CSV_PATH));
        $this->assertCount(2, file(self::CSV_PATH));
    }

    public function testCsvFileNamePrefixCanBeAltered(): void
    {
        $fileNamePrefix = 'findologic.new';
        $expectedOutputPath = '/tmp/findologic.new.csv';

        $item = $this->getMinimalItem();
        $expectedCsvContent = $this->exporter->serializeItems([$item], 0, 1, 1);

        $this->exporter->setFileNamePrefix($fileNamePrefix);
        $this->exporter->serializeItemsToFile('/tmp', [$item], 0, 1, 1);

        $this->assertEquals($expectedCsvContent, file_get_contents($expectedOutputPath));
        $this->assertCount(2, file($expectedOutputPath));

        // Remove CSV after test.
        unlink($expectedOutputPath);
    }

    public function testKitchenSink(): void
    {
        $expectedId = '123';
        $expectedParentId = '';
        $expectedOrdernumbers = ['987654321', 'BAC-123'];
        $expectedName = 'Velobike';
        $expectedSummary = 'This is a brief exposition of the product.';
        $expectedDescription =
            'Here I keep on rambling in length about how the product will enhance your existence as a consumer.';
        $expectedPrice = 11.99;
        $expectedOverriddenPrice = 10.11;
        $expectedUrl = 'https://example.org/wonderful_product.html';
        $expectedImage0 = 'https://example.org/wonderful_product.png';
        $expectedImage1 = 'https://example.org/wonderful_product2.png';
        $expectedImage2 = '';
        $expectedAttributeKeys = ['cat', 'vendor', 'use'];
        $expectedAttributes = [
            $expectedAttributeKeys[0] => ['Bikes', 'Bikes_Racing Bikes'],
            $expectedAttributeKeys[1] => ['Ultrabikes, Megabikes'],
            $expectedAttributeKeys[2] => ['Outdoor', 'Race', "&=<>=%"]
        ];
        $expectedAttributesColumns = [
            implode(',', ['Bikes', 'Bikes_Racing Bikes']),
            implode(',', ['Ultrabikes\, Megabikes']),
            implode(',', ['Outdoor', 'Race', "&==%"]),
        ];
        $expectedKeywords = ['bike', 'race', 'velobike', 'ultrabikes'];
        $expectedGroups = [1, 2, 3];
        $expectedBonus = 3;
        $expectedSalesFrequency = 123;
        $expectedDateAdded = new DateTime();
        $expectedSort = 0;
        $expectedPropertyKeys = ['availability', 'sale'];
        $expectedProperties = [
            $expectedPropertyKeys[0] => 'Ready to ship',
            $expectedPropertyKeys[1] => 'true'
        ];

        $exporter = Exporter::create(Exporter::TYPE_CSV, 20, $expectedPropertyKeys, $expectedAttributeKeys);

        $expectedCsvLine = sprintf(
            "%s\t%s\t%s\t%s\t%s\t%s\t%.2f\t%.2f\t%s\t%s\t%s\t%s\t%d\t%s\t%s\t%s\t%s\t%s\t%s\t%s\n",
            $expectedId,
            $expectedParentId,
            implode('|', $expectedOrdernumbers),
            $expectedName,
            $expectedSummary,
            $expectedDescription,
            $expectedPrice,
            $expectedOverriddenPrice,
            $expectedUrl,
            implode(',', $expectedKeywords),
            implode(',', $expectedGroups),
            $expectedBonus,
            $expectedSalesFrequency,
            $expectedDateAdded->format(DATE_ATOM),
            $expectedSort,
            $expectedImage0,
            $expectedImage1,
            $expectedImage2,
            implode("\t", array_values($expectedProperties)),
            implode("\t", array_values($expectedAttributesColumns))
        );

        $item = $exporter->createItem($expectedId);

        foreach ($expectedOrdernumbers as $ordernumber) {
            $item->addOrdernumber(new Ordernumber($ordernumber));
        }

        $item->addName($expectedName);
        $item->addSummary($expectedSummary);
        $item->addDescription($expectedDescription);
        $item->addPrice($expectedPrice);
        $item->addOverriddenPrice($expectedOverriddenPrice);
        $item->addUrl($expectedUrl);
        $item->addImage(new Image($expectedImage0));
        $item->addImage(new Image($expectedImage1));

        foreach ($expectedAttributes as $attribute => $values) {
            $item->addAttribute(new Attribute($attribute, $values));
        }

        foreach ($expectedKeywords as $keyword) {
            $item->addKeyword(new Keyword($keyword));
        }

        foreach ($expectedGroups as $group) {
            $item->addGroup(new Group($group));
        }

        $item->addBonus($expectedBonus);
        $item->addSalesFrequency($expectedSalesFrequency);
        $item->addDateAdded($expectedDateAdded);
        $item->addSort($expectedSort);

        foreach ($expectedProperties as $key => $value) {
            $item->addProperty(new Property($key, ['' => $value]));
        }

        $item->addName($expectedName);

        $this->assertEquals($expectedCsvLine, $item->getCsvFragment($expectedPropertyKeys, $expectedAttributeKeys, 3));
    }

    public function testItemsCanHaveVaryingProperties(): void
    {
        $firstPropertyName = 'only first item';
        $secondPropertyName = 'all items';
        $thirdPropertyName = 'second and third item';

        $exporter = Exporter::create(
            Exporter::TYPE_CSV,
            20,
            [$firstPropertyName, $secondPropertyName, $thirdPropertyName],
            [],
            0
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
            "%s\tprop_%s\tprop_%s\tprop_%s",
            self::DEFAULT_CSV_HEADING,
            $firstPropertyName,
            $secondPropertyName,
            $thirdPropertyName
        );
        $this->assertEquals($expectedCsvHeading, $lines[0]);
    }

    public static function illegalPropertyProvider(): array
    {
        return [
            'tab' => [new Property("This\tcontains\ttabs", [null => 'some value'])],
            'line feed' => [new Property("This\ncontains\nline\nfeeds", [null => 'some value'])]
        ];
    }

    /**
     * @dataProvider illegalPropertyProvider
     *
     * @param Property $property The property with an illegal key to test.
     */
    public function testFormatBreakingCharactersAreNotAllowedInPropertyKeys(Property $property): void
    {
        $this->expectException(BadPropertyKeyException::class);
        $exporter = Exporter::create(
            Exporter::TYPE_CSV,
            20,
            [$property->getKey()]
        );

        $item = $this->getMinimalItem($exporter);
        $item->addProperty($property);

        $exporter->serializeItems([$item], 0, 1, 1);
    }

    public function testAttemptingToGetXmlVersionForACSVItemCausesAnException(): void
    {
        $this->expectException(BadMethodCallException::class);

        $this->getMinimalItem()->getDomSubtree(new DOMDocument());
    }

    public function testAddingRelativeUrlIsNotCausingAnException(): void
    {
        $imageWithRelativePath = '/media/images/image.jpg';
        $image = new Image($imageWithRelativePath);

        $this->assertEquals($imageWithRelativePath, $image->getCsvFragment([], [], 1));
    }

    /**
     * Provides a dataset for testing if tab and new line characters which are removed by the sanitize method
     * don't break the CSV export.
     *
     * @return array Scenarios with value, the element class and the elements setter method name.
     */
    public static function csvSanitizedElementsInputProvider(): array
    {
        return [
            'Ordernumber with invalid characters' => ["ordernumber\t\n", Ordernumber::class, 'addOrdernumber'],
            'Name with invalid characters' => ["Product title\t\n", Name::class, 'setName'],
            'Summary with invalid characters' => ["Short product summary\t\n", Summary::class, 'setSummary'],
            'Description with invalid characters' =>
                ["Long product description\t\n", Description::class, 'setDescription'],
            'Url with invalid characters' => ["https://www.example.org/url\t\n", Url::class, 'setUrl'],
            'Keyword with invalid characters' => ["ImportantKeyword\t\n", Keyword::class, 'addKeyword']
        ];
    }

    /**
     * @dataProvider csvSanitizedElementsInputProvider
     * @param string $value
     * @param string $elementType
     * @param string $setterMethodName
     */
    public function testSanitizingOfElementsWorks(string $value, string $elementType, string $setterMethodName): void
    {
        $item = $this->getMinimalItem();

        if (get_parent_class($elementType) === UsergroupAwareMultiValueItem::class) {
            $element = new $elementType($value);
            $item->$setterMethodName($element);
        } else {
            /** @var UsergroupAwareSimpleValue $element */
            $element = new $elementType();
            $element->setValue($value);
            $item->$setterMethodName($element);
        }

        $csvLine = $item->getCsvFragment([], [], 0);

        $this->assertEquals(1, preg_match_all('/\n/', $csvLine));
        $this->assertEquals(14, preg_match_all('/\t/', $csvLine));
        $this->assertEquals(0, preg_match_all('/\r/', $csvLine));
    }
}
