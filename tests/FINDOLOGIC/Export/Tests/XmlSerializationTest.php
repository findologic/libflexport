<?php

namespace FINDOLOGIC\Export\Tests;

use FINDOLOGIC\Export\Data\Attribute;
use FINDOLOGIC\Export\Data\Image;
use FINDOLOGIC\Export\Data\Keyword;
use FINDOLOGIC\Export\Data\Ordernumber;
use FINDOLOGIC\Export\Data\Price;
use FINDOLOGIC\Export\Data\Property;
use FINDOLOGIC\Export\Data\Url;
use FINDOLOGIC\Export\Data\Usergroup;
use FINDOLOGIC\Export\Exporter;
use FINDOLOGIC\Export\Helpers\InvalidUrlException;
use FINDOLOGIC\Export\Helpers\UnsupportedValueException;
use FINDOLOGIC\Export\Helpers\XMLHelper;
use FINDOLOGIC\Export\XML\Page;
use FINDOLOGIC\Export\XML\XMLExporter;
use FINDOLOGIC\Export\XML\XMLItem;
use PHPUnit\Framework\TestCase;

/**
 * Class XmlSerializationTest
 * @package FINDOLOGIC\Tests
 *
 * Tests that the serialized output adheres to the defined schema and to things that cannot be covered by it.
 *
 * The schema is fetched from GitHub every time the test case is run, to ensure that tests still pass in case the schema
 * changed in the meantime.
 */
class XmlSerializationTest extends TestCase
{
    const SCHEMA_URL = 'https://raw.githubusercontent.com/findologic/xml-export/master/src/main/resources/findologic.xsd';

    private static $schema;

    public static function setUpBeforeClass()
    {
        parent::setUpBeforeClass();

        // Download the schema once for the whole test case for speed as compared to downloading it for each test.
        self::$schema = file_get_contents(self::SCHEMA_URL);
    }

    public function tearDown()
    {
        try {
            unlink('/tmp/findologic_0_1.xml');
        } catch (\Exception $e) {
            // No need to delete a written file if the test didn't write it.
        }
    }

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

    private function assertPageIsValid($xmlString)
    {
        $xmlDocument = new \DOMDocument('1.0', 'utf-8');
        $xmlDocument->loadXML($xmlString);

        $this->assertTrue($xmlDocument->schemaValidateSource(self::$schema));
    }

    public function testEmptyPageIsValid()
    {
        $page = $this->exporter->serializeItems([], 0, 0, 0);

        $this->assertPageIsValid($page);
    }

    public function testMinimalItemIsValid()
    {
        $item = $this->getMinimalItem();
        $page = $this->exporter->serializeItems([$item], 0, 1, 1);

        $this->assertPageIsValid($page);
    }

    /**
     * @expectedException \FINDOLOGIC\Export\XML\ItemsExceedCountValueException
     */
    public function testMoreItemsSuppliedThanCountValueCausesException()
    {
        $items = [];

        for ($i = 0; $i <= 2; $i++) {
            $item = $this->exporter->createItem((string)$i);

            $price = new Price();
            //Generate a random price
            $price->setValue(rand(1, 2000)*1.24);
            $item->setPrice($price);

            $items[] = $item;
        }

        $this->exporter->serializeItems($items, 0, 1, 1);
    }

    public function testPropertyKeysAndValuesAreCdataWrapped()
    {
        $item = $this->getMinimalItem();

        $property = new Property('&quot;</>', [null => '&quot;</>']);
        $item->addProperty($property);

        $page = $this->exporter->serializeItems([$item], 0, 1, 1);

        $this->assertPageIsValid($page);
    }

    public function testAttributesAreCdataWrapped()
    {
        $item = $this->getMinimalItem();

        $attribute = new Attribute('&quot;</>', ['&quot;</>', 'regular']);
        $item->addAttribute($attribute);

        $page = $this->exporter->serializeItems([$item], 0, 1, 1);

        $this->assertPageIsValid($page);
    }

