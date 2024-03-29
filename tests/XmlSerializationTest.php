<?php

declare(strict_types=1);

namespace FINDOLOGIC\Export\Tests;

use BadMethodCallException;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DOMDocument;
use DOMXPath;
use Exception;
use FINDOLOGIC\Export\Constant;
use FINDOLOGIC\Export\CSV\CSVConfig;
use FINDOLOGIC\Export\Data\Attribute;
use FINDOLOGIC\Export\Data\Group;
use FINDOLOGIC\Export\Data\Image;
use FINDOLOGIC\Export\Data\Item;
use FINDOLOGIC\Export\Data\Keyword;
use FINDOLOGIC\Export\Data\Name;
use FINDOLOGIC\Export\Data\Ordernumber;
use FINDOLOGIC\Export\Data\OverriddenPrice;
use FINDOLOGIC\Export\Data\Price;
use FINDOLOGIC\Export\Data\Property;
use FINDOLOGIC\Export\Data\Url;
use FINDOLOGIC\Export\Data\Variant;
use FINDOLOGIC\Export\Data\Visibility;
use FINDOLOGIC\Export\Enums\ExporterType;
use FINDOLOGIC\Export\Enums\ImageType;
use FINDOLOGIC\Export\Exceptions\BaseImageMissingException;
use FINDOLOGIC\Export\Exceptions\EmptyValueNotAllowedException;
use FINDOLOGIC\Export\Exceptions\ImagesWithoutUsergroupMissingException;
use FINDOLOGIC\Export\Exceptions\InvalidUrlException;
use FINDOLOGIC\Export\Exceptions\ItemsExceedCountValueException;
use FINDOLOGIC\Export\Exceptions\UsergroupsNotAllowedException;
use FINDOLOGIC\Export\Exceptions\XMLSchemaViolationException;
use FINDOLOGIC\Export\Exporter;
use FINDOLOGIC\Export\Helpers\XMLHelper;
use FINDOLOGIC\Export\XML\Page;
use FINDOLOGIC\Export\XML\XMLExporter;
use FINDOLOGIC\Export\XML\XMLItem;
use FINDOLOGIC\Export\XML\XmlVariant;
use InvalidArgumentException;
use stdClass;

/**
 * Class XmlSerializationTest
 * @package FINDOLOGIC\Tests
 *
 * Tests that the serialized output adheres to the defined schema and to things that cannot be covered by it.
 *
 * The schema is fetched from GitHub every time the test case is run, to ensure that tests still pass in case the schema
 * changed in the meantime.
 */
final class XmlSerializationTest extends TestCase
{
    /** @var XMLExporter */
    private Exporter $exporter;

    private static string $schema;

    public static function setUpBeforeClass(): void
    {
        parent::setUpBeforeClass();
        self::$schema = file_get_contents(Constant::$XSD_SCHEMA_PATH_20);
    }

    protected function tearDown(): void
    {
        if (file_exists('/tmp/findologic_0_1.xml')) {
            unlink('/tmp/findologic_0_1.xml');
        }
    }

    protected function setUp(): void
    {
        $this->exporter = Exporter::create(ExporterType::XML);
    }

    private function getMinimalItem(): Item
    {
        $item = $this->exporter->createItem('123');

        $item->addName('Alternative name');
        $item->addUrl('http://example.org/item.html');
        $item->addOrdernumber(new Ordernumber('123-1'));
        $item->addSalesFrequency(1337);

        $price = new Price();
        $price->setValue('13.37');
        $item->setPrice($price);

        $overriddenPrice = new OverriddenPrice();
        $overriddenPrice->setValue('16.67');
        $item->setOverriddenPrice($overriddenPrice);

        return $item;
    }

    private function getMinimalVariant(string $parentId): Variant
    {
        $variant = $this->exporter->createVariant('123-V', $parentId);

        $variant->addName('Variant name');
        $variant->addUrl('https://example.com/variant1');
        $variant->addOrdernumber(new Ordernumber('variant1'));
        $variant->addAttribute(new Attribute('key', ['value1']));

        $price = new Price();
        $price->setValue('13.37');
        $variant->setPrice($price);

        $overriddenPrice = new OverriddenPrice();
        $overriddenPrice->setValue('16.67');
        $variant->setOverriddenPrice($overriddenPrice);

        return $variant;
    }

