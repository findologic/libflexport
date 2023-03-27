<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\Tests;

use BadMethodCallException;
use DateTime;
use DOMDocument;
use FINDOLOGIC\Export\CSV\CSVConfig;
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
use FINDOLOGIC\Export\Data\OverriddenPrice;
use FINDOLOGIC\Export\Data\Price;
use FINDOLOGIC\Export\Data\Property;
use FINDOLOGIC\Export\Data\SalesFrequency;
use FINDOLOGIC\Export\Data\Sort;
use FINDOLOGIC\Export\Data\Summary;
use FINDOLOGIC\Export\Data\Url;
use FINDOLOGIC\Export\Data\Group;
use FINDOLOGIC\Export\Data\Variant;
use FINDOLOGIC\Export\Enums\ExporterType;
use FINDOLOGIC\Export\Enums\ImageType;
use FINDOLOGIC\Export\Exceptions\BadPropertyKeyException;
use FINDOLOGIC\Export\Exporter;
use FINDOLOGIC\Export\Helpers\UsergroupAwareMultiValueItem;
use FINDOLOGIC\Export\Helpers\UsergroupAwareSimpleValue;

final class CSVSerializationTest extends TestCase
{
    /**
     * @var string
     */
    private const DEFAULT_CSV_HEADING = "id\tparent_id\tordernumber\tname\tsummary\tdescription\tprice\t" .
        "overriddenPrice\turl\tkeywords\tgroups\tbonus\tsales_frequency\tdate_added\tsort\tvisibility";

    /**
     * @var string
     */
    private const CSV_PATH = '/tmp/findologic.csv';

    /** @var CSVExporter */
    private Exporter $exporter;

    protected function setUp(): void
    {
        $this->exporter = Exporter::create(ExporterType::CSV);
    }

    protected function tearDown(): void
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

        $overriddenPrice = new OverriddenPrice();
        $overriddenPrice->setValue('16.67');
        $item->setOverriddenPrice($overriddenPrice);

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

    private function getMinimalVariant(string $parentId): Variant
    {
        $variant = $this->exporter->createVariant('123-V', $parentId);

        $name = new Name();
        $name->setValue('Foobar &quot;</>]]>');
        $variant->setName($name);

        $price = new Price();
        $price->setValue('13.37');
        $variant->setPrice($price);

        return $variant;
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
        $expectedThumbnail = 'https://example.org/wonderful_product_thumb.png';
        $expectedAttributeKeys = ['cat', 'vendor', 'use', 'not_set'];
        $expectedAttributes = [
            $expectedAttributeKeys[0] => ['Bikes', 'Bikes_Racing Bikes'],
            $expectedAttributeKeys[1] => ['Ultrabikes, Megabikes'],
            $expectedAttributeKeys[2] => ['Outdoor', 'Race', "&=<>=%"]
        ];
        $expectedAttributesColumns = [
            implode(',', ['Bikes', 'Bikes_Racing Bikes']),
            implode(',', ['Ultrabikes\, Megabikes']),
            implode(',', ['Outdoor', 'Race', "&==%"]),
            ''
        ];
        $expectedKeywords = ['bike', 'race', 'velobike', 'ultrabikes'];
        $expectedGroups = [1, 2, 3];
        $expectedBonus = 3;
        $expectedSalesFrequency = 123;
        $expectedDateAdded = new DateTime();
        $expectedSort = 0;
        $expectedVisibility = 1;
        $expectedPropertyKeys = ['availability', 'sale'];
        $expectedProperties = [
            $expectedPropertyKeys[0] => 'Ready to ship',
            $expectedPropertyKeys[1] => 'true'
        ];

        $csvConfig = new CSVConfig($expectedPropertyKeys, $expectedAttributeKeys, 4);
        $exporter = Exporter::create(ExporterType::CSV, 20, $csvConfig);

        $expectedCsvLine = sprintf(
            "%s\t%s\t%s\t%s\t%s\t%s\t%.2f\t%.2f\t%s\t%s\t%s\t%s\t%d\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\t%s\n",
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
            $expectedVisibility,
            $expectedImage0,
            $expectedImage1,
            $expectedThumbnail,
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
        $item->addImage(new Image($expectedThumbnail, ImageType::THUMBNAIL));

        foreach ($expectedAttributes as $attribute => $values) {
            $item->addAttribute(new Attribute($attribute, $values));
        }

        foreach ($expectedKeywords as $expectedKeyword) {
            $item->addKeyword(new Keyword($expectedKeyword));
        }

        foreach ($expectedGroups as $expectedGroup) {
            $item->addGroup(new Group($expectedGroup));
        }

        $item->addBonus($expectedBonus);
        $item->addSalesFrequency($expectedSalesFrequency);
        $item->addDateAdded($expectedDateAdded);
        $item->addSort($expectedSort);
        $item->addVisibility($expectedVisibility);

        foreach ($expectedProperties as $key => $value) {
            $item->addProperty(new Property($key, ['' => $value]));
        }

        $item->addName($expectedName);

        $this->assertEquals($expectedCsvLine, $item->getCsvFragment($csvConfig));
    }

    public function testItemsCanHaveVaryingProperties(): void
    {
        $firstPropertyName = 'only first item';
        $secondPropertyName = 'all items';
        $thirdPropertyName = 'second and third item';

        $csvConfig = new CSVConfig([$firstPropertyName, $secondPropertyName, $thirdPropertyName], [], 0, 0);
        $exporter = Exporter::create(ExporterType::CSV, 20, $csvConfig);

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
            ExporterType::CSV,
            20,
            new CSVConfig([$property->getKey()]),
        );

        $item = $this->getMinimalItem($exporter);
        $item->addProperty($property);

        $exporter->serializeItems([$item], 0, 1, 1);
    }

