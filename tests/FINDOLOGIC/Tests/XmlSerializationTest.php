<?php

namespace FINDOLOGIC\Tests;

use FINDOLOGIC\Export\Data\Attribute;
use FINDOLOGIC\Export\Data\Image;
use FINDOLOGIC\Export\Data\Keyword;
use FINDOLOGIC\Export\Data\Ordernumber;
use FINDOLOGIC\Export\Data\Price;
use FINDOLOGIC\Export\Data\Property;
use FINDOLOGIC\Export\Data\Usergroup;
use FINDOLOGIC\Export\Exporter;
use FINDOLOGIC\Export\XML\XMLExporter;
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

    /** @var XMLExporter */
    private $exporter;

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
        $page = $this->exporter->serializeItems(array(), 0, 0);

        $this->assertPageIsValid($page);
    }

    public function testMinimalItemIsValid()
    {
        $item = $this->getMinimalItem();
        $page = $this->exporter->serializeItems(array($item), 0, 1);

        $this->assertPageIsValid($page);
    }

    public function testPropertyKeysAndValuesAreCdataWrapped()
    {
        $item = $this->getMinimalItem();

        $property = new Property('&quot;</>', array(null => '&quot;</>'));
        $item->addProperty($property);

        $page = $this->exporter->serializeItems(array($item), 0, 1);

        $this->assertPageIsValid($page);
    }

    public function testAttributesAreCdataWrapped()
    {
        $item = $this->getMinimalItem();

        $attribute = new Attribute('&quot;</>', array('&quot;</>', 'regular'));
        $item->addAttribute($attribute);

        $page = $this->exporter->serializeItems(array($item), 0, 1);

        $this->assertPageIsValid($page);
    }

    public function testImagesCanBeDefaultAndThumbnail()
    {
        $item = $this->getMinimalItem();

        $item->setAllImages(array(
            new Image('http://example.org/default.png'),
            new Image('http://example.org/thumbnail.png', Image::TYPE_THUMBNAIL),
            new Image('http://example.org/ug_default.png', Image::TYPE_DEFAULT, 'usergroup'),
        ));

        $page = $this->exporter->serializeItems(array($item), 0, 1);

        $this->assertPageIsValid($page);
    }

    public function testOrdernumbersSupportUsergroups()
    {
        $item = $this->getMinimalItem();

        $item->setAllOrdernumbers(array(
            new Ordernumber('137-42-23.7'),
            new Ordernumber('137-42-23.7-A', 'usergroup'),
        ));

        $page = $this->exporter->serializeItems(array($item), 0, 1);

        $this->assertPageIsValid($page);
    }

    public function testKeywordsSupportUsergroups()
    {
        $item = $this->getMinimalItem();

        $item->setAllKeywords(array(
            new Keyword('awesome &quot;</>]]>7'),
            new Keyword('restricted', 'usergroup'),
        ));

        $page = $this->exporter->serializeItems(array($item), 0, 1);

        $this->assertPageIsValid($page);
    }

    public function testUsergroupVisibilitiesAreExported()
    {
        $item = $this->getMinimalItem();

        $item->setAllUsergroups(array(
            new Usergroup('one group'),
            new Usergroup('another group')
        ));

        $page = $this->exporter->serializeItems(array($item), 0, 1);

        $this->assertPageIsValid($page);
    }
}