    private function assertPageIsValid(string $xmlString): void
    {
        $xmlDocument = new DOMDocument('1.0', 'utf-8');
        $xmlDocument->loadXML($xmlString);

        $this->assertTrue($xmlDocument->schemaValidateSource(self::$schema));
    }

    public function testEmptyPageIsValid(): void
    {
        $page = $this->exporter->serializeItems([], 0, 0, 0);

        $this->assertPageIsValid($page);
    }

    public function testMinimalItemIsValid(): void
    {
        $item = $this->getMinimalItem();
        $page = $this->exporter->serializeItems([$item], 0, 1, 1);

        $this->assertPageIsValid($page);
    }

    public function testMinimalItemWithVariantsIsValid(): void
    {
        $item = $this->getMinimalItem();
        $item->addVariant($this->getMinimalVariant('123'));

        $page = $this->exporter->serializeItems([$item], 0, 1, 1);

        $this->assertPageIsValid($page);
    }

    public function testMoreItemsSuppliedThanCountValueCausesException(): void
    {
        $this->expectException(ItemsExceedCountValueException::class);

        $items = [];

        for ($i = 0; $i <= 2; $i++) {
            $item = $this->exporter->createItem((string)$i);

            $price = new Price();
            //Generate a random price
            $price->setValue(rand(1, 2000) * 1.24);
            $item->setPrice($price);

            $items[] = $item;
        }

        $this->exporter->serializeItems($items, 0, 1, 1);
    }

    public function testPropertyKeysAndValuesAreCdataWrapped(): void
    {
        $item = $this->getMinimalItem();

        $property = new Property('&quot;</>', [null => '&quot;</>']);
        $item->addProperty($property);

        $page = $this->exporter->serializeItems([$item], 0, 1, 1);

        $this->assertPageIsValid($page);
    }

    public function testAttributesAreCdataWrapped(): void
    {
        $item = $this->getMinimalItem();

        $attribute = new Attribute('&quot;</>', ['&quot;</>', 'regular']);
        $item->addAttribute($attribute);

        $page = $this->exporter->serializeItems([$item], 0, 1, 1);

        $this->assertPageIsValid($page);
    }

    public function testImagesCanBeDefaultAndThumbnail(): void
    {
        $item = $this->getMinimalItem();

        $item->setAllImages([
            new Image('http://example.org/default.png'),
            new Image('http://example.org/thumbnail.png', ImageType::THUMBNAIL),
            new Image('http://example.org/ug_default.png', ImageType::DEFAULT, 'usergroup'),
        ]);

        $page = $this->exporter->serializeItems([$item], 0, 1, 1);

        $this->assertPageIsValid($page);
    }

    public function testBaseImageCanBeExported(): void
    {
        $item = $this->getMinimalItem();

        $imageUrl = 'http://example.org/thumbnail.png';
        $item->setAllImages([
            new Image($imageUrl),
        ]);

        $document = new DOMDocument('1.0', 'utf-8');
        $root = XMLHelper::createElement($document, 'findologic', ['version' => '1.0']);
        $document->appendChild($root);

        $xmlItems = XMLHelper::createElement($document, 'items', [
            'start' => 0,
            'count' => 1,
            'total' => 1
        ]);
        $root->appendChild($xmlItems);

        $itemDom = $item->getDomSubtree($document);

        foreach ($itemDom->childNodes as $node) {
            if ($node->nodeName === 'allImages') {
                $this->assertEquals($imageUrl, $node->nodeValue);
            }
        }
    }

    public function testMissingBaseImageCausesException(): void
    {
        $this->expectException(BaseImageMissingException::class);

        $item = $this->getMinimalItem();

        $item->setAllImages([
            new Image('http://example.org/thumbnail.png', ImageType::THUMBNAIL),
            new Image('http://example.org/ug_default.png', ImageType::THUMBNAIL, 'usergroup'),
        ]);

        $this->exporter->serializeItems([$item], 0, 1, 1);
    }

    public function testImagesWithoutUsergroupMissingCausesException(): void
    {
        $this->expectException(ImagesWithoutUsergroupMissingException::class);

        $item = $this->getMinimalItem();

        $item->setAllImages([
            new Image('http://example.org/ug_default.png', ImageType::DEFAULT, 'usergroup'),
        ]);

        $this->exporter->serializeItems([$item], 0, 1, 1);
    }

