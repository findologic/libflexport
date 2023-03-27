<?php

namespace FINDOLOGIC\Export\Examples;

require_once __DIR__ . '/../vendor/autoload.php';

use FINDOLOGIC\Export\CSV\CSVConfig;
use FINDOLOGIC\Export\Exporter;

/**
 * This example class builds an XML export based on the example of the FINDOLOGIC documentation, which can be found
 * under the following link:
 * @link https://docs.findologic.com/doku.php?id=xml_export_documentation:XML_format
 */
final class CsvVariantsExample extends BaseVariantsExample
{
    public function createExport(): string
    {
        $csvConfig = new CSVConfig(
            availableProperties: [
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
            availableAttributes: [
                'cat',
                'cat_url',
                'brand',
                'color',
                'type',
                'variant_value'
            ],
            imageCount: 3,
        );

        $exporter = Exporter::create(Exporter::TYPE_CSV, 20, $csvConfig);

        $items = $this->buildItems($exporter);

        return $exporter->serializeItems($items, 0, count($items), count($items));
    }
}

$example = new CsvVariantsExample();

// Output the CSV content.
echo $example->createExport();