    public function testImagesCanBeDefaultAndThumbnail()
    {
        $item = $this->getMinimalItem();

        $item->setAllImages([
            new Image('http://example.org/default.png'),
            new Image('http://example.org/thumbnail.png', Image::TYPE_THUMBNAIL),
            new Image('http://example.org/ug_default.png', Image::TYPE_DEFAULT, 'usergroup'),
        ]);

        $page = $this->exporter->serializeItems([$item], 0, 1, 1);

        $this->assertPageIsValid($page);
    }

    public function testBaseImageCanBeExported()
    {
        $item = $this->getMinimalItem();

        $imageUrl = 'http://example.org/thumbnail.png';
        $item->setAllImages([
            new Image($imageUrl),
        ]);

        $document = new \DOMDocument('1.0', 'utf-8');
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

    /**
     * @expectedException \FINDOLOGIC\Export\Data\BaseImageMissingException
     */
    public function testMissingBaseImageCausesException()
    {
        $item = $this->getMinimalItem();

        $item->setAllImages([
            new Image('http://example.org/thumbnail.png', Image::TYPE_THUMBNAIL),
            new Image('http://example.org/ug_default.png', Image::TYPE_THUMBNAIL, 'usergroup'),
        ]);

        $this->exporter->serializeItems([$item], 0, 1, 1);
    }

    /**
     * @expectedException \FINDOLOGIC\Export\Data\ImagesWithoutUsergroupMissingException
     */
    public function testImagesWithoutUsergroupMissingCausesException()
    {
        $item = $this->getMinimalItem();

        $item->setAllImages([
            new Image('http://example.org/ug_default.png', Image::TYPE_DEFAULT, 'usergroup'),
        ]);

        $this->exporter->serializeItems([$item], 0, 1, 1);
    }

    public function testOrdernumbersSupportUsergroups()
    {
        $item = $this->getMinimalItem();

        $item->setAllOrdernumbers([
            new Ordernumber('137-42-23.7'),
            new Ordernumber('137-42-23.7-A', 'usergroup'),
        ]);

        $page = $this->exporter->serializeItems([$item], 0, 1, 1);

        $this->assertPageIsValid($page);
    }

    public function testKeywordsSupportUsergroups()
    {
        $item = $this->getMinimalItem();

        $item->setAllKeywords([
            new Keyword('awesome &quot;</>]]>7'),
            new Keyword('restricted', 'usergroup'),
        ]);

        $page = $this->exporter->serializeItems([$item], 0, 1, 1);

        $this->assertPageIsValid($page);
    }

    public function testUsergroupVisibilitiesAreExported()
    {
        $item = $this->getMinimalItem();

        $item->setAllUsergroups([
            new Usergroup('one group'),
            new Usergroup('another group')
        ]);

        $page = $this->exporter->serializeItems([$item], 0, 1, 1);

        $this->assertPageIsValid($page);
    }

    public function testXmlCanBeWrittenDirectlyToFile()
    {
        $item = $this->getMinimalItem();

        $expectedXml = $this->exporter->serializeItems([$item], 0, 1, 1);
        $this->exporter->serializeItemsToFile('/tmp', [$item], 0, 1, 1);

        self::assertEquals($expectedXml, file_get_contents('/tmp/findologic_0_1.xml'));
    }

    public function testAttemptingToGetCsvFromAnXmlItemResultsInAnException()
    {
        $item = new XMLItem(123);

        try {
            $item->getCsvFragment();
        } catch (\BadMethodCallException $e) {
            $this->assertEquals('XMLItem does not implement CSV export.', $e->getMessage());
        }
    }

    /**
     * @return array Name of add method to call in a test to add a certain value, and an array of values with
     *      usergroup names as key.
     */
    public function simpleValueAddingShortcutProvider()
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

        return [
            'name' => ['Name', $stringValuesWithUsergroupKeys],
            'summary' => ['Summary', $stringValuesWithUsergroupKeys],
            'description' => ['Description', $stringValuesWithUsergroupKeys],
            'price' => ['Price', [
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
        ];
    }

    /**
     * @dataProvider simpleValueAddingShortcutProvider
     *
     * @param string $valueType
     * @param array $values
     */
    public function testSimpleValuesAddedToItemViaShortcutAccumulate($valueType, array $values)
    {
        $item = new XMLItem(123);

        foreach ($values as $usergroup => $value) {
            $item->{'add' . $valueType}($value, $usergroup);
        }

        $this->assertEquals($values, $item->{'get' . $valueType}()->getValues());
    }

    public function testDateValuesAddedToItemViaShortcutAccumulate()
    {
        $values = [
            '' => new \DateTime('today midnight'),
            'foo' => new \DateTime('yesterday midnight'),
            'bar' => new \DateTime('tomorrow midnight')
        ];

        // On assignment, dates are converted to strings according to the format set in the schema.
        $expectedValues = array_map(function (\DateTime $date) {
            return $date->format(DATE_ATOM);
        }, $values);

        $item = new XMLItem(123);

        foreach ($values as $usergroup => $value) {
            $item->addDateAdded($value, $usergroup);
        }

        $this->assertEquals($expectedValues, $item->getDateAdded()->getValues());
    }

    /**
     * Provides a data set for testing if adding wrong url values to elements of type UsergroupAwareSimpleValue fails.
     *
     * @return array Scenarios with a value and the expected exception
     */
    public function urlValidationProvider()
    {
        return [
            'Url with value' => ['value', InvalidUrlException::class],
            'Url without schema' => ['www.store.com/images/thumbnails/277KTLmen.png', InvalidUrlException::class],
            'Url without wrong schema' => ['tcp://www.store.com/images/thumbnails/277KTLmen.png', InvalidUrlException::class],
        ];
    }

    /**
     * @dataProvider urlValidationProvider
     *
     * @param string $value
     * @param \Exception|null $expectedException
     */
    public function testUrlValidationWorks($value, $expectedException)
    {
        try {
            $url =  new Url();
            $url->setValue($value);
            $url->getDomSubtree(new \DOMDocument());
        } catch (\Exception $e) {
            $this->assertEquals($expectedException, get_class($e));
        }
    }

    public function testItemsCanBeAddedToXmlPageAsWell()
    {
        $page = new Page(0, 1, 1);
        $page->addItem($this->getMinimalItem());
        $this->assertNotNull($page->getXml());
    }

    public function unsupportedValueProvider()
    {
        return [
            'getInsteadPrice' => ['getInsteadPrice', null],
            'setInsteadPrice' => ['setInsteadPrice', 13.37],
            'getMaxPrice' => ['getMaxPrice', null],
            'setMaxPrice' => ['setMaxPrice', 42.00],
            'getTaxRate' => ['getTaxRate', null],
            'setTaxRate' => ['setTaxRate', 20.0],
        ];
    }

    /**
     * @expectedException \FINDOLOGIC\Export\Helpers\UnsupportedValueException
     * @dataProvider unsupportedValueProvider
     *
     * @param string $method Name of the method to call to interact with an unsupported value.
     * @param mixed $parameter The parameter in case of a setter.
     */
    public function testUsingValuesUnsupportedByXmlCauseExceptions($method, $parameter)
    {
        $item = $this->getMinimalItem();

        if ($parameter === null) {
            $item->{$method}();
        } else {
            $item->{$method}($parameter);
        }
    }

    public function testAddingPropertyWithUsergroupWorksAsExpected()
    {
        $item = $this->getMinimalItem();

        $item->addUsergroup(new Usergroup('myusergroup'));
        $item->addProperty(new Property('property1', ['myusergroup' => 'usergroupvalue']));

        $this->assertPageIsValid($this->exporter->serializeItems([$item], 0, 1, 1));
    }


    /**
     * @expectedException \FINDOLOGIC\Export\Helpers\InvalidUrlException
     */
    public function testAddingInvalidUrlToImageElementCausesException()
    {
        $image = new Image('www.store.com/images/277KTL.png');
        $image->getDomSubtree(new \DOMDocument());
    }

    public function testAddingUrlsToXmlDomWorksAsExpected()
    {
        $item = $this->getMinimalItem();

        $item->addUrl('https://www.store.com/images/277KTL.png');

        $this->assertPageIsValid($this->exporter->serializeItems([$item], 0, 1, 1));
    }
}