    public function testOrdernumbersSupportUsergroups(): void
    {
        $item = $this->getMinimalItem();

        $item->setAllOrdernumbers([
            new Ordernumber('137-42-23.7'),
            new Ordernumber('137-42-23.7-A', 'usergroup'),
        ]);

        $page = $this->exporter->serializeItems([$item], 0, 1, 1);

        $this->assertPageIsValid($page);
    }

    public function testKeywordsSupportUsergroups(): void
    {
        $item = $this->getMinimalItem();

        $item->setAllKeywords([
            new Keyword('awesome &quot;</>]]>7'),
            new Keyword('restricted', 'usergroup'),
        ]);

        $page = $this->exporter->serializeItems([$item], 0, 1, 1);

        $this->assertPageIsValid($page);
    }

    public function testVisibilitiesSupportUsergroups(): void
    {
        $item = $this->getMinimalItem();

        $visibility = new Visibility();
        $visibility->setValue(true, '');
        $visibility->setValue(false, 'foo');
        $visibility->setValue(1, 'bar');
        $item->setVisibility($visibility);

        $page = $this->exporter->serializeItems([$item], 0, 1, 1);

        $this->assertPageIsValid($page);
    }

    public function testGroupVisibilitiesAreExported(): void
    {
        $item = $this->getMinimalItem();

        $item->setAllGroups([
            new Group('one group'),
            new Group('another group')
        ]);

        $page = $this->exporter->serializeItems([$item], 0, 1, 1);

        $this->assertPageIsValid($page);
    }

    public function testXmlCanBeWrittenDirectlyToFile(): void
    {
        $item = $this->getMinimalItem();

        $expectedXml = $this->exporter->serializeItems([$item], 0, 1, 1);
        $this->exporter->serializeItemsToFile('/tmp', [$item], 0, 1, 1);

        self::assertEquals($expectedXml, file_get_contents('/tmp/findologic_0_1.xml'));
    }

    public function testXmlFileNamePrefixCanBeAltered(): void
    {
        $fileNamePrefix = 'blub.ber.gurken';
        $expectedOutputPath = '/tmp/blub.ber.gurken_0_1.xml';

        $item = $this->getMinimalItem();

        $this->exporter->setFileNamePrefix($fileNamePrefix);
        $expectedXml = $this->exporter->serializeItems([$item], 0, 1, 1);
        $this->exporter->serializeItemsToFile('/tmp', [$item], 0, 1, 1);

        self::assertEquals($expectedXml, file_get_contents($expectedOutputPath));

        // Remove created XML file.
        unlink($expectedOutputPath);
    }

    public function testAttemptingToGetCsvFromAnXmlItemResultsInAnException(): void
    {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('XMLItem does not implement CSV export.');

        $item = new XMLItem('123');

        $item->getCsvFragment(new CSVConfig());
    }

    public function testAttemptingToGetCsvFromAnXmlVariantResultsInAnException(): void
    {
        $this->expectException(BadMethodCallException::class);
        $this->expectExceptionMessage('XmlVariant does not implement CSV export.');

        $variant = new XmlVariant('123-V1', '123');

        $variant->getCsvFragment(new CSVConfig());
    }

    /**
     * @return array Name of add method to call in a test to add a certain value, and an array of values with
     *      usergroup names as key.
     */
    public static function simpleValueAddingShortcutProvider(): array
    {
        $stringValuesWithUsergroupKeys = [
            '' => 'No usergroup',
            'foo' => 'One usergroup',
            'bar' => 'Another usergroup'
        ];

        $integerValuesWithUsergroupKeys = [
            '' => 0,
            'foo' => 3,
            'bar' => 10
        ];

        $booleanValuesWithUsergroupKeys = [
            '' => 0,
            'xyz' => 1,
            'foo' => true,
            'foo1' => false,
            'bar' => 'faLSe',
            'bar2' => 'TruE',
            'bar3' => 'false',
            'bar4' => '1',
        ];
        $expectedBooleanValuesWithUsergroupKeys = [
            '' => 0,
            'xyz' => 1,
            'foo' => 1,
            'foo1' => 0,
            'bar' => 0,
            'bar2' => 1,
            'bar3' => 0,
            'bar4' => 1,
        ];

        return [
            'name' => ['Name', $stringValuesWithUsergroupKeys],
            'summary' => ['Summary', $stringValuesWithUsergroupKeys],
            'description' => ['Description', $stringValuesWithUsergroupKeys],
            'price' => ['Price', [
                '' => 13.37,
                'foo' => 42,
                'bar' => 12.00
            ]],
            'overriddenPrice' => ['OverriddenPrice', [
                '' => 13.37,
                'foo' => 42,
                'bar' => 12.00
            ]],
            'url' => ['Url', [
                '' => 'https://example.org/product.html',
                'foo' => 'https://example.org/product.html?group=foo',
                'bar' => 'https://example.org/product.html?group=bar'
            ]],
            'bonus' => ['Bonus', $integerValuesWithUsergroupKeys],
            'sales frequency' => ['SalesFrequency', $integerValuesWithUsergroupKeys],
            'sort' => ['Sort', $integerValuesWithUsergroupKeys],
            'visibility' => ['Visibility', $booleanValuesWithUsergroupKeys, $expectedBooleanValuesWithUsergroupKeys],
        ];
    }