    public function testAttemptingToGetXmlVersionForACSVItemCausesAnException(): void
    {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('CSVItem does not implement XML export.');

        $this->getMinimalItem()->getDomSubtree(new DOMDocument());
    }

    public function testAttemptingToGetXmlVersionForACSVVariantCausesAnException(): void
    {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('CSVVariant does not implement XML export.');

        $this->getMinimalVariant('123')->getDomSubtree(new DOMDocument());
    }

    public function testAddingRelativeUrlIsNotCausingAnException(): void
    {
        $imageWithRelativePath = '/media/images/image.jpg';
        $image = new Image($imageWithRelativePath);

        $this->assertEquals($imageWithRelativePath, $image->getCsvFragment(new CSVConfig()));
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
     */
    public function testSanitizingOfElementsWorks(string $value, string $elementType, string $setterMethodName): void
    {
        $item = $this->getMinimalItem();

        if (get_parent_class($elementType) === UsergroupAwareMultiValueItem::class) {
            $element = new $elementType($value);
        } else {
            /** @var UsergroupAwareSimpleValue $element */
            $element = new $elementType();
            $element->setValue($value);
        }
        $item->$setterMethodName($element);

        $csvLine = $item->getCsvFragment(new CSVConfig());

        $this->assertEquals(1, preg_match_all('/\n/', $csvLine));
        $this->assertEquals(16, preg_match_all('/\t/', $csvLine));
        $this->assertEquals(0, preg_match_all('/\r/', $csvLine));
    }

    public function testVariantsAreAdded(): void
    {
        $item = $this->getMinimalItem();

        $item->addVariant($this->getMinimalVariant($item->getId()));

        $csvData = $item->getCsvFragment(new CSVConfig());

        $this->assertEquals(2, preg_match_all('/\n/', $csvData));
        $this->assertEquals(32, preg_match_all('/\t/', $csvData));
        $this->assertEquals(0, preg_match_all('/\r/', $csvData));
    }

    public function testMainProductIncludesVariantOrdernumbers(): void
    {
        $item = $this->getMinimalItem();
        $item->addOrdernumber(
            new Ordernumber('number1')
        );

        $variant = $this->getMinimalVariant($item->getId());
        $variant->addOrdernumber(
            new Ordernumber('variant1')
        );

        $item->addVariant($variant);

        $csvData = $item->getCsvFragment(new CSVConfig());
        $mainProduct = explode("\n", $csvData)[0];

        $this->assertStringContainsString('number1|variant1', $mainProduct);
    }
}
