<?php

require_once __DIR__ . '/BaseExample.php';

use FINDOLOGIC\Export\Exporter;

/**
 * This example class builds an XML export based on the example of the FINDOLOGIC documentation, which can be found
 * under the following link:
 * @link https://docs.findologic.com/doku.php?id=xml_export_documentation:XML_format
 */
class XmlVariantsExample extends BaseExample
{
    public function __construct()
    {
        parent::__construct();

        $this->products = [];
        $this->products[] = new ExampleProductItemWithVariants();
        $this->products[] = new ExampleContentItemWithoutUsergroups();
    }

    public function createExport(): string
    {
        $exporter = Exporter::create(Exporter::TYPE_XML);
        $items = $this->buildItems($exporter);

        return $exporter->serializeItems($items, 0, count($items), count($items));
    }
}

$example = new XmlVariantsExample();

// Output the XML content.
echo $example->createExport();