    /**
     * @dataProvider simpleValueAddingShortcutProvider
     */
    public function testSimpleValuesAddedToItemViaShortcutAccumulate(
        string $valueType,
        array $values,
        ?array $expectedValues = null
    ): void {
        $expectedValues ??= $values;

        $item = new XMLItem('123');

        foreach ($values as $usergroup => $value) {
            $item->{'add' . $valueType}($value, $usergroup);
        }

        $this->assertEquals($expectedValues, $item->{'get' . $valueType}()->getValues());
    }

    public function testDateValuesAddedToItemViaShortcutAccumulate(): void
    {
        $values = [
            '' => new DateTime('today midnight'),
            'foo' => new DateTime('yesterday midnight'),
            'bar' => new DateTime('tomorrow midnight')
        ];

        // On assignment, dates are converted to strings according to the format set in the schema.
        $expectedValues = array_map(
            static fn(DateTime $date): string => $date->format(DATE_ATOM),
            $values
        );

        $item = new XMLItem('123');

        foreach ($values as $usergroup => $value) {
            $item->addDateAdded($value, $usergroup);
        }

        $this->assertEquals($expectedValues, $item->getDateAdded()->getValues());
    }

    public function testImagesAddedToItemViaShortcutAccumulate(): void
    {
        $imageWithoutUsergroup = new Image('https://example.com/image1');
        $imageWithUsergroup = new Image('https://example.com/image2', ImageType::DEFAULT, 'foo');

        $values = [$imageWithoutUsergroup, $imageWithUsergroup];
        $expectedValues = [
            '' => [$imageWithoutUsergroup],
            'foo' => [$imageWithUsergroup],
        ];

        $item = new XMLItem('123');

        foreach ($values as $value) {
            $item->addImage($value);
        }

        $this->assertEquals($expectedValues, $item->getImages());
    }

    public function testKeywordsAddedToItemViaShortcutAccumulate(): void
    {
        $keywordWithoutUsergroup = new Keyword('keyword1');
        $keywordWithUsergroup = new Keyword('keyword2', 'foo');

        $values = [$keywordWithoutUsergroup, $keywordWithUsergroup];
        $expectedValues = [
            '' => [$keywordWithoutUsergroup],
            'foo' => [$keywordWithUsergroup],
        ];

        $item = new XMLItem('123');

        foreach ($values as $value) {
            $item->addKeyword($value);
        }

        $this->assertEquals($expectedValues, $item->getKeywords()->getValues());
    }

    public function testGroupsAddedToItemViaShortcutAccumulate(): void
    {
        $groups = [
            'group1',
            'group2',
        ];

        $item = new XMLItem('123');

        foreach ($groups as $group) {
            $item->addGroup(new Group($group));
        }

        $this->assertEquals($groups, $item->getGroups());
    }

    public function testVariantsAddedToItemViaShortcutAccumulates(): void
    {
        $item = new XMLItem('123');

        $expectedVariants = [
            $this->getMinimalVariant('123'),
            $this->getMinimalVariant('123'),
            $this->getMinimalVariant('123'),
        ];

        foreach ($expectedVariants as $variant) {
            $item->addVariant($variant);
        }

        $this->assertEquals($expectedVariants, $item->getVariants());
    }

