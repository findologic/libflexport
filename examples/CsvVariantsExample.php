<?php

require_once __DIR__ . '/BaseVariantsExample.php';

use FINDOLOGIC\Export\CSV\CSVConfig;
use FINDOLOGIC\Export\Exporter;

/**
 * This example class builds an XML export based on the example of the FINDOLOGIC documentation, which can be found
 * under the following link:
 * @link https://docs.findologic.com/doku.php?id=xml_export_documentation:XML_format
 */
class CsvVariantsExample extends BaseVariantsExample
{
    public function createExport(): string
    {
        $csvConfig = new CSVConfig(
            [
                'sale',
                'novelty',
                'logo',
                'availability',
                'old_price',
                'Basic_rate_price',
                'file_type',
                'number_of_comments',
                'badge',
            ],
            [
                'cat',
                'cat_url',
                'brand',
                'color',
                'type',
                'variant_value'
            ],
            2,
            2
        );

        $exporter = Exporter::create(Exporter::TYPE_CSV, 20, $csvConfig);

        $items = $this->buildItems($exporter);

        return $exporter->serializeItems($items, 0, count($items), count($items));
    }
}

$example = new CsvVariantsExample();

// Output the XML content.
echo $example->createExport();