    public function testAllVariantsCanBeSet(): void
    {
        $item = new XMLItem('123');

        $expectedVariants = [
            $this->getMinimalVariant('123'),
            $this->getMinimalVariant('123'),
            $this->getMinimalVariant('123'),
        ];

        $item->setAllVariants($expectedVariants);

        $this->assertEquals($expectedVariants, $item->getVariants());
    }

    /**
     * Provides a data set for testing if adding wrong url values to elements of type UsergroupAwareSimpleValue fails.
     *
     * @return array Scenarios with a value and the expected exception
     */
    public static function urlValidationProvider(): array
    {
        return [
            'Url with value' => ['value', InvalidUrlException::class],
            'Url without schema' => ['www.store.com/images/thumbnails/277KTLmen.png', InvalidUrlException::class],
            'Url without wrong schema' => [
                'tcp://www.store.com/images/thumbnails/277KTLmen.png',
                InvalidUrlException::class
            ],
        ];
    }

    /**
     * @dataProvider urlValidationProvider
     */
    public function testUrlValidationWorks(string $value, string $expectedException): void
    {
        try {
            $url =  new Url();
            $url->setValue($value);
            $this->assertNotNull($url);
        } catch (Exception $e) {
            $this->assertInstanceOf($expectedException, $e);
        }
    }

    public function testItemsCanBeAddedToXmlPageAsWell(): void
    {
        /** @var XMLItem $item */
        $item = $this->getMinimalItem();

        $page = new Page(0, 1, 1);
        $page->addItem($item);
        $this->assertNotNull($page->getXml());
    }

    public function testAddingPropertyWithUsergroupWorksAsExpected(): void
    {
        $item = $this->getMinimalItem();

        $item->addGroup(new Group('myusergroup'));
        $item->addProperty(new Property('property1', ['myusergroup' => 'usergroupvalue']));

        $this->assertPageIsValid($this->exporter->serializeItems([$item], 0, 1, 1));
    }

    public function testAddingInvalidUrlToImageElementCausesException(): void
    {
        $this->expectException(InvalidUrlException::class);

        $image = new Image('www.store.com/images/277KTL.png');
        $image->getDomSubtree(new DOMDocument());
    }

    public function testAddingImproperlyEncodedUrlToImageElementCausesException(): void
    {
        $this->expectException(InvalidUrlException::class);
        $this->expectExceptionMessage('"https://store.com/Alu-Style-Ø-270-cm50324901e845e.jpg" is not a valid url!');

        $image = new Image('https://store.com/Alu-Style-Ø-270-cm50324901e845e.jpg');
        $image->getDomSubtree(new DOMDocument());
    }

    public function testAddingUrlsToXmlDomWorksAsExpected(): void
    {
        $item = $this->getMinimalItem();

        $item->addUrl('https://www.store.com/images/277KTL.png');

        $this->assertPageIsValid($this->exporter->serializeItems([$item], 0, 1, 1));
    }

    public function testUrlsContainingSquareBracketsFailValidation(): void
    {
        $this->expectException(XMLSchemaViolationException::class);
        $this->expectExceptionMessage(
            'XML schema validation failed: DOMDocument::schemaValidate(): Element ' .
            "'url': 'https://www.store.com/search?attrib[cat][]=Foobar' " .
            "is not a valid value of the atomic type 'httpURI'."
        );

        /** @var XMLItem $item */
        $item = $this->getMinimalItem();
        $item->addUrl('https://www.store.com/search?attrib[cat][]=Foobar');

        $page = new Page(0, 1, 1);
        $page->addItem($item);

        $page->getXml();
    }

    public function testUsergroupIsSetOnSimpleValues(): void
    {
        $expectedUsergroup = 'Foobar';

        /** @var XMLItem $item */
        $item = $this->getMinimalItem();
        $item->addDateAdded(new DateTime(), $expectedUsergroup);
        $item->addDescription('Descriptive things', $expectedUsergroup);
        $item->addName('Alternative name', $expectedUsergroup);
        $item->addSalesFrequency(123, $expectedUsergroup);
        $item->addSort(345, $expectedUsergroup);
        $item->addSummary('Summing up things', $expectedUsergroup);
        $item->addUrl('http://example.org', $expectedUsergroup);
        $item->addVisibility(true, $expectedUsergroup);

        $page = new Page(0, 1, 1);
        $page->addItem($item);
        $document = $page->getXml();

        $xpath = new DOMXPath($document);
        $usergroupAttributeQuery = sprintf('[@usergroup="%s"]', $expectedUsergroup);

        $this->assertEquals(1, $xpath->query('//dateAdded' . $usergroupAttributeQuery)->length);
        $this->assertEquals(1, $xpath->query('//description' . $usergroupAttributeQuery)->length);
        $this->assertEquals(1, $xpath->query('//name' . $usergroupAttributeQuery)->length);
        $this->assertEquals(1, $xpath->query('//salesFrequency' . $usergroupAttributeQuery)->length);
        $this->assertEquals(1, $xpath->query('//sort' . $usergroupAttributeQuery)->length);
        $this->assertEquals(1, $xpath->query('//summary' . $usergroupAttributeQuery)->length);
        $this->assertEquals(1, $xpath->query('//url' . $usergroupAttributeQuery)->length);
        $this->assertEquals(1, $xpath->query('//visible' . $usergroupAttributeQuery)->length);
    }

    public function testEmptyNameCausesException(): void
    {
        $this->expectException(EmptyValueNotAllowedException::class);

        $item = $this->getMinimalItem();

        $item->addName('');

        $this->exporter->serializeItems([$item], 0, 1, 1);
    }

    public function testWhitespaceOnlyNameCausesException(): void
    {
        $this->expectException(EmptyValueNotAllowedException::class);

        $item = $this->getMinimalItem();

        $item->addName('     ');

        $this->exporter->serializeItems([$item], 0, 1, 1);
    }

    public function testMissingNameCausesException(): void
    {
        $this->expectException(XMLSchemaViolationException::class);

        $item = $this->exporter->createItem('123');

        $price = new Price();
        $price->setValue('13.37');
        $item->setPrice($price);

        $this->exporter->serializeItems([$item], 0, 1, 1);
    }

    public function testDateTimesWhichExtendDateTimeInterfaceCanBeSetAsDateAdded(): void
    {
        $expectedDateTime = new DateTimeImmutable();
        $expectedValue = $expectedDateTime->format(DateTimeInterface::ATOM);

        /** @var XMLItem $item */
        $item = $this->getMinimalItem();
        $item->addDateAdded($expectedDateTime);

        $page = new Page(0, 1, 1);
        $page->addItem($item);
        $document = $page->getXml();

        $xpath = new DOMXPath($document);
        $this->assertEquals($expectedValue, $xpath->query('//dateAdded')->item(0)->nodeValue);
    }

    public function testAllPricesCanBeSet(): void
    {
        $expectedUsergroup = 'best usergroup';

        $price = new Price();
        $price->setValue('13.37');
        $anotherPrice = new Price();
        $anotherPrice->setValue(4.20, $expectedUsergroup);

        /** @var XMLItem $item */
        $item = $this->exporter->createItem('123');
        $item->setAllPrices([$price, $anotherPrice]);
        $item->addName('Best item ever');
        $item->addUrl('http://example.org/item.html');
        $item->addSalesFrequency(1337);
        $item->addOrdernumber(new Ordernumber('123-1'));

        $page = new Page(0, 1, 1);
        $page->addItem($item);
        $document = $page->getXml();
        $xpath = new DOMXPath($document);

        $this->assertEquals('13.37', $xpath->query('//price')->item(0)->nodeValue);
        $this->assertEquals('4.2', $xpath->query('//price')->item(1)->nodeValue);
        $this->assertEquals(
            $expectedUsergroup,
            $xpath->query('//price')->item(1)->getAttribute('usergroup')
        );
    }

    public function testPricesAreNotInstancesOfPriceThrowsAnException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(sprintf('Given prices must be instances of %s', Price::class));

        $item = $this->exporter->createItem('123');
        $item->setAllPrices([new stdClass()]);
    }

    public function testAllOverriddenPricesCanBeSet(): void
    {
        $expectedUsergroup = 'best usergroup';

        $price = new OverriddenPrice();
        $price->setValue('13.37');
        $anotherPrice = new OverriddenPrice();
        $anotherPrice->setValue(4.20, $expectedUsergroup);

        /** @var XMLItem $item */
        $item = $this->exporter->createItem('123');
        $item->addPrice(10.0);
        $item->setAllOverriddenPrices([$price, $anotherPrice]);
        $item->addName('Best item ever');
        $item->addUrl('http://example.org/item.html');
        $item->addSalesFrequency(1337);
        $item->addOrdernumber(new Ordernumber('123-1'));

        $page = new Page(0, 1, 1);
        $page->addItem($item);
        $document = $page->getXml();
        $xpath = new DOMXPath($document);

        $this->assertEquals('13.37', $xpath->query('//overriddenPrice')->item(0)->nodeValue);
        $this->assertEquals('4.2', $xpath->query('//overriddenPrice')->item(1)->nodeValue);
        $this->assertEquals(
            $expectedUsergroup,
            $xpath->query('//overriddenPrice')->item(1)->getAttribute('usergroup')
        );
    }

    public function testOverriddenPricesAreNotInstancesOfOverriddenPriceThrowsAnException(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage(
            sprintf('Given overridden prices must be instances of %s', OverriddenPrice::class)
        );

        $item = $this->exporter->createItem('123');
        $item->setAllOverriddenPrices([new stdClass()]);
    }

    public function testAttributeValueTypes(): void
    {
        /** @var XMLItem $item */
        $item = $this->getMinimalItem();

        $expectedAttributes = ['string', '4.5', '11', '1'];
        $attr1 = new Attribute('values', ['string', 4.5, 11, true]);

        $item->addAttribute($attr1);

        $page = new Page(0, 1, 1);
        $page->addItem($item);
        $document = $page->getXml();
        $xpath = new DOMXPath($document);

        $values = $xpath->query('//attribute')->item(0)->childNodes->item(1)->childNodes;

        $actualAttributes = [];
        foreach ($values as $value) {
            $actualAttributes[] = $value->nodeValue;
        }

        $this->assertEquals($expectedAttributes, $actualAttributes);
    }

    public function testMergingAttributesWillNotOverrideExistingOnes(): void
    {
        /** @var XMLItem $item */
        $item = $this->getMinimalItem();

        $expectedAttributes = ['orange', 'yellow', 'pink'];
        $attr1 = new Attribute('color', ['orange', 'yellow', 'yellow']);
        $attr2 = new Attribute('color', ['pink', 'orange', 'pink']);

        $item->addMergedAttribute($attr1);
        $item->addMergedAttribute($attr2);

        $page = new Page(0, 1, 1);
        $page->addItem($item);
        $document = $page->getXml();
        $xpath = new DOMXPath($document);

        $values = $xpath->query('//attribute')->item(0)->childNodes->item(1)->childNodes;

        $actualAttributes = [];
        foreach ($values as $value) {
            $actualAttributes[] = $value->nodeValue;
        }

        $this->assertEquals($expectedAttributes, $actualAttributes);
    }

    public function testExceptionIsThrownWhenUsingUsergroupStringWithExistingVariants(): void
    {
        $this->expectException(UsergroupsNotAllowedException::class);
        $this->expectExceptionMessage('Usergroups are not supported when using variants');

        $item = $this->getMinimalItem();

        $item->addVariant($this->getMinimalVariant($item->getId()));

        $item->addName('name', 'usergroup');
    }

    public function testExceptionIsThrownWhenUsingUsergroupValueWithExistingVariants(): void
    {
        $this->expectException(UsergroupsNotAllowedException::class);
        $this->expectExceptionMessage('Usergroups are not supported when using variants');

        $item = $this->getMinimalItem();

        $item->addVariant($this->getMinimalVariant($item->getId()));

        $name = new Name();
        $name->setValue('name', 'usergroup');
        $item->setName($name);
    }

    public function testExceptionIsThrownWhenAddingVariantsWithUsedUsergroups(): void
    {
        $this->expectException(UsergroupsNotAllowedException::class);
        $this->expectExceptionMessage('Usergroups are not supported when using variants');

        $item = $this->getMinimalItem();

        $item->addName('name', 'usergroup');
        $item->addVariant($this->getMinimalVariant($item->getId()));
    }

    public function testExceptionIsThrownWhenSettingVariantsWithUsedUsergroups(): void
    {
        $this->expectException(UsergroupsNotAllowedException::class);
        $this->expectExceptionMessage('Usergroups are not supported when using variants');

        $item = $this->getMinimalItem();

        $item->addName('name', 'usergroup');
        $item->setAllVariants([
            $this->getMinimalVariant($item->getId())
        ]);
    }
}
